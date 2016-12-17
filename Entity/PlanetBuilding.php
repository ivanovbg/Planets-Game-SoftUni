<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanetBuilding
 *
 * @ORM\Table(name="planet_building")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetBuildingRepository")
 */
class PlanetBuilding
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
     * @ORM\Column(name="level", type="integer")
     */
    private $level;
    /**
     * @var Planet
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="buildings")
     * @ORM\JoinColumn(name="planet_id")
     */

    private $planet;

    /**
     * @var Building
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building", inversedBy="planetBuildings")
     * @ORM\JoinColumn(name="building_id")
     */

    private $building;


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
     * Set level
     *
     * @param integer $level
     *
     * @return PlanetBuilding
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return Planet
     */
    public function getPlanet(): Planet
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
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building)
    {
        $this->building = $building;
    }

}

