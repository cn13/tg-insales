<?php

namespace app\controllers;

use app\models\Category;
use app\models\GoodSite;

class CategoryController extends \yii\web\Controller
{

    public function actionIndex($id)
    {
        $category = Category::one($id);
        if ($category) {
            $this->view->title = ($category->name ?? '') . ' - ';
        }
        $goods = GoodSite::all(['group_id' => $id]);
        return $this->render('view', ['goods' => $goods]);
    }
}