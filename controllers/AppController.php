<?php

namespace app\commands;

class AppController extends \yii\web\Controller
{
    public function actionInstall()
    {
        file_put_contents('runtime/install.log', print_r($_REQUEST ?? [], 1) . PHP_EOL, FILE_APPEND);
    }

    public function actionUninstall()
    {
        file_put_contents('runtime/install.log', print_r($_REQUEST ?? [], 1) . PHP_EOL, FILE_APPEND);
    }
}