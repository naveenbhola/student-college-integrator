<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Log memory usage for request
 */
function set_siteview()
{
    global $flag_mobile_user_agent;
    global $flag_mobile_js_support_user_agent;

    $siteview = 'desktop';
    if($_COOKIE['ci_mobile'] == 'mobile' || $flag_mobile_user_agent == 'mobile') {
        if($_COOKIE['ci_mobile_js_support'] == 'yes' || $flag_mobile_js_support_user_agent == 'yes') {
            $siteview = 'mobile5';
        }
        else {
            $siteview = 'mobile4';
        }
    }
    setcookie('siteview', $siteview, (time() + (3600 * 24 * 30)), '/', COOKIEDOMAIN);
}

