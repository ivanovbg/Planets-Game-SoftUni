<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingInProccess
 *
 * @ORM\Table(name="building_in_proccess")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingInProccessRepository")
 * @return object
 */
class BuildingInProccess
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
     * @ORM\Column(name="building_id", type="integer")
     */
    private $buildingId;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateoffinished", type="datetime")
     */
    private $dateoffinished;


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
     * @return BuildingInProccess
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
     * Set buildingId
     *
     * @param integer $buildingId
     *
     * @return BuildingInProccess
     */
    public function setBuildingId($buildingId)
    {
        $this->buildingId = $buildingId;

        return $this;
    }

    /**
     * Get buildingId
     *
     * @return int
     */
    public function getBuildingId()
    {
        return $this->buildingId;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return BuildingInProccess
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
     * Set dateoffinished
     *
     * @param \DateTime $dateoffinished
     *
     * @return BuildingInProccess
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

    public function getDateoffinishedinSeconds()
    {
        $date=$this->dateoffinished->format("H:i:s d.m.Y");
        return strtotime($date);
    }
}

