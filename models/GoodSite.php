<?php

namespace app\models;

use app\service\AqsiApi;

class GoodSite extends ModelAqsi
{
    private static $aqsi;

    protected static function getApi(): AqsiApi
    {
        if (self::$aqsi === null) {
            self::$aqsi = (new AqsiApi());
        }
        return self::$aqsi;
    }

    protected static function getAllModel(array $params): array
    {
        return self::getApi()->getGoods($params)['rows'];
    }

    /**
     * @return array
     */
    public static function all(array $params = [])
    {
        $return = [];
        foreach (static::getAllModel($params) as $model) {
            $return[] = new static($model);
        }
        return $return;
    }
}