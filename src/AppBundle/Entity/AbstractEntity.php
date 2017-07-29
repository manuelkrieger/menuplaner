<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_from", referencedColumnName="id")
     */
    protected $createdFrom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="updated_from", referencedColumnName="id")
     */
    protected $updatedFrom;

    /**
     * Set createdAt and createdFrom
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setCreated(\AppBundle\Entity\User $user)
    {
        $this->setCreatedAt(new \DateTime());
        $this->setCreatedFrom($user);
        return $this;
    }

    /**
     * Set updatedAt and updatedFrom
     * @param \AppBundle\Entity\User $user
     * @return $this
     */
    public function setUpdated(\AppBundle\Entity\User $user)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->setUpdatedFrom($user);
        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AbstractEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return AbstractEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdFrom
     *
     * @param \AppBundle\Entity\User $createdFrom
     *
     * @return AbstractEntity
     */
    public function setCreatedFrom(\AppBundle\Entity\User $createdFrom = null)
    {
        $this->createdFrom = $createdFrom;

        return $this;
    }

    /**
     * Get createdFrom
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedFrom()
    {
        return $this->createdFrom;
    }

    /**
     * Set updatedFrom
     *
     * @param \AppBundle\Entity\User $updatedFrom
     *
     * @return AbstractEntity
     */
    public function setUpdatedFrom(\AppBundle\Entity\User $updatedFrom = null)
    {
        $this->updatedFrom = $updatedFrom;

        return $this;
    }

    /**
     * Get updatedFrom
     *
     * @return \AppBundle\Entity\User
     */
    public function getUpdatedFrom()
    {
        return $this->updatedFrom;
    }
}
