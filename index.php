<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 0:46
 */

define('ROOT_PATH', __DIR__ );
define('APP_PATH', __DIR__ . '/application/');

require_once 'vendor/autoload.php';
require_once 'application/bootstrap.php';

$config = require __DIR__ . '/application/config/main.php';
(new \core\App($config))->run();