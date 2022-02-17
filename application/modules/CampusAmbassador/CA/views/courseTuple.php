<div id="connect-wrapp" style="clear: both; <?php if($tab=='overview'){ echo 'cursor:pointer;';}?>" <?php if($tab=='overview'){ ?>onClick="window.location='<?=$url.'#connect-wrapp'?>';" <?php } ?>>
	
	<?php $inputId1 = '';
		  $inputId2 = '';
		  $inputId3 = '';
	?>
	
	<?php if(!$studyIndia):?>
		<?php if($tab!='overview' && $courseCount>1){ ?>
			<a href="<?=$instituteAnAURL?>" class="view-discussion">View discussions for other courses <i class="view-icon"></i></a>
		<?php } ?>
	<?php endif;?>
	
	<?php
				$displayString = '';
				$displayCourse = true;
				if($mainCourse){ 
					$courseName = $mainCourse->getName();
				} 
				else{ 
					$courseName= $course->getName();
				}
				$courseName= $course->getName();
				if(count($result['data']) > 0){
					$image = getSmallImage($result['data'][0]['imageURL']);
					$displayname = $result['data'][0]['displayName'];
					if($result['data'][0]['badge']=='CurrentStudent'){
						$badge = 'Current Student';
						$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am a Current student of ".$courseName." course.";
					}
					else if($result['data'][0]['badge']=='Alumni'){
						$badge = 'Alumni';
						$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Alumni of ".$courseName." course.";
					}
					else{
						$badge = 'Official';
						$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";
					}
                                        if($image==''){
                                                $image = getSmallImage('/public/images/photoNotAvailable.gif');
                                        }
                                        
                    $inputId1 = $result['data'][0]['userId'].'_'.$badge;                    
				}
				else if($result['instituteRep'] == 'true'){
					$instInfo = getInstituteOfficial($course,$institute,$currentLocation,$courseName);
					$image = $instInfo['image'];
					$displayname = $instInfo['displayname'];
					$badge = $instInfo['badge'];
					$displayString = $instInfo['displayString'];					
				}
				
			?>
			
	
	<?php if($result['totalReps'] <1 && $result['instituteRep']=='false' || ($displayname=='' && $result['instituteRep']=='true')){?>
		<div class="callout-box">
	<?php }else {?>
		<div class="callout-box2">
	<?php } ?>
		<?php if($tab=='overview' && ($result['totalReps'] >0 || ($result['instituteRep']=='true' && $displayname!=''))){ ?>
		<div class="discussion-head">
		    <i class="connect-icon"></i>
		    <div class="connect-title">
			<h2>Campus Connect</h2>
			<p>Current students of this college are here to answer your questions. You can view their profile below. </p>
		    </div>
		</div>	
		<?php }else if($tab=='overview' && (($result['totalReps'] <1 && $result['instituteRep']=='false') || ($displayname=='' && $result['instituteRep']=='true'))){ ?>
		<div class="discussion-head" style="padding-bottom:15px ">
		   
		    <div style="font-size: 14px">
			<strong> Have a question about <?=$course->getName(); ?> , <?=$institute->getName(); ?>?<br/>Ask our Career Counselors.</strong>
		    </div>
		    
            <div class="spacer8 clearFix"></div>
               <input type="button" value="Ask your question" class="orange-button" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }"/>
            
		</div>	
		<?php }else if($result['totalReps'] <1 && $result['instituteRep']=='false' || ($displayname=='' && $result['instituteRep']=='true')){ ?>
		<div class="discussion-head" style="padding-bottom:15px ">
		    
		    <div style="font-size: 14px">
			<strong> Have a question about <?=$course->getName(); ?> , <?=$institute->getName(); ?>?<br/>Ask our Career Counselors.</strong>
		    </div>
		    <div class="spacer8 clearFix"></div>
               
                        <input type="button" value="Ask your question" class="orange-button" onClick=" $j('#ask_question_askAQuestion').focus(); $('askQuestionFormDiv').scrollIntoView(false); "/>
		    </div>
		
		<?php } else{ ?>
		<div class="discussion-head other-details-wrap" style="margin-top:0px;" >
			<h2 class="mb14"style="padding-bottom:11px">
			Ask your queries to current students of this college
			</h2>

		    <div class="flLt title-txt" style="margin-left: 10px; width: 63%;">
			Current students of this college are here to answer your questions. You can view their profile below.
		    </div>
		    <div class="total-cmts flRt">
			<p><i class="cmnt-icon"></i> <a href="#questionListOnPage" id="numberOfComments"></a></p>
			<!--<span>Posted: 06-19-2013, 03:21 PM</span>-->
		    </div>
		</div>
		<?php } ?>
       <?php  if($result['totalReps'] >0 || ($result['instituteRep']=='true' && $displayname!='')){ ?>
		<ul class="current-discussion">
			
			
				<li class="course-discussion">
				   <div class="student-figure"><img src="<?=$image?>" alt="" height=60 width=60 /></div>
				   <div class="discussion-details">
					   <div class="student-name"><span class="font-15"><strong><?=$displayname?></strong></span> <span class="blue-btn"><?=$badge?></span></div>
				       <?php if(true){ ?>
				       <p class="student-details">
					   <!--<strong>Course:</strong>-->
					   <span style="margin-left: 0px;"><?=$courseName?></span>
				       </p>
					<?php } ?>
							  
				   </div>
			       </li>
		    
			<?php
				if(count($result['data']) > 1){
					$image = getSmallImage($result['data'][1]['imageURL']);
					$displayname = $result['data'][1]['displayName'];
					if($result['data'][1]['badge']=='CurrentStudent')
						$badge = 'Current Student';
					else if($result['data'][1]['badge']=='Alumni')
						$badge = 'Alumni';
					else
						$badge = 'Official';
					$displayString .= " I am joined by <span style='color:#0065DE;'>$displayname</span>";
	                                if($image==''){
        	                                $image = getSmallImage('/public/images/photoNotAvailable.gif');
                	                }
                	$inputId2 = $result['data'][1]['userId'].'_'.$badge;                	                
					?>
					<li class="helper-box">
					    <div class="tac">
						<p><img src="<?=$image?>" /></p>
						<p><span class="font-15"><strong><?=$displayname?></strong></span></p>
						<p><span class="blue-btn"><?=$badge?></span></p>
					    </div>
					</li>
					<?php
				}
			?>
		    
			<?php
				if(count($result['data']) > 2){
					$image = getSmallImage($result['data'][2]['imageURL']);
					$displayname = $result['data'][2]['displayName'];
					if($result['data'][2]['badge']=='CurrentStudent')
						$badge = 'Current Student';
					else if($result['data'][2]['badge']=='Alumni')
						$badge = 'Alumni';
					else
						$badge = 'Official';
					$displayString .= " and <span style='color:#0065DE;'>$displayname</span>.";
                                        if($image==''){
                                                $image = getSmallImage('/public/images/photoNotAvailable.gif');
                                        }
                    $inputId3 = $result['data'][2]['userId'].'_'.$badge;
					?>
					<li class="helper-box last">
					    <div class="tac">
						<p><img src="<?=$image?>" /></p>
						<p><span class="font-15"><strong><?=$displayname?></strong></span></p>
						<p><span class="blue-btn"><?=$badge?></span></p>
					    </div>
					</li>
					<?php
				}
				
				if($displayString!='' && count($result['data']) > 1){
					$displayString .= " We are here to answer any queries you have.";
				}
				else if($displayString!=''){
					$displayString .= " I am here to answer any queries you have.";
				}
								if((count($result['data']) == 1 || count($result['data']) == 2) && $result['instituteRep'] == 'true'){

                                        $instInfo = getInstituteOfficial($course,$institute,$currentLocation,$courseName);

                                        $image = $instInfo['image'];

                                        $displayname = $instInfo['displayname'];

                                        $badge = $instInfo['badge'];

                                        $displayString .= " We are here to answer any queries you have.";

			?>
                                        <li class="helper-box last">

                                            <div class="tac">

                                                <p><img src="<?=$image?>" /></p>

                                                <p><span class="font-15"><strong><?=$displayname?></strong></span></p>

                                                <p><span class="blue-btn"><?=$badge?></span></p>

                                            </div>

                                        </li>

                        <?php } ?>
	
		</ul>
        
	<?php
		$eligibility = $fees = $selection = '';
		$styleString = "style='display:none;'";
		if(isset($result['data'][0]['badge']) && $result['data'][0]['badge']=='CurrentStudent' && isset($result['data'][0]['eligibilty'])){
			$eligibility = $result['data'][0]['eligibilty'];
		}
		else{
			$eligibility = $course->getOtherEligibilityCriteria();
		}
		
		if(isset($result['data'][0]['badge']) && $result['data'][0]['badge']=='CurrentStudent' && isset($result['data'][0]['fees'])){
			$fees = $result['data'][0]['fees'];
		}
		else if($course->getFees()->getValue()){
			$fees = $course->getFees();
		}
		
		if(isset($result['data'][0]['badge']) && $result['data'][0]['badge']=='CurrentStudent' && isset($result['data'][0]['selectionProcess'])){
			$selection = $result['data'][0]['selectionProcess'];
		}
		else{
			$exams = $course->getEligibilityExams();
			if(count($exams) > 0){
				$examAcronyms = array();
				foreach($exams as $exam) {
					$examAcronyms[] = $exam->getAcronym();
				}
				$selection = implode(', ',$examAcronyms);
			}			
		}
		if($eligibility!='' || $selection!='' || $fees!=''){
			$styleString = "";
		}
		$styleStringForCompleteMessage = 'style="display:none;"';
		if($eligibility!='' || $selection!='' || $fees!='' || $displayString!=''){
			$styleStringForCompleteMessage = "";
		}
	?>
	<input id="input_1" value="<?php echo $inputId1;?>" style="display:none;" />
	<input id="input_2" value="<?php echo $inputId2;?>" style="display:none;" />
	<input id="input_3" value="<?php echo $inputId3;?>" style="display:none;" />
	
        <div class="callout-content">
        	<!--<ul class="course-details">
                <li <?=$styleString?>><span>Given below is useful information about the institute you can read through before you ask your questions.</span></li>-->

		<?php /*if($eligibility!=''){ ?>
                <li>
                    <strong>Eligibility: </strong>
                    <p><?=$eligibility?></p>
                </li>
		<?php } ?>
                
		<?php if($selection!=''){ ?>
                <li>
                    <strong>Selection criteria: </strong>
                    <p><?=$selection?></p>
                </li>
		<?php } ?>
		
		<?php if($fees!=''){ ?>
                <li>
                    <strong>Course fee:</strong>
                    <p><?=$fees?></p>
                </li>
		<?php } */?>
            <!--</ul>
            <div class="spacer5 clearFix"></div>-->
            <!--<p>Feel free to raise your queries and the campus representatives will be happy to resolve them for you.</p>-->
	   
            <div class="mt15">
               <?php if($tab!='overview'){ ?>
                        <input type="button" value="Ask your question" class="orange-button" onClick=" $j('#ask_question_askAQuestion').focus(); $('askQuestionFormDiv').scrollIntoView(false); "/>
               <?php }else{ ?>
                       <input type="button" value="Ask your question" class="orange-button" onClick="window.location = '<?=$url.'#ask-question'?>'; if (event.stopPropagation) { event.stopPropagation(); } else { event.cancelBubble = true; }"/>
               <?php } ?>
            </div>
	    
        </div>
         <?php } ?>
    </div>
