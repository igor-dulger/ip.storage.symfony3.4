<?php declare(strict_types=1);

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\IP as IpEntity;
use AppBundle\Repository\IpDriverInterface;

/**
 * IPRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IpSqlRepository implements IpDriverInterface
{
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
        $this->repository = $em->getRepository(IpEntity::class);
    }

    public function save(IpEntity $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function query(string $ip): ?IpEntity 
    {
        $entity = $this->repository->findOneBy(["ip" => $ip]);
        return $entity;
    }
}
