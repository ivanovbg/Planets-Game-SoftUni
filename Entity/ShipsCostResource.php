<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShipsCostResource
 *
 * @ORM\Table(name="ships_cost_resource")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipsCostResourceRepository")
 */
class ShipsCostResource
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
     * @var Ships
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ships", inversedBy="costs")
     * @ORM\JoinColumn(name="ship_id")
     */

    private $ship;

    /**
     * @var GameResource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", inversedBy="shipCost")
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
     * @return ShipsCostResource
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

