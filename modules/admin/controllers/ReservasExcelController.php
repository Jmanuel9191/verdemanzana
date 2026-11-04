<?php

namespace app\modules\admin\controllers;

use app\helpers\FormatoHelper;
use app\models\Organizadoras;
use app\models\PresupuestoForm;
use app\models\ReservaCotizacion;
use app\models\ReservaPago;
use app\models\ReservasSearch;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date as FechaExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Descarga en Excel de la lista de reservas, con los MISMOS filtros y orden
 * que se están viendo en el listado (usa ReservasSearch).
 *
 * Lo usan administradores tipo 1 y tipo 2 (no está en $soloAdminPrincipal).
 */
class ReservasExcelController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        @set_time_limit(120);

        $search = new ReservasSearch();
        $proveedor = $search->search(Yii::$app->request->queryParams);
        $proveedor->pagination = false;
        /** @var \app\models\Reservas[] $reservas */
        $reservas = $proveedor->getModels();

        $ids = array_map(function ($r) {
            return (int)$r->idreservas;
        }, $reservas);

        // Datos de las reservas nuevas (presupuesto por pasos), en pocas consultas
        $cotizaciones = $ids ? ReservaCotizacion::find()->where(['idreservas' => $ids])->indexBy('idreservas')->all() : [];
        $pagados = $ids ? ReservaPago::find()
            ->select(['idreservas', 'suma' => 'SUM(monto)'])
            ->where(['idreservas' => $ids, 'estado' => ReservaPago::ESTADO_VALIDO])
            ->groupBy('idreservas')->asArray()->all() : [];
        $pagadoPorReserva = [];
        foreach ($pagados as $p) {
            $pagadoPorReserva[(int)$p['idreservas']] = (float)$p['suma'];
        }
        $organizadoras = Organizadoras::find()->select(['nombre', 'idorganizadora'])->indexBy('idorganizadora')->column();

        // ---------------------------------------------------------------- hoja
        $libro = new Spreadsheet();
        $libro->getProperties()->setCreator('Verde Manzana')->setTitle('Reservas');
        $hoja = $libro->getActiveSheet();
        $hoja->setTitle('Reservas');

        $columnas = [
            // titulo, ancho, tipo (texto | fecha | numero | entero)
            ['N.º reserva', 11, 'entero'],
            ['Estado', 10, 'texto'],
            ['Fecha del evento', 14, 'fecha'],
            ['Hora', 10, 'texto'],
            ['Sucursal', 22, 'texto'],
            ['Tipo de evento', 14, 'texto'],
            ['Nombre del evento', 30, 'texto'],
            ['Cliente', 28, 'texto'],
            ['Teléfono', 14, 'texto'],
            ['Email / usuario', 30, 'texto'],
            ['Paquete', 22, 'texto'],
            ['Invitados', 10, 'entero'],
            ['Organizadora', 24, 'texto'],
            ['Comisión (Bs)', 13, 'numero'],
            ['Garzones', 10, 'entero'],
            ['Total (Bs)', 14, 'numero'],
            ['Cancelado (Bs)', 14, 'numero'],
            ['Saldo (Bs)', 14, 'numero'],
            ['Formulario', 11, 'texto'],
            ['Fecha de registro', 17, 'fechahora'],
        ];

        foreach ($columnas as $i => [$titulo, $ancho]) {
            $letra = Coordinate::stringFromColumnIndex($i + 1);
            $hoja->setCellValue($letra . '1', $titulo);
            $hoja->getColumnDimension($letra)->setWidth($ancho);
        }
        $ultima = Coordinate::stringFromColumnIndex(count($columnas));

        $fila = 2;
        foreach ($reservas as $r) {
            $id = (int)$r->idreservas;
            $c = $cotizaciones[$id] ?? null;

            if ($c !== null) {
                // Reserva nueva: números exactos de la cotización y los pagos
                $total = (float)$c->total;
                $cancelado = $pagadoPorReserva[$id] ?? 0.0;
                $saldo = round($total - $cancelado, 2);
            } else {
                // Reserva vieja: los montos eran texto libre; se convierten si se puede
                $total = self::monto($r->presupuesto);
                $cancelado = self::monto($r->monto_canceladop);
                $saldo = self::monto($r->saldo_pagarp);
            }

            $valores = [
                $id,
                (int)$r->estado === 1 ? 'Activa' : 'Inactiva',
                $r->fecha_reserva,
                (string)$r->hora_inicio,
                (string)$r->sucursal,
                PresupuestoForm::TIPOS_EVENTO[$r->tipoevento] ?? (string)$r->tipoevento,
                (string)$r->tipo_evento,
                (string)$r->nombre,
                (string)$r->telefono,
                (string)$r->email,
                (string)$r->paquete_reservar,
                $r->limite_invitados,
                $organizadoras[(int)$r->idorganizadora] ?? '',
                self::monto($r->comision_organizadora),
                $r->cant_garzones,
                $total,
                $cancelado,
                $saldo,
                $c !== null ? 'Nuevo' : 'Anterior',
                $r->fecha_registro,
            ];

            foreach ($valores as $i => $valor) {
                $celda = Coordinate::stringFromColumnIndex($i + 1) . $fila;
                $tipo = $columnas[$i][2];
                self::escribir($hoja, $celda, $valor, $tipo);
            }
            $fila++;
        }
        $ultimaFila = max(1, $fila - 1);

        // ---------------------------------------------------------------- formato
        $encabezado = $hoja->getStyle("A1:{$ultima}1");
        $encabezado->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $encabezado->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('527F1A');
        $encabezado->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $hoja->getRowDimension(1)->setRowHeight(30);
        $hoja->freezePane('A2');
        if ($ultimaFila >= 2) {
            $hoja->setAutoFilter("A1:{$ultima}{$ultimaFila}");
        }

        foreach ($columnas as $i => [, , $tipo]) {
            $letra = Coordinate::stringFromColumnIndex($i + 1);
            $rango = "{$letra}2:{$letra}" . max(2, $ultimaFila);
            if ($tipo === 'numero') {
                $hoja->getStyle($rango)->getNumberFormat()->setFormatCode('#,##0.00');
            } elseif ($tipo === 'fecha') {
                $hoja->getStyle($rango)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            } elseif ($tipo === 'fechahora') {
                $hoja->getStyle($rango)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');
            }
        }

        // Totales al final (solo cuentan las celdas con número)
        if ($ultimaFila >= 2) {
            $filaTotal = $ultimaFila + 1;
            $hoja->setCellValue('A' . $filaTotal, 'TOTALES (' . ($ultimaFila - 1) . ' reservas)');
            foreach ($columnas as $i => [$titulo, , $tipo]) {
                if ($tipo !== 'numero') {
                    continue;
                }
                $letra = Coordinate::stringFromColumnIndex($i + 1);
                $hoja->setCellValue("{$letra}{$filaTotal}", "=SUBTOTAL(9,{$letra}2:{$letra}{$ultimaFila})");
                $hoja->getStyle("{$letra}{$filaTotal}")->getNumberFormat()->setFormatCode('#,##0.00');
            }
            $estiloTotal = $hoja->getStyle("A{$filaTotal}:{$ultima}{$filaTotal}");
            $estiloTotal->getFont()->setBold(true);
            $estiloTotal->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF4E6');
        }

        // ---------------------------------------------------------------- descarga
        $tmp = Yii::getAlias('@runtime/excel');
        FileHelper::createDirectory($tmp, 0775, true);
        $archivo = $tmp . DIRECTORY_SEPARATOR . 'reservas_' . Yii::$app->security->generateRandomString(8) . '.xlsx';
        (new Xlsx($libro))->save($archivo);
        $libro->disconnectWorksheets();

        $contenido = file_get_contents($archivo);
        @unlink($archivo);

        return Yii::$app->response->sendContentAsFile($contenido, 'reservas_' . date('Y-m-d_Hi') . '.xlsx', [
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Escribe una celda según el tipo (las fechas como fecha real de Excel, para poder ordenar/filtrar).
     */
    private static function escribir($hoja, string $celda, $valor, string $tipo): void
    {
        if ($valor === null || $valor === '') {
            return;
        }
        switch ($tipo) {
            case 'fecha':
            case 'fechahora':
                $t = strtotime((string)$valor);
                if ($t !== false && (int)date('Y', $t) > 1900) {
                    $hoja->setCellValue($celda, FechaExcel::PHPToExcel($tipo === 'fecha' ? date('Y-m-d', $t) : date('Y-m-d H:i:s', $t)));
                }
                return;
            case 'entero':
                $s = trim((string)$valor);
                if (ctype_digit($s)) {
                    $hoja->setCellValue($celda, (int)$s);
                } else {
                    $hoja->setCellValueExplicit($celda, $s, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
                return;
            case 'numero':
                if (is_float($valor) || is_int($valor)) {
                    $hoja->setCellValue($celda, $valor);
                } else {
                    $hoja->setCellValueExplicit($celda, (string)$valor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
                return;
            default:
                // Texto siempre como texto (teléfonos con 0 adelante, "=" al inicio, etc.)
                $hoja->setCellValueExplicit($celda, (string)$valor, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
    }

    /**
     * Convierte un monto escrito como texto ("Bs 7.235,00", "1500", "1.500,50") a número.
     * Si no se puede, devuelve el texto tal cual (o null si está vacío).
     */
    private static function monto($texto)
    {
        $t = trim((string)$texto);
        if ($t === '') {
            return null;
        }
        $limpio = trim(str_ireplace(['bs.', 'bs', 'Bs'], '', $t));
        $n = FormatoHelper::normalizarMonto($limpio);
        return is_numeric($n) ? (float)$n : $t;
    }
}
