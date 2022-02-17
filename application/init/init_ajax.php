<?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');
    if ( ! class_exists('ajax')) {
         require_once(APPPATH.'libraries/ajax'.EXT);
    }
    $obj =& get_instance();
    $obj->xajax = new ajax();
    $obj->ci_is_loaded[] = 'ajax';
?>

