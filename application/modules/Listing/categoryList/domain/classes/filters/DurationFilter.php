<?php

class DurationFilter extends AbstractFilter
{
    private $displayNum = 3;
    private $rangeParameter = 5;
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function getFilteredValues()
    {
        $durationValues = $this->values;
        arsort($durationValues);
        $topDurations = array_slice(array_keys($durationValues),0,$this->displayNum);
        usort($topDurations,array($this,'_sortDurationValues'));
        return $topDurations;
    }
    
    private function _sortDurationValues($duration1,$duration2)
    {
        return strtotime("+ ".$duration1) - strtotime("+ ".$duration2);
    }
    
    /*
     * Detailed Logic: http://svn.infoedge.com:8080/Shiksha/ticket/488#comment:2
     */
    public function getDurationTypes()
    {
        $durationValues = array_keys($this->values);
        if(count($durationValues) <= $this->displayNum) {
            return NULL;
        }
        
        $durationTypes = $this->_getDurationTypesWithValues($durationValues);
        uksort($durationTypes,array($this,'__sortByDurationUnits'));
        
        /*
         * Create ranges of duration
         */ 
        foreach($durationTypes as $durationUnit => $durationValues) {
            $durationTypes[$durationUnit] = $this->_createRange($durationValues);
        }
        return $durationTypes;
    }
    
    private function __sortByDurationUnits($unit1,$unit2)
    {
        $precedence = array('Hours' => 1,'Days' => 2, 'Weeks' => 3, 'Months' => 4, 'Years' => 5);
        return $precedence[$unit1] - $precedence[$unit2];
    }
    
    private function _getDurationTypesWithValues($durationValues)
    {
        $durationTypes = array();
        
        foreach($durationValues as $value) {
            list($durationValue,$durationUnit) = explode(' ',$value);
            
            if(substr($durationUnit,-1) != 's') {
                $durationUnit .= 's';
            }
            
            if(!in_array($durationValue,$durationTypes[$durationUnit])) {
                $durationTypes[$durationUnit][] = $durationValue;
            }
        }
        
        foreach($durationTypes as $durationUnit => $durationValues) {
            sort($durationValues);
            $durationTypes[$durationUnit] = $durationValues;
        }
        
        return $durationTypes;
    }
    
    private function _createRange($durationValues)
    {
        $range = array();
        
        if(count($durationValues) <= $this->rangeParameter) {
            $range = array_combine($durationValues,$durationValues);
        }
        else {
            $i = 0;
            while($i < count($durationValues)) {
                
                if(count($durationValues) < $i + $this->rangeParameter) {
                    $range[$this->_getRangeKey($durationValues,$i)] = '>'.$durationValues[$i-1];
                }
                else {
                    $range[$this->_getRangeKey($durationValues,$i)] = $durationValues[$i].' - '.$durationValues[$i+$this->rangeParameter-1];
                }
                $i += $this->rangeParameter;
            }
        }
        
        return $range;
    }
    
    private function _getRangeKey($durationValues,$start,$end = -1)
    {
        if(count($durationValues) < $start + $this->rangeParameter) {
            $end = count($durationValues);
        }
        else {
            $end = $start + $this->rangeParameter;
        }
        
        $valuesInRange = array();
        for($i=$start;$i < $end;$i++) {
            $valuesInRange[] = $durationValues[$i];
        }
        return implode('-',$valuesInRange);
    }
    
    public function extractValue(Institute $institute,Course $course)
    {
        return (string) $course->getDuration();
    }
    
    public function addValue(Institute $institute,Course $course)
    {
        if($duration = $this->extractValue($institute,$course)) {
            if(isset($this->values[$duration])) {
                $this->values[$duration]++;
            }
            else {
                $this->values[$duration] = 1;
            }
            
        }
    }
    
    function setFilterValues($filterVaules = array()) {
	foreach($filterVaules as $key => $duration){
            if(isset($this->values[$duration])) {
                $this->values[$duration]++;
            }
            else {
                $this->values[$duration] = 1;
            }
        }
    	
    }
}