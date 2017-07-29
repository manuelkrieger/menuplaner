<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodRepository")
 * @ORM\Table(name="Food")
 */
class Food extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Foodgroup")
     * @ORM\JoinColumn(name="foodgroup_id", referencedColumnName="id", nullable=false)
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="Unity")
     * @ORM\JoinColumn(name="unity_id", referencedColumnName="id", nullable=false)
     */
    private $unity;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Food
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

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Foodgroup $group
     *
     * @return Food
     */
    public function setGroup(\AppBundle\Entity\Foodgroup $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\Foodgroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set unity
     *
     * @param \AppBundle\Entity\Unity $unity
     *
     * @return Food
     */
    public function setUnity(\AppBundle\Entity\Unity $unity)
    {
        $this->unity = $unity;

        return $this;
    }

    /**
     * Get unity
     *
     * @return \AppBundle\Entity\Unity
     */
    public function getUnity()
    {
        return $this->unity;
    }
}
