<?php

namespace registration\libraries\FieldValueSources;

/**
 * Abstract class for field value source
 */ 
abstract class AbstractValueSource
{
    /**
     * @var object CodeIgniter object
     */ 
    protected $CI;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 */ 
    abstract function getValues($params = array());
	
	public function getSelectedDesiredCourse($params)
	{
		/**
		 * Check if desired course is explicitly provided
		 */ 
		$desiredCourseId =  intval($params['desiredCourse']);
		if($desiredCourseId) {
			return $desiredCourseId;
		}
		
		/**
		 * Check if desired course is in the POST
		 */
		$desiredCourseId =  intval($_POST['desiredCourse']);
		if($desiredCourseId) {
			return $desiredCourseId;
		}
		
		/**
		 * Else it should be the case of MMP having only one desired course
		 */
		
		if($params['form'] instanceof \registration\libraries\Forms\MMP\National) {
			$mmpDetails = $params['form']->getMMPDetails();
			$fields = $params['form']->getFields();
			$desiredCourses = $fields['desiredCourse']->getValues(array('mmpFormId' => $mmpDetails['page_id']));
			foreach($desiredCourses as $group => $coursesInGroup) {
				foreach($coursesInGroup as $courseId => $courseName) {
					if($courseId) {
						$desiredCourseId = $courseId;
						break;
					}
				}
			}
			return $desiredCourseId;
		}
	}
}