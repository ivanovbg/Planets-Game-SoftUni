<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\BuildingInProccess;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\PlanetShips;
use AppBundle\Entity\Ships;
use AppBundle\Entity\ShipsInProccess;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

class ShipsController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/planet/ships", name="planet_ships")
     * @Method("GET")
     */
    public function showPlanetShips()
    {

        $user_id = $this->get('user_service')->getUser();
        $planet_id = $this->get('planet_service')->getPlanet();
        if($user_id && $planet_id)
        {
            $planet = $this->getDoctrine()->getRepository(Planet::class)->findOneBy(['id'=>$planet_id]);
            $ships = $this->getDoctrine()->getRepository(PlanetShips::class)->findBy(['planet'=>$planet]);
            $resources = $this->getDoctrine()->getRepository(GameResource::class)->findAll();

            $shipsinProccess=$this->getDoctrine()->getRepository(ShipsInProccess::class)->findOneBy(['planetId'=>$planet_id], ['id'=>'DESC']);

            if($shipsinProccess==null)
            {
              $timetolastShip=0;
            }
            else
            {
                $timetolastShip = $shipsinProccess->getDateoffinishedinSeconds();
            }


            return $this->render("planets/ships/manager.html.twig", [
                'planet'=>$planet,
                'ships'=>$ships,
                'resources'=>$resources,
                'timetolastShip'=>$timetolastShip,
                'now'=>$this->get('game_service')->returnNowinSeconds()
            ]);

        }
        return $this->redirectToRoute("user_login");
    }
    /**
     * @Route("/ships/evolve/{id}", name="ship_evolve")
     * @param $id
     */
    public function evolve($id)
    {
        if (!$this->get('user_service')->getUser()) { $this->redirectToRoute('user_login');}

            $planet = $this->getDoctrine()->getRepository(Planet::class)->find($this->get('session')->get('planet_id'));
            $ship = $this->getDoctrine()->getRepository(Ships::class)->find($id);
            $planetShip = $this->getDoctrine()->getRepository(PlanetShips::class)
                ->findOneBy(['planet' => $planet, 'ship' => $ship]);
            $shipinProgress = new ShipsInProccess();
            $currentLevel = $planetShip->getLevel();
            $costs = $ship->getCosts();
            $timeCosts = $ship->getTimeCost()->getAmount();
            $allResources = [];
            foreach ($costs as $cost) {
                $resourcesInPlanet = $this->getDoctrine()->getRepository(PlanetResource::class)
                    ->findOneBy(['resources' => $cost->getResources(), 'planet' => $planet]);
                if ($resourcesInPlanet->getAmount() >= ($cost->getAmount() * ($currentLevel + 1))) {
                    $allResources[$cost->getResources()->getName()] = ($cost->getAmount() * ($currentLevel + 1));
                } else {
                    return $this->redirectToRoute("planet_ships");
                }
            }

            $planetResources = $this->getDoctrine()->getRepository(PlanetResource::class)
                ->findBy(['planet' => $planet]);

            $em = $this->getDoctrine()->getManager();
            foreach ($planetResources as $planetResource) {
                $name = $planetResource->getResources()->getName();
                $cost = $allResources[$name];
                $planetResource->setAmount(
                    $planetResource->getAmount() - $cost
                );
                $em->persist($planetResource);
                $em->flush();
            }
            $shipinProgress->setLevel(($currentLevel + 1));
            $shipinProgress->setShipId($ship->getId());
            $shipinProgress->setPlanetId($planet->getId());
            $time = $this->get('game_service')->returnNowinSeconds() + ($timeCosts * ($currentLevel+1));
            $time = date('H:i:s d.m.Y', $time);
            $time = new \DateTime($time);
            $shipinProgress->setDateoffinished($time);
            $em->persist($shipinProgress);
            $em->flush();

            return $this->redirectToRoute("planet_ships");
        }







}
