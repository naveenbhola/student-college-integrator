<?php
	$headingImageClass 	= ($actionType == "add" ? "plus-icon":"minus-icon");
	$sectionDisplayStyle	= ($actionType == "add" ? "display:none;":"");
	if($status == 'live')
	{
		$helpText = "This exam already in <b style='color:green'>live</b> and please make changes and publish";
	}
	elseif($status == 'draft')
	{
		$helpText = "This exam is <b style='color:green'>not yet live</b> and please make changes and make live";
	}
	echo "<span style='top:-10px;position:relative;'>$helpText</span>";
	foreach ($sectionOrder as $secKey => $secValue) {
		switch ($secKey) {
			case 'homepage':
					$this->load->view('cms/examHomePage',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
				break;
			case 'importantdates':
					$this->load->view('cms/examImportantDates',array('headingImageClass' => $headingImageClass,'events'=>$events));
					break;
			case 'pattern':
					$this->load->view('cms/examPattern',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'syllabus':
					$this->load->view('cms/examSyllabus',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'results':
					$this->load->view('cms/examResults',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'admitcard':
					$this->load->view('cms/examAdmitCard',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'slotbooking':
					$this->load->view('cms/examSlotBooking',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'answerkey':
					$this->load->view('cms/examAnswerKey',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'cutoff':
					$this->load->view('cms/examCutOff',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'counselling':
					$this->load->view('cms/examCounselling',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'applicationform':
					$this->load->view('cms/examApplicationForm',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'samplepapers':
					$this->load->view('cms/examSamplePapers',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'preptips':
					$this->load->view('cms/examPrepTips',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'vacancies':
					$this->load->view('cms/examVacancies',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;					
			case 'callletter':
					$this->load->view('cms/examCallLetter',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;
			case 'news':
					$this->load->view('cms/examNews',array('headingImageClass' => $headingImageClass,'sectionDisplayStyle' => $sectionDisplayStyle));
					break;					
			default:
				# code...
				break;
		}
	}
?>

	<input name="examPageId" value ="<?= !empty($examPageId) ? $examPageId : 0?>" class="universal-txt-field cms-text-field" type="hidden"/> 
	<input name="createdBy" value ="<?= !empty($createdBy) ? $createdBy : 0?>" class="universal-txt-field cms-text-field" type="hidden"/> 
	<input name="creationDate" value ="<?= !empty($creationDate) ? $creationDate : 0?>" class="universal-txt-field cms-text-field" type="hidden"/>
	<input name="examOrder" value ="<?= !empty($examOrder) ? $examOrder : -1?>" class="universal-txt-field cms-text-field" type="hidden"/>
	<input name="isFeatured" value ="<?= !empty($isFeatured) ? $isFeatured : 0?>" class="universal-txt-field cms-text-field" type="hidden"/>
	<input name="exam_status" value="<?=!empty($status) ? $status : ''?>" class="universal-txt-field cms-text-field" type="hidden">
	<input name="view_count" value="<?=!empty($view_count) ? $view_count : 0?>" class="universal-txt-field cms-text-field" type="hidden">
	<input name="no_of_past_views" value="<?=!empty($no_Of_Past_Views) ? $no_Of_Past_Views : 0?>" class="universal-txt-field cms-text-field" type="hidden">


<script type="text/javascript">
	var pos = 0;
	var sectionOrder = [];
	<?php foreach($sectionOrder as $secKey => $secValue ) {?>
			sectionOrder[pos++] = {'name' : '<?=$secKey;?>','position' : <?=$secValue;?>};
	<?php } ?>
</script>
