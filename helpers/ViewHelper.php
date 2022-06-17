<?php

namespace app\helpers;

use yii\web\View;

class ViewHelper
{
    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function view(string $name, array $params = []): string
    {
        return (new View())->renderFile(
            __DIR__ . "/../views/message/$name.php",
            $params
        );
    }
}