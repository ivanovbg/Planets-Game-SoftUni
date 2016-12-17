<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Data\Util\ArrayAccessibleResourceBundle;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @var Messages[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Messages", mappedBy="sender")
     * @ORM\JoinColumn(name="receiver_id")
     */

    private $sender;

    /**
     * @var Messages[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Messages", mappedBy="receiver")
     * @ORM\JoinColumn(name="receiver_id")
     */


    private $receiver;

    /**
     * @var DayResources
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\DayResources", mappedBy="user_resource")     *
     */


    private $user_resource;
    /**
     * @var Planet[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Planet", mappedBy="player")
     * @ORM\JoinColumn(name="player_id")
     */

    private $planets;
    /**
     * @var GameActivity[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GameActivity", mappedBy="player")
     * @ORM\JoinColumn(name="player_id")
     */

    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;
    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255, unique=true)
     */
    private $fullname;
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateofregistration", type="datetime")
     */
    private $dateofregistration;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
     public function __construct()
     {
         $this->planets=new ArrayCollection();
         $this->activity=new ArrayCollection();
         $this->sender = new ArrayCollection();
         $this->receiver=new ArrayCollection();
     }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateofregistration
     *
     * @param \DateTime $dateofregistration
     *
     * @return User
     */
    public function setDateofregistration($dateofregistration)
    {
        $this->dateofregistration = $dateofregistration;

        return $this;
    }

    /**
     * Get dateofregistration
     *
     * @return \DateTime
     */
    public function getDateofregistration()
    {
        return $this->dateofregistration;
    }
    public function getformDateofregistration()
    {
        return $this->dateofregistration->format('d.m.y');
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     */
    public function setFullname(string $fullname=null)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return Planet[]
     */
    public function getPlanets()
    {
        return $this->planets;
    }

    /**
     * @param Planet[] $planets
     */
    public function setPlanets(array $planets)
    {
        $this->planets = $planets;
    }

    /**
     * @return GameActivity[]
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param GameActivity[] $activity
     */
    public function setActivity(array $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return Messages[]
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param Messages[] $sender
     */
    public function setSender(array $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Messages[]
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param Messages[] $receiver
     */
    public function setReceiver(array $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return DayResources
     */
    public function getUserResource(): DayResources
    {
        return $this->user_resource;
    }

    /**
     * @param DayResources $user_resource
     */
    public function setUserResource(DayResources $user_resource)
    {
        $this->user_resource = $user_resource;
    }


}

