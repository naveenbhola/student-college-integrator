<?php

class TopInstitutesSorter extends AbstractSorter
{
    private $instituteRepository;
    
    function __construct($params = array(),InstituteRepository $instituteRepository)
    {
        parent::__construct($params);
        $this->instituteRepository = $instituteRepository;
    }
    
    public function sort($institutes)
    {
        $sortedInstitutes = array();
        
        /*
         * Fetch all top institutes
         */
        $categoryId = (int) $this->params['category'];
        $topInstitutes = $this->instituteRepository->getTopInstitutesInCategory($categoryId);
        
        if(is_array($topInstitutes)) {
            /*
             * Top institutes come on top
             */ 
            foreach($topInstitutes as $instituteId) {
                if(isset($institutes[$instituteId])) {
                    $sortedInstitutes[$instituteId] = $institutes[$instituteId];
                }
            }
        
            /*
             * followed by all others
             */ 
            $sortedInstitutes += array_diff_key($institutes,array_flip($topInstitutes));
        }
        else {
            $sortedInstitutes = $institutes;
        }
    
        return $sortedInstitutes;
    }
    
    public function extractSortValue(Institute $institute,Course $course)
    {
        return NULL;
    }
}