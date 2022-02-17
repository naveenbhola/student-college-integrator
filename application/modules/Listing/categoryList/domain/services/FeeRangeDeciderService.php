<?php

class FeeRangeDeciderService
{
	private $allFeesRanges 		= array();
	private $feesRangeType 		= "RS_RANGE_IN_LACS";
	private $textForUnboundedFee   = "No Limit";
	
    function __construct($feesRangeType = "RS_RANGE_IN_LACS")
    {
		$this->feesRangeType 	= $feesRangeType;
		$this->allFeesRanges 	= $GLOBALS['CP_FEES_RANGE'];
    }
    
    public function setFeesRangeType($feesRangeType)
    {
        $this->feesRangeType = $feesRangeType;    
    }
    
    public function setFeeRanges($feeRanges)
    {
    	$this->allFeesRanges = $feeRanges;
    }
    
    public function setTextForUnboundedFee($textForUnboundedFee = "No Limit")
    {
    	$this->textForUnboundedFee = $textForUnboundedFee;
    }
    
    public function getTextForUnboundedFee(){
    	return $this->textForUnboundedFee;
    	
    }
    public function feesRange($value)
    {
		if(!array_key_exists($this->feesRangeType, $this->allFeesRanges)) {
            throw new Exception("Fees range mentioned is not valid range");
        }
		
        $feeRanges = $this->allFeesRanges[$this->feesRangeType];
		$rangeText = $this->feesRangeText($value, $feeRanges);
		return $rangeText;
	}
	
	private function feesRangeText($value = NULL, $feeRanges = NULL)
	{
		$range = false;
		if(!empty($feeRanges) && !empty($value))
		{
			$indexValue = 0;
			$minValue = min(array_keys($feeRanges));
			$maxValue = max(array_keys($feeRanges));
			if((int)$value <= (int)$minValue)
			{
				$range = $feeRanges[$minValue];
			}
			else if((int)$value > (int)$maxValue)
			{
				$range = $this->textForUnboundedFee;
			}
			else
			{
				foreach($feeRanges as $rangeValue => $rangeText)
				{
					if((int)$value <= (int)$rangeValue)
					{
						$range = $rangeText;
						break;
					}
				}	
			}
		}
		return $range;
	}
}