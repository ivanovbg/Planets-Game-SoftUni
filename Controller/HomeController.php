<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GameActivity;
use AppBundle\Entity\Planet;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function navigationAction()
    {
        $this->get('building_service')->updatePlanetBuildings();
        $this->get('ships_service')->updatePlanetShips();
        return $this->render('/partials/navigation.html.twig');
    }
    public function getUser()
    {
        return $this->get('session')->get('id');
    }
    public function getCurrentPlanet()
    {
        return $this->get('planet_service')->getPlanet();
    }
    /**
     * @Route("/", name="index_page")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            $planets = $this->getDoctrine()->getRepository(Planet::class);
            $user = $this->get('user_service')->getUserRepository();
            $choiced_planet = $this->getCurrentPlanet();
            $user_planets_exit = $planets->findBy(['player' => $user]);
            $count_planets = count($user_planets_exit);
            $newMessage = $this->get('user_service')->checknewMessage();
            $this->get('user_service')->giveDayResource();
            return $this->render('users/dashboards.html.twig', [
                'planets' => $user_planets_exit,
                'count' => $count_planets,
                'user' => $user,
                'choiced_planet' => $choiced_planet,
                'newmessage'=>$newMessage
            ]);
        }
        return $this->render('base.html.twig');
    }

    /**
     * @Route("activity", name="game_activity")
     * @Method("GET")
     */
    public function showActivity()
    {
        if($this->getUser()){
          $activity=$this->get('game_service')->showActivity();
            return $this->render('users/activity.html.twig', [
                'activity'=>$activity
            ]);
        }
        return $this->redirectToRoute("user_login");
    }
}
