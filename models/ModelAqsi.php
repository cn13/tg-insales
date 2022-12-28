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
        $cache = \Yii::$app->cache;
        $key = 'models_' . md5(static::class) . '_' . md5(implode('', $params));
        if ($cache->exists($key)) {
            return unserialize($cache->get($key), ['allowed_classes' => true]);
        }
        $return = [];
        foreach (static::getAllModel($params) as $model) {
            $return[] = new static($model);
        }
        $cache->set($key, serialize($return), 600);
        return $return;
    }
}