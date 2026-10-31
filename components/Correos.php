<?php

namespace app\components;

use app\models\Configuracion;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\Url;

class Correos extends Component
{

    static public function enviarCorreos()
    {

    }

    static public function nuevoUsuario($model)
    {
        $conf = Configuracion::find()->one();

        $mensaje2 = Html::tag('h1', '¡Se Registro una nueva Sugerencia o reclamo!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'NOMBRE:') . ' ' . $model->nombre);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'EMAIL:') . ' ' . $model->email);
        $mensaje2 .= Html::tag('p', 'sigue el link para ver la sugerencia completa <a target="_blank" href="' . Url::to(['admin/sugerencias/view?id='.$model->idsugerencia], true) . '">clic aquí</a>');

        if ($model['email']) {


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo($conf['email'])
                ->setFrom([$conf['email'] => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - Se Registro una nueva Sugerencia')
                ->send();

            return true;
        }
        return false;
    }


    static public function nuevoReclamo($model)
    {
        $conf = Configuracion::find()->one();

        $mensaje2 = Html::tag('h1', '¡Se Registro una nuevo Reclamo!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'NOMBRE:') . ' ' . $model->nombre);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'EMAIL:') . ' ' . $model->email);
        $mensaje2 .= Html::tag('p', 'sigue el link para ver el recalamo completo <a target="_blank" href="' . Url::to(['admin/reclamos/view?id='.$model->idreclamo], true) . '">clic aquí</a>');

        if ($model['email']) {


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo($conf['email'])
                ->setFrom([$conf['email'] => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - Se Registro una nuevo reclamo')
                ->send();

            return true;
        }
        return false;
    }


    static public function nuevoTrabajo($model)
    {
        $conf = Configuracion::find()->one();


        $mensaje2 = Html::tag('h1', '¡Se Registro una nuevo Postulante!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'NOMBRE:') . ' ' . $model->nombre);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'EMAIL:') . ' ' . $model->email);

        $mensaje2 .= Html::tag('p', 'sigue el link para ver el postulante completo <a target="_blank" href="' . Url::to(['admin/trabajos/view?id='.$model->idtrabajo], true) . '">clic aquí</a>');
        if ($model['email']) {


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo($conf['email'])
                ->setFrom([$conf['email'] => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - Se Registro una nuevo Postulante')
                ->send();

            return true;
        }
        return false;
    }


    static public function nuevoCotizacion($model)
    {
        $conf = Configuracion::find()->one();

        $mensaje2 = Html::tag('h1', '¡Se Registro una nueva cotizacion!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Nombre:') . ' ' . $model->nombre);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Email:') . ' ' . $model->email);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Sucursal:') . ' ' . $model->sucursal);

        $mensaje2 .= Html::tag('p', 'sigue el link para ver la cotizacion completa <a target="_blank" href="' . Url::to(['admin/cotizacion/view?id='.$model->idcotizacion], true) . '">clic aquí</a>');
        if ($model['email']) {


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo('reservas@verdemanzana.com.bo')
                ->setFrom(['reservas@verdemanzana.com.bo' => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - ¡Se Registro una nueva cotizacion!')
                ->send();

            return true;
        }
        return false;
    }




    static public function nuevoPedido($model)
    {
        $conf = Configuracion::find()->one();
        $categorias5 = \app\models\Categoria::findOne(['idcategoria' => $model->idcategoria]);
        $mensaje = Html::tag('h1', '¡Gracias por hacer tu pedido en Master!');
        $mensaje .= Html::tag('p', Html::tag('strong', 'Numero de pedido:') . ' ' . $model->idpedido);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Nombre Completo:') . ' ' . $model->nombre);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Cantidad de promo pollo familiar: ') . ' ' . $model->cantidadpollo);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Cantidad de promo hamburgues familiar: ') . ' ' . $model->cantidadham);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Horario: ') . ' ' . $model->horarios);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Zona: <br>') . ' ' . $categorias5['nombre']);
        $mensaje .= Html::tag('p', Html::tag('strong', 'Total a cancelar: <br>') . ' ' . $model->preciototal);

        $mensaje2 = Html::tag('h1', '¡Se Registro una nuevo pedido!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Nombre:') . ' ' . $model->nombre);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Email:') . ' ' . $model->email);

        $mensaje2 .= Html::tag('p', 'sigue el link para ver el pedido completo <a target="_blank" href="' . Url::to(['admin/pedidos/view?id='.$model->idpedido], true) . '">clic aquí</a>');

        if (isset($model['email'])) {
            Yii::$app->mailer->compose('layouts/template2', [
                'config' => $conf,
                'content' => $mensaje,
            ])
                ->setTo($model->email)
                ->setFrom([$conf['email'] => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - Nuevo Pedido')
                ->send();


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo('jose_manuel3000@hotmail.com')
                ->setFrom([$conf['email'] => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' -Nuevo Pedido')
                ->send();
            return true;
        }
        return false;
    }


    static public function nuevoComentario($model)
    {
        $conf = Configuracion::find()->one();

        $mensaje2 = Html::tag('h1', '¡Se Registro un nuevo comentario!');
        $mensaje2 .= Html::tag('h5', 'Sus datos son');
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Comentario decoracion:') . ' ' . $model->comentario_decoracion);
        $mensaje2 .= Html::tag('p', Html::tag('strong', 'Comentario cronograma:') . ' ' . $model->comentario_cronograma);

        $mensaje2 .= Html::tag('p', 'sigue el link para ver la ficha <a target="_blank" href="' . Url::to(['admin/reservas/view?id='.$model->idreservas], true) . '">clic aquí</a>');
        if ($model['email']) {


            Yii::$app->mailer->compose('layouts/template2', [
                'content' => $mensaje2,
                'config' => $conf,
            ])
                ->setTo('reservas@verdemanzana.com.bo')
                ->setFrom(['reservas@verdemanzana.com.bo' => $conf['titulo_pagina']])
                ->setSubject($conf->titulo_pagina . ' - Se Registro un nuevo comentario!')
                ->send();

            return true;
        }
        return false;
    }
}