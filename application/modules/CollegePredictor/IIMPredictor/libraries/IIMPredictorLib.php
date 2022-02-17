<?php
class IIMPredictorLib {
	public function pageViewTracking($pageIdentifier){
		return array(
			'pageIdentifier' => $pageIdentifier,
			'extraData' => array(
				'hierarchy' => array(
						'streamId' => MANAGEMENT_STREAM,
				        'substreamId' => 0,
	        			'specializationId' => 0,
					),
				'baseCourseId' => MANAGEMENT_COURSE,
				'educationType' => EDUCATION_TYPE,
				'level' => 15
			)
		);	
	}

	function getGTMArray($pageType, $iims_results_data){
        
        $gtmArray = array(
            "pageType" => $pageType,
            "exam"=> 'CAT',
            "toolName"=>'IIMCallPredictor',
            "ugDiscipline"=>$iims_results_data['graduationStream'],
            "catTotalMarks"=>$iims_results_data['userData']['cat_total'],
            "catTotalPercentile"=>$iims_results_data['userData']['cat_percentile']
        );
        if($userStatus!='false' && $userStatus[0]['experience']!==""){
            $gtmArray['workExperience'] = $userStatus[0]['experience'];
        }
        return $gtmArray;

    }
}