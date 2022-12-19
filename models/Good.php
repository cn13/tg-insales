<?php

namespace app\models;

use app\service\AqsiApi;
use yii\db\ActiveRecord;

class Good extends ActiveRecord
{
    public static function primaryKey()
    {
        return ['uniq_id'];
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

    public function setBarcode($barcode)
    {
        $aqsi = (new AqsiApi());
        $good = $aqsi->getGood($this->uniq_id);
        $good['barcodes'] = array_unique(array_merge($good['barcodes'], [$barcode]));
        $aqsi->updateGood($good);
        $this->barcodes = json_encode($good['barcodes']);
        $this->save();
    }
}
