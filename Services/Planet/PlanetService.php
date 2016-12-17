<?php
namespace AppBundle\Services\Planet;
use AppBundle\Entity\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\PlanetShips;
use AppBundle\Entity\Ships;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PlanetService
{
    const MAX_X = 100;
    const MAX_Y = 100;
    const MIN_X = 0;
    const MIN_Y = 0;
    const INITIAL_RESOURCES = 1000;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container=$container;

    }

    protected function getUser()
    {
        return $this->container->get('user_service')->getUser();
    }

      public function getPlanet()
        {
    $user = $this->container->get('doctrine')->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
    $planet=$this->container->get('session')->get('planet_id');
    if($planet==null)
    {
        $planets = $this->container->get('doctrine')->getRepository(Planet::class);
        $user_planets_exit=$planets->findBy(['player'=>$user]);
        if($user_planets_exit!=null){
            $planet = $user->getPlanets()[0]->getId();}
        else
        {
            $planet=0;
        }
        $this->container->get('session')->set('planet_id', $planet);
    }
    return $planet;
}

    public  function createnewPlanet(User $user, $name)
    {

        $entityManager = $this->container->get('doctrine')->getManager();
        $planetRepository = $this->container->get('doctrine')->getRepository(Planet::class);
        do{
            $x=rand(self::MIN_X, self::MAX_X);
            $y=rand(self::MIN_Y, self::MAX_Y);
            $usedplanet = $planetRepository->findOneBy(['x'=>$x, 'y'=>$y]);
            // var_dump($usedplanet);
            //exit;
        }
        while($usedplanet != null);
        $planet=new Planet();
        $planet->setName($name);
        $planet->setX($x);
        $planet->setY($y);
        $planet->setPlayer($user);
        $entityManager->persist($planet);
        $entityManager->flush();
        $resourceRepository = $this->container->get('doctrine')->getRepository(GameResource::class);
        $resourceType=$resourceRepository->findAll();
        foreach ($resourceType as $resource)
        {
            $planetResource= new PlanetResource();
            $planetResource->setResources($resource);
            $planetResource->setPlanet($planet);
            $planetResource->setAmount(self::INITIAL_RESOURCES);
            $entityManager->persist($planetResource);
            $entityManager->flush();
        }
        $buildingRepository = $this->container->get('doctrine')->getRepository(Building::class);
        $buildingTypes=$buildingRepository->findAll();
        foreach ($buildingTypes as $buildingType)
        {
            $planetBuilding = new PlanetBuilding();
            $planetBuilding->setPlanet($planet);
            $planetBuilding->setBuilding($buildingType);
            $planetBuilding->setLevel(0);
            $entityManager->persist($planetBuilding);
            $entityManager->flush();
        }
        $shipsRepository = $this->container->get('doctrine')->getRepository(Ships::class);
        $shipsTypes=$shipsRepository->findAll();
        foreach ($shipsTypes as $shipsType)
        {
            $planetShip=new PlanetShips();
            $planetShip->setPlanet($planet);
            $planetShip->setShip($shipsType);
            $planetShip->setLevel(0);
            $entityManager->persist($planetShip);
            $entityManager->flush();
        }
    }

    public function getPlanetRepository()
    {
        return $this->container->get('doctrine')->getRepository(Planet::class)->find($this->getPlanet());
    }
}