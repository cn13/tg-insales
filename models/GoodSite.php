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
            $goodModelDb = Good::findOne($model['id']);

            $goodModel = new static(
                array_merge($model, [
                    'balance' => $goodModelDb->balance ?? 0,
                    'price'   => $goodModelDb->price ?? 0,
                ])
            );
            $return[] = $goodModel;
        }
        return $return;
    }
}