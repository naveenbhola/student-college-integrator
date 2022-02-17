<?php 
class ExamPage_Server extends MX_Controller {
	function index(){

		/*$this->dbLibObj = DbLibCommon::getInstance('default');
		$this->db = $this->_loadDatabaseHandle();*/

		$this->load->model('exampagemodel');

		$config['functions']['updateViewCount'] = array('function' => 'ExamPage_Server.updateViewCount');
		$args = func_get_args(); 
		$method = $this->getMethod($config,$args);

		error_log('SQL Injection - Code Usability Check :: Class Name : ExamPage_Server :: Func Name : '.$method);
		return $this->$method($args[1]);
	}

	function updateViewCount($request)
	{
		error_log('Product Exam');
		//$this->db = $this->_loadDatabaseHandle('write');
		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$groupId = $parameters['1'];
		$pageType = $parameters['2'];
/*
		$this->load->model('Viewcountmodel');
		$this->Viewcountmodel->updateViewCounts($request,"exampage");*/


		$result = $this->exampagemodel->updateViewCount($groupId);
		//alter table view_Count_Details modify column `listingType` enum('course_free','institute_free','scholarship','notification','tutor','student','consultant','blogs','qna','course_paid','institute_paid','abroadcourse','abroaduniversity','abraoddepartment','abroadsnapshotcourse','exam_group') NOT NULL;
		if(!$result){
			$response = array(array('error'=>'UpdateViewCount for exampage_master Query Failed','struct'));
			return $this->xmlrpc->send_response($response);
		}
		$response = array('added','struct');
		return $this->xmlrpc->send_response($response);
	}

}
?>