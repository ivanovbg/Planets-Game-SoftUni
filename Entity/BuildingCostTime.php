<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingCostTime
 *
 * @ORM\Table(name="building_cost_time")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingCostTimeRepository")
 */
class BuildingCostTime
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
     * @ORM\Column(name="amout", type="integer")
     */
    private $amout;

    /**
     * @var Building
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Building", inversedBy="timeCost")
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
     * Set amout
     *
     * @param integer $amout
     *
     * @return BuildingCostTime
     */
    public function setAmout($amout)
    {
        $this->amout = $amout;

        return $this;
    }

    /**
     * Get amout
     *
     * @return int
     */
    public function getAmout()
    {
        return $this->amout;
    }
}