</div>
<?php

function getInstituteOfficial($course,$institute,$currentLocation,$courseName){

	$image = $institute->getMainHeaderImage()->getThumbURL();

	$locations = $course->getLocations();

	$location = $locations[$currentLocation->getLocationId()];

	$getInstituteLocation = false;

	if(!$location){

		$getInstituteLocation = true;

	}

	else{

		$contactDetail = $location->getContactDetail();

		if($contactDetail->getContactPerson()){

			$displayname = $contactDetail->getContactPerson();

			$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";

		}

		else{

			$getInstituteLocation = true;

		}

	}

	if($getInstituteLocation){	//Check for Institute contact person

			$locations = $institute->getLocations();

			$location = $locations[$currentLocation->getLocationId()];

			$contactDetail = $location->getContactDetail();

			if($contactDetail->getContactPerson()){

				$displayname = $contactDetail->getContactPerson();

				$displayString .= "Hi, I am <span><strong>$displayname</strong></span>. I am an Official of ".$courseName." course.";

			}

			else{

				$displayname = "";

				$displayString .= "Hi, I am an Official of ".$courseName." course.";

			}

	}



	$badge = 'Official';				

	$displayCourse = false;

	if($image==''){

		$image = '/public/images/avatar.gif';

	}

	$res['badge'] = $badge;$res['displayname'] = $displayname;$res['displayString'] = $displayString;$res['image']=$image;

	return $res;

}

?>
