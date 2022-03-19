<?php

namespace App\HelpersClass;

class Date
{
    public static function getListDayInMonth($month, $year, $check)
    {
        $arrayDay = [];
        $month = $month;
        $year = $year;
        if(empty($month)) {
            $arrayDay = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            return $arrayDay;
        }
        if($check == 1) {
            for ($month = 1; $month <= 12; $month++) {
                $time = mktime(12, 0, 0, $month, 1, $year);
                if (date('Y', $time) == $year) {
                    $arrayDay[] = date('Y-m', $time);
                }
            }
            return $arrayDay;
        }

        // lay tat ca cac ngay trong thang
        for ($day = 1; $day <= 31; $day++) {
            $time = mktime(12, 0, 0, $month, $day, $year);
            if (date('m', $time) == $month) {
                $arrayDay[] = date('Y-m-d', $time);
            }
        }
        return $arrayDay;
    }

    public static function getListDayInDate($dateBefore, $dateAfter)
    {
        $arrDay = [];

    }
}
