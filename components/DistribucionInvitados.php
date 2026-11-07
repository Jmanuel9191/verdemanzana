<?php

namespace app\components;

use app\models\Reservas;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Imagen única de "distribución de la lista de invitados" por reserva.
 * Se guarda en imagen/reservalistainvitados/ con el nombre <usuario>_<idreserva>.<ext>
 * (reservas1 no tiene columna "usuario", así que se usa el email; el id ya la hace única).
 * No usa tabla: para saber si existe, se busca por el id de reserva.
 */
class DistribucionInvitados
{
    public const CARPETA = '@webroot/imagen/reservalistainvitados';
    public const WEB     = '@web/imagen/reservalistainvitados';
    public const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp'];
    public const MAX_BYTES = 15728640; // 15 MB

    private static function dir(): string
    {
        $d = Yii::getAlias(self::CARPETA);
        FileHelper::createDirectory($d, 0775, true);
        return $d;
    }

    /** Nombre base <usuario>_<idreserva> (sin extensión). */
    private static function base(Reservas $reserva): string
    {
        $u = strtolower(trim((string)$reserva->email));
        $u = $u !== '' ? preg_replace('/@.*/', '', $u) : (string)$reserva->nombre;
        $u = preg_replace('/[^a-z0-9]+/i', '', (string)$u);
        if ($u === '') {
            $u = 'reserva';
        }
        return $u . '_' . (int)$reserva->idreservas;
    }

    /** Nombre del archivo si existe la imagen de esa reserva, o null. */
    public static function buscar(int $idreservas): ?string
    {
        $dir = Yii::getAlias(self::CARPETA);
        foreach (self::EXTENSIONES as $ext) {
            $m = glob($dir . '/*_' . $idreservas . '.' . $ext);
            if ($m) {
                return basename($m[0]);
            }
        }
        return null;
    }

    public static function ruta(string $archivo): string
    {
        return Yii::getAlias(self::CARPETA) . '/' . basename($archivo);
    }

    public static function url(string $archivo): string
    {
        return Yii::getAlias(self::WEB) . '/' . rawurlencode(basename($archivo));
    }

    /**
     * Guarda la imagen (reemplaza la anterior de esa reserva).
     * @return array [bool ok, string nombreArchivo|mensajeError]
     */
    public static function guardar(Reservas $reserva, ?UploadedFile $file): array
    {
        if ($file === null) {
            return [false, 'No se eligió ninguna imagen.'];
        }
        $ext = strtolower((string)$file->extension);
        if (!in_array($ext, self::EXTENSIONES, true)) {
            return [false, 'Formato no válido. Subí una imagen JPG, PNG o WEBP.'];
        }
        if ($file->size > self::MAX_BYTES) {
            return [false, 'La imagen pesa más de 15 MB.'];
        }
        $dir = self::dir();
        self::eliminar((int)$reserva->idreservas); // solo una por reserva
        $nombre = self::base($reserva) . '.' . $ext;
        if (!$file->saveAs($dir . '/' . $nombre)) {
            return [false, 'No se pudo guardar la imagen. Probá de nuevo.'];
        }
        return [true, $nombre];
    }

    public static function eliminar(int $idreservas): void
    {
        $dir = Yii::getAlias(self::CARPETA);
        foreach (self::EXTENSIONES as $e) {
            foreach (glob($dir . '/*_' . $idreservas . '.' . $e) as $f) {
                @unlink($f);
            }
        }
    }
}
