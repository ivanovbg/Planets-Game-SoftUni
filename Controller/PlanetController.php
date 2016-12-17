<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GameActivity;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\PlanetShips;
use AppBundle\Entity\User;
use AppBundle\Form\CreatePlanet;
use AppBundle\Services\SaveGameActivity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanetController extends Controller
{


    public function getUser()
    {
        return $this->get('session')->get('id');
    }

    public function getPlanet()
    {
     return  $this->get('planet_service')->getPlanet();
    }
    /**
     * @Route("/planet/create", name="create_planet")
     * @Method("GET")
     */

    public function createPlanet()
    {
        if($this->getUser()) {
            $form = $this->createForm(CreatePlanet::class);
            return $this->render('planets/createplanet.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute("user_login");

    }
    /**
     * @Route("/planet/create", name="create_planet_post")
     * @Method("POST")
     * @param $request
     */

    public function createPlanetPost(Request $request)
    {
        if($this->get('user_service')->getUser()) {
            $form = $this->createForm(CreatePlanet::class);
            $postData = $request->request->all();
            $name = $postData[$form->getName()]['name'];
            $em = $this->getDoctrine()->getManager();
            $planetRepository = $this->getDoctrine()->getRepository(Planet::class);
            $user = $this->get('user_service')->getUserRepository();
            $create_planet = $this->get('planet_service')->createnewPlanet($user, $name);
            $activity_save = $this->get('game_service')->saveActivity('error', 'createplanet', $user);
            $this->addFlash(
                'notice', "Планета $name е създадена успешно"
            );
            return $this->redirectToRoute('create_planet');
        }
        $this->redirectToRoute('user_login');

    }

    /**
     * @Route("/change/planet/{planet_id}", name="change_planet")
     * @Method("GET")
     */
    public function checkPlanet($planet_id)
    { if($this->get('user_service')->getUser()) {
        $planet = $this->getDoctrine()->getRepository(Planet::class)->findOneBy(['id' => $planet_id]);
        if ($planet_id != null && $this->getUser() === $planet->getPlayer()->getId()) {
            $this->get("session")->set('planet_id', $planet_id);
            $user = $this->get('user_service')->getUserRepository();
            $activity_save = $this->get('game_service')->saveActivity("Notice", "changeplanet", $user);
            return $this->redirectToRoute("index_page");
        }
        return $this->redirectToRoute("index_page");
    }
    $this->redirectToRoute('user_login');
    }




    public function resourcesAction()
    {
        $id=$this->getPlanet();
        $planet = $this->getDoctrine()->getRepository(Planet::class)->findOneBy(['id'=>$id]);
        $buildings = $this->getDoctrine()->getRepository(PlanetBuilding::class)->findBy(['planet'=>$id]);
        return $this->render('planets/partials/planetresources.html.twig', [
            'planet'=>$planet,
            'buildings'=>$buildings
        ]);
    }


    /**
     * @Route("/planet", name="planet")
     * @Method("GET")
     */
    public function Planet()
    {
        if($this->getUser())
        {
            $planet = $this->getDoctrine()->getRepository(Planet::class)->findOneBy(['id'=>$this->getPlanet()]);
            $resources = $this->getDoctrine()->getRepository(PlanetResource::class)->findBy(['planet'=>$planet]);
            $buildings = $this->getDoctrine()->getRepository(PlanetBuilding::class)->findBy(['planet'=>$planet]);
            $ships = $this->getDoctrine()->getRepository(PlanetShips::class)->findBy(['planet'=>$planet]);


            return $this->render("/planets/planet.html.twig", [
                'planet'=>$planet,
                'resources'=>$resources,
                'buildings'=>$buildings,
                'ships'=>$ships
            ]);
        }
        $this->redirectToRoute("user_login");

        }


}
