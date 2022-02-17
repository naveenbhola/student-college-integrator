<?php

class RankSorter extends AbstractSorter
{
    protected $sortKey = ABROAD_CP_SORTER_RANK;
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function extractSortValue($defaultSortValue)
    {
        return $defaultSortValue+1;
    }
}