<?php

namespace app\models;

use app\helpers\FormatoHelper;
use yii\db\ActiveRecord;

/**
 * Una línea de la cotización: el paquete, un ítem o un proveedor.
 *
 * Los datos (nombre, detalle, unidad, precio) se COPIAN del catálogo al agregarse:
 * si después cambia el catálogo, esta línea no cambia.
 *
 * Cuentas (siempre se recalculan en el servidor al guardar):
 *   subtotal  = cantidad × precio_unitario
 *   descuento = descuento_monto (entre 0 y subtotal)
 *   %         = descuento / subtotal × 100
 *   total     = subtotal − descuento
 * En pantalla se puede editar %, monto de descuento o total: el JavaScript mantiene
 * los tres coherentes y el servidor toma el MONTO de descuento como dato final.
 *
 * @property int         $iddetalle
 * @property int         $idcotizacion
 * @property string      $seccion
 * @property int|null    $idorigen
 * @property string      $nombre
 * @property string|null $empresa
 * @property string|null $detalle
 * @property string      $unidad
 * @property string      $cantidad
 * @property string      $precio_unitario
 * @property string      $subtotal
 * @property string      $descuento_porcentaje
 * @property string      $descuento_monto
 * @property string      $total
 * @property int         $orden
 *
 * @property ReservaCotizacion $cotizacion
 */
class ReservaCotizacionDetalle extends ActiveRecord
{
    public const SECCION_PAQUETE = 'paquete';
    public const SECCION_ITEM = 'item';
    public const SECCION_PROVEEDOR = 'proveedor';
    public const SECCION_EXPERIENCIA = 'experiencia';

    /** Topes para que las cuentas en centavos no desborden */
    public const CANTIDAD_MAX = 99999.99;
    public const PRECIO_MAX = 99999999.99;

    public static function tableName()
    {
        return 'reserva_cotizacion_detalle';
    }

    public static function secciones(): array
    {
        return [
            self::SECCION_PAQUETE     => 'Paquete',
            self::SECCION_ITEM        => 'Ítem',
            self::SECCION_PROVEEDOR   => 'Proveedor',
            self::SECCION_EXPERIENCIA => 'Experiencia',
        ];
    }

    public function rules()
    {
        return [
            [['seccion', 'nombre', 'empresa', 'unidad'], 'trim'],

            [['seccion', 'nombre', 'unidad', 'cantidad', 'precio_unitario'], 'required'],

            [['seccion'], 'in', 'range' => array_keys(self::secciones())],
            [['idcotizacion', 'idorigen', 'orden'], 'integer'],
            [['idorigen'], 'default', 'value' => null],
            [['orden'], 'default', 'value' => 0],

            [['nombre', 'empresa'], 'string', 'max' => 200],
            [['empresa'], 'default', 'value' => null],
            [['unidad'], 'string', 'max' => 20],

            [['detalle'], 'string'],
            [['detalle'], 'filter', 'filter' => [FormatoHelper::class, 'limpiarHtml']],

            [['cantidad'], 'number', 'min' => 0.01, 'max' => self::CANTIDAD_MAX,
                'tooSmall' => 'La cantidad tiene que ser mayor a 0.'],
            [['precio_unitario'], 'number', 'min' => 0, 'max' => self::PRECIO_MAX],
            [['descuento_monto', 'descuento_porcentaje', 'total'], 'number', 'min' => 0],
            [['descuento_monto', 'descuento_porcentaje'], 'default', 'value' => 0],

            // subtotal / total / % se recalculan siempre en calcular()
        ];
    }

    public function attributeLabels()
    {
        return [
            'seccion'              => 'Sección',
            'nombre'               => 'Descripción',
            'empresa'              => 'Empresa',
            'detalle'              => 'Detalle',
            'unidad'               => 'Unidad',
            'cantidad'             => 'Cantidad',
            'precio_unitario'      => 'Precio',
            'subtotal'             => 'Subtotal',
            'descuento_porcentaje' => 'Desc. %',
            'descuento_monto'      => 'Desc. monto',
            'total'                => 'Total',
        ];
    }

