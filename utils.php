<?php

function translate_day(string $date)
{
    $day_en = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday',
        'Friday', 'Saturday', 'Sunday'
    ];

    $day_cz = [
        'Pondělí', 'Úterý', 'Středa', 'Čtvrtek',
        'Pátek', 'Sobota', 'Neděle'
    ];

        return str_replace($day_en, $day_cz, $date);
}

function format_date($date)
{
    return date('h:i ',strtotime($date)) . translate_day(date('l',strtotime($date))) . date(' d',strtotime($date));
}

function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}
