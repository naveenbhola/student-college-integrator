<?php

class SorterService
{
    private $defaultSorters;
    
    function __construct($sorters = array())
    {
        $this->defaultSorters = $sorters;
    }
    
    public function sort($institutes,$sortingCriteria,$sortWithFreshData = FALSE, $institutesGroupByType = array())
    {
        $sorter = $this->defaultSorters[$sortingCriteria['sortBy']];
        $sorter->setParams($sortingCriteria['params']);
        $sortedInstitutes = $sortWithFreshData ? $sorter->sortWithFreshData($institutes) : $sorter->sort($institutes, $institutesGroupByType);
        return $sortedInstitutes;
    }
    
    public function getSortValues($institute, $course)
    {
        $sortValues = array();
        foreach($this->defaultSorters as $sorterKey => $sorter) {
            $sortValues[constant('CP_SORTER_'.strtoupper($sorterKey))] = $sorter->extractSortValue($institute,$course);
        }
        return $sortValues;
    }
    
    /* Purpose: Sort functions for abroad sorting
     * Author:  Nikita Jain
     */
    public function abroadSort($universities, $sortingCriteria) {
        $sorter = $this->defaultSorters[$sortingCriteria['sortBy']];
        
        if(empty($sorter)){
            $sorter = $this->defaultSorters['viewCount'];
        }
        if(empty($sorter)){ // If we still don't have a sorter, then return universities as is
            return $universities;
        }
        if(key_exists('exam',$sortingCriteria['params']) && $sortingCriteria['params']['exam']=='CAE')
        {
            $sortingCriteria['params']['order'] = ($sortingCriteria['params']['order']=='ASC')?'DESC':'ASC';
        }
        $sorter->setParams($sortingCriteria['params']);    
        $sortedUniversities = $sorter->sort($universities);
        return $sortedUniversities;
    }
    
    public function getAbroadSortValues($course)
    {        
        $sortValues = array();
        foreach($this->defaultSorters as $sorterKey => $sorter) {
            if(constant('ABROAD_CP_SORTER_'.strtoupper($sorterKey)) !=2){
                $sortValues[constant('ABROAD_CP_SORTER_'.strtoupper($sorterKey))] = $sorter->extractSortValue($course);
            }
        }
        return $sortValues;    
    }
}
