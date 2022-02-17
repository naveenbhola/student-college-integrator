<!--  Middle Section Start  -->
<div class="exam-mid-col">
 <?php
	switch ($pageType) {
		case "home" :
			$this->load->view ( 'examPages/examPageHomeContent' );
			break;
	 	case "imp_dates" :
			$this->load->view ( 'examPages/examPageImportantDateContent' );
			break;
		case "syllabus" :
			$this->load->view ( 'examPages/examPageSyllabusContent' );
			break;
		case "article" :
			$this->load->view ( 'examPages/examPageNewsArticle' );
			break;
	    case "preptips" :
			$this->load->view ( 'examPages/examPagePrepTips' );
			break;
		case "results" :
			$this->load->view ( 'examPages/examPageResultsContent' );
			break;
		case "discussion" :
			$this->load->view ( 'examPages/examPageDiscussionContent' );
			break;
		default :
			$this->load->view ( 'examPages/examPageHomeContent' );
	}
	?> 
</div>
<!--  Middle Section Ends  -->
