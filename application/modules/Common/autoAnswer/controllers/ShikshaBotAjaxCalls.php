<?php

class ShikshaBotAjaxCalls extends MX_Controller {


	public function __construct(){
		/*$this->load->config('shikshaBotMain');
		$this->responseFinderLib = $this->load->library('ShikshaBotResponseFinder');
		$this->htmlGeneratorLib = $this->load->library('HtmlGenerator');
		$this->load->library("autoAnswer/Request");
		$this->load->library("autoAnswer/DataPopulators");
		
		$this->request = new Request();

		$this->dataPopulator = new DataPopulators();
		$this->load->library('session');*/
		$this->load->model('autoAnswer/shikshabotmodel');
		$this->htmlGeneratorLib = $this->load->library('HtmlGenerator');
	}

	public function index(){

	}
	public function autoSuggestor($type='instituteName',$resultFormat='html'){
		

		$result = "";
		switch ($type) {
			case 'instituteName':
				$instituteName = array();
				$text = $this->input->post('text');
				$instituteData = $this->shikshabotmodel->fetchInstituteData($text);
				$optionsArray = array();
				foreach ($instituteData as $key => $value) {
					$optionsArray[$value['institute_id']."__".$value['institute_name']] = $value['institute_name'];
				}
				if($resultFormat == 'html')
					$result = $this->htmlGeneratorLib->generateHTML($optionsArray);
				break;
			
			default:
				# code...
				break;
		}
		echo $result;

	}


}
?>
