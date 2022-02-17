<?php

$this->load->library('Online/courseLevelManager');

$this->load->view('studentFormsDashBoard/help_layer_'.$this->courselevelmanager->getCurrentDepartment(),$data);