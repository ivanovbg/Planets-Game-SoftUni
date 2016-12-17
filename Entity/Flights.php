<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flights
 *
 * @ORM\Table(name="flights")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlightsRepository")
 */
class Flights
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
     * @ORM\Column(name="view", type="integer")

         */
    private $view;

    /**
     * @var int
     *
     * @ORM\Column(name="system", type="integer")

     */
    private $system;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="impact_on", type="datetime")
     */
    private $impactOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arriving_on", type="datetime")
     */
    private $arrivingOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_on", type="datetime")
     */
    private $returnOn;

    /**
     * @var Planet
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="defender_planet")

     */

    private $defender_planet;

    /**
     * @var Planet
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="attacked_planet")
     */

    private $attacked_planet;


    public function getId()
    {
        return $this->id;
    }

    /**
     * Set impactOn
     *
     * @param \DateTime $impactOn
     *
     * @return Flights
     */
    public function setImpactOn($impactOn)
    {
        $this->impactOn = $impactOn;

        return $this;
    }

    /**
     * Get impactOn
     *
     * @return \DateTime
     */
    public function getImpactOn()
    {
        return $this->impactOn;
    }

    /**
     * Set arrivingOn
     *
     * @param \DateTime $arrivingOn
     *
     * @return Flights
     */
    public function setArrivingOn($arrivingOn)
    {
        $this->arrivingOn = $arrivingOn;

        return $this;
    }

    /**
     * Get arrivingOn
     *
     * @return \DateTime
     */
    public function getArrivingOn()
    {
        return $this->arrivingOn;
    }

    /**
     * @return \DateTime
     */
    public function getReturnOn()
    {
        return $this->returnOn;
    }

    /**
     * @param \DateTime $returnOn
     */
    public function setReturnOn(\DateTime $returnOn)
    {
        $this->returnOn = $returnOn;
    }

    /**
     * @return Planet
     */
    public function getDefenderPlanet()
    {
        return $this->defender_planet;
    }

    /**
     * @param Planet $defender_planet
     */
    public function setDefenderPlanet(Planet $defender_planet)
    {
        $this->defender_planet = $defender_planet;
    }

    /**
     * @return Planet
     */
    public function getAttackedPlanet()
    {
        return $this->attacked_planet;
    }

    /**
     * @param Planet $attacked_planet
     */
    public function setAttackedPlanet(Planet $attacked_planet)
    {
        $this->attacked_planet = $attacked_planet;
    }

    /**
     * @return integer
     */
    public function getSecondToArrivingOn()
    {
        $date=$this->arrivingOn->format("H:i:s d.m.Y");

        return strtotime($date);
    }

    /**
     * @return int
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param int $view
     */
    public function setView(int $view)
    {
        $this->view = $view;
    }

    /**
     * @return int
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param int $system
     */
    public function setSystem(int $system)
    {
        $this->system = $system;
    }



}

