<?php

class ZonaHorariaHelper
{
    public static function listarTodas()
    {
        return array_combine(
            timezone_identifiers_list(),
            timezone_identifiers_list()
        );
    }
}
