<?php
namespace AppBundle\Services\Ships;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetShips;
use AppBundle\Entity\Ships;
use AppBundle\Entity\ShipsInProccess;
use Symfony\Component\DependencyInjection\Container;

class ShipsService
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container=$container;

    }
    public function updatePlanetShips()
    {
        $user = $this->container->get('user_service')->getUserRepository();
        $shipsinProgress=$this->container->get('doctrine')->getRepository(ShipsInProccess::class)->findAll();
        $planetShipRepository=$this->container->get('doctrine')->getRepository(PlanetShips::class);
        $entityManager=$this->container->get('doctrine')->getManager();
        foreach ($shipsinProgress as $ship)
        {
            $shipFinishTime = $ship->getDateoffinished()->format("H:i:s d.m.Y");
            $nowTime = date("H:i:s d.m.Y");
            if($shipFinishTime<=$nowTime)
            {
                $shipId=$ship->getShipId();
                $planetId = $ship->getPlanetId();
                $planetsRepository = $this->container->get('doctrine')->getRepository(Planet::class)->find($planetId);
                $shipsRepository = $this->container->get('doctrine')->getRepository(Ships::class)->find($shipId);
                $shipToUpdate = $planetShipRepository->findOneBy(['planet'=>$planetsRepository, 'ship'=>$shipsRepository]);

                $shipToUpdate->setLevel($ship->getLevel());
                $shipToUpdate->setPlanet($planetsRepository);
                $shipToUpdate->setShip($shipsRepository);
                $entityManager->persist($shipToUpdate);

                $shiptoRemove = $this->container->get('doctrine')->getRepository(ShipsInProccess::class)->findOneBy(['id'=>$ship->getId()]);
                $entityManager->remove($shiptoRemove);
                $entityManager->flush();
                $this->container->get('game_service')->saveActivity('notice', 'shipslevelup', $user);

            }
        }
    }

}