<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/confd.
 *
 * @link     https://github.com/friendsofhyperf/confd
 * @document https://github.com/friendsofhyperf/confd/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Confd\Listener;

use FriendsOfHyperf\Confd\Confd;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Coordinator\Constants;
use Hyperf\Coordinator\CoordinatorManager;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;
use Psr\Container\ContainerInterface;

#[Listener()]
class WatchOnBootListener implements ListenerInterface
{
    public function __construct(private ContainerInterface $container, private ConfigInterface $config, private Confd $confd, private StdoutLoggerInterface $logger)
    {
    }

    public function listen(): array
    {
        return [
            MainWorkerStart::class,
        ];
    }

    public function process(object $event): void
    {
        $this->confd->watch();
        $this->logger->debug('Confd watch started.');

        while (true) {
            $isWorkerExited = CoordinatorManager::until(Constants::WORKER_EXIT)->yield(1);

            if ($isWorkerExited) {
                break;
            }
        }
    }
}
