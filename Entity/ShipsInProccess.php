<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShipsInProccess
 *
 * @ORM\Table(name="ships_in_proccess")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipsInProccessRepository")
 */
class ShipsInProccess
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
     * @ORM\Column(name="planet_id", type="integer")
     */
    private $planetId;

    /**
     * @var int
     *
     * @ORM\Column(name="ship_id", type="integer")
     */
    private $shipId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateoffinished", type="datetime")
     */
    private $dateoffinished;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;


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
     * Set planetId
     *
     * @param integer $planetId
     *
     * @return ShipsInProccess
     */
    public function setPlanetId($planetId)
    {
        $this->planetId = $planetId;

        return $this;
    }

    /**
     * Get planetId
     *
     * @return int
     */
    public function getPlanetId()
    {
        return $this->planetId;
    }

    /**
     * Set shipId
     *
     * @param integer $shipId
     *
     * @return ShipsInProccess
     */
    public function setShipId($shipId)
    {
        $this->shipId = $shipId;

        return $this;
    }

    /**
     * Get shipId
     *
     * @return int
     */
    public function getShipId()
    {
        return $this->shipId;
    }

    /**
     * Set dateoffinished
     *
     * @param \DateTime $dafeoffinished
     *
     * @return ShipsInProccess
     */
    public function setDateoffinished($dateoffinished)
    {
        $this->dateoffinished = $dateoffinished;

        return $this;
    }

    /**
     * Get dateoffinished
     *
     * @return \DateTime
     */
    public function getDateoffinished()
    {
        return $this->dateoffinished;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return ShipsInProccess
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
    public function getDateoffinishedinSeconds()
    {
        $date=$this->dateoffinished->format("H:i:s d.m.Y");
        return strtotime($date);
    }
}

