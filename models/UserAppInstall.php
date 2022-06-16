<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_app_install".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $install_at
 * @property string|null $uninstall_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class UserAppInstall extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_app_install';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['install_at', 'uninstall_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'install_at' => 'Install At',
            'uninstall_at' => 'Uninstall At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
