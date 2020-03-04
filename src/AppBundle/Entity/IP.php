<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use AppBundle\Validator\Constraints\IpAddress;

use Doctrine\ORM\Mapping as ORM;

/**
 * IP
 *
 * @ORM\Table(name="ip")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IPRepository")
 */
class IP
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, unique=true)
     */
    private $ip;
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('ip', new IpAddress());
    }

    /**
     * @var int
     *
     * @ORM\Column(name="counter", type="integer")
     */
    private $counter;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return IP
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     *
     * @return IP
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }
}

