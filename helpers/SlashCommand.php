<?php

namespace app\helpers;

use app\models\UserShop;

class SlashCommand
{
    /**
     * @param UserShop $u
     * @param string $cmd
     * @return string
     */
    public static function run(UserShop $u, string $cmd): string
    {
        $cmd = preg_replace('#[^a-zA-Z0-9]#', '', $cmd);
        if (method_exists(self::class, $cmd)) {
            return self::$cmd($u);
        }
        return 'Не понял команду!';
    }

    /**
     * @param UserShop $userShop
     * @return string
     * @see
     */
    private static function start(UserShop $userShop): string
    {
        return 'Выполнили ' . __METHOD__ . ' ' . $userShop->id;
    }

    private static function info(UserShop $userShop): string
    {
        return 'Выполнили ' . __METHOD__ . ' ' . $userShop->id;
    }
}