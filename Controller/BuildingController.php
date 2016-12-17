<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use AppBundle\Entity\BuildingInProccess;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BuildingController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/planet/buildings", name="planet_buildings")
     * @Method("GET")
     */
    public function showPlanetBuildings()
    {

        $user_id = $this->get('user_service')->getUser();
        $planet_id = $this->get('planet_service')->getPlanet();
        if($user_id && $planet_id)
        {
            $planet = $this->getDoctrine()->getRepository(Planet::class)->findOneBy(['id'=>$planet_id]);
            $buildings = $this->getDoctrine()->getRepository(PlanetBuilding::class)->findBy(['planet'=>$planet]);
            $resources = $this->getDoctrine()->getRepository(GameResource::class)->findAll();

            $buildinginProgress=$this->getDoctrine()->getRepository(BuildingInProccess::class)->findOneBy(['planetId'=>$planet_id], ['id'=>'DESC']);

            if($buildinginProgress==null)
            {
              $timetolastBuilding=0;
            }
            else
            {
                $timetolastBuilding = $buildinginProgress->getDateoffinishedinSeconds();
            }


            return $this->render("planets/building/manager.html.twig", [
                'planet'=>$planet,
                'buildings'=>$buildings,
                'resources'=>$resources,
                'timetolastBuilding'=>$timetolastBuilding,
                'now'=>$this->get('game_service')->returnNowinSeconds()
            ]);

        }
        return $this->redirectToRoute("user_login");
    }
    /**
     * @Route("/building/evolve/{id}", name="evolve")
     * @param $id
     */
    public function evolve($id)
    {   if (!$this->get('user_service')->getUser()) { $this->redirectToRoute('user_login');}
        $planet = $this->get('planet_service')->getPlanetRepository();
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);
        $planetBuilding = $this->getDoctrine()->getRepository(PlanetBuilding::class)
            ->findOneBy(['planet'=>$planet, 'building'=>$building]);
        $currentLevel = $planetBuilding->getLevel();
        $costs = $building->getCosts();
       $timeCosts = $building->getTimeCost()->getAmout();
        $allResources = [];
        foreach ($costs as $cost) {
            $resourcesInPlanet = $this->getDoctrine()->getRepository(PlanetResource::class)
                ->findOneBy(['resources' => $cost->getResources(), 'planet' => $planet]);
            if ($resourcesInPlanet->getAmount() >= ($cost->getAmount() * ($currentLevel + 1))) {
                $allResources[$cost->getResources()->getName()] = ($cost->getAmount() * ($currentLevel + 1));
            } else {
                return $this->redirectToRoute("planet_buildings");
            }
        }

        $planetResources = $this->getDoctrine()->getRepository(PlanetResource::class)
            ->findBy(['planet'=>$planet]);

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
        $buildingInProgress = new BuildingInProccess();
        $buildingInProgress->setLevel($currentLevel+1);
        $buildingInProgress->setBuildingId($building->getId());
        $buildingInProgress->setPlanetId($planet->getId());
        $time = $this->get('game_service')->returnNowinSeconds()+($timeCosts*($currentLevel+1));
        $time = date('H:i:s d.m.Y', $time);
        $time = new \DateTime($time);
        $buildingInProgress->setDateoffinished($time);
        $em->persist($buildingInProgress);
        $em->flush();

        return $this->redirectToRoute("planet_buildings");
    }


}
