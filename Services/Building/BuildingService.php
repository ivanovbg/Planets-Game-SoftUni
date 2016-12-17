<?php
namespace AppBundle\Services\Building;
use AppBundle\Entity\Building;
use AppBundle\Entity\BuildingInProccess;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BuildingService
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container=$container;
    }

    public function updatePlanetBuildings()
    {
        $buildinginProgress=$this->container->get('doctrine')->getRepository(BuildingInProccess::class)->findAll();
        $planetBuildingRepository=$this->container->get('doctrine')->getRepository(PlanetBuilding::class);
        $entityManager=$this->container->get('doctrine')->getManager();
        $user=$this->container->get('user_service')->getUserRepository();
        foreach ($buildinginProgress as $building)
        {
            $buildingFinishTime = $building->getDateoffinished()->format("H:i:s d.m.Y");
            $nowTime = date("H:i:s d.m.Y");
            if($buildingFinishTime<=$nowTime)
            {
                $buildingId=$building->getBuildingId();
                $planetId = $building->getPlanetId();
                $planetsRepository = $this->container->get('doctrine')->getRepository(Planet::class)->find($planetId);
                $buildingsRepository = $this->container->get('doctrine')->getRepository(Building::class)->find($buildingId);
                $buildingToUpdate = $planetBuildingRepository->findOneBy(['planet'=>$planetsRepository, 'building'=>$buildingsRepository]);

                $buildingToUpdate->setLevel($building->getLevel());
                $buildingToUpdate->setPlanet($planetsRepository);
                $buildingToUpdate->setBuilding($buildingsRepository);
                $entityManager->persist($buildingToUpdate);

                $buildtoRemove = $this->container->get('doctrine')->getRepository(BuildingInProccess::class)->findOneBy(['id'=>$building->getId()]);
                $entityManager->remove($buildtoRemove);
                $entityManager->flush();

                $this->container->get('game_service')->saveActivity('notice', 'buildinglevelup', $user);

            }
        }
    }

}