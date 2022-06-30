<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/confd.
 *
 * @link     https://github.com/friendsofhyperf/confd
 * @document https://github.com/friendsofhyperf/confd/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Confd;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'confd',
                    'description' => 'The configuration file for confd.',
                    'source' => __DIR__ . '/../publish/confd.php',
                    'destination' => BASE_PATH . '/config/autoload/confd.php',
                ],
            ],
        ];
    }
}
