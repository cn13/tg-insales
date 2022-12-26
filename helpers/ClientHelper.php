<?php

namespace app\helpers;

class ClientHelper
{
    private static string $url = 'https://lk.aqsi.ru/api/v2/clients/{aqsi_id}';

    /**
     * @param string $aqsi_id
     * @return mixed
     */
    public static function get(string $aqsi_id): array
    {
        $url = str_replace('{aqsi_id}', $aqsi_id, self::$url);
        return CurlAqsi::get($url)->getData();
    }
}