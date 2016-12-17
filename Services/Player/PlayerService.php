<?php
namespace AppBundle\Services\Player;
use AppBundle\Entity\Building;
use AppBundle\Entity\DayResources;
use AppBundle\Entity\Messages;
use AppBundle\Entity\Planet;
use AppBundle\Entity\PlanetBuilding;
use AppBundle\Entity\PlanetResource;
use AppBundle\Entity\User;
use AppBundle\Services\ServiceAbstract;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class PlayerService
{
    private $container;
    const ResourcesToGive= 100;

    public function __construct(ContainerInterface $container)
    {
        $this->container=$container;

    }
    public function getUser()
    {
        return $this->container->get('session')->get('id');
    }
    public function logOut()
    {
        $session = $this->container->get('session');
        $session->remove('id');
        $session->remove('planet_id');

    }
    public function saveUser(User $user){
       $userManager = $this->container->get('doctrine')->getManager();
       $userManager->persist($user);
        $userManager->flush();
    }
    public function getUserRepository()
    {
     return  $this->container->get('doctrine')->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);

    }
    public function checknewMessage()
    {
        $newMessage = $this->container->get('doctrine')->getRepository(Messages::class)->findBy(['receiver'=>$this->getUserRepository(), 'isRead'=>0]);
       return count($newMessage);
    }
    public function sendPrivateMessage(User $receiver, User $sender, $text)
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $message = new Messages();
        $message->setReceiver($receiver);
        $message->setSender($sender);
        $message->setSendOn(new \DateTime('now'));
        $message->setIsRead(0);
        $message->setText($text);
        $entityManager->persist($message);
        $entityManager->flush();


    }
    public function giveDayResource()
    {
        $user = $this->getUserRepository();
        $dayResource = $this->container->get('doctrine')->getRepository(DayResources::class)->findOneBy(['user_resource'=>$user]);
        $entityManager = $this->container->get('doctrine')->getManager();
        $planets = $this->container->get('doctrine')->getRepository(Planet::class)->findBy(['player'=>$user]);
        if($dayResource==null)
        {
            $giveResource = new DayResources();
            $giveResource->setUserResource($user);
            $date = date('d.m.Y');
            $giveResource->setDategive(new \DateTime($date));
            $entityManager->persist($giveResource);
            $entityManager->flush();
            $levelsum = 0;
            foreach ($planets as $planet)
            {
                $planetsRepositorys = $this->container->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$planet]);
                $buildingReposityorys = $this->container->get('doctrine')->getRepository(PlanetBuilding::class)->findBy(['planet'=>$planet]);
                foreach ($buildingReposityorys as $buildingReposityory)
                {
                    $levelsum=$levelsum+($buildingReposityory->getLevel()+1);
                }

                foreach ($planetsRepositorys as $planetsRepository)
                {
                    $planetsRepository->setAmount($planetsRepository->getAmount()+(self::ResourcesToGive*$levelsum));
                    $entityManager->persist($planetsRepository);
                    $entityManager->flush();
                }
            }
        }
        elseif($dayResource->getDategive()!=date('d.m.Y'))
        {
            $date = date('d.m.Y');
            $dayResource->setDategive(new \DateTime($date));
            $entityManager->persist($dayResource);
            $entityManager->flush();
            $levelsum = 0;
            foreach ($planets as $planet)
            {
                $planetsRepositorys = $this->container->get('doctrine')->getRepository(PlanetResource::class)->findBy(['planet'=>$planet]);
                $buildingReposityorys = $this->container->get('doctrine')->getRepository(PlanetBuilding::class)->findBy(['planet'=>$planet]);
                foreach ($buildingReposityorys as $buildingReposityory)
                {
                    $levelsum=$levelsum+($buildingReposityory->getLevel()+1);
                }

                foreach ($planetsRepositorys as $planetsRepository)
                {
                    $planetsRepository->setAmount($planetsRepository->getAmount()+(100*$levelsum));
                    $entityManager->persist($planetsRepository);
                    $entityManager->flush();
                }
            }
        }

    }






}