<?php

namespace app\helpers;

use DateTime;

class Shifts
{
    public static function get()
    {
        date_default_timezone_set('Europe/Moscow');

        $beginDate = new DateTime(date('Y-m-01'));
        $beginDate->modify('-1 day');

        $url = "https://lk.aqsi.ru/api/v1/shifts?" .
            http_build_query(
                [
                    'page' => 0,
                    'pageSize' => 100,
                    'filtered' => ['beginDate' => $beginDate->format('Y-m-d') . 'T23:00:00']
                ]
            );
        $return = [];
        $result = CurlAqsi::get($url)->getData();
        foreach ($result['rows'] as $row) {
            // Create two new DateTime-objects...
            $start = new DateTime(date('Y-m-d H:i:sT', strtotime($row['startDate'])));
            if (empty($row['dateClose'])) {
                $row['dateClose'] = 'NOW';
            }
            $close = new DateTime(date('Y-m-d H:i:sT', strtotime($row['dateClose'])));

            $diff = $close->diff($start);
            $hour = $diff->format('%h');
            if ($hour <= 1) {
                continue;
            }

            $hours = (int)$diff->format('%h');
            $minutes = (int)$diff->format('%i');
            if ($minutes > 35) {
                $hours++;
            }

            $value = $start->format('d.m.y H:i') . '/' . $close->format('H:i') . ' ' . $hours . 'ч.';

            $return[$row['cashierOpened']['name']][$start->getTimestamp()] = $value;
            $return[$row['cashierOpened']['name']] = array_unique($return[$row['cashierOpened']['name']]);
            krsort($return[$row['cashierOpened']['name']]);
        }

        //ksort($return);
        $message = '';
        foreach ($return as $user => $rows) {
            $i = 1;
            $message .= $user . PHP_EOL;
            foreach ($rows as $row) {
                $message .= "-$i- " . $row . PHP_EOL;
                $i++;
            }
            $message .= '==========================================' . PHP_EOL;
        }

        return $message;
    }
}