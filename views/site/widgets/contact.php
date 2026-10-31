<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$model = new  \app\models\ContactForm();
?>


<!-- call us section -->
<section id="callus" class="callus callus_home3">
    <div class="container">
        <div class="row">

            <div class="main_call_us_area main_call_us_area_home3 textwhite">
                <div class="col-sm-10">
                    <div class="call_us_left_text">
                        <div class="row">
                            <div class="col-xs-1">
                                <div class="call_us_left_icon margin-top-10">
                                    <img src="<?= Url::to(['assets_b/images/h3subcribe.png'])?>" alt="" />
                                </div>
                            </div>
                            <div class="col-xs-11">
                                <h2 class="textwhite">DESEA SABER MÁS SOBRE NUESTROS SERVICIOS?</h2>
                                <p>Siempre estamos esperando con gusto escuchar sus preguntas valiosas.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="call_us_right_text">
                        <a href="<?= Url::to('contacto') ?>" class="h33 btn btn-default">CONTACTÉNOS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- End off call Us section -->