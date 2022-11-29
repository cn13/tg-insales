<?php

namespace app\helpers;

use app\models\UserShop;

class CmdHelper
{
    private static $internalMethod = ['newcard'];

    /**
     * @param string $message
     * @return bool
     */
    public static function isCmd($message): bool
    {
        $message = trim($message);
        return $message[0] === '/';
    }

    /**
     * @param $m
     * @return void
     */
    public static function index($m)
    {
        try {
            $cmd = new SendCommand();
            $message = ViewHelper::view('shop_not_found');
            $cmd->sendMessage(
                $m['message']['chat']['id'],
                $message
            );
        } catch (\Throwable $e) {
            syslog(LOG_NOTICE, $e->getMessage());
        }
    }

    /**
     * @param array $m
     * @return void
     * @throws \JsonException
     */
    public static function execute(array $m): void
    {
        $cmd = preg_replace('#[^a-zA-Z0-9]#', '', $m['text']);
        (new SendCommand())->sendMessage(
            $m['chat']['id'],
            SlashCommand::run($cmd, $m)
        );
    }
}