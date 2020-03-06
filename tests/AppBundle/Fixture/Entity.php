<?php

namespace Tests\AppBundle\Fixture;
use AppBundle\Entity\IP;

class Entity{

    static public function getIp($options = []): IP 
    {
        $defaults = [
            "ip" => '1.1.1.1',
            "counter" => 1,
        ];
        $fields = array_merge($defaults, $options);
        
        $result = new IP;
        $result->setIp($fields['ip']);
        $result->setCounter($fields['counter']);

        return $result;
    }
}