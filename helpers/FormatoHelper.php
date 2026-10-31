<?php

namespace app\helpers;

use yii\helpers\HtmlPurifier;

/**
 * Funciones compartidas por los catálogos (Paquetes, Ítems/Proveedores) y,
 * más adelante, por el presupuesto de la reserva.
 */
class FormatoHelper
{
    /**
     * Convierte un monto escrito por una persona al formato de la base (DECIMAL).
     *   "20000" "20.000" "20,000" "20 000" "20.000,50" "20000,50"  ->  "20000" / "20000.50"
     * Regla: punto o coma seguido de 3 dígitos = miles; de 1 o 2 dígitos = decimales.
     * Si no es un número, devuelve el texto tal cual para que la validación muestre el error.
     */
    public static function normalizarMonto($valor)
    {
        if (!is_string($valor) || $valor === '') {
            return $valor;
        }
        $p = str_replace(' ', '', $valor);
        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/', $p)) {
            $p = str_replace(['.', ','], ['', '.'], $p);  
        } elseif (preg_match('/^\d{1,3}(,\d{3})+(\.\d{1,2})?$/', $p)) {
            $p = str_replace(',', '', $p);                
        } else {
            $p = str_replace(',', '.', $p);               
        }
        return $p;
    }

    /**
     * 20000.5 -> "Bs 20.000,50"
     */
    public static function formatoBs($monto): string
    {
        return 'Bs ' . number_format((float)$monto, 2, ',', '.');
    }

  
    public static function limpiarHtml($html): ?string
    {
        if ($html === null || trim((string)$html) === '') {
            return null;
        }
        $limpio = HtmlPurifier::process((string)$html, [
            'HTML.Allowed' => 'p[style],h2[style],h3[style],h4[style],span[style],br,strong,b,em,i,u,s,ul,ol,li[style],a[href]',
            'CSS.AllowedProperties' => [
                'font-size', 'color', 'background-color', 'text-align',
                'font-weight', 'font-style', 'text-decoration',
            ],
            'AutoFormat.RemoveEmpty' => true,
            'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true],
        ]);
        $limpio = trim($limpio);
        return $limpio === '' ? null : $limpio;
    }

  
    public static function opcionesEditor(int $alto = 260): array
    {
        return [
            'options' => ['rows' => 6],
            'preset' => 'custom',
            'clientOptions' => [
                'language' => 'es',
                'height' => $alto,
                'extraPlugins' => 'font,colorbutton,justify',
                'toolbar' => [
                    ['name' => 'formato', 'items' => ['Format', 'FontSize']],
                    ['name' => 'estilos', 'items' => ['Bold', 'Italic', 'Underline', 'Strike', '-', 'TextColor', 'BGColor', '-', 'RemoveFormat']],
                    ['name' => 'parrafo', 'items' => ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']],
                    ['name' => 'enlaces', 'items' => ['Link', 'Unlink']],
                    ['name' => 'deshacer', 'items' => ['Undo', 'Redo']],
                ],
                'format_tags' => 'p;h2;h3;h4',
                'fontSize_sizes' => '10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;24/24px;28/28px;32/32px',
                // Lo mismo que permite limpiarHtml(): lo demás se quita al pegar desde Word
                'allowedContent' => 'p h2 h3 h4 li{text-align}; span{font-size,color,background-color}; strong em u s ul ol li br; a[!href]',
                'pasteFromWordRemoveFontStyles' => false,
                'removePlugins' => 'elementspath',
                'removeDialogTabs' => 'link:advanced;link:target',
                'resize_enabled' => false,
                'versionCheck' => false,
            ],
        ];
    }
}
