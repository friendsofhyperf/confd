<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/confd.
 *
 * @link     https://github.com/friendsofhyperf/confd
 * @document https://github.com/friendsofhyperf/confd/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
return [
    'default' => env('CONFD_DRIVER', 'etcd'),

    'drivers' => [
        'etcd' => [
            'driver' => \FriendsOfHyperf\Confd\Driver\Etcd::class,
            'client' => [
                'uri' => env('ETCD_URI', ''),
                'version' => 'v3beta',
                'options' => ['timeout' => 10],
            ],
            'namespace' => '/test',
            'mapping' => [
                // etcd key => env key
                '/test/foo' => 'TEST_FOO',
                '/test/bar' => 'TEST_BAR',
            ],
            'watches' => [
                '/test/foo',
            ],
        ],
    ],

    'env_path' => BASE_PATH . '/.env',
    'interval' => 1,
    'reload_api' => '',// the api of reload service
    'reload_lock_key' => '',// multi node reload lock key with redis
];
