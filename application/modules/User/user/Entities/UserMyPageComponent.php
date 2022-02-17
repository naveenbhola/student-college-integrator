<?php
/**
 * File for user\Entities\UserMyPageComponent entity
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserMyPageComponent
 */
class UserMyPageComponent
{
    /**
     * Field for Component
     * @var string $component
     */
    private $component;

    /**
     * Field for Position
     * @var integer $position
     */
    private $position;

    /**
     * Field for Display
     * @var string $display
     */
    private $display;

    /**
     * Field for itemCount
     * @var integer $itemCount
     */
    private $itemCount;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Entity user\Entities\User
     * @var user\Entities\User
     */
    private $user;


    /**
     * Set component
     *
     * @param string $component
     * @return UserMyPageComponent
     */
    public function setComponent($component)
    {
        $this->component = $component;
        return $this;
    }

    /**
     * Get component
     *
     * @return string 
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return UserMyPageComponent
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set display
     *
     * @param string $display
     * @return UserMyPageComponent
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }

    /**
     * Get display
     *
     * @return string 
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set itemCount
     *
     * @param integer $itemCount
     * @return UserMyPageComponent
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;
        return $this;
    }

    /**
     * Get itemCount
     *
     * @return integer 
     */
    public function getItemCount()
    {
        return $this->itemCount;
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
     * @return UserMyPageComponent
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