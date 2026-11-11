<?php

namespace app\components;

use app\models\Reservas;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

/**
 * Archivo único de "Guía" por reserva (lo sube el administrador).
 * Admite PDF, Word, Excel o imagen. Se guarda en imagen/guiareserva/
 * con el nombre <usuario>_<idreserva>.<ext> (el id ya lo hace único).
 * No usa tabla: para saber si existe, se busca por el id de reserva.
 */
class GuiaReserva
{
    public const CARPETA = '@webroot/imagen/guiareserva';
    public const WEB     = '@web/imagen/guiareserva';
    public const EXTENSIONES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'webp'];
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

    /** Nombre del archivo si existe la guía de esa reserva, o null. */
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

    /** Nombre lindo para la descarga: Guia_<evento>.<ext> */
    public static function nombreDescarga(Reservas $reserva, string $archivo): string
    {
        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        $base = Inflector::slug('Guia ' . (string)$reserva->nombre, '_', false);
        return ($base !== '' ? $base : 'Guia') . '.' . $ext;
    }

    /**
     * Guarda el archivo (reemplaza el anterior de esa reserva).
     * @return array [bool ok, string nombreArchivo|mensajeError]
     */
    public static function guardar(Reservas $reserva, ?UploadedFile $file): array
    {
        if ($file === null) {
            return [false, 'No se eligió ningún archivo.'];
        }
        $ext = strtolower((string)$file->extension);
        if (!in_array($ext, self::EXTENSIONES, true)) {
            return [false, 'Formato no válido. Subí un PDF, Word, Excel o imagen (JPG, PNG o WEBP).'];
        }
        if ($file->size > self::MAX_BYTES) {
            return [false, 'El archivo pesa más de 15 MB.'];
        }
        $dir = self::dir();
        self::eliminar((int)$reserva->idreservas); // solo uno por reserva
        $nombre = self::base($reserva) . '.' . $ext;
        if (!$file->saveAs($dir . '/' . $nombre)) {
            return [false, 'No se pudo guardar el archivo. Probá de nuevo.'];
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
