<?php

class ViewCountSorter extends AbstractSorter
{
    protected $sortKey = CP_SORTER_VIEWCOUNT;
    private $instituteRepository;
    
    function __construct($params = array(),InstituteRepository $instituteRepository)
    {
        parent::__construct($params);
        $this->instituteRepository = $instituteRepository;
    }
    
    public function extractSortValue(Institute $institute,Course $course)
    {
        return $institute->getViewCount();
    }
    
    public function sortWithFreshData($institutes)
    {
        /*
         * Fetch fresh view count for each institute
         */
        if(count($institutes) > 0) {
            $instituteIds = array_keys($institutes);
            $viewCounts = $this->instituteRepository->getViewCount($instituteIds);
            
            foreach($institutes as $instituteId => $courses) {
                foreach($courses as $courseId => $course) {
                    $institutes[$instituteId][$courseId]['sortValues'][$this->sortKey] = $viewCounts[$instituteId]['view_count']['viewCount'];
                }
            }
            
            return parent::sort($institutes);
        }
    }
}