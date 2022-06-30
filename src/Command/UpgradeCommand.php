<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/confd.
 *
 * @link     https://github.com/friendsofhyperf/confd
 * @document https://github.com/friendsofhyperf/confd/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Confd\Command;

use FriendsOfHyperf\Confd\Confd;
use FriendsOfHyperf\Confd\DotEnv\Writer;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;

#[Command()]
class UpgradeCommand extends HyperfCommand
{
    protected ?string $signature = 'confd:upgrade';

    protected string $description = 'Upgrade .env from etcd.';

    protected ConfigInterface $config;

    protected StdoutLoggerInterface $logger;

    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);

        parent::__construct();
    }

    public function handle()
    {
        $template = $this->config->get('confd.template');
        $confd = $this->container->get(Confd::class);

        if (! is_file($template)) {
            throw new \Exception($template . ' is not exists!', 1);
        }

        $writer = $this->makeWriter($template);
        $values = $confd->fetch();

        $writer->setValues($values)->write();

        $this->logger->debug($template . ' is updated.');
    }

    public function makeWriter(string $path): Writer
    {
        return make(Writer::class, [
            'path' => $path,
        ]);
    }
}
