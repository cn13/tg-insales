<?php

namespace app\helpers;

use DateTime;

class Shifts
{
    public static function get()
    {
        date_default_timezone_set('Europe/Moscow');

        $url = "https://lk.aqsi.ru/api/v1/shifts?" . urlencode(
                http_build_query(
                    [
                        'page' => 0,
                        'pageSize' => 100,
                        'filtered' => ['beginDate' => date('Y-m-01')]
                    ]
                )
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
            $value = $start->format('d.m.y H:i') . '/' . $close->format('H:i') . ' ' . $diff->format(
                    '%hч.'
                );
            $return[$row['cashierOpened']['name']][] = $value;
            $return[$row['cashierOpened']['name']] = array_unique($return[$row['cashierOpened']['name']]);
            rsort($return[$row['cashierOpened']['name']]);
        }

        ksort($return);
        $message = '';
        foreach ($return as $user => $rows) {
            $i = 1;
            $message .= $user . PHP_EOL;
            foreach ($rows as $row) {
                $message .= "-$i- " . $row . PHP_EOL;
                $i++;
            }
            $message .= '=============================' . PHP_EOL;
        }

        return $message;
    }
}