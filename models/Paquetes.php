<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\FormatoHelper;

/**
 * Catálogo de paquetes (tabla "paquetes").
 *
 * Es una PLANTILLA: cuando un paquete se usa en una reserva, sus datos se copian
 * a la reserva. Editar o desactivar un paquete no modifica reservas ya guardadas.
 *
 * @property int         $idpaquete
 * @property string      $nombre
 * @property string|null $detalle
 * @property string      $tipo_evento
 * @property string      $precio
 * @property int|null    $invitados_incluidos
 * @property int|null    $horas_incluidas
 * @property int         $estado
 * @property int         $posicion
 * @property string      $fecha_registro
 * @property string      $fecha_actualizacion
 */
class Paquetes extends ActiveRecord
{
    public const ESTADO_INACTIVO = 0;
    public const ESTADO_ACTIVO = 1;

    public static function tableName()
    {
        return 'paquetes';
    }

    /**
     * Opciones de tipo de evento. Para agregar uno nuevo (ej. 'infantil' => 'Infantil')
     * basta con sumarlo acá: la columna es texto y no hace falta tocar la base.
     */
    public static function tiposEvento(): array
    {
        return [
            'todos'      => 'Todos',
            'matrimonio' => 'Matrimonio',
            'quinceanos' => 'Quince años',
            'otros'      => 'Otros',
        ];
    }

    public static function estados(): array
    {
        return [
            self::ESTADO_ACTIVO   => 'Activo',
            self::ESTADO_INACTIVO => 'Inactivo',
        ];
    }

    public function rules()
    {
        return [
            // Limpieza de espacios antes de validar
            [['nombre', 'tipo_evento'], 'trim'],

            [['nombre', 'precio', 'tipo_evento'], 'required'],

            [['nombre'], 'string', 'max' => 200],
            [['detalle'], 'string'],
            // Limpieza del HTML en el servidor (aunque se salteen el editor):
            // solo quedan párrafos, negrita, cursiva, subrayado, listas y enlaces.
            [['detalle'], 'filter', 'filter' => [self::class, 'limpiarHtml']],

            [['tipo_evento'], 'in', 'range' => array_keys(self::tiposEvento())],

            // Precio en Bs: número >= 0 con hasta 2 decimales (DECIMAL(12,2) en la base)
            [['precio'], 'number', 'min' => 0, 'max' => 9999999999.99],
            [['precio'], 'match', 'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'El precio debe ser un número con hasta 2 decimales (ej. 15000 o 15000.50).'],

            // Opcionales: vacío = NULL (no "0")
            [['invitados_incluidos', 'horas_incluidas'], 'default', 'value' => null],
            [['invitados_incluidos', 'horas_incluidas'], 'integer', 'min' => 0],

            [['estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['estado'], 'in', 'range' => array_keys(self::estados())],

            [['posicion'], 'default', 'value' => 0],
            [['posicion'], 'integer', 'min' => 0],

            // Las fechas las maneja la base de datos; no se reciben del formulario.
        ];
    }

    public function attributeLabels()
    {
        return [
            'idpaquete'           => 'ID',
            'nombre'              => 'Nombre del paquete',
            'detalle'             => 'Detalle (qué incluye)',
            'tipo_evento'         => 'Tipo de evento',
            'precio'              => 'Precio por persona (Bs)',
            'invitados_incluidos' => 'Invitados (base)',
            'horas_incluidas'     => 'Horas incluidas',
            'estado'              => 'Estado',
            'posicion'            => 'Posición',
            'fecha_registro'      => 'Fecha de registro',
            'fecha_actualizacion' => 'Última actualización',
        ];
    }

    /**
     * Acepta precios como "20.000", "20.000,50" o "20000,50" (ver FormatoHelper).
     */
    public function beforeValidate()
    {
        $this->precio = FormatoHelper::normalizarMonto($this->precio);
        return parent::beforeValidate();
    }

    // ---------- Ayudas para vistas y para el formulario de reservas ----------

    public function getTipoEventoTexto(): string
    {
        return self::tiposEvento()[$this->tipo_evento] ?? (string)$this->tipo_evento;
    }

    public static function limpiarHtml($html): ?string
    {
        return FormatoHelper::limpiarHtml($html);
    }

    public static function formatoBs($monto): string
    {
        return FormatoHelper::formatoBs($monto);
    }

    /** "Bs 280,00 / persona" */
    public function getPrecioTexto(): string
    {
        return self::formatoBs($this->precio) . ' / persona';
    }

    public function getEstadoTexto(): string
    {
        return self::estados()[(int)$this->estado] ?? '';
    }

    /**
     * Paquetes activos para un selector, ordenados por posición y nombre.
     * Si se pasa un tipo de evento, incluye también los marcados como "todos".
     *
     * @return array [idpaquete => nombre]
     */
    public static function listaActivos(?string $tipoEvento = null): array
    {
        $query = self::find()
            ->select(['nombre', 'idpaquete'])
            ->where(['estado' => self::ESTADO_ACTIVO])
            ->orderBy(['posicion' => SORT_ASC, 'nombre' => SORT_ASC]);

        if ($tipoEvento !== null && $tipoEvento !== '') {
            $query->andWhere(['tipo_evento' => ['todos', $tipoEvento]]);
        }

        return $query->indexBy('idpaquete')->column();
    }
}
