<?php
use yii\helpers\Html;
use kartik\select2\Select2;
?>

<div class="col-xs-12" style="margin-bottom: 20px;">
<div class="redes-input">
    <?= Html::activeHiddenInput($model, "[$index]idcupon") ?>

    <div class="col-md-4">
        <div <?= ($model->hasErrors('idmovil')) ? 'class="has-error"' : ''; ?> >

 

            <?= Html::activeDropDownList($model, "[$index]idmovil",
                \app\models\Cupones::getm(),
                ['prompt' => 'ELIJE EL MOVIL', 'class'=>'form-control']
            ) ?>
            
     
         </div>
    </div>

    <div class="col-md-4">
        <div >
            <?= Html::activeDropDownList($model, "[$index]mes",
                \app\models\Cupones::getMes(),
                ['prompt' => 'ELIJE EL MES', 'class'=>'form-control']
            ) ?>
        </div>
    </div>

    <div class="col-md-3">
    <div >
        <?= Html::activeTextInput($model, "[$index]valor", ['class' => 'form-control input-sm', 'id' => 'trabajo_' . $index]); ?>
    </div>
</div>

    <div class="col-md-1">
        <?= Html::a('<i class="fa fa-remove"></i>', '#',
            [
                'class' => 'btn btn-block redes-input-remove',
            ]
        ) . Html::hiddenInput('cancel', $index, ['class' => 'redes-input-index']);
        ?>
    </div>

</div>

    <!--<div class="col-md-3">
        <div <?//= ($model->hasErrors('valor')) ? 'class="has-error"' : ''; ?> >
            <?//= Html::activeDropDownList($model, "[$index]tipo",
            //    \app\models\DatosExtraEvento::getValor(),
             //   ['prompt' => 'ELIJE EL TIPO DE LINK', 'class'=>'form-control']
           // ) ?>
        </div>
    </div>
   <!-- <div class="col-md-1">
        <?/*= Html::a('<i class="fa fa-remove"></i>', '#',
            [
                'class' => 'btn btn-block redes-input-remove',
            ]
        ) . Html::hiddenInput('cancel', $index, ['class' => 'redes-input-index']);
        */?>
    </div> -->
</div>