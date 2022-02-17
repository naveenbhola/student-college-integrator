<?php

class FeesSpecification extends CompositeSpecification
{
    private $feesRangeType;
    private $allFeesRanges;
    
    function __construct()
    {
        $this->allFeesRanges 	= $GLOBALS['CP_FEES_RANGE'];
        $this->feesRangeType    = "RS_RANGE_IN_LACS";
    }
    
    function isSatisfiedBy($course)
    {
        $feesRange      = $course['filterValues'][CP_FILTER_FEES];
        $filterFeesValue = false;
        if(!empty($this->filterValues)){
            $filterFeesValue =  $this->filterValues[0];
        }
        $validRanges    = $this->getValidRanges($filterFeesValue);
        if(!empty($feesRange) && !empty($validRanges) && in_array($feesRange, $validRanges))
        {
            return TRUE;
        }
        return FALSE;
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
        $feesRangesByType       = $this->allFeesRanges[$this->feesRangeType];
        $allFeeRangeLabels      = array_merge(array_values($feesRangesByType), array("No Limit"));
        if(!in_array($feesRange, $allFeeRangeLabels) || empty($feesRange))
        {
            return $allFeeRangeLabels;
        }
        $flag = TRUE;
        foreach($feesRangesByType as $rangeValue => $rangeLabel)
        {
            $ranges[] = $rangeLabel;
            if($rangeLabel == $feesRange)
            {
               $ranges[] = $rangeLabel;
               $flag = FALSE;
               break;
            }
        }
        if($flag)
        {
            $ranges[] = "No Limit";
        }
        $ranges = array_unique($ranges);
        return $ranges;
    }
}