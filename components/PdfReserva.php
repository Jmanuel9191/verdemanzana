<?php

namespace app\components;

use app\components\DistribucionInvitados;
use app\models\Configuracion;
use app\models\Invitados;
use app\models\ReservaCotizacion;
use app\models\ReservaCronograma;
use app\models\ReservaPago;
use app\models\Reservas;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;

/**
 * Arma los PDF de una reserva nueva (con presupuesto por pasos) usando mPDF.
 *
 * Tipos:
 *  - presupuesto : el presupuesto para el cliente (líneas, descuentos, total, cancelado, saldo)
 *  - pagos       : estado de cuenta (solo pagos válidos)
 *  - detalles    : organizadora, equipamiento, decoración, coordinación
 *  - cronograma  : el cronograma pegado desde Word, con su formato
 *  - completo    : todo junto, cada parte en su página (solo pagos válidos, como el estado de cuenta)
 *
 * Las plantillas están en @app/views/pdf-reserva/ para poder usarlas también
 * desde la vista del cliente más adelante.
 */
class PdfReserva
{
    public const TIPOS = [
        'presupuesto' => 'Presupuesto',
        'pagos'       => 'Estado de cuenta',
        'detalles'    => 'Detalles del evento',
        'cronograma'  => 'Cronograma',
        'completo'    => 'Reserva completa',
    ];

    /**
     * @param array $opciones  'mostrarComision' => bool (por defecto true; false para la
     *                         vista pública cuando no es la organizadora del evento o es el cliente)
     * @return array [contenido binario del PDF, nombre de archivo sugerido]
     * @throws NotFoundHttpException
     */
    public static function generar(Reservas $reserva, string $tipo, array $opciones = []): array
    {
        if (!isset(self::TIPOS[$tipo])) {
            throw new NotFoundHttpException('Tipo de PDF desconocido.');
        }
        $cotizacion = ReservaCotizacion::deReserva((int)$reserva->idreservas);
        if ($cotizacion === null) {
            throw new NotFoundHttpException('Esta reserva no tiene presupuesto por pasos.');
        }

        $id = (int)$reserva->idreservas;
        $completo = $tipo === 'completo';
        $datos = [
            'tipo'         => $tipo,
            'titulo'       => self::TIPOS[$tipo],
            'reserva'      => $reserva,
            'cotizacion'   => $cotizacion,
            'empresa'      => Configuracion::find()->one(),
            'logo'         => self::rutaLogo(),
            'secciones'    => $completo ? ['presupuesto', 'pagos', 'detalles', 'cronograma'] : [$tipo],
            // Los PDF muestran solo pagos válidos (los anulados se ven en el sistema, no en el PDF)
            'conAnulados'  => false,
            'pagos'        => ReservaPago::find()
                ->where(['idreservas' => $id, 'estado' => ReservaPago::ESTADO_VALIDO])
                ->with(['adminAnulo'])
                ->orderBy(['fecha_pago' => SORT_ASC, 'idpago' => SORT_ASC])
                ->all(),
            'cronograma'   => ReservaCronograma::deReserva($id),
            'invitados'    => (int)Invitados::find()->where(['idreservas' => $id])->count(),
            'mostrarComision' => $opciones['mostrarComision'] ?? true,
        ];

        $html = Yii::$app->view->render('@app/views/pdf-reserva/documento', $datos);

        $tmp = Yii::getAlias('@runtime/mpdf');
        FileHelper::createDirectory($tmp, 0775, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'Letter',
            'margin_left'   => 14,
            'margin_right'  => 14,
            'margin_top'    => 30,
            'margin_bottom' => 18,
            'margin_header' => 8,
            'margin_footer' => 8,
            'default_font'  => 'dejavusans',
            'tempDir'       => $tmp,
        ]);
        $mpdf->SetTitle(self::TIPOS[$tipo] . ' - ' . self::nombreEvento($reserva));
        $mpdf->SetAuthor('Verde Manzana');
        $mpdf->showImageErrors = false;
        $mpdf->WriteHTML($html);

        $nombre = Inflector::slug(self::TIPOS[$tipo] . ' ' . self::nombreEvento($reserva), '_', false) . '_' . $id . '.pdf';
        return [$mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN), $nombre];
    }

    /**
     * PDF de la lista de invitados (mismo formato/encabezado que los demás).
     * Funciona para cualquier reserva (vieja o nueva). Si hay imagen de distribución
     * cargada, se agrega después de la lista.
     *
     * @return array [contenido binario del PDF, nombre de archivo sugerido]
     */
    public static function generarInvitados(Reservas $reserva): array
    {
        $id = (int)$reserva->idreservas;

        $invitados = Invitados::find()
            ->where(['idreservas' => $id])
            ->orderBy(['nro_mesa' => SORT_ASC, 'apellido' => SORT_ASC, 'nombre' => SORT_ASC])
            ->all();

        $imagen = DistribucionInvitados::buscar($id);

        $html = Yii::$app->view->render('@app/views/pdf-reserva/documento_invitados', [
            'titulo'    => 'Lista de invitados',
            'reserva'   => $reserva,
            'empresa'   => Configuracion::find()->one(),
            'logo'      => self::rutaLogo(),
            'invitados' => $invitados,
            'limite'    => (int)$reserva->limite_invitados,
            // Imagen de distribución en disco (mPDF la lee por ruta de archivo)
            'imagen'    => $imagen ? DistribucionInvitados::ruta($imagen) : null,
        ]);

        $tmp = Yii::getAlias('@runtime/mpdf');
        FileHelper::createDirectory($tmp, 0775, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'Letter',
            'margin_left'   => 14,
            'margin_right'  => 14,
            'margin_top'    => 30,
            'margin_bottom' => 18,
            'margin_header' => 8,
            'margin_footer' => 8,
            'default_font'  => 'dejavusans',
            'tempDir'       => $tmp,
        ]);
        $mpdf->SetTitle('Lista de invitados - ' . self::nombreEvento($reserva));
        $mpdf->SetAuthor('Verde Manzana');
        $mpdf->showImageErrors = false;
        $mpdf->WriteHTML($html);

        $nombre = Inflector::slug('Lista de invitados ' . self::nombreEvento($reserva), '_', false) . '_' . $id . '.pdf';
        return [$mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN), $nombre];
    }

    public static function nombreEvento(Reservas $reserva): string
    {
        $e = trim((string)$reserva->tipo_evento);
        return $e !== '' ? $e : trim((string)$reserva->nombre);
    }

    /** Logo del sitio si existe en el servidor (si no, el PDF usa el nombre en texto). */
    private static function rutaLogo(): ?string
    {
        foreach (['@webroot/assets_b/img/logo.png', '@webroot/imagen/logo.png'] as $alias) {
            $ruta = Yii::getAlias($alias);
            if (is_file($ruta)) {
                return $ruta;
            }
        }
        return null;
    }
}
