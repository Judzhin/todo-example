<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace TODO;

use Doctrine\DBAL\Driver\PDOPgSql\Driver;

return [
    'doctrine' => [
        'dev_mode' => false,
        'connection' => [
            'orm_default' => [
                'driverClass' => Driver::class,
                'params' => [
                    'host' => 'host.docker.internal',
                    // 'host' => '127.0.0.1',
                    'port' => 5432,
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'todo',
                    'charset' => 'UTF8',
                ],
            ],
        ],
    ],
];
