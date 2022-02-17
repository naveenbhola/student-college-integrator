<?php
class ExamPageTest extends ShikshaMobileWebSite_Controller {

    function dummyPage() {
		$displayData['pageName'] = "mobileExamPage";		
		$displayData['noJqueryMobile'] = true;
		$this->load->view("mobile_examPages5/newExam/examHomePage",$displayData);
    }
}
?>
