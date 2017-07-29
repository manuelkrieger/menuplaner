<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractDisableableEntity extends AbstractEntity
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $disabledAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="disabled_by", referencedColumnName="id")
     */
    protected $disabledBy;

    /**
     * Set disabledAt and disabledFrom
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setDisabled(\AppBundle\Entity\User $user)
    {
        $this->setUpdated($user);
        $this->setDisabledAt(new \DateTime());
        $this->setDisabledBy($user);
        return $this;
    }

    /**
     * Set disabledAt
     *
     * @param \DateTime $disabledAt
     *
     * @return $this
     */
    public function setDisabledAt($disabledAt)
    {
        $this->disabledAt = $disabledAt;

        return $this;
    }

    /**
     * Get disabledAt
     *
     * @return \DateTime
     */
    public function getDisabledAt()
    {
        return $this->disabledAt;
    }

    /**
     * Set disabledFrom
     *
     * @param \AppBundle\Entity\User $disabledBy
     *
     * @return $this
     */
    public function setDisabledBy(\AppBundle\Entity\User $disabledBy = null)
    {
        $this->disabledBy = $disabledBy;

        return $this;
    }

    /**
     * Get disabledFrom
     *
     * @return \AppBundle\Entity\User
     */
    public function getDisabledBy()
    {
        return $this->disabledBy;
    }
}
