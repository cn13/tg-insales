<?php

$config = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config.json'), true);

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'tg_token' => $config['tg_token'] ?? '',
    'tg_secret' => $config['tg_secret'] ?? '',
    'tg_login' => $config['tg_login'] ?? '',
    'hook_url' => $config['hook_url'] ?? ''
];
