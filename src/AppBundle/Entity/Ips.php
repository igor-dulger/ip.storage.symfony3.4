<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class Ips
{
    protected $description;

    /**
     *
     * @Assert\Valid()
     */
    protected $ips;

    public function __construct()
    {
        $this->ips = new ArrayCollection();
    }

    public function getIps(): ArrayCollection
    {
        return $this->ips;
    }
}

