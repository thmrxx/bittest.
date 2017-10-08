<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 07.10.17, 1:08
 */

return [
    'baseUrl'     => 'http://bittest.local/',
    'title'       => 'Тестовое задание для "ООО БИТ"',
    'projectName' => 'BIT TEST',
    'components'  => [
        'db' => require __DIR__ . '/db.php',
    ],
    'logFile' => ROOT_PATH . '/app.log',
];