    public function getCotizacion()
    {
        return $this->hasOne(ReservaCotizacion::class, ['idcotizacion' => 'idcotizacion']);
    }

    /**
     * Normaliza montos escritos a mano ("1.260,50") antes de validar.
     */
    public function beforeValidate()
    {
        foreach (['cantidad', 'precio_unitario', 'descuento_monto', 'descuento_porcentaje', 'total'] as $campo) {
            $this->$campo = FormatoHelper::normalizarMonto($this->$campo);
        }
        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        parent::afterValidate();
        if (!$this->hasErrors()) {
            $this->calcular();
        }
    }

    /**
     * Recalcula subtotal, %, descuento y total con aritmética en centavos (sin errores de redondeo).
     *
     * @param string $origen qué dato manda para el descuento:
     *                       'monto' (por defecto), 'porcentaje' o 'total'
     */
    public function calcular(string $origen = 'monto'): void
    {
        $cantidadC = self::aCentavos($this->cantidad);
        $precioC = self::aCentavos($this->precio_unitario);

        // subtotal = cantidad × precio  (centavos × centavos / 100, redondeo comercial)
        $subtotalC = self::dividirRedondeando($cantidadC * $precioC, 100);

        switch ($origen) {
            case 'porcentaje':
                $pct = max(0.0, min(100.0, (float)$this->descuento_porcentaje));
                $descuentoC = (int)round($subtotalC * $pct / 100);
                break;
            case 'total':
                $descuentoC = $subtotalC - self::aCentavos($this->total);
                break;
            default:
                $descuentoC = self::aCentavos($this->descuento_monto);
        }

        // El descuento nunca es negativo ni mayor que el subtotal
        $descuentoC = max(0, min($descuentoC, $subtotalC));

        $this->subtotal = self::deCentavos($subtotalC);
        $this->descuento_monto = self::deCentavos($descuentoC);
        $this->total = self::deCentavos($subtotalC - $descuentoC);
        $this->descuento_porcentaje = $subtotalC > 0
            ? number_format($descuentoC * 100 / $subtotalC, 4, '.', '')
            : '0.0000';
    }

    // ---------------------------------------------------------------- centavos

    /** "1260.5" -> 126050 */
    public static function aCentavos($valor): int
    {
        if ($valor === null || $valor === '') {
            return 0;
        }
        return (int)round(((float)$valor) * 100);
    }

    /** 126050 -> "1260.50" */
    public static function deCentavos(int $centavos): string
    {
        $signo = $centavos < 0 ? '-' : '';
        $centavos = abs($centavos);
        return $signo . intdiv($centavos, 100) . '.' . str_pad((string)($centavos % 100), 2, '0', STR_PAD_LEFT);
    }

    /** División entera con redondeo comercial (0,5 hacia arriba) */
    private static function dividirRedondeando(int $a, int $b): int
    {
        $q = intdiv($a, $b);
        $r = $a % $b;
        if ($r * 2 >= $b) {
            $q++;
        }
        return $q;
    }

    // ---------------------------------------------------------------- catálogo

    /**
     * Crea una línea copiando los datos de un paquete del catálogo.
     * Cantidad inicial = invitados base del paquete (o 1).
     */
    public static function desdePaquete(Paquetes $paquete): self
    {
        $linea = new self();
        $linea->seccion = self::SECCION_PAQUETE;
        $linea->idorigen = $paquete->idpaquete;
        $linea->nombre = $paquete->nombre;
        $linea->detalle = $paquete->detalle;
        $linea->unidad = 'persona';
        $linea->cantidad = $paquete->invitados_incluidos ?: 1;
        $linea->precio_unitario = $paquete->precio;
        $linea->descuento_monto = 0;
        $linea->calcular();
        return $linea;
    }

