<?php 

class OAFPortingFactory{
	public static function getPortingRepository() {
		$CI = &get_instance();
		$model = $CI->load->model('oafPorting/oafportingmodel');

		$CI->load->repository('OAFPortingRepository','oafPorting');
		$portingRepository = new OAFPortingRepository($model);
		return $portingRepository;
	}

	public static function getPorterObj(){
	    $CI = &get_instance();
		$CI->load->library('oafPorting/OAFPorter');
		return new OAFPorter();
	}
}
?>
