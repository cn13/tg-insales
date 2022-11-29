<?php

$config = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config.json'), true);
return $config;
