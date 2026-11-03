<?php

namespace app\modules\admin;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * Módulo de administración.
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $layout = 'main';

    /**
     * Secciones que solo puede usar un administrador con tipo_admin = 1.
     */
      private $soloAdminPrincipal = [
        'administrador', 'banner', 'camposgenerales', 'categorias', 'galeria',
        'items', 'noticias', 'organizadoras', 'paquetes', 'productos',
        'promociones', 'proveedores', 'videos',
    ];

    public function init()
    {
        parent::init();

        // Sesión propia del administrador (no se mezcla con organizadora ni cliente)
              Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Administrador',
            'loginUrl' => ['admin/default/login'],
            'idParam' => 'admin_id',
            'authKeyParam' => '__authKey_admin',
            'returnUrlParam' => '__returnUrl_admin',
            'authTimeout' => 36000,                          // admin: 30 min sin actividad
            'authTimeoutParam' => '__expire_admin',
        ]);
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // 1) Las acciones de borrar (delete..., erase..., eliminar...) solo por POST.
        //    Evita que un enlace o imagen maliciosa haga borrar datos a un admin logueado.
        $accion = strtolower($action->id);
        $esDestructiva = strncmp($accion, 'delete', 6) === 0
            || strncmp($accion, 'erase', 5) === 0
            || strncmp($accion, 'eliminar', 8) === 0;

        if ($esDestructiva && !Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException('Esta acción solo se permite desde el botón correspondiente.');
        }

        // 2) Secciones restringidas a tipo_admin = 1
        $identidad = Yii::$app->user->identity;
        if ($identidad !== null
            && in_array($action->controller->id, $this->soloAdminPrincipal, true)
            && (int)$identidad->tipo_admin !== 1
        ) {
            throw new ForbiddenHttpException('Tu usuario no tiene acceso a esta sección.');
        }

        return true;
    }
}