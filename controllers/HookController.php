<?php

namespace app\controllers;

class HookController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        file_put_contents(__DIR__ . '../runtime/hook.log', $this->request, FILE_APPEND);
        return $this->response;
    }
}