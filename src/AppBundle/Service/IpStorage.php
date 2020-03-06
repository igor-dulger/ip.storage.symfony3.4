<?php declare(strict_types=1);

namespace AppBundle\Service;
use AppBundle\Repository\IpDriverInterface;
use AppBundle\Entity\IP as IpEntity;

class IpStorage {

    private $repository;
    private $lockFactory;
    
    public function __construct(IpDriverInterface $repository, LockFactoryCreatorInterface $lockFactoryCreator) 
    {
        $this->repository = $repository;
        $this->lockFactory = $lockFactoryCreator->create();
    }

    public function add(string $ip): int 
    {
        $lock = $this->lockFactory->createLock('ip-storage-add-'.$ip);

        if ($lock->acquire()) {
            try {
                $entity = $this->repository->query($ip);
        
                if ($entity === null) {
                    $entity = new IpEntity();
                    $entity->setIp($ip);
                    $entity->setCounter(0);
                }
        
                $entity->setCounter(1 + $entity->getCounter() ?? 0);
                $this->repository->save($entity);

            } finally {
                $lock->release();
            }
            return $entity->getCounter();
        }
        return 0;
    }

    public function query(string $ip): int 
    {
        $result = $this->repository->query($ip);
        return $result ? $result->getCounter() : 0;
    }
}