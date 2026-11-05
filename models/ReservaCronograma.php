<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * Cronograma de una reserva nueva (Paso 4). Una fila por reserva.
 *
 * El contenido lo arma la coordinadora en Word y lo PEGA en el editor:
 * se guarda con su formato (tablas, bordes, colores, fuentes, alineación)
 * para que el admin y el cliente lo vean "tal cual". Antes de guardar se
 * limpia con HTMLPurifier: se quitan scripts, eventos, iframes, imágenes
 * y cualquier cosa peligrosa, pero se respeta el formato.
 *
 * No toca reservas1: se engancha por idreservas.
 *
 * @property int         $idcronograma
 * @property int         $idreservas
 * @property string|null $contenido
 * @property int|null    $idadmin_registro
 * @property int|null    $idadmin_actualizo
 * @property string      $fecha_registro
 * @property string      $fecha_actualizacion
 *
 * @property Administrador|null $adminActualizo
 */
class ReservaCronograma extends ActiveRecord
{
    /** Tamaño máximo del HTML pegado (Word genera mucho código): 2 MB */
    public const TAMANO_MAX = 2 * 1024 * 1024;

    public static function tableName()
    {
        return 'reserva_cronograma';
    }

    public function rules()
    {
        return [
            [['contenido'], 'string', 'max' => self::TAMANO_MAX,
                'tooLong' => 'El cronograma es demasiado grande. Probá pegarlo por partes o sin imágenes.'],
            [['contenido'], 'filter', 'filter' => [self::class, 'limpiar']],
        ];
    }

    public function attributeLabels()
    {
        return ['contenido' => 'Cronograma'];
    }

    public function getAdminActualizo()
    {
        return $this->hasOne(Administrador::class, ['idadmin' => 'idadmin_actualizo']);
    }

    /** Cronograma de la reserva (nuevo, sin guardar, si todavía no tiene). */
    public static function deReserva(int $idreservas): self
    {
        $c = self::findOne(['idreservas' => $idreservas]);
        if ($c === null) {
            $c = new self();
            $c->idreservas = $idreservas;
        }
        return $c;
    }

    /** Título que se muestra: "Cronograma de {Nombre del evento}". */
    public static function titulo(Reservas $reserva): string
    {
        $evento = trim((string)$reserva->tipo_evento);
        return 'Cronograma de ' . ($evento !== '' ? $evento : trim((string)$reserva->nombre));
    }

    public function tieneContenido(): bool
    {
        return trim(strip_tags((string)$this->contenido, '<table><img>')) !== '';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $idAdmin = self::idAdminActual();
        if ($insert) {
            $this->idadmin_registro = $idAdmin;
        }
        $this->idadmin_actualizo = $idAdmin;
        return true;
    }

    // ---------------------------------------------------------------- limpieza

    /**
     * Limpia el HTML pegado desde Word conservando el formato.
     * Permitido: párrafos, títulos, listas, tablas (con bordes, anchos, celdas
     * combinadas), colores, fondos, fuentes, tamaños, alineación, enlaces.
     * Se quita: scripts, eventos (onclick…), iframes, formularios, imágenes,
     * estilos raros de Word (mso-…), clases e ids.
     */
    public static function limpiar($html): ?string
    {
        if ($html === null || trim((string)$html) === '') {
            return null;
        }

        // Word marca el resaltado como "background:yellow" (forma corta). Se pasa a
        // "background-color" para conservarlo. Un fondo con imagen (url(...)) no se convierte
        // y lo quita el purificador.
        $html = preg_replace(
            '/(?<![-\w])background\s*:\s*(?![^;"\']*url\s*\()([^;"\']+)/i',
            'background-color:$1',
            (string)$html
        );

        $limpio = HtmlPurifier::process((string)$html, [
            'HTML.Allowed' => implode(',', [
                'p[style|align]', 'div[style|align]', 'span[style]', 'br', 'hr',
                'b', 'strong', 'i', 'em', 'u', 's', 'strike', 'sub', 'sup',
                'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'h5[style]', 'h6[style]',
                'ul[style]', 'ol[style|start]', 'li[style]', 'blockquote[style]',
                'a[href]',
                'table[style|border|cellpadding|cellspacing|width|align]', 'caption[style]',
                'colgroup', 'col[width|span|style]', 'thead', 'tbody', 'tfoot',
                'tr[style]',
                'td[style|colspan|rowspan|width|height|align|valign]',
                'th[style|colspan|rowspan|width|height|align|valign]',
            ]),
            'CSS.AllowedProperties' => [
                'font-family', 'font-size', 'font-weight', 'font-style', 'font-variant',
                'color', 'background-color',
                'text-align', 'text-decoration', 'text-indent', 'text-transform',
                'line-height', 'letter-spacing', 'white-space', 'vertical-align',
                'width', 'height',
                'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
                'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
                'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
                'border-color', 'border-style', 'border-width', 'border-collapse',
                'border-top-color', 'border-right-color', 'border-bottom-color', 'border-left-color',
                'border-top-style', 'border-right-style', 'border-bottom-style', 'border-left-style',
                'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width',
                'list-style-type',
            ],
            'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true],
            // Word deja párrafos vacíos para separar: se respetan
            'AutoFormat.RemoveEmpty' => false,
            'Attr.EnableID' => false,
        ]);

        $limpio = trim($limpio);
        return $limpio === '' ? null : $limpio;
    }

    // ---------------------------------------------------------------- editor

    /**
     * Opciones de CKEditor para pegar desde Word "tal cual".
     * El editor acepta todo lo pegado (allowedContent = true) y el servidor
     * lo limpia al guardar con limpiar().
     */
    public static function opcionesEditor(int $alto = 520): array
    {
        return [
            'options' => ['rows' => 20],
            'preset' => 'custom',
            'clientOptions' => [
                'language' => 'es',
                'height' => $alto,
                'extraPlugins' => 'font,colorbutton,justify,tableresize',
                'toolbar' => [
                    ['name' => 'pegar', 'items' => ['PasteFromWord', 'PasteText', '-', 'Undo', 'Redo']],
                    ['name' => 'formato', 'items' => ['Format', 'Font', 'FontSize']],
                    ['name' => 'estilos', 'items' => ['Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat']],
                    '/',
                    ['name' => 'parrafo', 'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']],
                    ['name' => 'insertar', 'items' => ['Table', 'HorizontalRule', 'Link', 'Unlink']],
                    ['name' => 'ver', 'items' => ['Maximize']],
                ],
                // Se acepta todo lo que venga de Word; la limpieza la hace el servidor
                'allowedContent' => true,
                'pasteFromWordRemoveFontStyles' => false,
                'pasteFromWord_inlineImages' => false,
                'disallowedContent' => 'img script iframe object embed form input button; *[on*]',
                'removePlugins' => 'elementspath,image,uploadimage',
                'resize_enabled' => true,
                'versionCheck' => false,
            ],
        ];
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
