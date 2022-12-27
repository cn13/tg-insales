<?php

namespace app\models;

use app\service\AqsiApi;

/**
 *
 * @property-read array|mixed $children
 * @property-read array $goods
 */
class Category extends ModelAqsi
{
    protected static function getAllModel($params = []): array
    {
        return (new AqsiApi())->getGoodsCategory();
    }

    /**
     * @return array
     */
    public static function all(array $params = [])
    {
        $return = parent::all($params);
        foreach ($return as $key => $model) {
            if (!$model->enabled()) {
                unset($return[$key]);
            }
        }
        return $return;
    }

    /**
     * @return array
     */
    public function getGoods()
    {
        return GoodSite::all(['group_id' => $this->id]);
    }

    public function issetChild()
    {
        return !empty($this->children);
    }

    /**
     * @return array|mixed
     */
    public function getChildrens()
    {
        $child = [];
        foreach ($this->children as $ch) {
            $child[] = new self($ch);
        }
        return $child;
    }

    public function enabled()
    {
        if (is_iterable($this->customProperties)) {
            foreach ($this->customProperties as $prop) {
                if ($prop['key'] === 'site') {
                    return true;
                }
            }
        }
        return false;
    }
}