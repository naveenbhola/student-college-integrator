<!-- Wiki-Start : Summery -->
<?php
if($examPageData->getSyllabus()->getDescription() != '') { 
     $this->load->view('examPages/examPageWiki',array('wiki'=> $examPageData->getSyllabus(), "sectionId" => "wiki-sec-0"));  } 
else { 
    $wikiLabel = new tidy ();
    $wikiLabel->parseString ($examPageData->getSyllabus()->getLabel(), array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
    ?>
    <div class="dynamic-content content-tupple <?=$tuppleAdditionalClass ? $tuppleAdditionalClass : ""?>" <?=$sectionId ? "id='".$sectionId."'" : ""?>>
         	
        <h1><?php echo $wikiLabel?></h1>   
	<p>
            Nothing interesting here!
            <br/>
            Go to <a href="<?=$sectionUrl['home']['url']?>"> <?=$examName;?> homepage </a>
        </p>
     </div>
 <?php } ?>

<!-- Wiki-End : Summery -->

<?php $this->load->view("widgets/newsArticleSliderWidget");?>

<!-- Start : Registration  -->
<?php $tracking_keyid = DESKTOP_NL_EXAM_PAGE_SYLLABUS_BELLY_REG; ?>
<?php 
$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 
?>

<!-- End : Registration  -->
<!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId, $examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->