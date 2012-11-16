<?php

namespace BookShop\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $id;
    private $username;
    private $password;
    private $salt;
    private $cart;

    public function __construct()
    {
        $this->cart = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function eraseCredentials()
    {
        // noop
    }

    public function getRoles()
    {
        return ["ROLE_USER"];

    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getCart()
    {
        return $this->cart;
    }
}
