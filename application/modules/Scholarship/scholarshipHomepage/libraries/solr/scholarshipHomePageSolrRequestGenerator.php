<?php

class scholarshipHomePageSolrRequestGenerator
{
    private $CI;
      
    function __construct(){
        $this->CI = & get_instance();
    }
    
    public function getPopularScholarshipsRequestUrl($scholarshipCount,$additionalFilters =array())
    {
        $this->request = $request;
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'fl=saScholarshipPopularityIndex,saScholarshipId,saScholarshipName,saScholarshipType,saScholarshipAmount,saScholarshipAwardsCount,saScholarshipCategory,saScholarshipUnivName,saScholarshipCountryId,saScholarshipCategoryId';
        $urlComponents[] = 'fq=facetype:abroadScholarship';
        foreach($additionalFilters as $key => $additionalFilter)
        {
            switch($key)
            {
                case 'saScholarshipCountryId':
                    $urlComponents[] = 'fq='.$key.': ('.implode(' ',$additionalFilter).')';
                    break;
                default : break;
            }
        }
        $urlComponents[] = 'start=0';
        $urlComponents[] = 'rows='.$scholarshipCount;
        $urlComponents[] = "sort=saScholarshipPopularityIndex desc";       
        return SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
    }
}
