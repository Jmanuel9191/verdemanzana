<?php

use app\helpers\FormatoHelper;
use app\models\DetallesForm;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form DetallesForm */
/* @var $reserva app\models\Reservas */
/* @var $cotizacion app\models\ReservaCotizacion */
/* @var $invitados int cantidad de invitados cargados en la lista */

$this->title = 'Reserva: ' . $reserva->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['reservas/index']];
$this->params['breadcrumbs'][] = ['label' => $reserva->nombre, 'url' => ['presupuesto', 'id' => $reserva->idreservas]];
$this->params['breadcrumbs'][] = 'Detalles';

$paquete = $cotizacion->lineaPaquete;
$limite = (int)$reserva->limite_invitados;
$opciones = FormatoHelper::opcionesEditor(180);

// Botón de galería (se abre en otra pestaña para no perder lo que se está escribiendo)
$botonGaleria = function (string $accion, $imagenes) use ($reserva) {
    return Html::a('<i class="fa fa-picture-o"></i> Galería <span class="badge">' . count($imagenes) . '</span>',
        ['reservas/' . $accion, 'id' => $reserva->idreservas],
        ['class' => 'btn btn-default btn-sm pull-right boton-galeria', 'target' => '_blank', 'title' => 'Ver o subir imágenes (se abre en otra pestaña)']);
};
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<section class="content reserva-pasos">

    <?php foreach (['success' => 'success', 'error' => 'danger'] as $tipoFlash => $clase): ?>
        <?php if (Yii::$app->session->hasFlash($tipoFlash)): ?>
            <div class="alert alert-<?= $clase ?> alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= Html::encode(Yii::$app->session->getFlash($tipoFlash)) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?= $this->render('_resumen', [
        'paquete'   => $paquete ? $paquete->nombre : '',
        'invitados' => $paquete ? (string)(int)round((float)$paquete->cantidad) : '',
        'fecha'     => $reserva->fecha_reserva,
        'sucursal'  => $reserva->sucursal,
        'total'     => $cotizacion->total,
        'pagado'    => $cotizacion->getTotalPagado(),
    ]) ?>

    <?= $this->render('_pasos', ['actual' => 'detalles', 'idreservas' => $reserva->idreservas]) ?>

    <?php $af = ActiveForm::begin(['id' => 'form-detalles']); ?>

    <div class="box box-success caja-paso">

        <!-- ===================== ORGANIZADORA ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"> Organizadora y personal</h3>
            <div class="row">
                <div class="col-md-6">
                    <?= $af->field($form, 'idorganizadora')->widget(Select2::class, [
                        'data' => $form->catalogoOrganizadoras(),
                        'language' => 'es',
                        'options' => ['placeholder' => 'Sin organizadora'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
                <div class="col-md-3 col-sm-6">
                    <?= $af->field($form, 'comision_organizadora', [
                        'enableClientValidation' => false, // acepta "1.500,50": se normaliza en el servidor
                        'template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">Bs</span>{input}</div>\n{hint}\n{error}",
                    ])->textInput(['inputmode' => 'decimal', 'autocomplete' => 'off', 'placeholder' => 'Ej. 1.500', 'class' => 'form-control text-right'])
                        ->hint($form->comisionAnteriorTexto !== null
                            ? '<span class="text-warning">Valor anterior (texto): <b>' . Html::encode($form->comisionAnteriorTexto) . '</b>. Escribilo como monto.</span>'
                            : 'Monto en bolivianos.') ?>
                </div>
                <div class="col-md-3 col-sm-6">
                    <?= $af->field($form, 'cant_garzones', ['enableClientValidation' => false])
                        ->input('number', ['min' => 0, 'max' => 999, 'step' => 1, 'class' => 'form-control text-right'])
                        ->hint($form->garzonesAnteriorTexto !== null
                            ? '<span class="text-warning">Valor anterior: <b>' . Html::encode($form->garzonesAnteriorTexto) . '</b></span>'
                            : '') ?>
                </div>
            </div>

            <div class="caja-invitados" >
                <i class="fa fa-users"></i>
                Lista de invitados: <b><?= $invitados ?></b> cargados
                <?php if ($limite > 0): ?>
                    de <b><?= $limite ?></b> (límite según el paquete del Paso 1)
                <?php endif; ?>
             <?= Html::a('<i class="fa fa-list"></i> Ver / cargar lista de invitados', ['reservas/invitados', 'idreservas' => $reserva->idreservas],
    ['class' => 'btn btn-default btn-sm', 'target' => '_blank', 'rel' => 'noopener', 'style' => 'margin-left:10px; background: #2f4a10; color: white !important; font-weight: 600; font-size: 15px;']) ?> </div>
        </div>

        <!-- ===================== EQUIPAMIENTO ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"> Equipamiento <?= $botonGaleria('galeriaequipamiento', $reserva->galeriaequipamiento) ?></h3>
            <div class="row">
                <div class="col-md-3 col-sm-6"><?= $af->field($form, 'color_sillas')->textInput(['maxlength' => 500]) ?></div>
                <div class="col-md-3 col-sm-6"><?= $af->field($form, 'color_servilleta')->textInput(['maxlength' => 500]) ?></div>
                <div class="col-md-3 col-sm-6"><?= $af->field($form, 'color_mantel')->textInput(['maxlength' => 500]) ?></div>
                <div class="col-md-3 col-sm-6"><?= $af->field($form, 'en_mesa')->textInput(['maxlength' => 500]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $af->field($form, 'tipo_bebidas')->widget(CKEditor::class, $opciones) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'vajillasyvasisenmesa')->widget(CKEditor::class, $opciones) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'katering')->widget(CKEditor::class, $opciones) ?></div>
            </div>
        </div>

        <!-- ===================== DECORACIÓN ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"> Decoración <?= $botonGaleria('galeriadecoracionus', $reserva->galeriadecoracionus) ?></h3>
            <div class="row">
                <div class="col-md-6"><?= $af->field($form, 'nombre_decoradora')->textInput(['maxlength' => 500]) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $af->field($form, 'detalle_decoradora')->widget(CKEditor::class, $opciones) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'detalle_decoracioniglesia')->widget(CKEditor::class, $opciones) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'otros_detalles')->widget(CKEditor::class, $opciones) ?></div>
            </div>
        </div>

        <!-- ===================== COORDINACIÓN ===================== -->
        <div class="box-body">
            <h3 class="titulo-seccion"></i> Coordinación <?= $botonGaleria('galeriacoordinacion', $reserva->galeriacoordinacion) ?></h3>
            <div class="row">
                <div class="col-md-4"><?= $af->field($form, 'nombre_cordinadora')->textInput(['maxlength' => 500]) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'nombre_subcordinadora')->textInput(['maxlength' => 500]) ?></div>
                <div class="col-md-4"><?= $af->field($form, 'nombre_supervisora')->textInput(['maxlength' => 500]) ?></div>
            </div>
            <?= $af->field($form, 'otrainfocoordinacion')->widget(CKEditor::class, FormatoHelper::opcionesEditor(220)) ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(' Guardar detalles', ['class' => 'btn btn-success btn-lg']) ?>
            <?= Html::a(' PDF (lo guardado)', ['pdf', 'id' => $reserva->idreservas, 'tipo' => 'detalles'],
                ['class' => 'btn btn-danger btn-lg', 'target' => '_blank']) ?>
            <?= Html::a('<i class="fa fa-arrow-left"></i> Pagos', ['pagos', 'id' => $reserva->idreservas], ['class' => 'btn btn-default btn-lg']) ?>
            <?= Html::a('Ir al listado de reservas', ['reservas/index'], ['class' => 'btn btn-default btn-lg']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</section>

