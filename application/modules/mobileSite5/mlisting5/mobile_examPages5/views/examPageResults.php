<?php 
if($show_link_in_menu==0){ ?>
    <div class="exam-accrodian clearfix" id="examDetails">
        <p>Nothing interesting here!</p>
        <p>Go to <a href="<?php echo $home['url'];?>"><?php echo $examName;?> homepage</a>.</p>
    </div>
<?php }else{
$examSectionIconMapping  = array('Result Analysis' 		=> 'result-analysis-icn',
			     					'Student Reaction' 	=> 'stu-reaction-icn',
                                                                'Topper interview' =>'toppers-intrvew-icn'
			   			   		 );
	$resultData  = $examPageData->getResultsData();
	$resultDelarationDateArray = $resultData['Declaration Date'];
	$topperInterview = $resultData['Topper interview'];
	
	unset($resultData['Declaration Date']);
        unset($resultData['Topper interview']);
    
        
$wikiCount 	= 0;

$resultDate =$result_decalation_date['resultDate'];
if(isset($resultDate)){		
                $startMonthName = $resultDate['startMonthName'];
                $endMonthName   = $resultDate['endMonthName'];
                $startDateNum   = $resultDate['startDateNum'];
                $endDateNum     = $resultDate['endDateNum'];
$dateHtml = '';
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
}	
?>
<div class="exam-accrodian clearfix" id="examDetails">
    
    <dl>
        <dt>
            <span class="flLt"></span>
        <?php if($dateHtml!=''){?>
        <div class="exam-date">
            <?php echo $dateHtml;?>
        </div>
        <?php } ?>  
        <div class="result-tabs">
                    <i class="exam-result-sprite result-declare-icn"></i>
                <div class="result-info-title">
                    <h1><?=$resultDelarationDateArray->getEventName();?></h1>
                </div>
            </div>
        </dt>
	<?php if(isset($result_decalation_date['articleInfo'])){ ?>
        <dd><p class="exam-result-title"><a href="<?=$result_decalation_date['articleInfo']['url']?>"><?=$result_decalation_date['articleInfo']['blogTitle']?></a></p>
	<?php } ?>
	            <!--<p>To view your results, click on the link above. </p>--></dd>
    </dl>
    
 <!-- Start : Exam Analysis and Exam Reaction  -->
 <?php
    foreach($resultData as $key=>$wikiObj) { 

    // to check if wiki description is empty or not
    $wikiDescription = trim(strip_tags(html_entity_decode(str_replace("&nbsp;"," ",$wikiObj->getDescription()))));
    // remove the wiki that need not to be shown as wiki sections and do not show the wiki if it's description is empty
    if(!empty($wikiDescription))
    {
        $wikiLabelText = $wikiObj->getLabel();
        $wikiDesc = new tidy ();
        $wikiDesc->parseString ($wikiObj->getDescription(), array ('show-body-only' => true ), 'utf8' );
        $wikiDesc->cleanRepair();
        
        $wikiLabel = new tidy ();
        $wikiLabel->parseString ($wikiLabelText, array ('show-body-only' => true ), 'utf8' );
        $wikiLabel->cleanRepair();
    ?>
    <dl class="examDetails">
        <dt>
            <span class="flLt"></span><i class="exam-mini-sprite flRt" id="desc<?php echo $wikiCount;?>"></i>
        <div class="result-tabs" style="border:0">
                    <i class="flLt exam-result-sprite <?php echo $examSectionIconMapping[$wikiLabelText];?>"></i>
                <div class="result-info-title">
                    <?php
                        if($wikiLabel=="Student Reaction"){
                                $wikiLabel="Students' Reaction";
                        }
                    ?>
                    <strong><?php echo $wikiLabel;?></strong>
                    <!--<span>Jan 14, 2014, 9.18pm</span>-->
                </div>
            </div>
        </dt>
        <dd><div class="mceContentBody"><?=html_entity_decode($wikiDesc);?></div></dd>
    </dl>
    <?php
    $wikiCount++;
    }
 } 
 ?>
 <!-- End : Exam Analysis and Exam Reaction  -->

 <!-- Start : Topper Interview  -->
 <?php 
 $interviewLabel = $topperInterview[0]->getLabel();
 if(!empty($interviewLabel)){ ?>
    <dl class="examDetails">
        <dt>
            <span class="flLt"></span><i class="exam-mini-sprite exam-plus-icon flRt" id="desc<?php echo $wikiCount;?>"></i>
        <div class="result-tabs" style="border:0">
                    <i class="flLt exam-result-sprite <?php echo $examSectionIconMapping[$interviewLabel];?>"></i>
                <div class="result-info-title">
                    <strong>Topper Interviews</strong>
                    <!--<span>Jan 14, 2014, 9.18pm</span>-->
                </div>
            </div>
        </dt>
        <dd><div class="mceContentBody">
        <?php foreach($topperInterview as $key=>$wikiObj) {
                $wikiLabelText = $wikiObj->getLabel();
                $wikiDesc = new tidy ();
                $wikiDesc->parseString ($wikiObj->getDescription(), array ('show-body-only' => true ), 'utf8' );
                $wikiDesc->cleanRepair();
                
                $wikiLabel = new tidy ();
                $wikiLabel->parseString ($wikiLabelText, array ('show-body-only' => true ), 'utf8' );
                $wikiLabel->cleanRepair();
                echo html_entity_decode($wikiDesc);
            } ?>
        </div></dd>
        </dl>
 <?php } ?>
 <!-- End : Topper Interview  -->
</div>
<?php } ?>



