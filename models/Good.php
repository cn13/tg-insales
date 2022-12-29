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
            [['uniq_id', 'name', 'deleted'], 'required'],
            [['barcodes', 'price', 'balance', 'group_id', 'group_name'], 'safe'],
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

    /**
     * @return string
     */
    public function getImage(): string
    {
        $fileDir = __DIR__ . '/../../smokelife.ru/images/';
        $fileName = 'item_' . $this->id . '.jpg';
        if (!file_exists($fileDir)) {
            if (!mkdir($fileDir) && !is_dir($fileDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $fileDir));
            }
        }
        if (!file_exists($fileDir . $fileName)) {
            $good = (new AqsiApi())->getGood($this->uniq_id);
            if (isset($good['img']['data'])) {
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $good['img']['data']));
                file_put_contents(
                    $fileDir . $fileName,
                    $data
                );
                chmod($fileDir . $fileName, 0777);
            } else {
                return 'default.jpg';
            }
        }
        return $fileName;
    }
}
