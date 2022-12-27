<?php

namespace app\models;

abstract class ModelAqsi extends \yii\base\DynamicModel
{
    abstract protected static function getAllModel(array $params): array;

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