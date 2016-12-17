<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessagesRepository")
 */
class Messages
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="receiver")

     */

    private $receiver;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sender")
     */

    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */


    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendOn", type="datetime")
     */
    private $sendOn;

    /**
     * @var int
     *
     * @ORM\Column(name="isRead", type="integer")
     */
    private $isRead;


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
     * Set text
     *
     * @param string $text
     *
     * @return Messages
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sendOn
     *
     * @param \DateTime $sendOn
     *
     * @return Messages
     */
    public function setSendOn($sendOn)
    {
        $this->sendOn = $sendOn;

        return $this;
    }

    /**
     * Get sendOn
     *
     * @return \DateTime
     */
    public function getSendOn()
    {
        return $this->sendOn->format("H:i:s d.m.Y");
    }

    /**
     * Set isRead
     *
     * @param integer $isRead
     *
     * @return Messages
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return int
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @return User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param User $receiver
     */
    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;
    }


}

