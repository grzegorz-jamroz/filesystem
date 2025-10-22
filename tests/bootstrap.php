<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__, 4) . '/vendor/autoload.php';

define('ABSPATH', dirname(__DIR__));
define('DATA_DIRECTORY', ABSPATH . '/data');
define('TESTS_DATA_DIRECTORY', ABSPATH . '/tests/data');

(new Dotenv())->load(sprintf('%s/.env', ABSPATH));
