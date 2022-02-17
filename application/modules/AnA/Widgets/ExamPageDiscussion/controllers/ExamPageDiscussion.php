<?php 


class ExamPageDiscussion extends MX_Controller {

	function init($library=array('message_board_client','category_list_client','register_client','alerts_client','ajax','listing_client','relatedClient'),$helper=array('url','image','shikshautility','string'))
	{
		if(is_array($helper)){
			$this->load->helper($helper);
		}
		if(is_array($library)){
			$this->load->library($library);
		}
		if(($this->userStatus == ""))
			$this->userStatus = $this->checkUserValidation();
	}
	
	function examPageDiscussionWidget($dataFromExamPage)
	{
		$this->init();
		$page_number = 0;
		$context = '';
		$displayData = array();
		if($this->input->is_ajax_request()){
			$page_number = filter_var($this->input->post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			$discussionIds = $this->input->post('discussionsIds');
			$displayData['item_per_page'] = $this->input->post('item_per_page');
			$displayData['total_discussions'] = count(explode(',',$discussionIds));
			//$context =  $this->input->post('context');
		}
		else
		{
			$discussionIds = implode(',', $dataFromExamPage['discussionsIds']);
			$displayData['total_discussions'] = count($dataFromExamPage['discussionsIds']);
			$displayData['item_per_page'] = $dataFromExamPage['item_per_page'];
			//$context = $dataFromExamPage['context'];
		}
		$this->load->model('messageBoard/qnamodel');
		$resultArr = array();
		$lastCommentArr = array();
		$countArr = array();
		if(isset($dataFromExamPage)  && ($dataFromExamPage!='')){
			$displayData['dataFromExamPage'] = $dataFromExamPage;
		}else{
			$displayData['dataFromExamPage']=$_POST;
		}
		
		if(!empty($discussionIds))
		{
			$resultArr = $this->qnamodel->getDiscussionsDetailsForExamPage($discussionIds, $page_number, $displayData['item_per_page']);
			$countArr = $this->qnamodel->getNoOfCommentsForDiscussions($discussionIds);
			$msgIdArr = array();
			foreach($resultArr as $discussion)
			{
				$msgIdArr[] = $discussion['msgId'];
			}
			$msgIds = implode(',', $msgIdArr);
			if(!empty($msgIdArr))
			{
				$lastCommentArr = $this->qnamodel->getLastCommentDetails($msgIds);
			}
		}
		$displayData['discussionArr'] = $resultArr;
		$displayData['countArr'] = $countArr;
		$displayData['lastCommentArr'] = $lastCommentArr;
		//_p($displayData);die;
		//if($context=='mobile')
		//{
		//	//$this->load->view('examPageDiscussionWidgetForMobile',$displayData);//mobile view
		//	$this->load->view('examPageDiscussionWidget',$displayData);//desktop view
		//}
		//else
		//{
			$this->load->view('examPageDiscussionWidget',$displayData);
		//}
	}
	function validateDiscussionIds($discussionIdArr)
	{
		$validatedIds = array();
		$this->load->model('messageBoard/qnamodel');
		$discussionIds = implode(',', $discussionIdArr);
		if($discussionIds != '')
		{
			$validatedIds = $this->qnamodel->validateDiscussionIds($discussionIds);
		}
		return $validatedIds;
	}
}
?>
