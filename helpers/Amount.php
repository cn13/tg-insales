<?php

namespace app\helpers;

class Amount
{
    private static string $url = 'https://lk.aqsi.ru/api/v1/clients?page={page}&pageSize=25&sorted%5B0%5D%5Bid%5D=fio&sorted%5B0%5D%5Bdesc%5D=false';

    public static function getAmount($page = 0)
    {
        $balance = [];
        while (true) {
            $url = str_replace('{page}', $page, static::$url);
            $result = CurlAqsi::get($url)->getData();
            if (empty($result['rows'])) {
                break;
            }
            foreach ($result['rows'] as $row) {
                $balance[$row['externalId']] = [
                    'account_id' => $row['externalId'],
                    'aqsi_id' => $row['id'],
                    'amount' => $row['amount'] ?? 0,
                    'receipts_count' => $row['receiptsCount'] ?? 0
                ];
            }
            $page++;
        }
        return $balance;
    }
}