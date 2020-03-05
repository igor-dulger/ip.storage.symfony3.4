<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use AppBundle\Validator\Constraints\IpAddress as IpAddress;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * IP
 * @ORM\Entity
 * @ORM\Table(name="ip")
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
     * @Assert\NotBlank()
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
    public function getId(): ?int
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
    public function setIp(string $ip): IP
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp(): ?string
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
    public function setCounter(int $counter): IP
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return int
     */
    public function getCounter(): ?int
    {
        return $this->counter;
    }
}

