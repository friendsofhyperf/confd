# confd-incubator

[![Latest Stable Version](https://img.shields.io/packagist/v/friendsofhyperf/confd)](https://packagist.org/packages/friendsofhyperf/confd)
[![Total Downloads](https://img.shields.io/packagist/dt/friendsofhyperf/confd)](https://packagist.org/packages/friendsofhyperf/confd)
[![License](https://img.shields.io/packagist/l/friendsofhyperf/confd)](https://github.com/friendsofhyperf/confd)

## Installation

```shell
composer require friendsofhyperf/confd
```

## Command

```shell
php bin/hyperf.php confd:env
```

## Listener

```php
<?php

namespace App\Listener;

use FriendsOfHyperf\Confd\Event\ConfigChanged;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

#[Listener()]
class ConfigChangedListener implements ListenerInterface
{
    public function __construct(private StdoutLoggerInterface $logger)
    {
    }

    public function listen(): array
    {
        return [
            ConfigChanged::class,
        ];
    }

    public function process(object $event): void
    {
        $this->logger->warning('[confd] ConfdChanged');
        // do something
    }
}
```

## Support

- [x] Etcd
- [ ] Consul
