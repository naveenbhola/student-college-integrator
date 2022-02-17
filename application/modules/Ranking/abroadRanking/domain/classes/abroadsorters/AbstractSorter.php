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
    
    public function sort($getRankingPageData)
    {
        $getRankingPageDataRaw     = $this->_groupRankingPageTuplesBySortValue($getRankingPageData);
        $getRankingPageData = $getRankingPageDataRaw['groups'];
        $withoutSortValues = $getRankingPageDataRaw['withoutSortValues'];
        $order = $this->params['order'];
        
        $order == 'ASC'? ksort($getRankingPageData) : krsort($getRankingPageData);
        $sortedRankingPageData = array();
        
        foreach($getRankingPageData as $sortKey => $sortedRankingPageTuple){
            foreach($sortedRankingPageTuple as $key => $dataTuple){
                $sortedRankingPageData[$key] = $dataTuple;
            }
        }
        foreach($withoutSortValues as $sortKey => $sortedRankingPageTuple){
            foreach($sortedRankingPageTuple as $key => $dataTuple){
                $sortedRankingPageData[$key] = $dataTuple;
            }
        }
        return $sortedRankingPageData;
    }
    
    private function _groupRankingPageTuplesBySortValue($getRankingPageData)
    {
        $groups = array();
        $withoutFilterResult = array();
        foreach($getRankingPageData as $key => $rankingPageTuple) {
                    if($this->sortKey == 3) {
                        $sortValue = $rankingPageTuple['sortValues'][$this->sortKey][$this->params['exam']];
                    }
                    else {
                        $sortValue = $rankingPageTuple['sortValues'][$this->sortKey];
                    }
                    if(!empty($sortValue)) {
                        $groups[$sortValue][$key] = $rankingPageTuple;
                    }else{
                        $withoutFilterResult[$sortValue][$key] = $rankingPageTuple;
                    }
                }     
        return array('groups'=>$groups,'withoutSortValues'=>$withoutFilterResult);
    }
}