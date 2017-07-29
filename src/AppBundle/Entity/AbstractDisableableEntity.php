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
     * @ORM\JoinColumn(name="disabled_from", referencedColumnName="id")
     */
    protected $disabledFrom;

    /**
     * Set disabledAt and disabledFrom
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setDisabled(\AppBundle\Entity\User $user)
    {
        $this->setUpdated($user);
        $this->setDisabledAt(new \DateTime());
        $this->setDisabledFrom($user);
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
     * @param \AppBundle\Entity\User $disabledFrom
     *
     * @return $this
     */
    public function setDisabledFrom(\AppBundle\Entity\User $disabledFrom = null)
    {
        $this->disabledFrom = $disabledFrom;

        return $this;
    }

    /**
     * Get disabledFrom
     *
     * @return \AppBundle\Entity\User
     */
    public function getDisabledFrom()
    {
        return $this->disabledFrom;
    }
}
