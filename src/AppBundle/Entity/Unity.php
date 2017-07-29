<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnityRepository")
 * @ORM\Table(name="Unity")
 */
class Unity extends AbstractDisableableEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $short;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->name . ' (' . $this->short . ')';
    }

    /**
     * Set label
     *
     * @param string $name
     *
     * @return Unity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set short
     *
     * @param string $short
     *
     * @return Unity
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get short
     *
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }
}
