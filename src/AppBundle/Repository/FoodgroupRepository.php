<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FoodgroupRepository extends EntityRepository
{

    /**
     * @param array $criteria
     * @return array
     */
    public function findByDefaultSort($criteria)
    {
        return $this->findBy($criteria, [
            'name' => 'ASC'
        ]);
    }

    public function findAll()
    {
        return $this->findByDefaultSort([]);
    }
}
