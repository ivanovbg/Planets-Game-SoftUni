<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Planet
 *
 * @ORM\Table(name="planets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetRepository")
 */
class Planet
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="planets")

     */

    private $player;
    /**
     * @var PlanetResource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanetResource", mappedBy="planet")
     *
     */
    private $resources;
    /**
     * @var PlanetBuilding[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanetBuilding", mappedBy="planet")
     *
     */
    private $buildings;
    /**
     * @var PlanetShips[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PlanetShips", mappedBy="planet")
     *
     */
    private $ships;


    /**
     * @var int
     *
     * @ORM\Column(name="x", type="integer")
     */
    private $x;

    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer")
     */
    private $y;


    /**
     * @var Flights[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Flights", mappedBy="defender_planet")
     * @ORM\JoinColumn(name="defender_planet_id")
     */

    private $sender;

    /**
     * @var Flights[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Flights", mappedBy="attacked_planet")
     * @ORM\JoinColumn(name="attacked_planet_id")
     */


    private $attacked_planet;

    public function __construct()
    {
        $this->resources=new ArrayCollection();
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
     * @return Planet
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
     * Set x
     *
     * @param integer $x
     *
     * @return Planet
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     *
     * @return Planet
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return User
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param User $player
     */
    public function setPlayer(User $player)
    {
        $this->player = $player;
    }

    /**
     * @return PlanetResource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param PlanetResource[] $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

}

