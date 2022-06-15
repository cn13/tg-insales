<?php

namespace app\controllers;

class HookController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . '../runtime/hook.log', $this->request, FILE_APPEND);
        syslog(LOG_NOTICE, 'MESSAGE');
        return $this->response;
    }
}