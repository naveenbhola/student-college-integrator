<?php
/**
 * Registration absract observer
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration absract observer
 */ 
class AbstractObserver
{
    /**
     * @var object CodeIgniter object
     */ 
    protected $CI;
    
    /**
     * @var object User repository
     */ 
    protected $repository;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
        $this->repository = \user\Builders\UserBuilder::getUserRepository();
    }
}