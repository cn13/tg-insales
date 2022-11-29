<?php

namespace app\helpers;

use app\models\UserShop;

class SlashCommand
{
    /**
     * @param string $cmd
     * @return string
     */
    public static function run(string $cmd): string
    {
        $cmd = preg_replace('#[^a-zA-Z0-9]#', '', $cmd);
        if (method_exists(self::class, $cmd)) {
            return self::$cmd();
        }
        return 'Не понял команду!';
    }

    /**
     * @return string
     * @see
     */
    private static function start(): string
    {
        return 'Выполнили ' . __METHOD__;
    }

    private static function info(): string
    {
        return 'Выполнили ' . __METHOD__;
    }
}