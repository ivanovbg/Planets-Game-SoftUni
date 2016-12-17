<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanetShips
 *
 * @ORM\Table(name="planet_ships")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetShipsRepository")
 */
class PlanetShips
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="ships")
     * @ORM\JoinColumn(name="planet_id")
     */

    private $planet;

    /**
     * @var Ships
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ships", inversedBy="planetShips")
     * @ORM\JoinColumn(name="ship_id")
     */

    private $ship;


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
     * @return PlanetShips
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
     * @return Ships
     */
    public function getShip()
    {
        return $this->ship;
    }

    /**
     * @param Ships $ship
     */
    public function setShip(Ships $ship)
    {
        $this->ship = $ship;
    }


}

