<?php
class CareerEnterpriseConfig{
	public static $ldbCourseVariables =	array('courseMap_1','courseMap_2','courseMap_3','courseMap_4','courseMap_5');
	public static $addDetailTabVariables =	array('careerId','shortIntro','difficultyLevel','minimumSalaryInLacs','maximumSalaryInLacs','minimumSalaryInThousand', 					     'maximumSalaryInThousand','mandatorySubject');
	public static $smallImageIntro =	array('smallImageIntro');
	public static $logoImageUrl = array('logoImageIndia1','logoImageIndia2','logoImageIndia3','logoImageIndia4','logoImageAbroad1','logoImageAbroad2','logoImageAbroad3','logoImageAbroad4','firstCompanyImage','secondCompanyImage','thirdCompanyImage','forthCompanyImage');
	public static $largeImageUrl = array('largeImageIntro');
	public static $mandatoryImagesInPageValueTable = array('thumbnailImageIntro','largeImageIntro');
	public static $mandatoryImagesInCareerTable = array('smallImageIntro');
	public static $message = 'Please upload image.';

	function __construct() {
		$this->CI =& get_instance();
	}

	function getFinalCareerArrayToInsert($hierArr, $careerId, $courseIds){
		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new ListingBaseBuilder();
		$hierarchyRepo = $listingBase->getHierarchyRepository();
		$hierArrFinal =  $hierarchyRepo->getHierarchiesByMultipleBaseEntities($hierArr);  //Get Hierarchy Ids
		foreach ($hierArrFinal as $key => $value) {
			$newKey = $value['stream_id'].'_'.$value['substream_id'].'_'.$value['specialization_id'];
			$indexedHierarchyArray[$newKey] = $hierArrFinal[$key];
		}
		foreach ($hierArr as $key => $value) {
			if($hierArr[$key]['substreamId'] == 'none' || $hierArr[$key]['substreamId'] == 'any'){
				$hierArr[$key]['substreamId'] = '';
			}
			if($hierArr[$key]['specializationId'] == 'none' || $hierArr[$key]['specializationId'] == 'any'){
				$hierArr[$key]['specializationId'] = '';
			}
			$newKey = $hierArr[$key]['streamId'].'_'.$hierArr[$key]['substreamId'].'_'.$hierArr[$key]['specializationId'];
			if(!empty($indexedHierarchyArray[$newKey])){
				if(empty($courseIds[$key])){
						$temp['hierarchyId'] = $indexedHierarchyArray[$newKey]['hierarchy_id'];
						$temp['careerId'] = $careerId;
						$temp['courseId'] = 0;
						$temp['status'] = 'live';
						$temp['creationDate'] = date("Y-m-d H:i:s");
						$temp['modificationDate'] = date("Y-m-d H:i:s");
						$finalArr[] = $temp;
			
				}else{
					foreach ($courseIds[$key] as $v) {
						$temp['hierarchyId'] = $indexedHierarchyArray[$newKey]['hierarchy_id'];
						$temp['careerId'] = $careerId;
						$temp['courseId'] = $v;
						$temp['status'] = 'live';
						$temp['creationDate'] = date("Y-m-d H:i:s");
						$temp['modificationDate'] = date("Y-m-d H:i:s");
						$finalArr[] = $temp;
			
					}
				}
			}
		}
		return $finalArr;
	}
}
?>