    /**
     * Crea una línea copiando los datos de un ítem/proveedor del catálogo.
     */
    public static function desdeItem(Items $item): self
    {
        $linea = new self();
        $mapaSeccion = [
            Items::TIPO_PROVEEDOR   => self::SECCION_PROVEEDOR,
            Items::TIPO_EXPERIENCIA => self::SECCION_EXPERIENCIA,
        ];
        $linea->seccion = $mapaSeccion[$item->tipo] ?? self::SECCION_ITEM;
        $linea->idorigen = $item->iditem;
        $linea->nombre = $item->nombre;
        $linea->empresa = $item->empresa;
        $linea->detalle = $item->detalle;
        $linea->unidad = $item->unidad;
        $linea->cantidad = 1;
        $linea->precio_unitario = $item->precio_unitario;
        $linea->descuento_monto = 0;
        $linea->calcular();
        return $linea;
    }

    // ---------------------------------------------------------------- textos

    public function getSeccionTexto(): string
    {
        return self::secciones()[$this->seccion] ?? (string)$this->seccion;
    }

    // ---------------------------------------------------------------- presupuesto: orden y colores

    /** Orden de aparición en el presupuesto: Paquete, Proveedor, Experiencia, Ítem. */
    public const ORDEN_SECCION = [
        self::SECCION_PAQUETE     => 0,
        self::SECCION_PROVEEDOR   => 1,
        self::SECCION_EXPERIENCIA => 2,
        self::SECCION_ITEM        => 3,
    ];

    /** Color del texto de cada tipo en el presupuesto (vista y PDF). */
    public const COLORES_SECCION = [
        self::SECCION_PAQUETE     => '#527f1a', // verde de la página
        self::SECCION_PROVEEDOR   => '#222222', // negro
        self::SECCION_EXPERIENCIA => '#c0392b', // rojo suave
        self::SECCION_ITEM        => '#2574a9', // azul
    ];

    public static function colorSeccion(?string $seccion): string
    {
        return self::COLORES_SECCION[$seccion] ?? '#222222';
    }

    /** Nombre en plural para la referencia de colores. */
    public static function nombresGrupos(): array
    {
        return [
            self::SECCION_PAQUETE     => 'Paquetes',
            self::SECCION_PROVEEDOR   => 'Proveedores',
            self::SECCION_EXPERIENCIA => 'Experiencias',
            self::SECCION_ITEM        => 'Ítems',
        ];
    }

    public function esPrecioCero(): bool
    {
        return self::aCentavos($this->precio_unitario) === 0;
    }

    /**
     * Separa las líneas del presupuesto en dos bloques: precio 0 y precio > 0.
     * Cada bloque queda ordenado por tipo (Paquete, Proveedor, Experiencia, Ítem),
     * respetando el orden original dentro del mismo tipo.
     *
     * @param self[] $lineas
     * @return array{0: self[], 1: self[]}  [cero, conCosto]
     */
    public static function agruparPresupuesto(array $lineas): array
    {
        // Arriba (fondo verde claro): el paquete SIEMPRE, más todo lo de precio 0. Abajo: el resto.
        $cero = [];
        $conCosto = [];
        foreach ($lineas as $l) {
            if ($l->seccion === self::SECCION_PAQUETE || $l->esPrecioCero()) {
                $cero[] = $l;
            } else {
                $conCosto[] = $l;
            }
        }
        $cmp = static function (self $a, self $b): int {
            return (self::ORDEN_SECCION[$a->seccion] ?? 99) <=> (self::ORDEN_SECCION[$b->seccion] ?? 99);
        };
        usort($cero, $cmp);
        usort($conCosto, $cmp);
        return [$cero, $conCosto];
    }

    /** Grupos (tipos) presentes en estas líneas, en el orden del presupuesto, para la referencia de colores. */
    public static function gruposPresentes(array $lineas): array
    {
        $presentes = [];
        foreach ($lineas as $l) {
            $presentes[$l->seccion] = true;
        }
        $orden = [];
        foreach (array_keys(self::ORDEN_SECCION) as $sec) {
            if (isset($presentes[$sec])) {
                $orden[] = $sec;
            }
        }
        return $orden;
    }

    public function getUnidadTexto(): string
    {
        if ($this->unidad === 'persona') {
            return 'Persona';
        }
        return Items::unidades()[$this->unidad] ?? (string)$this->unidad;
    }
}
