<?php

abstract class LeadConsumptionReportGeneratorAbstract
{    
    protected $model;
    protected $dateFrom;
    protected $dateTo;
    protected $deliveryType;
    
    function __construct($model)
    {
        $this->model = $model;
    }
    
    public function setDateRange($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function setDeliveryType($deliveryType)
    {
        $this->deliveryType = $deliveryType;
    }
    
    abstract protected function getFields();
    abstract protected function getLeadConsumptionData();
    
    public function generateCSVReport()
    {
        /**
         * CSV headings
         */ 
        $headings = $this->getFields();
        
        /**
         * Merge heading with report data
         */ 
        $data = array_merge(array($headings), $this->getLeadConsumptionData());
        
        /**
         * Generate CSV
         */
        $csvReport = "";
        foreach($data as $dataRow) {
            $dataStr = array();
            foreach($dataRow as $datum) {
                $dataStr[] = '"'.addslashes($datum).'"';
            }
            $csvReport .= implode(",",$dataStr)."\n";
        }
        
        return $csvReport;
    }   		    	
}