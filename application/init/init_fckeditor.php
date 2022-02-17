<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! class_exists('FCKeditor'))
{
     require_once(APPPATH.'libraries/fckeditor'.EXT);
}
$this->fckeditor->BasePath = 'system/plugins/fckeditor/';
$obj =& get_instance();
$obj->fckeditor = new FCKeditor('FCKEDITOR1');
$obj->ci_is_loaded[] = 'fckeditor';
?> 
