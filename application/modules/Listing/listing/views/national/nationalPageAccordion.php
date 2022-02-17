
<?php 
if(!($pageRequestType == 'institute'))
{
$isAdmissionProcedureOnTop = $isEligibilityVisitOnTop = true;
//($contentTitle == "Eligibility") && $isEligibilityVisitOnTop) || (($contentTitle == "Admission Procedure") && $isAdmissionProcedureOnTop))
$wikisExists =true;
if($isAdmissionProcedureOnTop && $isEligibilityVisitOnTop && count($wikisData) ==2 && !empty($wikisData['Eligibility']) && !empty($wikisData['Admission Procedure']))
{
	$wikisExists =false;
}elseif($isAdmissionProcedureOnTop && count($wikisData) ==1 && !empty($wikisData['Admission Procedure']))
{
	$wikisExists =false;
}elseif($isEligibilityVisitOnTop && count($wikisData) ==1 && !empty($wikisData['Eligibility']))
{
	$wikisExists =false;
}elseif(empty($wikisData))
{
	$wikisExists = false;
}


?>

<?php if($wikisExists || count($displayDates) > 1){?>
<div class="other-details-wrap course-details-section clear-width">	
<h2>Course Details</h2>
	<div id="accordion">
		<ul >
		<?php  //if(count($displayDates) > 1) {
			if(0){?>
			<li>
				<h3 id="Imp"  uniqueattr="NATIONAL_COURSE_PAGE/Wiki_Important_Date">
					<span class="flLt">Important Dates</span> <i
						class="sprite-bg dwn-arrow"></i>
				</h3>
				<div class="detail">
					<div class="accordian-details">

						<ol class="imp-dates clear-width">
						<?php $LastDateElement = end($displayDates);
						      foreach ($displayDates as $displayDateType => $displayDate)  {?>
							<li style="width: 125px">
								<div class="calender-box">
									<i class="sprite-bg cal-points"></i>
									<div class="calen-child-box">
										<p class="cal-day"><?php echo $displayDate['month']."'". $displayDate['year'];?></p>
										<p><?php echo $displayDate['dayOfWeek'].", ".$displayDate['dayOfMonth'];?></p>
									</div>
								</div> <strong><?php echo $displayDate['dateTitle']; ?></strong>
							</li>
							<?php if($LastDateElement != $displayDate) {?>
							<li class="steps-arrow"><i class="sprite-bg content-arrow"></i></li>
							<?php } }?>
						</ol>

						<div class="clearFix"></div>
					</div>
				</div>
			</li>
<?php } 

	
	$courseDescriptions = array ();
	$customeWikis = array ();
	foreach ( $courseComplete->getDescriptionAttributes () as $attribute ) {
		
		$contentTitle = $attribute->getName ();
		if ($contentTitle == "Course Description") {
			$courseDescription = array (
					$attribute 
			);
		} elseif($attribute->getId() == -1) {
          $customeWikis [] = $attribute;
		}
		else if((($contentTitle == "Eligibility") && $isEligibilityVisitOnTop) || (($contentTitle == "Admission Procedure") && $isAdmissionProcedureOnTop)){
			continue; //not show wikis if present on top
		}
        else{
			$courseDescriptions [] = $attribute;
		}
	}
	if ($courseDescription) {
		$courseDescriptions = array_merge ( $courseDescription, $courseDescriptions );
	}
	if($customeWikis) {
	$courseDescriptions = array_merge ($courseDescriptions,$customeWikis);
	}
	foreach ( $courseDescriptions as $attribute ) {
		
		$contentTitle = $attribute->getName ();
		if (strlen ( $contentTitle ) > 103) {
			$contentTitle = preg_replace ( '/\s+?(\S+)?$/', '', substr ( $contentTitle, 0, 100 ) ) . "...";
		}
		?>

<li>
				<h3  uniqueattr="NATIONAL_COURSE_PAGE/Wiki_<?=$contentTitle?>">
					<span class="flLt" title="<?=$attribute->getName()?>" ><?=$contentTitle?></span>
					<i class="sprite-bg up-arrow"></i>
				</h3>
				
				<div class="detail">	
				<div class = "scrollbar1 scrollbar2 accordion_scrollbar"> 
				<div class="scrollbar"><div class="track" ><div class="thumb"><div class="end"></div></div></div></div> 
				<div class="course-accordian viewport_h viewport" style = "overflow: hidden">
				<?php 
				$itemprop = '';
				if($contentTitle == 'Course Description') {
					$itemprop = 'itemprop="description"';
				}
				?>
				<div class="overview overview_h" <?=$itemprop;?>>
<?php
				$allowedTags = '<td><dt><dd><ul><li><br><table><colgroup><tbody><tr><td><img><ol><strong><em><span><a><textarea><iframe><cite><code><blockquote><h><b><i><small><sup><sub><ins><del><mark><kbd><samp><var><pre><abbr><address><bdo><blockquote><q><dfn>
		';
				$summary = new tidy ();
				$summary->parseString (strip_tags($attribute->getValue(),$allowedTags), array ('show-body-only' => true ), 'utf8' );
				$summary->cleanRepair ();
				?>
				<?=$summary?>
				</div>
				</div>
		<div class="scrollbar_h">
			<div class="track_h">
				<div class="thumb_h">
					<div class="end_h"></div>
				</div>
			</div>
		</div>

				</div>
				</div>
			</li>
<?php
	}
	?>
</ul>
</div>
</div>
<?php
}
?>
<?php } else {
	if(empty($collegeOrInstituteRNR)){
		$collegeOrInstituteRNR = "Institute";
	}
	$contentTitleMapping = array("Institute Description" => "$collegeOrInstituteRNR Description",
					  		 "Partner Institutes"	 => "Partner {$collegeOrInstituteRNR}s",
					  		 "Institute Awards"	 	 => "$collegeOrInstituteRNR Awards",
					  		 "Institute History"	 => "$collegeOrInstituteRNR History");
	   		$isMBA = in_array(23,$institute->instituteCourses);
		if(!$isMBA)
	        {
		$relatedQuestionsData  = Modules::run('Listing/ListingPage/getInstituteRelatedQuestions', $institute->getId() , $js_enabled);
		$tempData = strip_tags(trim($relatedQuestionsData));
		$tempData = " ".$tempData;
		$data_pos = strpos($tempData,"No Related Questions found!");
		$isrelatedQuestionsDataExist =  empty($relatedQuestionsData) || ($data_pos >= 1) ? false : true;
		}else
       		 {
			$isrelatedQuestionsDataExist = false;
        	 }
	     if($instituteComplete->getDescriptionAttributes () || $isrelatedQuestionsDataExist)
	     {
				$wikisInOrder =array();
                $customWikiType = array();
                $customWiki = array();
                $wikisType = array("placement services","top recruiting companies","rankings & awards","hostel details","infrastructure / teaching facilities","top faculty","institute description","partner institutes");
                foreach($wikisType as $wikiElement ){
                        foreach ( $instituteComplete->getDescriptionAttributes () as $attribute ) {
                               	 $contentTitle = $attribute->getName ();
	                             if(strcasecmp($contentTitle,$wikiElement) == 0){
                                     $wikisInOrder []  = $attribute;
                                      break;
        	                        }
    	                            elseif(!in_array(strtolower($contentTitle),$wikisType)) {
				                     		if(!in_array(strtolower($contentTitle),$customWikiType)){
                                                $customWikiType [] = strtolower($contentTitle);
                                                $customWiki [] = $attribute;
                                          }
                                    }
                         }
          		   }
             	$wikisInOrder = array_merge($wikisInOrder,$customWiki);
                ?>
             	<div class="other-details-wrap clear-width institute-details-sec">
             	<h2>College Details</h2>
             	<div id="accordion" class="accordion-ins-wiki">
             	<ul>
             <?php 	foreach ( $wikisInOrder as $attribute ) {
		
				$contentTitle = $attribute->getName ();
				if (strlen ( $contentTitle ) > 103) {
				$contentTitle = preg_replace ( '/\s+?(\S+)?$/', '', substr ( $contentTitle, 0, 100 ) ) . "...";
				}
				
				if(isset($contentTitleMapping[$contentTitle]) && $contentTitleMapping[$contentTitle] != '') {
					$contentTitle = $contentTitleMapping[$contentTitle];
				}

				?>
				<li>
				<h3  uniqueattr="NATIONAL_INSTITUTE_PAGE/Wiki_<?=$contentTitle?>">
					<span class="flLt" title="<?=$attribute->getName()?>" ><?=$contentTitle?></span>
					<i class="sprite-bg up-arrow"></i>
				</h3>
				
				<div class="detail">	
				<div class = "scrollbar1 scrollbar2 accordion_scrollbar"> 
					<div class="scrollbar"><div class="track" ><div class="thumb"><div class="end"></div></div></div></div> 
 					<div class="ins-detl ins-acc-det viewport viewport_h" style = "overflow: hidden;" >
					<div class="overview overview_h">
<?php
				$allowedTags = '<td><dt><dd><ul><li><br><table><colgroup><tbody><tr><td><img><ol><strong><em><span><a><textarea><iframe><cite><code><blockquote><h><b><i><small><sup><sub><ins><del><mark><kbd><samp><var><pre><abbr><address><bdo><blockquote><q><dfn>
		';
				$summary = new tidy ();
				$summary->parseString (strip_tags($attribute->getValue(),$allowedTags), array ('show-body-only' => true ), 'utf8' );
				$summary->cleanRepair ();
				?>
				<?=$summary;?>
				</div>
				</div>
		<div class="scrollbar_h">
			<div class="track_h">
				<div class="thumb_h">
					<div class="end_h"></div>
				</div>
			</div>
		</div>
				</div>
				</div>
			</li>
			
			
			<?php } if($isrelatedQuestionsDataExist) {?>
			 
				<li>
				<h3  uniqueattr="NATIONAL_INSTITUTE_PAGE/Wiki_Related_questions_about_this_institute">
					<span class="flLt" title="Related questions about this institute" >Related questions about this college</span>
					<i class="sprite-bg up-arrow"></i>
				</h3>
				
				<div class="detail" style = "margin-top: 10px">	
				<div class = "scrollbar1 scrollbar2 accordion_scrollbar" id="related_ques_scrollbar"> 
					<div class="scrollbar"><div class="track" ><div class="thumb"><div class="end"></div></div></div></div> 
 					<div class="ins-detl  viewport viewport_h" style = "overflow: hidden;" >
					<div class="overview overview_h" id="related_ques_overview">
					<?php echo $relatedQuestionsData; ?>
					
				</div>
				</div>
		<div class="scrollbar_h">
			<div class="track_h">
				<div class="thumb_h">
					<div class="end_h"></div>
				</div>
			</div>
		</div>

				</div>
				</div>
			</li>
			<?php } ?>
</ul>
</div>
</div>             	
<?php 
	     }


}?>

