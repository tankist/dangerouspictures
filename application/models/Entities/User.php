<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 * @ORM\Table(name="users",
 *             uniqueConstraints={
 *                  @ORM\UniqueConstraint(name="email", columns={"email"}),
 *            })
 */
class User extends AbstractEntity
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="email", unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", name="password", length=32)
     */
    protected $password;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $role = 0;

    /**
     * @var string
     * @ORM\Column(type="string", length=35)
     */
    protected $twitter;

    /**
     * @var string
     * @ORM\Column(type="string", length=35)
     */
    protected $facebook;

    /**
     * @var string
     * @ORM\Column(type="string", length=35)
     */
    protected $vimeo;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true, name="online_last")
     */
    protected $onlineLast;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $online = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param boolean $online
     * @return User
     */
    public function setOnline($online)
    {
        $this->online = $online;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param \DateTime $onlineLast
     * @return User
     */
    public function setOnlineLast($onlineLast)
    {
        $this->onlineLast = $onlineLast;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOnlineLast()
    {
        return $this->onlineLast;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param int $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $facebook
     * @return User
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $vimeo
     * @return User
     */
    public function setVimeo($vimeo)
    {
        $this->vimeo = $vimeo;
        return $this;
    }

    /**
     * @return string
     */
    public function getVimeo()
    {
        return $this->vimeo;
    }

    /**
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PreUpdate
     * @return User
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return User
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
        return $this;
    }

}
