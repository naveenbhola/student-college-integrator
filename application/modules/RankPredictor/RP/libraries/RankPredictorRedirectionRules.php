<?php
class RankPredictorRedirectionRules{
	
	function __construct(){
		$this->CI = & get_instance();
		$this->CI->load->config('RP/RankPredictorConfig',TRUE);
		$this->settingsRankPredictor = $this->CI->config->item('settings','RankPredictorConfig');
	}
	
	function redirectionRule($examName){
		$redirectURL = RANK_PREDICTOR_BASE_URL.$_SERVER['SCRIPT_URL'];
		if(in_array($_SERVER['SCRIPT_URL'],array('/'.$examName.'-rank-predictor'))){
			redirect($redirectURL,'location',301);
			exit;
		}
		if(!array_key_exists($examName,$this->settingsRankPredictor)){
                        $redirectURL = RANK_PREDICTOR_BASE_URL.'/jee-main-rank-predictor';
                        redirect($redirectURL,'location',301);
                        exit;
                }
	}
}

?>
