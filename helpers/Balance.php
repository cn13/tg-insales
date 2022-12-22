<?php

namespace app\helpers;

class Balance
{
    private static string $url = 'https://lk.aqsi.ru/api/v1/warehouses/faaad631-46ac-43d4-8291-7d4e3543d737?page={page}&pageSize=50&sorted%5B0%5D%5Bid%5D=name&sorted%5B0%5D%5Bdesc%5D=false';

    public static function getBalance($page = 0)
    {
        $balance = [];
        while (true) {
            $url = str_replace('{page}', $page, static::$url);
            $result = CurlAqsi::get($url)->getData();
            if (empty($result['goods']['rows'])) {
                break;
            }
            foreach ($result['goods']['rows'] as $row) {
                $balance[] = [
                    'id' => $row['id'],
                    'price' => $row['price'],
                    'balance' => $row['warehousesGoods']['balance'],
                    'group_id' => $row['group']['id'],
                    'group_name' => $row['group']['name'],
                ];
            }
            $page++;
        }
        return $balance;
    }
}