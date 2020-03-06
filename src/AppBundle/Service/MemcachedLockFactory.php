<?php declare(strict_types=1);

namespace AppBundle\Service;

use Symfony\Component\Lock\Store\MemcachedStore;
use Symfony\Component\Lock\Factory as LockFactory;

class MemcachedLockFactory implements LockFactoryCreatorInterface {
    
    private $server;
    private $port;
    
    public function __construct(string $server, int $port)
    {
        $this->server = $server;
        $this->port = $port;
    }

    public function create(): LockFactory
    {
        $memcached = new \Memcached();
        $memcached->addServer($this->server, $this->port);
        return new LockFactory(new MemcachedStore($memcached));
    }
}
