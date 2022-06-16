<?php

namespace app\controllers;

use app\models\UserShop;

class AppController extends \yii\web\Controller
{
    public function actionInstall()
    {
        $params = $this->request->queryParams;
        if (empty($params['token'])) {
            $params = $this->request->bodyParams;
        }
        if (empty($params['token'])) {
            throw new \Exception("Bad Request");
        }
        unset($params['r']);

        $user = UserShop::find()->where(
            [
                'insales_id' => $params['insales_id']
            ]
        )->one();

        if (!$user) {
            $user = new UserShop($params);
            $user->save();
        } elseif ($user->token !== $params['token']) {
            $user->token = $params['token'];
            $user->save();
        }

        file_put_contents(__DIR__ . '/../runtime/uninstall.log', $user->apiGetProfile() . PHP_EOL, FILE_APPEND);
    }

    public function actionUninstall()
    {
        file_put_contents(__DIR__ . '/../runtime/uninstall.log', print_r($_REQUEST ?? [], 1) . PHP_EOL, FILE_APPEND);
    }

    public function actionLogin()
    {
        file_put_contents(__DIR__ . '/../runtime/login.log', print_r($_REQUEST ?? [], 1) . PHP_EOL, FILE_APPEND);
    }
}