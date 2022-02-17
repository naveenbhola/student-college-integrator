<?php

/**
 *File for user\Entities\UserPointSystem Entity
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserPointSystem
 */
class UserPointSystem
{
    /**
     * Field for User Points
     * @var integer $userPoints
     */
    private $userPoints;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Entity  user\Entities\User
     * @var user\Entities\User
     */
    private $user;


    /**
     * Set userPoints
     *
     * @param integer $userPoints
     * @return UserPointSystem
     */
    public function setUserPoints($userPoints)
    {
        $this->userPoints = $userPoints;
        return $this;
    }

    /**
     * Get userPoints
     *
     * @return integer 
     */
    public function getUserPoints()
    {
        return $this->userPoints;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param user\Entities\User $user
     * @return UserPointSystem
     */
    public function setUser(\user\Entities\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return user\Entities\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}