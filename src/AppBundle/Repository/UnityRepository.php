<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UnityRepository extends EntityRepository
{

    /**
     * @param array $criteria
     * @return array
     */
    public function findByDefaultSort($criteria)
    {
        return $this->findBy($criteria, [
            'label' => 'ASC'
        ]);
    }

    public function findAllActive()
    {
        return $this->findByDefaultSort([
            'disabledAt' => null
        ]);
    }
}
