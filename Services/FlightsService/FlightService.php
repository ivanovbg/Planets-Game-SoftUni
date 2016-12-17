<?php
namespace AppBundle\Services\FlightsService;
use AppBundle\Entity\Flights;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetResource;
use Symfony\Component\DependencyInjection\Container;

class FlightService
{
    const winCost = 400;
    private $conteinar;


    public function __construct(Container $container)
    {
        $this->conteinar=$container;
    }

    public function creatnewFlight(Planet $attackedPlanet, Planet $defenderPlanet)
    {
        $entityManager = $this->conteinar->get('doctrine')->getManager();
        $entityManager = $this->conteinar->get('doctrine')->getManager();
        $flight = new Flights();
        $flight->setView(0);
        $flight->setSystem(0);
        $flight->setAttackedPlanet($attackedPlanet);
        $flight->setDefenderPlanet($defenderPlanet);
        $flight->setImpactOn(new \DateTime('now'));
        $flight->setReturnOn(new \DateTime('now'));
        $flight->setArrivingOn(new \DateTime('now'));
        $entityManager->persist($flight);
        $entityManager->flush();
    }

    public function updateFlight(Flights $flight, Planet $attackedPlanet, Planet $defenderPlanet)
    {
        $entityManager = $this->conteinar->get('doctrine')->getManager();
        $flight->setImpactOn(new \DateTime('now'));
        $timetoArrivingSe = (($attackedPlanet->getX() + $defenderPlanet->getX()) + ($attackedPlanet->getY() + $defenderPlanet->getY())) + $this->conteinar->get('game_service')->returnNowinSeconds();

        $timetoArriving = date('H:i:s d.m.Y', $timetoArrivingSe);
        $timetoArriving = new \DateTime($timetoArriving);
        $flight->setArrivingOn($timetoArriving);
        $timetoReturn = $timetoArrivingSe + 160;

        $timetoReturn = date('H:i:s d.m.Y', $timetoReturn);
        $timetoReturn = new \DateTime($timetoReturn);
        $flight->setReturnOn($timetoReturn);
        $flight->setView(0);
        $flight->setSystem(1);
        $entityManager->persist($flight);
        $entityManager->flush();
    }
    public function winnerDefender(Planet $attackedPlanet, Planet $defenderPlanet)
    {
        $attackedPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$attackedPlanet]);
        $defenderPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$defenderPlanet]);


        $entityManager = $this->conteinar->get('doctrine')->getManager();
        foreach ($defenderPlanetResources as $defenderPlanetResource)
        {

            $defenderPlanetResource->setAmount($defenderPlanetResource->getAmount() + self::winCost);
            $entityManager->persist($defenderPlanetResource);
            $entityManager->flush();
        }
        foreach ($attackedPlanetResources as $attackedPlanetResource) {
            $attackedPlanetResource->setAmount($attackedPlanetResource->getAmount() - self::winCost);
            $entityManager->persist($attackedPlanetResource);
            $entityManager->flush();
        }
        $this->conteinar->get('game_service')->saveActivity('notice','wonbittle', $attackedPlanet->getPlayer());
        $this->conteinar->get('game_service')->saveActivity('notice','winbittle', $defenderPlanet->getPlayer());
    }
    public function winnerAttacked(Planet $attackedPlanet, Planet $defenderPlanet)
    {
        $entityManager = $this->conteinar->get('doctrine')->getManager();
        $attackedPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$attackedPlanet]);
        $defenderPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$defenderPlanet]);


         foreach ($defenderPlanetResources as $defenderPlanetResource)
          {
              $defenderPlanetResource->setAmount($defenderPlanetResource->getAmount() - self::winCost);
              $entityManager->persist($defenderPlanetResource);
              $entityManager->flush();
          }
          foreach ($attackedPlanetResources as $attackedPlanetResource) {
              $attackedPlanetResource->setAmount($attackedPlanetResource->getAmount() + self::winCost);
              $entityManager->persist($attackedPlanetResource);
              $entityManager->flush();
          }
        $this->conteinar->get('game_service')->saveActivity('notice','winbittle', $attackedPlanet->getPlayer());
        $this->conteinar->get('game_service')->saveActivity('notice','wonbittle', $defenderPlanet->getPlayer());
    }

    public function checkDefenderResource(Planet $defenderPlanet)
    {
        $defenderPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$defenderPlanet]);
        $return = 0;
        $bet = false;
        foreach ($defenderPlanetResources as $defenderPlanetResource)
        {
            if(($defenderPlanetResource->getAmount()-self::winCost)>0)
            {$return++;}
            else $return--;

        }
        if($return>0) {$bet = true;}

        return $bet;
    }
    public function checkAttackedResource(Planet $attackedPlanet)
    {
        $attackedPlanetResources = $this->conteinar->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$attackedPlanet]);
        $return = 0;
        $bet = false;
        foreach ($attackedPlanetResources as $attackedPlanetResource)
        {
            if(($attackedPlanetResource->getAmount()-self::winCost)>0)
            {$return++;}
            else $return--;

        }
        if($return>0) {$bet = true;}

        return $bet;
    }

    public function whoisWinner($attackedRound, $defenderRound, Flights $flight, Planet $attackedPlanet, Planet $defenderPlanet)
    {
        if($attackedRound>$defenderRound)
        {
            $this->conteinar->get('flight_service')->winnerAttacked($attackedPlanet, $defenderPlanet);
        }
        elseif($attackedRound<$defenderRound){

            $this->conteinar->get('flight_service')->winnerDefender($attackedPlanet, $defenderPlanet);

        }
        $entityManager = $this->conteinar->get('doctrine')->getManager();
        $flight->setView(1);
        $flight->setSystem(0);
        $entityManager->persist($flight);
        $entityManager->flush();
    }
}