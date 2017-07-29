<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Ingredient")
 */
class Ingredient extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Food")
     * @ORM\JoinColumn(name="food_id", referencedColumnName="id", nullable=false)
     */
    private $food;

    /**
     * @ORM\ManyToOne(targetEntity="Meal", inversedBy="ingredients")
     * @ORM\JoinColumn(name="meal_id", referencedColumnName="id", nullable=false)
     */
    private $meal;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $qty;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $sort;

    /**
     * Set qty
     *
     * @param float $qty
     *
     * @return Ingredient
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return float
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Ingredient
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set food
     *
     * @param \AppBundle\Entity\Food $food
     *
     * @return Ingredient
     */
    public function setFood(\AppBundle\Entity\Food $food)
    {
        $this->food = $food;

        return $this;
    }

    /**
     * Get food
     *
     * @return \AppBundle\Entity\Food
     */
    public function getFood()
    {
        return $this->food;
    }

    /**
     * Set meal
     *
     * @param \AppBundle\Entity\Meal $meal
     *
     * @return Ingredient
     */
    public function setMeal(\AppBundle\Entity\Meal $meal)
    {
        $this->meal = $meal;

        return $this;
    }

    /**
     * Get meal
     *
     * @return \AppBundle\Entity\Meal
     */
    public function getMeal()
    {
        return $this->meal;
    }
}
