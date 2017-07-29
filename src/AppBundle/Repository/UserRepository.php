<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllActive()
    {
        return $this->findBy([
            'disabledAt' => null
        ]);
    }
}
