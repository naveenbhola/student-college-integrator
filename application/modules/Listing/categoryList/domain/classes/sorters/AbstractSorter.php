<?php

abstract class AbstractSorter
{
    protected $params;
    
    function __construct($params)
    {
        $this->params = $params;
    }
    
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function sort($institutes)
    {
        $institutes = $this->_groupInstitutesBySortValue($institutes);
        $groupedInstitutes = $institutes['groups'];
        $orphanInstitutes = $institutes['orphans'];
        
        $order = $this->params['order'];
        $order == 'ASC'? ksort($groupedInstitutes) : krsort($groupedInstitutes);
        
        $sortedInstitutes = array();
        foreach($groupedInstitutes as $group => $institutesInGroup) {
            $sortedInstitutes = $sortedInstitutes + $institutesInGroup;
        }
        $sortedInstitutes = $sortedInstitutes + $orphanInstitutes;
        return $sortedInstitutes;
    }
    
    private function _groupInstitutesBySortValue($institutes)
    {
        $groups = array();
        $orphans = array();
        
        foreach($institutes as $instituteId => $institute) {
            
            if(is_object($institute)) {
                $sortValue = $this->extractSortValue($institute,$institute->getFlagshipCourse());
            }
            else {
                $displayCourse = reset($institute);
                $sortValue = $displayCourse['sortValues'][$this->sortKey];
            }
            
            if($sortValue) {
                $groups[$sortValue][$instituteId] = $institute;
            }
            else {
                $orphans[$instituteId] = $institute;
            }
        }
        return array('groups' => $groups,'orphans' => $orphans);
    }
}