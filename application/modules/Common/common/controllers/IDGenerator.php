<?php

class IDGenerator extends MX_Controller
{
    private $idGeneratorModel;
    
    function __construct()
    {
        $this->load->model('common/idgeneratormodel');
        $this->idGeneratorModel = new IDGeneratorModel;
    }
    
    public function generateId($entity)
    {
        echo $this->idGeneratorModel->generateId($entity);
    }
}