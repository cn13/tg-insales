<?php

$config = json_decode(file_get_contents('config/config.json'), true);

return [
    'adminEmail'  => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName'  => 'Example.com mailer',
    'tg_token'    => $config['tg_token'] ?? '',
    'hook_url'    => $config['hook_url'] ?? ''
];
