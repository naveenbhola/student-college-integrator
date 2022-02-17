<?php

class scholarshipDetailPageSolrRequestGenerator
{
    private $CI;
      
    function __construct(){
        $this->CI = & get_instance();
    }
    
    public function getSimilarScholarshipsRequestUrl($filterData, $scholarshipCount = 8)
    {
        $this->request = $request;
        $urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'fl=saScholarshipId,saScholarshipCategory,saScholarshipUnivName,saScholarshipCountryId,saScholarshipCategoryId,saScholarshipCourseLevel';
        $urlComponents[] = 'fq=facetype:abroadScholarship';
        $urlComponents[] = 'fq=-(saScholarshipId:('.$filterData['currentScholarshipId'].'))';
        $urlCategoryComponents = $urlCourseLevelComponents = $urlCountryComponents = array();
        foreach ($filterData['courseLevels'] as $value) {
            $urlCourseLevelComponents[] = 'saScholarshipCourseLevel:"'.$value.'"';
        }
        if(!empty($urlCourseLevelComponents)){
            $courseLevel = '&fq=(('.implode(' AND ', $urlCourseLevelComponents).') OR saScholarshipCourseLevel:"all")';
        }else{
            $courseLevel = '&fq=saScholarshipCourseLevel:"all"';
        }

        foreach ($filterData['categories'] as $value) {
            $urlCategoryComponents[] = 'saScholarshipCategoryId:'.$value;
        }
        if(!empty($urlCategoryComponents)){
            $category = '&fq=(('.implode(' AND ', $urlCategoryComponents).') OR saScholarshipCategoryId:"all")';
        }else{
            $category = '&fq=saScholarshipCategoryId:"all"';
        }
        
        foreach ($filterData['applicableCountries'] as $value) {
            $urlCountryComponents[] = 'saScholarshipCountryId:'.$value;
        }
        if(!empty($urlCountryComponents)){
            $country = '&fq=(('.implode(' AND ', $urlCountryComponents).') OR saScholarshipCountryId:1)';
        }

        $urlComponents[] = 'start=0';
        $urlComponents[] = 'rows='.$scholarshipCount;
        $urlComponents[] = "sort=saScholarshipPopularityIndex desc";       
        return SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents).$country.$courseLevel.$category;
    }
}
