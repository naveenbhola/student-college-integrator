<?php

class SorterService
{
    private $defaultSorters;
    
    function __construct($sorters = array())
    {
        $this->defaultSorters = $sorters;
    }
    
    public function abroadSort($getRankingPageData, $sortingCriteria) {
        $sorter = $this->defaultSorters[$sortingCriteria['sortBy']];
        $sorter->setParams($sortingCriteria['params']);
        $sortedRankingPageData = $sorter->sort($getRankingPageData);
        return $sortedRankingPageData;
    }
    
    public function getAbroadSortValues($course,$defaultSortValue=1)
    {
        $sortValues = array();
        foreach($this->defaultSorters as $sorterKey => $sorter) {
            if($sorterKey != 'rank'){
            $sortValues[constant('ABROAD_CP_SORTER_'.strtoupper($sorterKey))] = $sorter->extractSortValue($course);
            }
            else
            {
            $sortValues[constant('ABROAD_CP_SORTER_'.strtoupper($sorterKey))] = $sorter->extractSortValue($defaultSortValue);
            }
        }
        return $sortValues;    
    }
}