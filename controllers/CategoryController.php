<?php

namespace app\controllers;

use app\models\GoodSite;

class CategoryController extends \yii\web\Controller
{

    public function actionIndex($id)
    {
        $goods = GoodSite::all(['group_id' => $id]);
        return $this->render('view', ['goods' => $goods]);
    }
}