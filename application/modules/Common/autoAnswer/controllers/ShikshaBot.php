<?php
session_start();
class ShikshaBot extends MX_Controller {


	public function __construct(){
		$this->load->config('shikshaBotMain');
		$this->responseFinderLib = $this->load->library('ShikshaBotResponseFinder');
		$this->htmlGeneratorLib = $this->load->library('HtmlGenerator');
		$this->load->library("autoAnswer/Request");
		$this->load->library("autoAnswer/DataPopulators");
		
		$this->request = new Request();

		$this->dataPopulator = new DataPopulators();
		$this->load->library('session');
	}

	public function init(){
		$this->dataPopulator->populateRequestObjectFromSession($this->request);
		if(isset($_POST['requestType'])) {
			$this->request->setRequestType($_POST['requestType']);
		}
	}

	public function startChat(){

		$displayData['initialMessage'] = "Hello";
		$this->load->view('autoAnswer/chatMain',$displayData);
	}

	public function findResponse(){

		$this->init();
		$text = $this->input->post('text');
		$whichCase = $this->input->post('whichRes');
		$this->request->setText($text);
		$this->request->setWhichCase($whichCase);
		$response = $this->responseFinderLib->findResponse($this->request,$_POST);
		echo $response;
	}

	public function checkData(){
		_p("========== SESSION =======================");
		_p($_SESSION);
		_p("========== Request =======================");
		$req = new Request();
		$this->dataPopulator->populateRequestObjectFromSession($req);
		_p($req);
	}
}
?>
