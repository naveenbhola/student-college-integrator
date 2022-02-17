<?php
class examCalendarRedirectionLib {
  private $CI;

  public function __construct(){
    $this->CI = & get_instance();
  }

public function urlRedirectionCheck($param){
    if($param == 'mba'){
      $this->oldURLDedirection(array('pageName'=>'eventCalendar','oldUrl'=>"mba-exams-dates",'oldDomainName'=>array(SHIKSHA_MANAGEMENT_HOME),'newUrl'=>SHIKSHA_HOME.'/mba/resources/exam-calendar','redirectRule'=>301));
    }else if($param == 'engineering'){
      $this->oldURLDedirection(array('pageName'=>'eventCalendar','oldUrl'=>"engineering-exams-dates",'oldDomainName'=>array(),'newUrl'=>SHIKSHA_HOME.'/engineering/resources/exam-calendar','redirectRule'=>301));		
    }
	
    $returnArr['canonicalUrl'] = '';
    if('https://'.$_SERVER['HTTP_HOST']== SHIKSHA_SCIENCE_HOME){ 
      if($param=='engineering'){
        header('location:'.SHIKSHA_ENGINEERING_CALENDAR,301);
      }else{
        show_404();exit;
      }
    }

    if('https://'.$_SERVER['HTTP_HOST']== SHIKSHA_MANAGEMENT_HOME){ 
        if($param=='mba'){
          header('location:'.SHIKSHA_MBA_CALENDAR,301);
        }else{
          show_404();exit;
	      }
    }

    if('https://'.$_SERVER['HTTP_HOST']==SHIKSHA_HOME){
        if($param!='mba' && $param!='engineering'){
          show_404();exit;
        }
        
        if($param == 'mba'){
          $returnArr = array(
                        'canonicalUrl'      => SHIKSHA_MBA_CALENDAR,
                        'streamId'          =>MANAGEMENT_STREAM,
                        'courseId'          => MANAGEMENT_COURSE,
                        'educationTypeId'   => EDUCATION_TYPE,
                        'examCalendarTitle' => 'MBA'
                        );
        }else if($param == 'engineering'){
          $returnArr = array(
                        'canonicalUrl'      => SHIKSHA_ENGINEERING_CALENDAR,
                        'streamId'          =>ENGINEERING_STREAM,
                        'courseId'          => ENGINEERING_COURSE,
                        'educationTypeId'   => EDUCATION_TYPE,
                        'examCalendarTitle' => 'Engineering'
                        );
        }
    }
    return $returnArr;
	}

  public function oldURLDedirection($inputArr){
    Modules::run('common/Redirection/validateRedirection',$inputArr);
  }
}