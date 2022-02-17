<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_apache_var() {
        if(function_exists('apache_setenv')) {
            $getCopy = $_GET;
            apache_setenv("g_param", http_build_query($getCopy));
            apache_setenv("p_param", ((empty($_POST))?'-':http_build_query($_POST)));
            $cookieCopy = $_COOKIE;
            global $cookies;
            if(is_array($cookies)){
                $cookieCopy = array_merge($_COOKIE, $cookies);
            }
            apache_setenv("c_param", ((empty($cookieCopy))?'-':http_build_query($cookieCopy)));
        }
}

