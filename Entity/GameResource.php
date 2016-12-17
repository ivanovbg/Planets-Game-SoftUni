<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameResourceRepository")
 */
class GameResource
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    /**
     * @var PlanetResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanetResource", mappedBy="resources")
     */
    private $planetResources;

    /**
     * @var BuildingCostResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BuildingCostResource", mappedBy="resources")
     *
     */
    private $buildingCosts;
    /**
     * @var ShipsCostResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShipsCostResource", mappedBy="resources")
     *
     */
    private $shipCost;


    public function __construct()
    {
        $this->planetResources=new ArrayCollection();
        $this->buildingCosts=new ArrayCollection();
        $this->shipsCosts=new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return GameResource
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
     * @return PlanetResource
     */
    public function getPlanetResources()
    {
        return $this->planetResources;
    }

    /**
     * @param PlanetResource $planetResources
     */
    public function setPlanetResources(PlanetResource $planetResources)
    {
        $this->planetResources = $planetResources;
    }

    /**
     * @return BuildingCostResource[]
     */
    public function getBuildingCosts()
    {
        return $this->buildingCosts;
    }

    /**
     * @param BuildingCostResource[] $buildingCosts
     */
    public function setBuildingCosts(array $buildingCosts)
    {
        $this->buildingCosts = $buildingCosts;
    }

    /**
     * @return ShipsCostResource[]
     */
    public function getShipCost()
    {
        return $this->shipCost;
    }

    /**
     * @param ShipsCostResource[] $shipCost
     */
    public function setShipCost(array $shipCost)
    {
        $this->shipCost = $shipCost;
    }




}

