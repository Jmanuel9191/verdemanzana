<?php

namespace app\models;

use app\helpers\FormatoHelper;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Historial de cambios del presupuesto de una reserva.
 *
 * Cada vez que se guarda el presupuesto queda UNA fila con:
 *  - quién y cuándo
 *  - total anterior → total nuevo
 *  - la lista de cambios (JSON): ítems agregados, quitados y modificados,
 *    cambio de paquete y cambio de observaciones
 *  - motivo (opcional)
 *
 * No se edita ni se borra desde la aplicación (solo se borra junto con la
 * cotización, por la FK con ON DELETE CASCADE).
 *
 * @property int         $idhistorial
 * @property int         $idcotizacion
 * @property int         $idreservas
 * @property string      $accion
 * @property string|null $total_anterior
 * @property string      $total_nuevo
 * @property string      $cambios
 * @property string|null $motivo
 * @property int|null    $idadmin
 * @property string      $fecha_registro
 *
 * @property Administrador|null $admin
 */
class ReservaCotizacionHistorial extends ActiveRecord
{
    public const ACCION_CREADO = 'creado';
    public const ACCION_MODIFICADO = 'modificado';

    /** Campos de una línea que se comparan / guardan en el historial */
    public const CAMPOS_LINEA = ['cantidad', 'precio_unitario', 'descuento_monto', 'total'];

    public static function tableName()
    {
        return 'reserva_cotizacion_historial';
    }

    public function getAdmin()
    {
        return $this->hasOne(Administrador::class, ['idadmin' => 'idadmin']);
    }

    /** @return self[] del más nuevo al más viejo */
    public static function deCotizacion(int $idcotizacion): array
    {
        return self::find()
            ->where(['idcotizacion' => $idcotizacion])
            ->with('admin')
            ->orderBy(['idhistorial' => SORT_DESC])
            ->all();
    }

    // ---------------------------------------------------------------- foto y comparación

    /**
     * "Foto" de las líneas para comparar: [iddetalle => datos].
     * Las líneas nuevas (sin id) van con clave 'n0', 'n1', …
     *
     * @param ReservaCotizacionDetalle[] $lineas
     */
    public static function foto(array $lineas): array
    {
        $foto = [];
        $n = 0;
        foreach ($lineas as $l) {
            $clave = $l->iddetalle ? (int)$l->iddetalle : 'n' . $n++;
            $foto[$clave] = [
                'seccion'         => (string)$l->seccion,
                'idorigen'        => (int)$l->idorigen,
                'nombre'          => (string)$l->nombre,
                'empresa'         => (string)$l->empresa,
                'unidad'          => (string)$l->unidad,
                'cantidad'        => self::dec($l->cantidad),
                'precio_unitario' => self::dec($l->precio_unitario),
                'descuento_monto' => self::dec($l->descuento_monto),
                'total'           => self::dec($l->total),
            ];
        }
        return $foto;
    }

    /**
     * Compara dos fotos y devuelve la lista de cambios (vacía si no cambió nada).
     */
    public static function comparar(array $antes, array $despues, ?string $obsAntes = null, ?string $obsDespues = null): array
    {
        $cambios = [];

        foreach ($despues as $clave => $d) {
            if (!isset($antes[$clave])) {
                $cambios[] = ['tipo' => 'agregado', 'despues' => $d];
                continue;
            }
            $a = $antes[$clave];

            // Misma fila del paquete pero con otro paquete elegido
            if ((int)$a['idorigen'] !== (int)$d['idorigen'] || $a['seccion'] !== $d['seccion']) {
                $cambios[] = ['tipo' => 'reemplazado', 'antes' => $a, 'despues' => $d];
                continue;
            }

            $campos = [];
            foreach (self::CAMPOS_LINEA as $campo) {
                if ($a[$campo] !== $d[$campo]) {
                    $campos[] = $campo;
                }
            }
            if ($campos) {
                $cambios[] = ['tipo' => 'modificado', 'campos' => $campos, 'antes' => $a, 'despues' => $d];
            }
        }

        foreach ($antes as $clave => $a) {
            if (!isset($despues[$clave])) {
                $cambios[] = ['tipo' => 'quitado', 'antes' => $a];
            }
        }

        if (trim((string)$obsAntes) !== trim((string)$obsDespues)) {
            $cambios[] = ['tipo' => 'observaciones', 'antes' => (string)$obsAntes, 'despues' => (string)$obsDespues];
        }

        return $cambios;
    }

    /**
     * Guarda una fila del historial.
     */
    public static function registrar(ReservaCotizacion $c, string $accion, ?string $totalAnterior, array $cambios, ?string $motivo): void
    {
        $h = new self();
        $h->idcotizacion = (int)$c->idcotizacion;
        $h->idreservas = (int)$c->idreservas;
        $h->accion = $accion;
        $h->total_anterior = $totalAnterior;
        $h->total_nuevo = $c->total;
        $h->cambios = Json::encode($cambios);
        $motivo = trim((string)$motivo);
        $h->motivo = $motivo !== '' ? mb_substr($motivo, 0, 500) : null;
        $h->idadmin = self::idAdminActual();
        $h->fecha_registro = date('Y-m-d H:i:s');
        if (!$h->save(false)) {
            throw new \RuntimeException('No se pudo guardar el historial del presupuesto.');
        }
    }

