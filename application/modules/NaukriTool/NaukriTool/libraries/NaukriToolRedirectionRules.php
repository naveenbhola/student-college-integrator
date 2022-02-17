<?php
class NaukriToolRedirectionRules{

	
	private $oldDomainName;
	private $currentUrl;
	
	function __construct(){
	       $this->oldDomainName = SHIKSHA_MANAGEMENT_HOME;
	       $this->currentUrl = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	}

        function redirectionRule($functionType){
		if($functionType!=''){
                        $oldUrl = 'best-colleges-for-'.$functionType.'-jobs-based-on-mba-alumni-data';
                        $newUrl = SHIKSHA_HOME.'/mba/resources/best-mba-'.$functionType.'-colleges-based-on-mba-alumni-data';
                }else{
                        $oldUrl = 'best-colleges-for-jobs-based-on-mba-alumni-data';
                        $newUrl = SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
                }
		$urlString = strpos($this->currentUrl,$oldUrl);
		$subDomain = $this->validateSubDomain($this->oldDomainName);
		if($urlString === false && $subDomain == FALSE)
		{	
			return true;
		}else if($subDomain == TRUE || $urlString != -1){
		    $this->redirectUrl($newUrl,'301');
		}
        }

	function redirectUrl($url,$redirectRule){
		redirect($url,'location',$redirectRule);exit();
    	}
	
	function validateSubDomain($domainArr){
		return in_array('https://'.$_SERVER['HTTP_HOST'], $domainArr);
    }

    function prepareBeaconTrackData(){
        $beaconTrackData = array(
			'pageIdentifier' => 'careerCompasPage',
			'pageEntityId'   => '0', // No Page entity id for this one
			'extraData' => array(
				'hierarchy' => array(
					'streamId' => MANAGEMENT_STREAM,
					'substreamId' => 0,
					'specializationId' => 0
					),
				'educationType' => EDUCATION_TYPE,
				'baseCourseId' => MANAGEMENT_COURSE,
				'countryId' => 2
			)
		);
		return $beaconTrackData;
	}
}

?>

