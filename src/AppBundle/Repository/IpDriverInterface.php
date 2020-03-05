<?php declare(strict_types=1);

namespace AppBundle\Repository;
use AppBundle\Entity\IP as IPEntity;

interface IpDriverInterface {
    function save(IPEntity $entity): void;
    function query(string $ip): ?IPEntity;
}
