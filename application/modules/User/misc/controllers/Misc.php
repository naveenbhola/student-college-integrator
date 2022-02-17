<?php 
class Misc extends MX_Controller {
	function init() {
		$this->load->helper(array('url', 'image','shikshautility'));
		$this->load->library('misc/MiscClient');
	}

	function contactUsers() {
		$this->init();
        //print_r($_POST);
        $Validate     = $this->checkUserValidation();
        $senderId     = (is_array($Validate) && isset($Validate[0]['userid']))?$Validate[0]['userid']:0;
        $recipientIds = $this->input->post('userIdCSV', true);
        $content      = $this->input->post('content', true);
        $mode         = $this->input->post('mode', true);
        $subject      = $this->input->post('subject', true);
        $product      = $this->input->post('product', true);
        $senderDetail = $this->input->post('senderDetail', true);
        if($senderId == 0 || $product == '' || $recipientIds == '' || $mode == '') {
            die('Not allowed to contact users.');
        }
        $miscClientObj = new MiscClient();
        $response      = $miscClientObj->trackCommunications(1,$recipientIds, $senderId, $senderDetail, $subject, $content, $product, $mode);
        echo json_encode(array('msg' => $response, 'today' => date('jS M Y')));
	}
}
?>
