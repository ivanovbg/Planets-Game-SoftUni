<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DayResources
 *
 * @ORM\Table(name="day_resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DayResourcesRepository")
 */
class DayResources
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dategive", type="datetime")
     */
    private $dategive;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="user_resource")
     * @ORM\JoinColumn(name="user_id")
     */

    private $user_resource;



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
     * Set dategive
     *
     * @param \DateTime $dategive
     *
     * @return DayResources
     */
    public function setDategive($dategive)
    {
        $this->dategive = $dategive;

        return $this;
    }

    /**
     * Get dategive
     *
     * @return \DateTime
     */
    public function getDategive()
    {
        return $this->dategive->format('d.m.Y');
    }

    /**
     * @return User
     */
    public function getUserResource()
    {
        return $this->user_resource;
    }

    /**
     * @param User $user_resource
     */
    public function setUserResource(User $user_resource)
    {
        $this->user_resource = $user_resource;
    }

}

