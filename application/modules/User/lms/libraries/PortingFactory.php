<?php

class PortingFactory
{

    public static function getPorterObj($params)
    {
        $CI = &get_instance();
        if ($params == 'lead' || $params == 'matched_response') {
            $CI->load->library('lms/porting/LeadPorter');
            return new LeadPorter();
        }
        else if ($params == 'response') {
            $CI->load->library('lms/porting/ResponsePorter');
            return new ResponsePorter();
        }else if ($params == 'examResponse') {
            $CI->load->library('lms/porting/examResponsePorter');
            return new examResponsePorter();
        }
    }

	public static function getPortingDataObject($params)
	{
		$CI = &get_instance();
		if (in_array($params, array('tuser','first_name','last_name','workex'))) {
		    $CI->load->library('lms/porting/UserBasicData');
		    return new UserBasicData();
		}
		else if ($params == 'citytable') {
		    $CI->load->library('lms/porting/UserCityData');
		    return new UserCityData();
		}
		else if ($params == 'course') {
		    $CI->load->library('lms/porting/UserPrefCourseDetails');
		    return new UserPrefCourseDetails();
		}
		else if ($params == 'country') {
		    $CI->load->library('lms/porting/UserPrefCountryDetails');
		    return new UserPrefCountryDetails();
		}
		else if ($params == 'prefcity') {
		    $CI->load->library('lms/porting/UserPrefCityData');
		    return new UserPrefCityData();
		}
		else if ($params == 'specialization') {
		    $CI->load->library('lms/porting/UserPrefSpecializationData');
		    return new UserPrefSpecializationData();
		}
		else if ($params == 'XIIPassYear') {
		    $CI->load->library('lms/porting/UserEducationData');
		    return new UserEducationData();
		}
		else if ($params == 'locality') {
		    $CI->load->library('lms/porting/UserLocalityData');
		    return new UserLocalityData();
		}
		else if ($params == 'GradPassYear') {
		    $CI->load->library('lms/porting/GradPassData');
		    return new GradPassData();
		}
		else if ($params == 'statetable') {
		    $CI->load->library('lms/porting/LDB_Residence_State');
		    return new LDB_Residence_State();
		}
		else if ($params == 'dateTime') {
		    $CI->load->library('lms/porting/Date_And_Time');
		    return new Date_And_Time();
		}
		else if ($params == 'date') {
		    $CI->load->library('lms/porting/Date');
		    return new Date();
		}
		else if ($params == 'time') {
		    $CI->load->library('lms/porting/Time');
		    return new Time();
		}
		else if ($params == 'leadID') {
		    $CI->load->library('lms/porting/Lead_ID');
		    return new Lead_ID();
		}
		else if ($params == 'exams') {
			$CI->load->library('lms/porting/UserExamsData');
		    return new UserExamsData();
		}
		
	}

	public static function getPortingRepository() {
		$CI = &get_instance();
		/*
		 * Load dependencies for Porting Repository
		 */
		$CI->load->model('lms/portingmodel');
		$model = $CI->portingmodel;
		/*
		 * Load the repository
		 */
		$CI->load->repository('PortingRepository','lms');
		$portingRepository = new PortingRepository($model);
		return $portingRepository;
	}

}
