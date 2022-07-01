<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/confd.
 *
 * @link     https://github.com/friendsofhyperf/confd
 * @document https://github.com/friendsofhyperf/confd/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\Confd\Driver;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Etcd\V3\KV;
use Psr\Container\ContainerInterface;

class Etcd implements DriverInterface
{
    private KV $client;

    private ?string $hash = null;

    public function __construct(private ContainerInterface $container, private ConfigInterface $config, private StdoutLoggerInterface $logger)
    {
        $this->client = make(KV::class, [
            'uri' => $this->config->get('confd.drivers.etcd.client.uri'),
            'version' => $this->config->get('confd.drivers.etcd.client.version', 'v3beta'),
            'options' => [
                'timeout' => (int) $this->config->get('confd.drivers.etcd.client.timeout', 10),
            ],
        ]);
    }

    public function fetch(): array
    {
        $namespace = $this->config->get('confd.drivers.etcd.namespace');
        $mapping = $this->config->get('confd.drivers.etcd.mapping', []);
        $kvs = (array) ($this->client->fetchByPrefix($namespace)['kvs'] ?? []);

        return collect($kvs)
            ->filter(fn ($kv) => isset($mapping[$kv['key']]))
            ->mapWithKeys(fn ($kv) => [$mapping[$kv['key']] => $kv['value']])
            ->toArray();
    }

    public function isChanged(): bool
    {
        $namespace = $this->config->get('confd.drivers.etcd.namespace');
        $watches = $this->config->get('confd.drivers.etcd.watches');

        $kvs = (array) ($this->client->fetchByPrefix($namespace)['kvs'] ?? []);
        $values = collect($kvs)
            ->filter(fn ($kv) => in_array($kv['key'], $watches))
            ->mapWithKeys(fn ($kv) => [$kv['key'] => $kv['value']])
            ->toArray();
        $hash = $this->getHash($values);

        if ($this->hash && $this->hash != $hash) {
            $this->logger->debug(sprintf('[confd#etcd] Config changed, pre_hash:%s cur_hash:%s.', $this->hash, $hash));
            return true;
        }

        $this->hash = $hash;

        return false;
    }

    private function getHash($value): string
    {
        return md5(serialize($value));
    }
}
