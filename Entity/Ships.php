<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ships
 *
 * @ORM\Table(name="ships")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipsRepository")
 */
class Ships
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
     * @var ShipsCostResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShipsCostResource", mappedBy="ship")
     *
     */
    private $costs;
    /**
     * @var ShipsCostTime
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ShipsCostTime", mappedBy="ship")
     *
     */
    private $timeCost;

    /**
     * @var PlanetShips[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanetShips", mappedBy="ship")
     */
    private $planetShips;

    public function __construct()
    {
        $this->costs=new ArrayCollection();
        $this->planetShips=new ArrayCollection();
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
     * @return Ships
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
     * @return ShipsCostResource[]
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param ShipsCostResource[] $costs
     */
    public function setCosts(array $costs)
    {
        $this->costs = $costs;
    }

    /**
     * @return ShipsCostTime
     */
    public function getTimeCost()
    {
        return $this->timeCost;
    }

    /**
     * @param ShipsCostTime $timeCost
     */
    public function setTimeCost(ShipsCostTime $timeCost)
    {
        $this->timeCost = $timeCost;
    }

    /**
     * @return PlanetShips[]
     */
    public function getPlanetShips()
    {
        return $this->planetShips;
    }

    /**
     * @param PlanetShips[] $planetShips
     */
    public function setPlanetShips(array $planetShips)
    {
        $this->planetShips = $planetShips;
    }


}

