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
    
    public function sort($universities)
    {
        $universities                   = $this->_groupUniversitiesBySortValue($universities);
        $groupedUniversities            = $universities['groups'];
        $orphanUniversitiesWithoutDept  = $universities['orphans']['orphansWithoutDept'];
        
        $order = $this->params['order'];
        
        $order == 'ASC'? ksort($groupedUniversities) : krsort($groupedUniversities);
        
        $sortedUniversitiesWithoutDept = array();
        foreach($groupedUniversities as $group => $universitiesInGroup) {
            foreach($universitiesInGroup as $uniId => $course){
                foreach($course as $courseId => $value){
                    $sortedUniversitiesWithoutDept[$uniId][$courseId] = $value;
                }
            }
        }
        
        if(!empty($orphanUniversitiesWithoutDept)) {
            $compositeArr = array();
            $compositeArr[0] = $sortedUniversitiesWithoutDept;
            $compositeArr[1] = $orphanUniversitiesWithoutDept;
            foreach($compositeArr as $key => $universitiesInGroup) {
                foreach($universitiesInGroup as $uniId => $course){
                    foreach($course as $courseId => $value){
                        $sortedUniversitiesWithoutDept[$uniId][$courseId] = $value;
                    }
                }
            }
        }
        
        $sortedUniversities['sortedUniversitiesWithoutDept'] = $sortedUniversitiesWithoutDept;
        
        return $sortedUniversities;
    }
    
    private function _groupUniversitiesBySortValue($universities)
    {
        $groups = array();
        $orphansWithDept = array();
        $orphansWithoutDept = array();
        
        foreach($universities as $universityId => $courses) {
                foreach($courses as $courseId => $value) {
                    if($this->sortKey == 3) {
                        $sortValue = $value['sortValues'][$this->sortKey][$this->params['exam']];
                    }
                    else {
                        $sortValue = $value['sortValues'][$this->sortKey];
                    }
                    if(!empty($sortValue)) {
                        $groups[$sortValue][$universityId][$courseId] = $value;
                    }
                    else {
                        $orphansWithoutDept[$universityId][$courseId] = $value;
                    }
                }
        }
        $orphans['orphansWithoutDept'] = $orphansWithoutDept;
        
        return array('groups' => $groups,'orphans' => $orphans);
    }
}