<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodgroupRepository")
 * @ORM\Table(name="Foodgroup")
 */
class Foodgroup extends AbstractDisableableEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Foodgroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
