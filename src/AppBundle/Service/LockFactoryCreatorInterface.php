<?php declare(strict_types=1);

namespace AppBundle\Service;

use Symfony\Component\Lock\Factory as LockFactory;

interface LockFactoryCreatorInterface {
    public function create(): LockFactory;
}
