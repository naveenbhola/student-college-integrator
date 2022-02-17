<?php
/**
 * File for Populator class for UserMyPageComponent entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserMyPageComponent entity
 */ 
class UserMyPageComponent extends AbstractPopulator
{
    /**
     * My Shiksha Page Components
     *
     * @var array 
     */ 
    public static $components = array(
        array(1,'myShikshaDiscussion',1,2),
        array(2,'myShikshaEvents',1,2),
        array(3,'myShikshaNetwork',1,10),
        array(4,'myShikshaCollgenetwork',1,3),
        array(5,'blogs',1,2),
        array(6,'polls',0,2),
        array(7,'myShikshaListing',1,3)
    );
    
    /**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into UserMyPageComponent entity
     *
     * @param object $myPageComponent \user\Entities\UserMyPageComponent
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserMyPageComponent $myPageComponent,$data = array())
    {
        $myPageComponent->setPosition($data[0]);
        $myPageComponent->setComponent($data[1]);
        $myPageComponent->setDisplay($data[2]);
        $myPageComponent->setItemCount($data[3]);
    }
}