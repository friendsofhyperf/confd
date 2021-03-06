<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/components.
 *
 * @link     https://github.com/friendsofhyperf/components
 * @document https://github.com/friendsofhyperf/components/blob/3.x/README.md
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
