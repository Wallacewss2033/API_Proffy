<?php

use Illuminate\Support\Str;



function ConvertHourToMinutes($time)
{
    $segments = Str::of($time)->split('/[:]+/');
    $hour = intval($segments[0]);
    $minutes = intval($segments[1]);
    return (60 * $hour) + $minutes;
}
