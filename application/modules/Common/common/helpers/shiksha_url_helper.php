<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('site_url'))
{
         if (!function_exists('get_instance')) return "Can't get CIinstance.";
         $CI= &get_instance();
         $CI->load->helper('url');
}


if (! function_exists('listing_detail_overview_url'))
{
	function listing_detail_overview_url($params)
	{

            $type = $params['type'];
            $locality = $params['locality'];
            $city = $params['city'];
            $instituteName = $params['instituteName'];
            $courseName = $params['courseName'];
            $location = array("location"=>array($params['locality'], $param['city']));
            if($type == 'institute'){
                $id = $params['instituteId'];
            return getSeoUrl($id,$type,$instituteName,$location);
            }else{
                $id = $params['courseId'];
            return getSeoUrl($id,$type,$courseName,$location);
            }
        }
}

if (! function_exists('listing_detail_ask_answer_url'))
{
	function listing_detail_ask_answer_url($params)
	{
            $id = $params['instituteId'];
            $type = $params['type'];
            $instituteName = $params['instituteName'];
	    $abbrevation = $params['abbrevation'];
	    $instituteName = seo_url(implode("-", array($abbrevation,$instituteName)), "-", 30);
            return getSeoUrl($id,'listingAnaTab',$instituteName);
        }
}

if (! function_exists('listing_detail_alumni_speak_url'))
{
	function listing_detail_alumni_speak_url($params)
	{
            $id = $params['instituteId'];
            $type = $params['type'];
            $instituteName = $params['instituteName'];
	    $abbrevation = $params['abbrevation'];
	    $instituteName = seo_url(implode("-", array($abbrevation,$instituteName)), "-", 30);
            return getSeoUrl($id,'listingAlumniTab',$instituteName);
        }
}

if (! function_exists('listing_detail_media_url'))
{
	function listing_detail_media_url($params)
	{
            $id = $params['instituteId'];
            $type = $params['type'];
            $instituteName = $params['instituteName'];
	    $abbrevation = $params['abbrevation'];
	    $instituteName = seo_url(implode("-", array($abbrevation,$instituteName)), "-", 30);
            return getSeoUrl($id,'listingMediaTab',$instituteName);

        }
}

if (! function_exists('listing_detail_course_url'))
{
	function listing_detail_course_url($params)
	{
            $id = $params['instituteId'];
            $type = $params['type'];
            $instituteName = $params['instituteName'];
	    $abbrevation = $params['abbrevation'];
	    $instituteName = seo_url(implode("-", array($abbrevation,$instituteName)), "-", 30);
            return getSeoUrl($id,'listingCourseTab',$instituteName);

        }
}
/* Don't Know ... might be dead code written here :P */
if (! function_exists('listing_detail_params'))
{
        function listing_detail_params($url)
        {
            $arr = explode("-", $url);
            $count = count($arr);
            return array('institute_id' => $arr[$count - 1], 'course_id' => $arr[$count -2]);
        }
}

if (! function_exists('listing_detail_get_old_url_params'))
{
        function listing_detail_get_old_url_params($url)
        {
            $arr = explode("/", $url);
            $count = count($arr);
            return array('Title' => $arr[$count - 1], 'Type' => $arr[$count -2],'Id'=> $arr[$count-3]);
        }
}

if (! function_exists('listing_campus_rep_url'))
{
	function listing_campus_rep_url($params)
	{
		  $instituteName = $params['instituteName'];

		  $course = $params['course'];
		  $courseName = $course->getName();
                  
                  $location = $course->getMainLocation();
                  $location = array($location->getCity()->getName(),$location->getCountry()->getName());
		  sort($location);
		  $location = seo_url(implode("-", $location), "-", 10);

		  $title = $courseName.'-'.$instituteName.'-'.$location;		  
		  $title = seo_url($title, "-", 30);
		  
		  $id = $params['courseId'];
		  return getSeoUrl($id,'listingcampusreptab',$title);
        }
}
if(! function_exists('addingDomainNameToUrl'))
{
    function addingDomainNameToUrl($params)
    {
        if(empty($params['url']) || empty($params['domainName']))
            return;
        $url = $params['url'];
        $domainName = $params['domainName'];

        if(strpos($url,'/public/') !== FALSE){
            $domainName =IMGURL_SECURE;
        }

        if(strpos($url, 'http') === 0){
            return $url;
        }

        $url = (strpos($url, $domainName) === FALSE && strpos($url,'.com') == FALSE) ? ((strpos($url,'/') != 0) ? $domainName.'/'.$url : $domainName.$url ): $url ;

        return $url;
    }
}

?>
