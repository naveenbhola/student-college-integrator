<?php

class ExamsScoreSpecification extends CompositeSpecification
{
    function isSatisfiedBy($course)
    {
        $courseExamScore      = $course['filterValues'][CP_FILTER_EXAMSSCORE];
        foreach($this->filterValues as $key=>$val)
        {
            $detailArray = explode('--',$val);
            if($detailArray[1] == "" || (integer)$detailArray[1] == 0){
                //In case of "Any" selection
                return true;
            }
            if(array_key_exists($detailArray[2],$courseExamScore))
            {
                if($detailArray[0]=="CAE" && $courseExamScore[$detailArray[2]] != "N/A" && $courseExamScore[$detailArray[2]] >= $detailArray[1])
                {
                    return true;
                }
                else if($detailArray[0]!="CAE" && $courseExamScore[$detailArray[2]] != "N/A" && $courseExamScore[$detailArray[2]] <= $detailArray[1])
                {
                    return true;
                }
                else{
                    return false;  
                }
            }
            else
            {
                return false;
            }
            
        }
        return false;
    }
}