<!-- Wiki-Start : Summery -->
<?php
	$homePageData 	= $examPageData->getHompageData();
	$wikiCount 	= 0;
	foreach($homePageData as $homePageWiki)
	{
		// to check if wiki description is empty or not
		$wikiDescription = trim(strip_tags(html_entity_decode(str_replace("&nbsp;"," ",$homePageWiki->getDescription()))));
		
		// remove the wiki that need not to be shown as wiki sections and do not show the wiki if it's description is empty
		if(!in_array($homePageWiki->getLabel(), array('Exam Title', 'Official Website')) && !empty($wikiDescription))
		{
			$this->load->view('examPages/examPageWiki', array("wiki" => $homePageWiki, "sectionId" => "wiki-sec-".$wikiCount, "tuppleAdditionalClass" => "home-tupple"));
			$wikiCount++;
                        if($wikiCount == 1) {
                        	$tracking_keyid = DESKTOP_NL_EXAM_PAGE_HOME_TOP_REG;
                        	$this->load->view("widgets/newsArticleSliderWidget");
                        	$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid' =>$tracking_keyid));
                        }
		}
	}
?>
<!-- Wiki-End : Summary -->

<!-- Start : Registration  -->
<?php
	if($wikiCount != 1) {
			$tracking_keyid = DESKTOP_NL_EXAM_PAGE_HOME_BELLY_REG;
        	$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid' =>$tracking_keyid));
        }
?>
<!-- End : Registration  -->

<!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->