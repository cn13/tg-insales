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
            $cName = $row['cashierOpened']['name'] ?? 'DeletedUser';
            $hours = (int)$diff->format('%h');
            $minutes = (int)$diff->format('%i');
            if ($minutes > 35) {
                $hours++;
            }

            $sum = 0;
            foreach ($row['calculatedCounters'] as $val) {
                $sum += ($val['sell'] ?? 0) - ($val['sellReturn'] ?? 0);
            }

            $value = $start->format('d.m.y H:i') . '/' . $close->format('H:i') . ' ' . $hours;

            $return[$cName][$start->getTimestamp()] = [
                'time' => $value,
                'sum' => $sum
            ];

            krsort($return[$cName]);
        }

        //ksort($return);
        $message = '';
        $itog = 0;
        foreach ($return as $user => $rows) {
            $i = 1;
            $message .= '============' . PHP_EOL;
            $message .= '<b>' . $user . '</b>' . PHP_EOL;
            $allSum = 0;
            foreach ($rows as $row) {
                $allSum += $row['sum'];
                $message .= sprintf(
                    "%s. %sч. %sруб." . PHP_EOL,
                    $i,
                    $row['time'],
                    number_format($row['sum'], 0, '.', ' ')
                );
                $i++;
            }
            $message .= PHP_EOL;
            $message .= 'Сумма: ' . number_format($allSum, 0, '.', ' ') . 'руб.' . PHP_EOL;
            if ($allSum < 125000) {
                $message .= sprintf(
                    "Процент выполнения: %s%%" . PHP_EOL,
                    number_format(round(($allSum / 125000) * 100), 0, '.', ' ')
                );
                $message .= sprintf(
                    "До выполнения плана: %sруб." . PHP_EOL,
                    number_format(125000 - $allSum, 0, '.', ' ')
                );
            } else {
                $message .= "ПЛАН ВЫПОЛНЕН!!!" . PHP_EOL;
            }
            $itog += $allSum;
        }
        $message .= '======================================' . PHP_EOL;
        $message .= sprintf("= ИТОГО ЗА %s: %sруб." . PHP_EOL, date("m.Y"), number_format($itog, 0, '.', ' '));
        $message .= sprintf(
            "= ПЛАН ВЫПОЛНЕН НА %s%%" . PHP_EOL,
            number_format(round(($itog / 250000) * 100), 0, '.', ' ')
        );
        $message .= '======================================' . PHP_EOL;

        return $message;
    }
}