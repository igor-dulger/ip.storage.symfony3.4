<?php

namespace Tests\AppBundle\Service;

use PHPUnit\Framework\TestCase;
use AppBundle\Repository\IpSqlRepository;
use AppBundle\Service\IpStorage;
use AppBundle\Service\MemcachedLockFactory;
use AppBundle\Entity\IP as IpEntity;
use Symfony\Component\Lock\Factory as LockFactory;
use Tests\AppBundle\Fixture\Entity as EntityFixture;


class FakeLock {
    public function acquire() {
        return(true);
    }
    public function release() {
        return(true);
    }
}

class IpStorageTest extends TestCase {


    protected function getLockFactoryCreator($lockFactoryMock) 
    {
        $result = $this->getMockBuilder(MemcachedLockFactory::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
        $result->method('create')
            ->willReturn($lockFactoryMock);

        return $result;
    }

    protected function getLockFactoryMock($lockMock, $create=true, $param = '') 
    {
        $lockFactory = $this->getMockBuilder(LockFactory::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
        if ($create) {
            $lockFactory->expects($this->once())
            ->method('createLock')
            ->with($this->equalTo($param))
            ->willReturn($lockMock);
        }

        return $lockFactory;
    }

    protected function getLockMock(){
        $lock = $this->getMockBuilder(FakeLock::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
        return $lock;
    }

    protected function getRepositoryMock() 
    {
        return $this->createMock(IpSqlRepository::class);
    }

    public function testAddUnsuccessfulLock() {

        $ip = '3.3.3.3';
        
        $lock = $this->getLockMock();
        $lock->expects($this->once())
            ->method('acquire')
            ->willReturn(false);

        $repository = $this->getRepositoryMock();

        $storage = new IpStorage(
            $repository, 
            $this->getLockFactoryCreator(
                $this->getLockFactoryMock($lock, true, 'ip-storage-add-'.$ip)
            )
        );

        $this->assertEquals($storage->add($ip), 0);
    }

    public function testAddSuccessfulLockExistingIp() {

        $ip = '3.3.3.3';
        $entityMockBeforeSave = EntityFixture::getIp(["ip" => $ip, "counter" => 1]);
        $entityMockForSave = EntityFixture::getIp(["ip" => $ip, "counter" => 2]);

        $lock = $this->getLockMock();
        $lock->expects($this->once())
            ->method('acquire')
            ->willReturn(true);
        $lock->expects($this->once())
            ->method('release');

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo($ip))
            ->willReturn($entityMockBeforeSave);

        $repository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($entityMockForSave));

        $storage = new IpStorage(
            $repository, 
            $this->getLockFactoryCreator(
                $this->getLockFactoryMock($lock, true, 'ip-storage-add-'.$ip)
            )
        );
        $actual = $storage->add($ip);
        $this->assertEquals(2, $actual);
    }

    public function testAddSuccessfulLockNewIp() {

        $ip = '3.3.3.3';
        $entityMockForSave = EntityFixture::getIp(["ip" => $ip, "counter" => 1]);

        $lock = $this->getLockMock();
        $lock->expects($this->once())
            ->method('acquire')
            ->willReturn(true);
        $lock->expects($this->once())
            ->method('release');

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo($ip))
            ->willReturn(null);

        $repository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($entityMockForSave));

        $storage = new IpStorage(
            $repository, 
            $this->getLockFactoryCreator(
                $this->getLockFactoryMock($lock, true, 'ip-storage-add-'.$ip)
            )
        );
        $actual = $storage->add($ip);
        $this->assertEquals(1, $actual);
    }

    public function testQueryForExistingIp() {
        $ip = '3.3.3.3';
        $entityMock = $this->createMock(IpEntity::class);

        $entityMock->expects($this->once())
            ->method('getCounter')
            ->willReturn(1);

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo($ip))
            ->willReturn($entityMock);

        $storage = new IpStorage(
            $repository, 
            $this->getLockFactoryCreator(
                $this->getLockFactoryMock($this->getLockMock(), false)
            )
        );

        $this->assertEquals($storage->query($ip), 1);
    }

    public function testQueryForNotExistingIp() {
        $ip = '3.3.3.3';

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo($ip))
            ->willReturn(null);

        $storage = new IpStorage(
            $repository, 
            $this->getLockFactoryCreator(
                $this->getLockFactoryMock($this->getLockMock(), false)
            )
        );

        $this->assertEquals($storage->query($ip), 0);
    }    
}


