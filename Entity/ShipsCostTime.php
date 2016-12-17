<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShipsCostTime
 *
 * @ORM\Table(name="ships_cost_time")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShipsCostTimeRepository")
 */
class ShipsCostTime
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ships", inversedBy="timeCost")
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return ShipsCostTime
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
}

