<?php
/**
 * Created by PhpStorm.
 * User: ivanovkbg
 * Date: 11.12.2016 г.
 * Time: 19:35
 */

namespace AppBundle\Services;


use AppBundle\Entity\Building;
use AppBundle\Entity\GameActivity;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\DependencyInjection\Container;

class GameService
{

    private $conteiner;
    const ACTIVITY_MESSAGE = 15;


    public function __construct(Container $container)
    {

        $this->conteiner=$container;
    }



    public function saveActivity($type, $message, User $user)
    {
        switch($message)
        {
            case 'createplanet': $message = "Създадена планета"; break;
            case 'changeplanet': $message = "Променена планета"; break;
            case 'buildinglevelup': $message = "Качено ниво на сграда"; break;
            case 'shipslevelup': $message = "Качено ниво на кораб"; break;
            case 'attack_planet': $message = "Атакувахте планета"; break;
            case 'wonbittle': $message = "Загубихте битка"; break;
            case 'winbittle': $message = "Спечелихте битка"; break;
            default: $message = "Друго"; break;
        }
        $entityManager = $this->conteiner->get('doctrine')->getManager();
        $activity = new GameActivity();
        $activity->setPlayer($user);
        $activity->setMessage($message);
        $activity->setType($type);
        $activity->setTimeActivity(new \DateTime("now"));
        $entityManager->persist($activity);
        $entityManager->flush();
    }

    public function showActivity()
    {
        $activity= $this->conteiner->get('doctrine')->getRepository(GameActivity::class);
        $activity=$activity->findBy(['player'=>$this->conteiner->get('user_service')->getUserRepository()], ['id'=>'DESC'], self::ACTIVITY_MESSAGE);
     return $activity;
    }
    public function returnNowinSeconds()
    {
        return strtotime(date("H:i:s d.m.Y"));
    }


}