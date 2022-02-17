<?php
/**
 * File for Populator class for UserCourseSpecialization entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserCourseSpecialization entity
 */ 
class UserCourseSpecialization extends AbstractPopulator
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
     * Populate data into UserCourseSpecialization entity
     *
     * @param object $UserCourseSpecialization \user\Entities\UserCourseSpecialization
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserCourseSpecialization $userCourseSpecialization,$data = array(), $courseLevel)
    {
        
        $this->setData($data);
        if(isset($userCourseSpecialization) && gettype($userCourseSpecialization) == 'object') {

            if(isset($data['specialization'])){
                $userCourseSpecialization->setSpecializationId($data['specialization']);
            }
            if(isset($data['baseCourse'])){
                $userCourseSpecialization->setBaseCourseId($data['baseCourse']);
            }
            if(isset($courseLevel[$data['baseCourse']])){
                $userCourseSpecialization->setCourseLevel($courseLevel[$data['baseCourse']]);
            }
            $userCourseSpecialization->setTime(new \DateTime());
            $userCourseSpecialization->setStatus('live');
            
	   }

    }
}
