<?php

namespace app\models;

use app\helpers\FormatoHelper;
use yii\db\ActiveRecord;

/**
 * Catálogo de Ítems y Proveedores (tabla "items").
 *
 *  - Ítem:      lo agrega Verde Manzana (sillas, mesas, garzón extra, hora extra...).
 *  - Proveedor: servicio de terceros que se cobra en el presupuesto (DJ, barra, catering...).
 *
 * El "tipo" (Ítem / Proveedor) funciona como su categoría: no hay subcategorías.
 *
 * Es una PLANTILLA: al usarse en una reserva se copia; editar aquí no cambia reservas guardadas.
 *
 * @property int         $iditem
 * @property string      $tipo
 * @property string      $nombre
 * @property string|null $empresa
 * @property string|null $telefono
 * @property string|null $detalle
 * @property string      $unidad
 * @property string      $precio_unitario
 * @property string      $tipo_evento
 * @property int         $estado
 * @property int         $posicion
 * @property string      $fecha_registro
 * @property string      $fecha_actualizacion
 */
class Items extends ActiveRecord
{
    public const TIPO_ITEM = 'item';
    public const TIPO_PROVEEDOR = 'proveedor';

    public const ESTADO_INACTIVO = 0;
    public const ESTADO_ACTIVO = 1;

    public static function tableName()
    {
        return 'items';
    }

    // ---------------------------------------------------------------- listas

    public static function tipos(): array
    {
        return [
            self::TIPO_ITEM      => 'Ítem',
            self::TIPO_PROVEEDOR => 'Proveedor',
        ];
    }

    /**
     * Unidades de cobro. Para agregar otra (ej. 'metro' => 'Metro') basta sumarla acá.
     */
    public static function unidades(): array
    {
        return [
            'unidad'   => 'Unidad',
            'persona'  => 'Persona',
            'hora'     => 'Hora',
            'servicio' => 'Servicio',
        ];
    }

    public static function tiposEvento(): array
    {
        return Paquetes::tiposEvento();
    }

    public static function estados(): array
    {
        return Paquetes::estados();
    }

    // ---------------------------------------------------------------- reglas

    public function rules()
    {
        return [
            [['tipo', 'nombre', 'empresa', 'telefono', 'unidad', 'tipo_evento'], 'trim'],

            [['tipo', 'nombre', 'unidad', 'precio_unitario', 'tipo_evento'], 'required'],

            [['tipo'], 'in', 'range' => array_keys(self::tipos())],

            [['nombre', 'empresa'], 'string', 'max' => 200],
            [['telefono'], 'string', 'max' => 50],
            [['empresa', 'telefono'], 'default', 'value' => null],

            [['detalle'], 'string'],
            [['detalle'], 'filter', 'filter' => [FormatoHelper::class, 'limpiarHtml']],

            [['unidad'], 'in', 'range' => array_keys(self::unidades())],
            [['tipo_evento'], 'in', 'range' => array_keys(self::tiposEvento())],

            [['precio_unitario'], 'number', 'min' => 0, 'max' => 9999999999.99],
            [['precio_unitario'], 'match', 'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'El precio debe ser un número con hasta 2 decimales (ej. 15 o 15,50).'],

            [['estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['estado'], 'in', 'range' => array_keys(self::estados())],

            [['posicion'], 'default', 'value' => 0],
            [['posicion'], 'integer', 'min' => 0],

            // Si es ítem, empresa y teléfono no aplican
            [['empresa', 'telefono'], 'filter', 'filter' => function ($v) {
                return $this->tipo === self::TIPO_PROVEEDOR ? $v : null;
            }],

            // Las fechas las pone la base.
        ];
    }

    public function attributeLabels()
    {
        return [
            'iditem'              => 'ID',
            'tipo'                => 'Tipo',
            'nombre'              => 'Nombre',
            'empresa'             => 'Empresa / proveedor',
            'telefono'            => 'Teléfono de contacto',
            'detalle'             => 'Detalle',
            'unidad'              => 'Se cobra por',
            'precio_unitario'     => 'Precio unitario (Bs)',
            'tipo_evento'         => 'Tipo de evento',
            'estado'              => 'Estado',
            'posicion'            => 'Posición',
            'fecha_registro'      => 'Fecha de registro',
            'fecha_actualizacion' => 'Última actualización',
        ];
    }

    public function beforeValidate()
    {
        $this->precio_unitario = FormatoHelper::normalizarMonto($this->precio_unitario);
        return parent::beforeValidate();
    }

    // ---------------------------------------------------------------- textos

    public function getTipoTexto(): string
    {
        return self::tipos()[$this->tipo] ?? (string)$this->tipo;
    }

    public function getUnidadTexto(): string
    {
        return self::unidades()[$this->unidad] ?? (string)$this->unidad;
    }

    public function getTipoEventoTexto(): string
    {
        return self::tiposEvento()[$this->tipo_evento] ?? (string)$this->tipo_evento;
    }

    public function getEstadoTexto(): string
    {
        return self::estados()[(int)$this->estado] ?? '';
    }

    /** "Bs 15,00 / unidad" */
    public function getPrecioTexto(): string
    {
        return FormatoHelper::formatoBs($this->precio_unitario) . ' / ' . mb_strtolower($this->getUnidadTexto());
    }


    /**
     * Ítems/proveedores activos para un selector de la reserva, agrupados por tipo.
     * @return array ['Ítems' => [id => nombre], 'Proveedores' => [id => 'nombre (empresa)']]
     */
    public static function listaActivos(?string $tipoEvento = null): array
    {
        $query = self::find()
            ->where(['estado' => self::ESTADO_ACTIVO])
            ->orderBy(['tipo' => SORT_ASC, 'posicion' => SORT_ASC, 'nombre' => SORT_ASC]);

        if ($tipoEvento !== null && $tipoEvento !== '') {
            $query->andWhere(['tipo_evento' => ['todos', $tipoEvento]]);
        }

        $resultado = [];
        foreach ($query->all() as $item) {
            $grupo = $item->tipo === self::TIPO_PROVEEDOR ? 'Proveedores' : 'Ítems';
            $resultado[$grupo][$item->iditem] = $item->nombre . ($item->empresa ? ' (' . $item->empresa . ')' : '');
        }
        return $resultado;
    }
}
