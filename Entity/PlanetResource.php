<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanetResource
 *
 * @ORM\Table(name="planet_resource")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetResourceRepository")
 */
class PlanetResource
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
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;
    /**
     * @var Planet
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="resources")
     * @ORM\JoinColumn(name="planet_id")
     */

    private $planet;

    /**
     * @var GameResource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", inversedBy="planetResources")
     * @ORM\JoinColumn(name="resource_Id")
     */

    private $resources;




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
     * Set amount
     *
     * @param integer $amount
     *
     * @return PlanetResource
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Planet
     */
    public function getPlanet()
    {
        return $this->planet;
    }

    /**
     * @param Planet $planet
     */
    public function setPlanet(Planet $planet)
    {
        $this->planet = $planet;
    }

    /**
     * @return GameResource
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param GameResource $resources
     */
    public function setResources(GameResource $resources)
    {
        $this->resources = $resources;
    }




}

