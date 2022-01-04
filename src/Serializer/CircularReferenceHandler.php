<?php

namespace App\Serializer;

use App\Entity\Owner;
use App\Entity\Pet;
use App\Entity\Toy;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke($object)
    {
        switch ($object) {
            case $object instanceof User:
                return $this->router->generate('api_user_id', ['id' => $object->getId()]);
            case $object instanceof Pet:
                return $this->router->generate('api_pet_one', ['id' => $object->getId()]);

        }
        return $object->toString();
    }
}