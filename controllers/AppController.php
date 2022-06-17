<?php

namespace app\controllers;

use app\models\UserShop;

class AppController extends \yii\web\Controller
{
    /**
     * @return void
     * @throws \Exception
     */
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
            $user->updateAttributes(['token' => $params['token']]);
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

    /**
     * @return void
     * @throws \yii\db\StaleObjectException
     */
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

    /**
     * @return void
     * @throws \Exception
     */
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

        $params = $this->request->queryParams;
        if (empty($params['user_email'])) {
            throw new \Exception("Bad Request");
        }

        $user = UserShop::find()->where(['insales_id' => $params['insales_id']])->one();
        $user->updateAttributes(['email' => $params['user_email']]);
    }
}