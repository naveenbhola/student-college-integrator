<?php

class MobileApp extends MX_Controller
{
    function dbSchema($name)
    {
	$this->load->view('shikshaSchema/app/'.$name);
    }	

    function document($name)
    {
        $this->load->view('shikshaSchema/app/'.$name);
    }

}
