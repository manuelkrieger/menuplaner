<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FoodRepository extends EntityRepository
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

    public function getAllAsArray()
    {
        $foods = [];
        foreach ($this->findByDefaultSort([]) as $food) {
            $foods[$food->getId()] = $food;
        }
        return $foods;
    }
}
