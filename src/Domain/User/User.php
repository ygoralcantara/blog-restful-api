<?php

namespace App\Domain\User;

use JsonSerializable;

/**
 * User Entity
 */
class User implements JsonSerializable{

    /**
     * Username
     *
     * @var string
     */
    private $username;
    
    /**
     * User name
     *
     * @var string
     */
    private $name;

    /**
     * User email
     *
     * @var string
     */
    private $email;

    /**
     * User password
     *
     * @var string
     */
    private $password;

    /**
     * User Construct
     *
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct($username, $name, $email, $password)
    {
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->setPassword($password);
    }

    /**
     * Get username
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param  string  $username  Username
     *
     * @return  self
     *
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }*/

    /**
     * Get user name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user name
     *
     * @param  string  $name  User name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get user email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user email
     *
     * @param  string  $email  User email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get user password
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set user password
     *
     * @param  string  $password  User password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = hash('sha256', $password);

        return $this;
    }

    /**
     * User Json
     *
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'username'  => $this->username,
            'name'      => $this->name,
            'email'     => $this->email,
        ];
    }
    
}

?>