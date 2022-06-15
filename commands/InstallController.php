<?php

namespace app\commands;

class InstallController extends \yii\console\Controller
{
    public function actionIndex()
    {
        file_put_contents('../runtime/requiest_install.log', print_r($_REQUEST ?? [], 1) . PHP_EOL, FILE_APPEND);
    }
}