<?php 
class countryHomeSolrRequestGenerator
{
	function __construct(){
        $this->CI = & get_instance();
    }

	public function getCourseDataRequestUrl($courseIdArr,$resultNum)
    {
    	if(empty($courseIdArr)){
    		return false;
    	}
    	$courseIdArr = implode(' ', $courseIdArr);
    	$urlComponents = array();
        $urlComponents[] = 'q=*:*';
        $urlComponents[] = 'wt=phps';
        $urlComponents[] = 'fl=saCourseId,saCourseName,saCourseLevel1,saCourseEligibilityExams,saCourseParentCategoryId,saCourseDesiredCourseId,saCourseSeoUrl,saCourseTotalFees,saCourseRemainingFees,saGMATExamScore,saGREExamScore,saIELTSExamScore,saTOEFLExamScore,saPTEExamScore,saMELABExamScore,saCAEExamScore,saCAELExamScore,saSATExamScore';
        $urlComponents[] = 'fq=facetype:abroadlisting';
        $urlComponents[] = 'fq=saCourseId:('.$courseIdArr.')';
        $urlComponents[] = 'rows='.$resultNum;
        return SOLR_AUTOSUGGESTOR_URL.implode('&',$urlComponents);
    }
}