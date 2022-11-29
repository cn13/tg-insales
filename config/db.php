<?php

$config = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config.json'), true);

return [
    'class'    => 'yii\db\Connection',
    'dsn'      => sprintf('mysql:host=%s;dbname=%s', $config['db']['host'], $config['db']['dbname']),
    'username' => $config['db']['username'],
    'password' => $config['db']['password'],
    'charset'  => 'utf8'
];


