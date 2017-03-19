<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 18/03/2017
 * Time: 16:51
 */

namespace WebLinks\Domain;


use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * User id
     * @var integer
     */
    private $id;

    /**
     * User name
     * @var string
     */
    private $username;

    /**
     * User password
     * @var string
     */
    private $password;

    /**
     * Salt that was originally used to encode the password
     * @var string
     */
    private $salt;

    /**
     * Role
     * Values: ROLE_USER or ROLE_ADMIN
     * @var string
     */
    private $role;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }
}
