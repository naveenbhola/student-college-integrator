<?php
/**
 * File for Populator class for UserWorkExp entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserWorkExp entity
 */ 
class UserWorkExp extends AbstractPopulator
{
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
     * Populate data into UserWorkExp entity
     *
     * @param object $UserWorkExp \user\Entities\UserWorkExp
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserWorkExp $userWorkExp,$data = array())
    {
        $this->setData($data);
        if(!empty($userWorkExp) && gettype($userWorkExp) == 'object') {

    	    if(!empty($data['employer'])){ 
                $userWorkExp->setEmployer(trim($data['employer']));
            }
            if(!empty($data['designation'])){
                $userWorkExp->setDesignation(trim($data['designation']));
            }
            if(!empty($data['department'])){
                $userWorkExp->setDepartment(trim($data['department']));
            }
            if(!empty($data['startDate']) && $this->_validtedate($data['startDate'])){
                $userWorkExp->setStartDate(new \DateTime($data['startDate']));
            }         
            if($data['currentJob'] == 'YES'){
                $userWorkExp->setCurrentJob('YES');
                $userWorkExp->setEndDate(new \DateTime('0000-00-00'));
            }else{
                $userWorkExp->setCurrentJob('NO');
                if(!empty($data['endDate']) && $this->_validtedate($data['endDate'])){
                    $userWorkExp->setEndDate(new \DateTime($data['endDate']));
                }
            }

	   }
    }

    private function _validtedate($date){
        if($data == '0000-00-00'){
            return true;
        }

        $dateArray = explode('-', $date);
        return checkdate($dateArray[1], $dateArray[0], $dateArray[2]);
    }
}
