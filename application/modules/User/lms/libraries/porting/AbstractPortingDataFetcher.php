<?php

abstract class AbstractPortingDataFetcher{
    protected $CI;
    protected $portingmodel;
    protected $portingEntity;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('lms/portingmodel');
        $this->portingmodel = new portingmodel();
    }

    function setPorting(PortingEntity $portingEntity){
        $this->portingEntity = $portingEntity;
    }

}
