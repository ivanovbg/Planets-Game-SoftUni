<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * GameActivity
 *
 * @ORM\Table(name="game_activity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameActivityRepository")
 */
class GameActivity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="game_activity")

     */

    private $player;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_activity", type="datetime")
     */
    private $time_activity;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return GameActivity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return GameActivity
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getTimeActivity(): \DateTime
    {
        return $this->time_activity;
    }

    public function getformatTimeActivity()
    {
        return $this->time_activity->format("H:i:s d.m.Y");
    }

    /**
     * @param \DateTime $time_activity
     */
    public function setTimeActivity(\DateTime $time_activity)
    {
        $this->time_activity = $time_activity;
    }

    /**
     * @return User
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param User $player
     */
    public function setPlayer(User $player)
    {
        $this->player = $player;
    }


}

