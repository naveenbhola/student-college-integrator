<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This class is use to generate URLs from parameters and parse out parameters from a given URL.
 */
class Url_manager
{
    function get_testprep_url($page_type, $blog_acronym, $city_name, $course_type, $page_num)
    {
        //handle exceptions
        if($city_name == 'Delhi/NCR') $city_name = 'Delhi-NCR';
        if(strstr($city_name, ' - Other') != false) $city_name = str_replace(' - Other', '-Other', $city_name);


        //create url
        $url_arr = array($blog_acronym, "Entrance-Exams-coaching-classes-institutes-in", $city_name, "India", $page_type, $course_type, "course", $page_num);
        return SHIKSHA_TESTPREP_HOME . "/testprep/".$this->get_url_from_array($url_arr);
    }

//    function get_testprep_url($page_type, $blog_id, $city_id, )


    function get_testprep_params_from_url($url)
    {
        $pos = strpos($url, "Entrance-Exams-coaching-classes-institutes-in");

        if($pos === false) {
            $arr = explode("Entrance-Exams-coaching-classes-institutes", $url);
        } else {
            $arr = explode("Entrance-Exams-coaching-classes-institutes-in", $url);
        }

        $blog_acronym = str_replace("-", " ", trim($arr[0], "-"));
        $arr = explode("India", $arr[1]);
        $city_name = str_replace("-", " ", trim($arr[0], "-"));
        $arr = explode("course", $arr[1]);
        $page_num = trim($arr[1], "-");
        $page_type = '';
        if(strpos($arr[0], 'most-viewed'))
        {
            $page_type = 'most-viewed';
            $course_type = str_replace("-", " ", trim(str_replace('most-viewed', '', $arr[0]), "-"));
        } else
        {
            $course_type = str_replace("-", " ", trim($arr[0], "-"));
        }
        //handle exceptions
        if($course_type == "E learning") $course_type = "E-learning";
        if($city_name == 'Delhi NCR') $city_name = 'Delhi/NCR';
        if(strstr($city_name, 'Other') != false)  $city_name = str_replace('Other', '- Other', $city_name);
        return array('blog_acronym' => $blog_acronym, 'city_name' => $city_name, 'page_num' => $page_num, 'page_type' => $page_type, 'course_type' => $course_type);
    }

    function get_url_from_array($url_arr)
    {
        $url_arr_blank_removed = array();
        foreach($url_arr as $el)
        {
            if ($el !== NULL and $el != '') array_push($url_arr_blank_removed, str_replace (" ", "-", $el));
        }
        return implode("-", $url_arr_blank_removed);
    }

    /*
        Function to test db connection obj if null then try to recreate it
        send mail that db connection failed
    */

    function CheckDB($db,$dbgroup='shiksha',$flag_connect=NULL,$flag_alert=NULL)
    {
        // get global CI instanse
        $CI =& get_instance();
        if (!is_null($CI))
        {
            // if $db is equal null
            if($db == null)
            {
                $ndnc_db = $shiksha_db = $sums_db = NULL;
                $msg = '';
                if ( $flag_connect != null)
                {
                    try {
                        if ($dbgroup == 'shiksha' || $dbgroup == 'default')
                        {
                            $shiksha_db = $CI->load->database('default', TRUE);
                            if($shiksha_db === null)
                            {
                                $shiksha_db->close();
                                $shiksha_db->initialize();
                            }
                            $db = $shiksha_db;
                        }
                        elseif ($dbgroup == 'sums')
                        {
                            $sums_db = $CI->load->database('sums', TRUE);
                            if($sums_db === null)
                            {
                                $sums_db->close();
                                $sums_db->initialize();
                            }
                            $db = $sums_db;
                        }
                        elseif ($dbgroup == 'ndnc')
                        {
                            $ndnc_db = $CI->load->database('ndnc', TRUE);
                            if($ndnc_db === null)
                            {
                                $ndnc_db->close();
                                $ndnc_db->initialize();
                            }
                            $db = $ndnc_db;
                        }
                        else
                        {
                            $shiksha_db = $CI->load->database('default', TRUE);
                            if($shiksha_db === null)
                            {
                                $shiksha_db->close();
                                $shiksha_db->initialize();
                           }
                            $db = $shiksha_db;
                        }
                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                        if ($flag_alert != null)
                        {
                        	/*
                        	 * defined @ /application/helpers/mailalert_helper.php (auto-loaded)
                        	 */
                        	sendMailAlert($msg,'DB connection was null, Trying to Reconnect.');
                        }
                    }
                }
                    return $db;
            }
            // $db is not null so return same
            else
            {
                return $db;
            }
        }
        else
        {
            return FALSE;
        }
    }
}
?>
