<?php

class MoreOptionsFilter extends AbstractFilter
{
    
	
	function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        return $this->values;
    }
    
	public function extractValue(University $university,AbroadInstitute $institute,AbroadCourse $course, $isCertDiplomaPage=false)
    {
    	$moreOptions = array();
       	if($course->isOfferingScholarship())
       	{
       		$moreOptions [] = 'OFR_SCHLSHP';
			$this->values['OFR_SCHLSHP'] = $GLOBALS['MORE_OPTIONS']['OFR_SCHLSHP'];
       	}
       	if($university->hasCampusAccommodation())
       	{
       		$moreOptions [] = 'WTH_ACMDTN';
			$this->values['WTH_ACMDTN'] = $GLOBALS['MORE_OPTIONS']['WTH_ACMDTN'];
       	}
       	if($university->isPublicalyFunded())
       	{
       		$moreOptions [] = 'PUB_FUND';
			$this->values['PUB_FUND'] = $GLOBALS['MORE_OPTIONS']['PUB_FUND'];
       	}
        global $certificateDiplomaLevels;
		// level is not cert diploma type or page is cert diploma type
		if(!in_array($course->getCourseLevel1Value(),$certificateDiplomaLevels) || $isCertDiplomaPage){
			$moreOptions[] = 'DEGREE_COURSE';
			$this->values['DEGREE_COURSE'] = $GLOBALS['MORE_OPTIONS']['DEGREE_COURSE'];
		}else{
		  global $levelFilter;
		  $levelFilter = true;
		}
    	return $moreOptions;
    }
    public function addValue(University $university,AbroadInstitute $institute,AbroadCourse $course)
    {
    	
    	if($course->isOfferingScholarship())
    	{
    		$this->values['OFR_SCHLSHP'] = $GLOBALS['MORE_OPTIONS']['OFR_SCHLSHP'];
    	}
    	if($university->hasCampusAccommodation())
    	{
    		$this->values['WTH_ACMDTN'] = $GLOBALS['MORE_OPTIONS']['WTH_ACMDTN'];
    	}
    	if($university->isPublicalyFunded())
    	{
    		$this->values['PUB_FUND'] = $GLOBALS['MORE_OPTIONS']['PUB_FUND'];
    	}
    	global $certificateDiplomaLevels;
        if(!in_array($course->getCourseLevel1Value(),$certificateDiplomaLevels)){
            $moreOptions[] = 'DEGREE_COURSE';
            $this->values['DEGREE_COURSE'] = $GLOBALS['MORE_OPTIONS']['DEGREE_COURSE'];
        }
    }

    public function unsetDegreeCourses(){
      unset($this->values['DEGREE_COURSE']);

    }
}
