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
            if (!$user->save()) {
                file_put_contents(
                    __DIR__ . '/../runtime/install.log',
                    print_r($user->getFirstErrors() ?? [], 1) . PHP_EOL,
                    FILE_APPEND
                );
            }
        } elseif ($user->token !== $params['token']) {
            $user->token = $params['token'];
            if (!$user->save()) {
                file_put_contents(
                    __DIR__ . '/../runtime/install.log',
                    print_r($user->getFirstErrors() ?? [], 1) . PHP_EOL,
                    FILE_APPEND
                );
            }
        }

        $user->api(
            '/admin/webhooks.json',
            [
                'webhook' => [
                    "address" => "https://cn13.ru/index.php?r=hook/order-create&id={$user->id}",
                    "topic" => "orders/create",
                    "format_type" => "json"
                ]
            ],
            true
        );

        $user->api(
            '/admin/webhooks.json',
            [
                'webhook' => [
                    "address" => "https://cn13.ru/index.php?r=hook/order-update&id={$user->id}",
                    "topic" => "orders/update",
                    "format_type" => "json"
                ]
            ],
            true
        );
    }

    public function actionUninstall()
    {
        $params = $this->request->queryParams;
        if (empty($params['token'])) {
            $params = $this->request->bodyParams;
        }
        if (empty($params['token'])) {
            throw new \Exception("Bad Request");
        }
        $user = UserShop::find()->where(['insales_id' => $params['insales_id']])->one();

        if ($user) {
            $user->delete();
        }
    }

    public function actionLogin()
    {
        /**
         * Array
         * (
         * [r] => app/login
         * [insales_id] => 1216288
         * [shop] => myshop-bvi406.myinsales.ru
         * [user_email] => shop@cn13.ru
         * [user_id] => 1315317
         * )
         */
    }
}