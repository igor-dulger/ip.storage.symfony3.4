<?php declare(strict_types=1);

namespace Tests\AppBundle\Repository;

use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Repository\IpSqlRepository;
use Tests\AppBundle\Fixture\Entity as EntityFixture;

class IpSqlRepositoryTest extends TestCase {

    protected function getEmMock($repositoryMock) 
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($repositoryMock);
        return $em;
    }

    protected function getRepositoryMock() 
    {
        return $this->createMock(ObjectRepository::class);
    }

    public function testSave() {

        $emMock = $this->getEmMock(
            $this->getRepositoryMock()
        );
        $ipEntity = EntityFixture::getIp();

        $emMock->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($ipEntity));

        $emMock->expects($this->once())
            ->method('flush');

        $repository = new IpSqlRepository($emMock);
        $repository->save($ipEntity);
    
//     $this->em->persist($entity);
//     $this->em->flush();
    }

    public function testQuery() {
        $ip = "2.2.2.2";
        $expected = EntityFixture::getIp(["ip" => $ip]);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(["ip" => $ip]))
            ->willReturn($expected);

        $emMock = $this->getEmMock($repositoryMock);

        $repository = new IpSqlRepository($emMock);

        $this->assertEquals($expected, $repository->query($ip));

        //     $entity = $this->repository->findOneBy(["ip" => $ip]);
        //     return $entity;
    }
}
