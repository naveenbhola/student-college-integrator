<?php
/**
 * Builder class to create objects used in user module
 */ 
    
namespace user\Builders;

/**
 * Builder class to create objects used in user module
 */ 
class UserBuilder
{
    /**
     * Get user repository
     *
     * @param string $mode
     * @return object \user\Repositories\UserRepository
     */ 
    public static function getUserRepository($mode = 'read')
    {
        return \Doctrine::getRepository('User','user\Entities\User',$mode);
    }
}