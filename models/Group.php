<?php

namespace app\models;

use yii\db\ActiveRecord;

class Group extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['icon_chat'], 'safe'],
        ];
    }

    public function getIcon()
    {
        switch ($this->icon_chat) {
            default:
                $icon = '💥';
        }
        return $icon;
    }
}
