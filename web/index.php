<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 0:46
 */

define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . '/application/');

require_once ROOT_PATH . '/vendor/autoload.php';

$config = require ROOT_PATH . '/application/config/main.php';
(new \core\App($config))->run();