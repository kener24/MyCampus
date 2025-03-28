<?php

    function tiempoTranscurrido($fecha) {
        date_default_timezone_set('America/Tegucigalpa'); // O tu zona horaria correcta

        $ahora = new DateTime();
        $publicacion = new DateTime($fecha);
        $diferencia = $ahora->diff($publicacion);

        if ($diferencia->y > 0) {
            return $diferencia->y === 1 ? 'hace 1 año' : 'hace ' . $diferencia->y . ' años';
        }
        if ($diferencia->m > 0) {
            return $diferencia->m === 1 ? 'hace 1 mes' : 'hace ' . $diferencia->m . ' meses';
        }
        if ($diferencia->d >= 7) {
            $semanas = floor($diferencia->d / 7);
            return $semanas === 1 ? 'hace 1 semana' : 'hace ' . $semanas . ' semanas';
        }
        if ($diferencia->d > 0) {
            return $diferencia->d === 1 ? 'hace 1 día' : 'hace ' . $diferencia->d . ' días';
        }
        if ($diferencia->h > 0) {
            return $diferencia->h === 1 ? 'hace 1 hora' : 'hace ' . $diferencia->h . ' horas';
        }
        if ($diferencia->i > 0) {
            return $diferencia->i === 1 ? 'hace 1 minuto' : 'hace ' . $diferencia->i . ' minutos';
        }
        return 'hace unos segundos';
    }
?>