<?php 

$examSectionIconMapping  = array('Exam Analysis' 		=> 'result-analysis-icn',
			     					'Exam Reaction' 	=> 'stu-reaction-icn',
			   			   		 );
	$resultData  = $examPageData->getResultsData();
	$resultDelarationDateArray = $resultData['Declaration Date'];
	$topperInterview = $resultData['Topper interview'];
	
	unset($resultData['Declaration Date']);
    unset($resultData['Topper interview']);
    
        
$wikiCount 	= 0;
$isResultPageEmpty = TRUE;
$resultDate =$result_decalation_date['resultDate'];

// set showlink menu value for result section
foreach($activeTileDetails as $activeTileRow){
	if($activeTileRow['name'] == 'results'){
		$showLinkMenu = $activeTileRow['show_link_in_menu'];
	}
}


if(isset($showLinkMenu) == 0) {?>
<div class="content-tupple">
	<p>
	Nothing interesting here!
	<br/>
	Go to <a href="<?=$sectionUrl['home']['url']?>"> <?=$examName;?> homepage </a>
	</p>
</div>	
<?php $isResultPageEmpty = FALSE; }

if(isset($showLinkMenu) == 1){

  if(isset($resultDate)){	?>
 <!-- Start : Declaration Date  -->
<div class="content-tupple home-tupple" id="wiki-sec-<?=$wikiCount?>">
    	<div class="result-declare-list">
        	<i class="exam-result-sprite result-declare-icn"></i>
            <div class="result-info-title">
            	<strong><?=$resultDelarationDateArray->getEventName()?></strong>  
            </div>
            <?php 
				$startMonthName = $resultDate['startMonthName'];
				$endMonthName   = $resultDate['endMonthName'];
				$startDateNum   = $resultDate['startDateNum'];
				$endDateNum     = $resultDate['endDateNum'];

            	if(strcmp($startMonthName,$endMonthName) == 0 && $startDateNum == $endDateNum){
					// same month same date
					$dateHtml = "<p><span class='font-14'>$startDateNum</span> $startMonthName</p>";
				}elseif(strcmp($startMonthName,$endMonthName) == 0 && $startDateNum != $endDateNum){
					// same month different date
					$dateHtml = "<p><span class='font-14'>$startDateNum - $endDateNum</span> $startMonthName</p>";
				}else{
					// different month different date
					$dateHtml = "<p><span class='font-14'>$startDateNum </span>$startMonthName<span class='font-14'> - $endDateNum</span> $endMonthName</p>";
				}
				echo "<div class='exam-date'>$dateHtml</div>";
            ?>
            
            <?php if(isset($result_decalation_date['articleInfo'])){ ?>          
            <div class="ExamResult-info">
	            <p class="exam-result-title"><a href="<?=$result_decalation_date['articleInfo']['url']?>"><?=$result_decalation_date['articleInfo']['blogTitle']?></a></p>
            </div>
            <?php } ?>            
        </div>     
 </div>
  <?php
		$wikiCount++;
		$isResultPageEmpty = FALSE;
   }?>      
  <!-- End : Declaration Date  -->

 
 <!-- Start : Exam Analysis and Exam Reaction  -->
 <?php 
 	foreach($resultData as $key=>$wikiObj) { 

 	// to check if wiki description is empty or not
	$wikiDescription = trim(strip_tags(html_entity_decode(str_replace("&nbsp;"," ",$wikiObj->getDescription()))));
	// remove the wiki that need not to be shown as wiki sections and do not show the wiki if it's description is empty
	if(!empty($wikiDescription))
	{
		$this->load->view('examPages/examPageResultWiki', array("wiki" => $wikiObj,"tuppleAdditionalClass" => "home-tupple", "sectionId" => "wiki-sec-".$wikiCount,'key'=>$key,'examSectionIconMapping'=>$examSectionIconMapping));
		$wikiCount++;
		$isResultPageEmpty = FALSE;
	}
 } 
 ?>
 <!-- End : Exam Analysis and Exam Reaction  -->

 <!-- Start : Topper Interview  -->
 <?php 
 $interviewLabel = $topperInterview[0]->getLabel();
 $topperInterviewCount = count($topperInterview);
 if(!empty($interviewLabel)){ ?>
 <div class="content-tupple home-tupple dynamic-content" id="wiki-sec-<?=$wikiCount?>">
    	<div class="result-declare-list">
        	<i class="exam-result-sprite toppers-intrvew-icn"></i>
            <div class="result-info-title">
            	<strong>Topper Interviews</strong>
            </div>
            <?php foreach($topperInterview as $key=>$wikiObj) {  

            	if($key == ($topperInterviewCount-1)){
					$seperatorClass = '';
            	}else{
            		$seperatorClass = 'topper-seperator';
            	}
            	
            	$this->load->view('examPages/examPageResultWiki', array("wiki" => $wikiObj,'sectionPart'=>'interview','seperatorClass'=>$seperatorClass));
            	$isResultPageEmpty = FALSE;
               } ?>
        </div>       
 </div> 
 <?php } ?> 

<?php } ?>
 <!-- End : Topper Interview  -->

<?php if($isResultPageEmpty) {?>
<div class="content-tupple">
	<p>
	Nothing interesting here!
	<br/>
	Go to <a href="<?=$sectionUrl['home']['url']?>"> <?=$examName;?> homepage </a>
	</p>
</div>	
<?php } ?>
                
<!-- Start : Registration  -->
<?php $tracking_keyid = DESKTOP_NL_EXAM_PAGE_RESULTS_REG; ?>
<?php 
$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 

?>    
<!-- End : Registration  -->

<!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->
