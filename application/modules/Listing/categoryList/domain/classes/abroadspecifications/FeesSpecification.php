<?php

class FeesSpecification extends CompositeSpecification
{
    private $feesRangeType;
    private $allFeesRanges;
    
    function __construct()
    {
        $this->allFeesRanges 	= $GLOBALS['CP_ABROAD_FEES_RANGE'];
        $this->feesRangeType    = "ABROAD_RS_RANGE_IN_LACS";
    }
    
    function isSatisfiedBy($course)
    {
        
        $feesRanges      = $course['filterValues'][CP_FILTER_FEES];
        foreach($feesRanges as $rangeVal => $range){
            if(in_array($rangeVal,$this->filterValues)){
                return true;
            }
        }
        return false;
        // The old logic when there were single buckets. This is changed to accomodate the "Upto x Lacs" filters.
        /*$filterFeesValue = false;
        if(!empty($this->filterValues)){
            $filterFeesValue =  $this->filterValues[0];
        }
        //$validRanges       = $this->getValidRanges($filterFeesValue);
        $feesRange         = current($feesRanges);
        $courseValidFee    = array_search($feesRange, $this->allFeesRanges[$this->feesRangeType]);
        if(!empty($courseValidFee) && !empty($filterFeesValue) && in_array($courseValidFee, $this->filterValues))
        {
            return TRUE;
        }
        return FALSE;*/
    }
    
    public function setFilterValues($filterValues)
    {
        if(!empty($filterValues))
        {
            $this->filterValues = $filterValues; 
        }
    }
    
    public function setFeesRangeType($feesRangeType = NULL)
    {
        if(!empty($feesRangeType) && !empty($this->allFeesRanges))
        {
            $rangeTypes = array_keys($this->allFeesRanges);
            if(in_array($feesRangeType, $rangeTypes))
            {
                $this->feesRangeType = $feesRangeType;
            }
        }
    }
    
    public function getValidRanges($feesRange)
    {
        $ranges = array();
        
        $allValidFeeRanges = $this->allFeesRanges[$this->feesRangeType];
        //$feeValue = array_search($feesRange, $allValidFeeRanges);
        //$feeValues = array_keys($allValidFeeRanges);
        //
        //foreach($feeValues as $fee)
        //{
        //    if($fee <= $feeValue)
        //    {
        //        array_push($ranges, $fee);
        //    }
        //}

        $ranges[] = $feesRange;
        return $ranges;
    }
}