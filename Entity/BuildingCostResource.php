<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingCostResource
 *
 * @ORM\Table(name="building_cost_resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingCostResourceRepository")
 */
class BuildingCostResource
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
     * @var Building
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building", inversedBy="costs")
     * @ORM\JoinColumn(name="building_id")
     */

    private $building;

    /**
     * @var GameResource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", inversedBy="buildingCost")
     * @ORM\JoinColumn(name="resource_Id")
     */

    private $resources;


    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;


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
     * @return BuildingCostResource
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
     * @return mixed
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building)
    {
        $this->building = $building;
    }

    /**
     * @return GameResource
     */
    public function getResources(): GameResource
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

