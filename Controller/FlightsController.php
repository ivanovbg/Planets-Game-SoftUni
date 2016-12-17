<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\Flights;

use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetShips;
use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class FlightsController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("planets/attack", name="planets_to_attack");
     * @Method("GET")
     */
    public function planetstoAttack()
    {
        if ($this->get('user_service')->getUser()) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $this->get('user_service')->getUser()]);
            $planets = $this->getDoctrine()->getRepository(Planet::class);
            $myplanet = $planets->findOneBy(['id' => $this->get('planet_service')->getPlanet()]);
            $all_planets = $planets->findAll();
            return $this->render('/planets/attack/planetstoAttack.html.twig', [
                'planets' => $all_planets,
                'user' => $user,
                'myplanet' => $myplanet,

            ]);
        }
        $this->redirectToRoute('user_login');
    }

    /**
     * @Param $id     *
     */
    /**
     * @Route("/planet/attack/{id}", name="attack_planet")
     * @Method("GET")
     * @param $id
     */
    public function creatFlight($id)
    {   if(!$this->get('user_service')->getUser()) {return $this->redirectToRoute('user_login');}
        $planetRepository = $this->getDoctrine()->getRepository(Planet::class);
        $attackedPlanet = $planetRepository->findOneBy(['id' => $id]);
        $defenderPlanet = $planetRepository->findOneBy(['id' => $this->get('planet_service')->getPlanet()]);
        if($this->get('flight_service')->checkDefenderResource($defenderPlanet) && $this->get('flight_service')->checkAttackedResource($attackedPlanet))
        {

            $user= $this->get('user_service')->getUserRepository();
            $planetRepository = $this->getDoctrine()->getRepository(Planet::class);


            $flightsRepository = $this->getDoctrine()->getRepository(Flights::class);
            $flight = $flightsRepository->findOneBy(['attacked_planet' => $attackedPlanet,
                'defender_planet' => $defenderPlanet], ['id' => 'DESC']);

            if ($flight == null) {
                $this->get('flight_service')->creatnewFlight($attackedPlanet, $defenderPlanet);
                return $this->redirectToRoute('attack_planet_process', [
                    'id' => $id
                ]);
            } else {
                return $this->redirectToRoute('attack_planet_process', [
                    'id' => $id
                ]);
            }
        }
        else
        {
            $this->addFlash(
                'error', "Недостатъчни ресурси на една от планетите."
            );
          return  $this->redirectToRoute('planets_to_attack');
        }


    }

    /**
     * @Route("/planet/attack/process/{id}", name="attack_planet_process")
     * @Method("GET")
     * @param $id
     */
    public function flight($id)
    {
        if ($this->get('user_service')->getUser()) {
            $planetRepository = $this->getDoctrine()->getRepository(Planet::class);
            $attackedPlanet = $planetRepository->findOneBy(['id' => $id]);
            $defenderPlanet = $planetRepository->findOneBy(['id' => $this->get('planet_service')->getPlanet()]);
            $flightsRepository = $this->getDoctrine()->getRepository(Flights::class);
            $flight = $flightsRepository->findOneBy(['attacked_planet' => $attackedPlanet,
                'defender_planet' => $defenderPlanet], ['id' => 'DESC']);

            $timeArriving = $flight->getSecondToArrivingOn();
            $nowtime = $this->get('game_service')->returnNowinSeconds();
            $timeArriving = $timeArriving - $nowtime;
            if ($flight->getSystem()==0 ) {
                $this->get('flight_service')->updateFlight($flight, $attackedPlanet, $defenderPlanet);
                return $this->redirectToRoute('attack_planet_process',
                    [
                        'id'=>$id
                    ]);
            }
            return $this->render('planets/attack/preparetobattle.html.twig',
                [
                    'timeArriving' => $timeArriving,
                    'defender' => $defenderPlanet,
                    'attacker' => $attackedPlanet

                ]);



        }
    }

    /**
     * @Route("/planet/battle/{id}", name="battle_planet")
     * @param $id
     */
      public function battle($id)
      {
          if($this->get('user_service')->getUser()) {
              $planetRepository = $this->getDoctrine()->getRepository(Planet::class);
              $user = $this->get('user_service')->getUserRepository();
              $attackedPlanet = $planetRepository->findOneBy(['id' => $id]);
              $defenderPlanet = $planetRepository->findOneBy(['id' => $this->get('planet_service')->getPlanet()]);
              $attackedPlanetShips = $this->getDoctrine()->getRepository(PlanetShips::class)->findBy(['planet' => $attackedPlanet]);
              $defenderPlanetShips = $this->getDoctrine()->getRepository(PlanetShips::class)->findBy(['planet' => $defenderPlanet]);
              $flightsRepository = $this->getDoctrine()->getRepository(Flights::class);
              $flight = $flightsRepository->findOneBy(['attacked_planet' => $attackedPlanet,
                          'defender_planet' => $defenderPlanet], ['id' => 'DESC']);

              if ($flight == null || $flight->getView() == 1) {
                          return $this->redirectToRoute('planet');
                      }
              $defenderRound = 0;
              $attackedRound = 0;
              foreach ($attackedPlanetShips as $attackedPlanetShip) {
                  foreach ($defenderPlanetShips as $defenderPlanetShip) {
                      if ($attackedPlanetShip->getShip()->getId() == $defenderPlanetShip->getShip()->getId()) {
                          if ($attackedPlanetShip->getLevel() > $defenderPlanetShip->getLevel()) {
                                      $attackedRound++;
                                  }
                          elseif ($attackedPlanetShip->getLevel() < $defenderPlanetShip->getLevel()) {
                                      $defenderRound++;
                                  }
                              }
                          }
                      }
                      $this->get('flight_service')->whoisWinner($attackedRound, $defenderRound, $flight, $attackedPlanet, $defenderPlanet);


              return $this->render('/planets/attack/battleinfo.html.twig', [
                          'defenderRound' => $defenderRound,
                          'attackerRound' => $attackedRound,
                          'defender' => $defenderPlanet,
                          'attacked' => $attackedPlanet

                      ]);
                  }
                  else{

                      return $this->redirectToRoute('user_login');
              }


}
}