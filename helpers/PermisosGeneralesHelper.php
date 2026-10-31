<?php

namespace app\helpers;

use app\models\Usuarios;
use app\models\Asignacion;
use app\models\Recursos;

class PermisosGeneralesHelper
{
    public static function puedeCrearRecurso(Usuarios $usuario, Asignacion $asignacion): bool
    {
        return (
            ($usuario->esConsultor() && $usuario->idusuario === $asignacion->idconsultor)
            || $usuario->esAdministrador()
            || $usuario->esGestor()
        );
    }

    public static function puedeEditarOEliminarRecurso(Usuarios $usuario, Asignacion $asignacion, Recursos $recurso): bool
    {
        return (
                (
                    $usuario->esConsultor() && $usuario->idusuario === $asignacion->idconsultor
                )
                || $usuario->esAdministrador()
                || $usuario->esGestor()
            ) && !$recurso->tieneHistorial();
    }
    public static function puedeAprobarRecurso(Usuarios $usuario, Asignacion $asignacion): bool
    {
        return $usuario->esCliente() && $usuario->idusuario === $asignacion->idcliente;
    }

    public static function puedeFinalizarTarea(Usuarios $usuario, Asignacion $asignacion, array $asignacionesFinalizables): bool
    {
        return (
                (
                    $usuario->esConsultor() && $usuario->idusuario === $asignacion->idconsultor
                )
                || $usuario->esAdministrador()
                || $usuario->esGestor()
            )
            && !empty($asignacionesFinalizables[$asignacion->idasignartarea])
            && $asignacion->tieneHistorialtareas();
    }
    public static function puedeReveritTarea(Usuarios $usuario, Asignacion $asignacion, array $asignacionesRevertibles): bool
    {
        return (
                (
                    $usuario->esConsultor() && $usuario->idusuario === $asignacion->idconsultor
                )
                || $usuario->esAdministrador()
                || $usuario->esGestor()
            )
            && !empty($asignacionesRevertibles[$asignacion->idasignartarea])
            && $asignacion->tieneHistorialtareas();
    }


    public static function puedeVolverAtras($usuario, $asignacion, $proyecto): bool
    {
        $esConsultorAsignado = $usuario->esConsultor() && $usuario->idusuario == $asignacion->idconsultor;
        $esGestorOAdmin = $usuario->esGestor() || $usuario->esAdministrador();

        return ($esConsultorAsignado || $esGestorOAdmin)
            && $proyecto->finalizado != '2';
    }


}
