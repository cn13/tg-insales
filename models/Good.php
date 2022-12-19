<?php

namespace app\models;

use yii\db\ActiveRecord;

class Good extends ActiveRecord
{
    public static function primaryKey()
    {
        return 'uniq_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'good';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uniq_id', 'name'], 'required'],
            [['barcodes'], 'safe'],
        ];
    }
}
