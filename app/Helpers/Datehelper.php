<?php

function generateDate($date)
{
    $date_array = [];
    $start_day = date('d', strtotime($date));
    $days = date("t", strtotime($date));

    for ($i = $start_day + 1; $i <= $days; $i++) {
        $date_array[] = date('Y', strtotime($date)) . '-' . date('m', strtotime($date)) . '-' . $i;
    }
    return $date_array;

}

function selectTimesOfDay($start, $end, $show = 0)
{
    $open_time = strtotime($start);
    $close_time = strtotime($end);
    if ($show == 0) $check_time = $close_time - (60 * 60);
    else
        $check_time = $close_time;
    $output = "";
    for ($i = $open_time; $i < $close_time; $i += 900) {
        if ($i > $check_time) break;
        $output .= "<option value=" . date("H:i", $i) . ">" . date("H:i", $i) . "</option>";
    }
    echo $output;
}