<?php

namespace app\controllers;

use app\models\Category;
use app\models\Good;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $category = Category::one($id);
        if ($category) {
            $this->view->title = ($category->name ?? '') . ' - ';
        }
        $goods = Good::find()->where(['group_id' => $id, 'deleted' => 0])->orderBy(['name' => SORT_ASC, 'balance' => SORT_ASC])->all();
        return $this->render('view', ['goods' => $goods]);
    }
}