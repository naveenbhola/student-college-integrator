<div class="slider_section">
  <p class="n-title">Students who got admitted through Shiksha Counseling Service</p>
  <div class="slider_wrap">
  <ul class="clear_max" style="width: <?php echo (count($userProfileData)+1)*310?>px;">
    <?php
    $cardsCount = 0;
    //_p($userProfileData);
    foreach ($userProfileData as $userId => $userDetails) {
    	$cardsCount++;
    	$courseName = $userDetails['admissionData']['courseName'];
    	$univName   = $userDetails['admissionData']['univName'];
    	if(strlen($courseName) > 60){
    		$courseName = substr($courseName,0,57).'...';
    	}
    	if(strlen($univName) > 60){
    		$univName = substr($univName,0,57).'...';
    	}
    	?>
    	<li>
	      <div class="card_profile">
	          <div class="align-cntr fix_h p-top">
	              <a class="inner-img" style="background-image: url('<?php echo $userDetails['image']?>')"></a>
	              <div class="dls_block">
	                <h3 class="fontw-6 fn-14 wrap_margin"><?php echo $userDetails['name']?></h3>
	                <p class="fontw-4 fn-12 m1-b">Admitted to <?php echo $courseName?></p>
	                <p class="fn-14 m2-h"><?php echo $univName?></p>
	              </div>
	          </div>
	          <div class="score_block">
	              <div>
	                <p class="name_lable">Exam<br/>Score</p>
	                <strong class="score_label fontw-6"><?php echo empty($userDetails['exam'])?'None': $userDetails['exam']['examName'].': '.$userDetails['exam']['educationMarks']?></strong>
	              </div>
	              <div>
	              	<?php
	              	if($userDetails['education']['educationLevel'] == '10'){
	                ?>
	                	<p class="name_lable">Class X<br/>Board</p>
	                	<strong class="score_label fontw-6"><?php echo (!empty($userDetails['education']['board']))?$userDetails['education']['board']:'None'?></strong>
	                <?php
	              	}else{
	              	?>
	              		<p class="name_lable">Graduation Percentage</p>
	              		<strong class="score_label fontw-6"><?php echo (!empty($userDetails['education']['educationMarks']))?$userDetails['education']['educationMarks']:'None'?></strong>
	              	<?php
	              	}
	              	?>
	              </div>
	              <div>
	              	<?php
	              	if($userDetails['education']['educationLevel'] == '10'){
	                ?>
	                	<p class="name_lable"><?php echo $userDetails['education']['educationMarksType']?></p>
	                	<strong class="score_label fontw-6"><?php echo (!empty($userDetails['education']['educationMarks']))?$userDetails['education']['educationMarks']:'None'?></strong>
	                <?php
	              	}else{
	              	?>
	              		<p class="name_lable">Work Experience</p>
	              		<strong class="score_label fontw-6"><?php echo (!empty($userDetails['education']['workex']))?$userDetails['education']['workex']:'None'?></strong>
	              	<?php
	              	}
	              	?>
	              </div>
	          </div>
	          <div class="fix_at_btm">
	              <a class="vwPrflLnk" href="<?php echo $userDetails['profileLink']?>">View Profile <span class="css_arrow"></span></a>
	          </div>
	      </div>
	    </li>
    	<?php
    	if($cardsCount == 1 && !empty($ratingData)){
    	?>
    		<li>
		      <div class="card_profile top_bar">
		        <div class="around_space">
		          <h3 class="info-title">Overseas Admission Counselling <span>by Shiksha.com</span></h3>
              <div>
                <div class="review_rate_tab">
                  <strong><?php echo $ratingData['overallRating'];?></strong>
                  <div class="starBlock">
                      <div class="starFullBlock" style="width: <?php echo $starRatingWidth;?>;">
                      </div>
                  </div>
                  <a href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/apply/reviews" class="reviewCount"><?php echo $ratingData['ratingCount']." review".($ratingData['ratingCount']>1?'s':'');?></a>
                </div>
                <div class="CounselingBox">
                    <div class="CounselingInnerBox">
                        <i class="counslngIcon"></i>
                        <span>Student Centric Process</span>
                    </div>
                    <div class="CounselingInnerBox">
                        <i class="chatIcon"></i>
                        <span>Instant chat Availability</span>
                    </div>
                    <div class="CounselingInnerBox">
                        <i class="univIcon"></i>
                        <span>Wide Choice of Universities</span>
                    </div>
                    <div class="CounselingInnerBox">
                        <i class="personalisedIcon"></i>
                        <span>100% Free & Personalised</span>
                    </div>
                </div>
              </div>
		          <div class="cntr_txt">
		            <a class="inline-btn knwMorLnk" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/apply">Know More</a>
		          </div>
		        </div>
		      </div>
		    </li>
    	<?php
    	}
    }
    ?>
  </ul>
  </div>
</div>
