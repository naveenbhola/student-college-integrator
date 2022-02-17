<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function marksRangeChecker($rangeArray, $valueToCompare){
	if(!isset($valueToCompare) || empty($valueToCompare)){
		$valueToCompare = 0;
	}

	foreach($rangeArray as $expersion=>$value){
		$criteria = explode('&&', $expersion);

		if(count($criteria) > 1 && eval('return ('.$valueToCompare.' '.$criteria[0].' && '.$valueToCompare.' '.$criteria[1].');')){
			return $rangeArray[$expersion];
		}else if(count($criteria) == 1 && eval('return ('.$valueToCompare.' '.$criteria[0].');')){
			return $rangeArray[$expersion];
		}
	}
	
}

function returnMappedGraduationDiscipline($graduationStream, $masterList){
	if(in_array($graduationStream, $masterList)){
		return $graduationStream;
	}else{
		return 'Others';
	}
}

function sortArrayByArray($arrayToBeOrdered, $orderedArray) {
    $ordered = array();
    foreach($orderedArray as $key) {
        if(array_key_exists($key,$arrayToBeOrdered)) {
            $ordered[$key] = $arrayToBeOrdered[$key];
            unset($arrayToBeOrdered[$key]);
        }
    }
    return $ordered + $arrayToBeOrdered;
}

function getValidPercentile($calculatedScore){
	if($calculatedScore > 300){
		return 300;
	}else{
		return round($calculatedScore);
	}
}

function printMarksType($type){
	$percentage = array('graduationPercentage', 'xthPercentage', 'xiithPercentage', 'X_XII_Science_avg', 'X_XII_Commerce_avg', 'X_XII_Arts_avg');
	if(in_array($type, $percentage)){
		return '%';
	}else{
		return '%ile';
	}
}

function getCompositeScore($instittuteId,$xth=0,$xiith=0,$grad=0,$workEx=0,$va=0,$di=0,$qa=0,$cs=0,$ad=0,$gd=0){
    	$compositeScore;
    	switch($instittuteId){
    		//IIM Ahmedabad
    		case 307:
    			$compositeScore = ((($xth+$xiith+$grad)/30)*0.3)+($cs*0.7);
    			break;
    		//IIM Bangalore
    		case 318:
    			$compositeScore = (20*$xth)+(10*$xiith)+(20*$grad)+($workEx)+$gd+(40*$cs);
    			break;
    		//IIM Calcutta
    		case 20190:
    			$compositeScore = (10*$xth)+(10*$xiith)+$gd+(28*$cs);
    			break;
    		//IIM Lucknow
    		case 333:
    			$compositeScore = (10*$xiith)+(10*$grad)+(10*$workEx)+$ad+$gd+(60*$cs);
    			break;
    		//IIM Indore
    		case 29623:
    			$compositeScore = ($xth + $xiith + $gd + (20*$cs));
    			break;
    		//IIM Kozhikode
    		case 20188:
    			$compositeScore = ($xiith+$grad+$workEx+max($ad,$gd)+(75*$cs));
    			break;
    		//IIM Rohtak
    		case 32736;
    			$compositeScore = ($xth+$xiith+$grad+$workEx+(($cs*100)*1.5)+($va*100*0.5)+($di*100*0.5)+($qa*100*0.5));
    			break;
    		default :
    			$compositeScore = "NONE";
    			break;

    	}
    	return $compositeScore;
    }