<?php
class CollegePredictorRedirectionRules{
	 private $collegeIdentifier = 'college-predictor';
	 private $cutOffIdentifier = 'cut-off-predictor';
	 private $branchIdentifier = 'branch-predictor';

	 function __construct() {
	 	$this->CI =& get_instance();
	 	$this->CI->load->config('CP/CollegePredictorConfig',TRUE);
	 }

	function redirectionRule($examName, $directoryName, $urlType, $instituteAndExamName = '', $collegeName = ''){
	 	$this->settings = $this->CI->config->item('settings','CollegePredictorConfig');
		$cpUrlMapping = $this->CI->config->item('cpUrlMapping','CollegePredictorConfig');
		$redirectURL = $this->createCollegePredictorUrl(strtolower($examName), $urlType, $instituteAndExamName);
		$queryParams = array();
        $queryParams = $_GET;
        if(!empty($queryParams) && count($queryParams) > 0) {
            $redirectURL .= '?'.http_build_query($queryParams);
        }

		//check to redirect old college predictor URLs to new URLs
		if(in_array($_SERVER['SCRIPT_URL'],array('/'.$examName.'-'.$this->collegeIdentifier,'/'.$examName.'-'.$this->cutOffIdentifier,'/'.$examName.'-'.$this->branchIdentifier))){
			redirect($redirectURL,'location',301);
			exit;
		}

		//check if current page url is not valid
		$currentPageUrl = getCurrentPageUrl();
		$redirectURL = urldecode($redirectURL);
		/*_P($currentPageUrl);
		_P($redirectURL);
		die;*/
		if($currentPageUrl != $redirectURL) {
			redirect($redirectURL,'location',301);
			exit;
		}
		
		if(!isset($cpUrlMapping[$directoryName])) {
			show_404();
		}
	}

	function createCollegePredictorUrl($examName, $urlType, $instituteAndExamName, $collegeName = '') {
		if(!empty($this->settings['CPEXAMS'][strtoupper($examName)]['directoryName'])) {
			$redirectDirectory = $this->settings['CPEXAMS'][strtoupper($examName)]['directoryName'];
		}
		else {
			$redirectDirectory = '/b-tech/resources';
		}
		
		switch ($urlType) {
			case 1:
				$url = '/'.$examName.'-'.$this->collegeIdentifier;
				break;
			case 2:
				$url = '/'.$examName.'-'.$this->cutOffIdentifier;
				break;
			case 'branch':
				$url = '/'.$examName.'-'.$this->branchIdentifier;
				break;
			case 'institute':
				$url = explode('/',$_SERVER['SCRIPT_URI']);
				$url = '/'.end($url);
				break;
		}
		return SHIKSHA_HOME.$redirectDirectory.$url;
	}
}

?>