    // ---------------------------------------------------------------- para mostrar

    public function getCambiosLista(): array
    {
        $datos = Json::decode((string)$this->cambios, true);
        return is_array($datos) ? $datos : [];
    }

    public function getNombreAdmin(): string
    {
        return ReservaPago::nombreAdmin($this->admin);
    }

    /** Diferencia de total con signo: "+ Bs 300,00" / "− Bs 3.150,00" / "" */
    public function getDiferenciaTexto(): string
    {
        if ($this->total_anterior === null) {
            return '';
        }
        $dif = ReservaCotizacionDetalle::aCentavos($this->total_nuevo) - ReservaCotizacionDetalle::aCentavos($this->total_anterior);
        if ($dif === 0) {
            return 'sin cambio en el total';
        }
        return ($dif > 0 ? '+ ' : '− ') . FormatoHelper::formatoBs(ReservaCotizacionDetalle::deCentavos(abs($dif)));
    }

    /**
     * Texto legible (HTML escapado) de un cambio.
     */
    public static function describir(array $cambio): string
    {
        $e = function ($t) {
            return \yii\helpers\Html::encode((string)$t);
        };
        $bs = function ($v) {
            return FormatoHelper::formatoBs($v);
        };
        $cant = function ($v) {
            $f = (float)$v;
            return floor($f) == $f ? (string)(int)$f : number_format($f, 2, ',', '.');
        };
        $titulo = function (array $l) use ($e) {
            $sec = ReservaCotizacionDetalle::secciones()[$l['seccion']] ?? $l['seccion'];
            return '<span class="text-muted">' . $e($sec) . '</span> <b>' . $e($l['nombre']) . '</b>'
                . ($l['empresa'] !== '' ? ' <small class="text-muted">(' . $e($l['empresa']) . ')</small>' : '');
        };
        $resumen = function (array $l) use ($bs, $cant) {
            $txt = $cant($l['cantidad']) . ' × ' . $bs($l['precio_unitario']);
            if (ReservaCotizacionDetalle::aCentavos($l['descuento_monto']) > 0) {
                $txt .= ' − desc. ' . $bs($l['descuento_monto']);
            }
            return $txt . ' = <b>' . $bs($l['total']) . '</b>';
        };
        $etiquetas = [
            'cantidad'        => 'cantidad',
            'precio_unitario' => 'precio',
            'descuento_monto' => 'descuento',
            'total'           => 'total',
        ];

        switch ($cambio['tipo'] ?? '') {
            case 'agregado':
                return '<i class="fa fa-plus-circle text-success"></i> Agregó ' . $titulo($cambio['despues']) . ': ' . $resumen($cambio['despues']);

            case 'quitado':
                return '<i class="fa fa-minus-circle text-danger"></i> Quitó ' . $titulo($cambio['antes']) . ' (tenía ' . $resumen($cambio['antes']) . ')';

            case 'reemplazado':
                return '<i class="fa fa-exchange text-warning"></i> Cambió ' . $titulo($cambio['antes']) . ' (' . $resumen($cambio['antes']) . ')'
                    . ' por ' . $titulo($cambio['despues']) . ' (' . $resumen($cambio['despues']) . ')';

            case 'modificado':
                $partes = [];
                foreach ((array)($cambio['campos'] ?? []) as $campo) {
                    $a = $cambio['antes'][$campo];
                    $d = $cambio['despues'][$campo];
                    $f = $campo === 'cantidad' ? $cant : $bs;
                    $partes[] = ($etiquetas[$campo] ?? $campo) . ' ' . $f($a) . ' → <b>' . $f($d) . '</b>';
                }
                return '<i class="fa fa-pencil text-primary"></i> Modificó ' . $titulo($cambio['despues']) . ': ' . implode(', ', $partes);

            case 'observaciones':
                return '<i class="fa fa-comment-o text-muted"></i> Cambió las observaciones del presupuesto';
        }
        return $e(Json::encode($cambio));
    }

    // ---------------------------------------------------------------- auxiliares

    /** Normaliza a "1234.50" para comparar sin problemas de formato */
    private static function dec($v): string
    {
        return ReservaCotizacionDetalle::deCentavos(ReservaCotizacionDetalle::aCentavos($v));
    }

    private static function idAdminActual(): ?int
    {
        $app = Yii::$app;
        if (!$app->has('user') || !($app->user instanceof \yii\web\User)) {
            return null;
        }
        $id = $app->user->id;
        return $id !== null ? (int)$id : null;
    }
}
