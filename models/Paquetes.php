<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\FormatoHelper;

/**

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
            [['nombre', 'tipo_evento'], 'trim'],

            [['nombre', 'precio', 'tipo_evento'], 'required'],

            [['nombre'], 'string', 'max' => 200],
            [['detalle'], 'string'],
        
            [['detalle'], 'filter', 'filter' => [self::class, 'limpiarHtml']],

            [['tipo_evento'], 'in', 'range' => array_keys(self::tiposEvento())],

         
            [['precio'], 'number', 'min' => 0, 'max' => 9999999999.99],
            [['precio'], 'match', 'pattern' => '/^\d+(\.\d{1,2})?$/',
                'message' => 'El precio debe ser un número con hasta 2 decimales (ej. 15000 o 15000.50).'],

            [['invitados_incluidos', 'horas_incluidas'], 'default', 'value' => null],
            [['invitados_incluidos', 'horas_incluidas'], 'integer', 'min' => 0],

            [['estado'], 'default', 'value' => self::ESTADO_ACTIVO],
            [['estado'], 'in', 'range' => array_keys(self::estados())],

            [['posicion'], 'default', 'value' => 0],
            [['posicion'], 'integer', 'min' => 0],

        ];
    }

    public function attributeLabels()
    {
        return [
            'idpaquete'           => 'ID',
            'nombre'              => 'Nombre del paquete',
            'detalle'             => 'Detalle (qué incluye)',
            'tipo_evento'         => 'Tipo de evento',
            'precio'              => 'Precio (Bs)',
            'invitados_incluidos' => 'Invitados incluidos',
            'horas_incluidas'     => 'Horas incluidas',
            'estado'              => 'Estado',
            'posicion'            => 'Posición',
            'fecha_registro'      => 'Fecha de registro',
            'fecha_actualizacion' => 'Última actualización',
        ];
    }

    /**
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

    public function getPrecioTexto(): string
    {
        return self::formatoBs($this->precio);
    }

    public function getEstadoTexto(): string
    {
        return self::estados()[(int)$this->estado] ?? '';
    }

    /**

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
