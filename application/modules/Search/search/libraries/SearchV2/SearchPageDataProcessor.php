<?php

class SearchPageDataProcessor {
	public function getCourseFeesInWords($feesObj) {
		$feesAmount = $feesObj->getValue();
        $feesCurrency = $feesObj->getCurrency();
        
        if((int)$feesAmount >= 100000) {
        	$courseFees = $this->convertNumericFeesInWords((int)$feesAmount, $feesCurrency);
        } else {
        	$courseFees = number_format((int)$feesAmount);
        }

        return $courseFees;
	}

	public function convertNumericFeesInWords($feesAmount, $feesCurrency) {
		if($feesCurrency == 'USD') {
			if($feesAmount >= 1000000000) {
				return round(($feesAmount/1000000000), 1).' billion';
			}
			else if($feesAmount >= 1000000) {
				return round(($feesAmount/1000000), 1).' million';
			}
		} else {
			if($feesAmount == 10000000) {
				return '1 crore';
			}
			else if($feesAmount == 100000) {
				return '1 lakh';
			}
			else if($feesAmount > 10000000) { 
				return round(($feesAmount/10000000), 1).' crore';
			}
			else if($feesAmount > 100000) {
				return round(($feesAmount/100000), 1).' lakh';
			}
			else{
				return $feesAmount;
			}
		}
		
	}
}