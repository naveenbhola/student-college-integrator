<section class="content-wrap clearfix also-viewed">
    <div class="detail-info-sec" style="padding-bottom:0">
		<strong>People who viewed this course also viewed</strong>
    </div>
    <div class="slider-box" id="otherCoursesSlider">
	<ul id="otherCoursesUl" style="width:10000px;" class="sliderUl">
	    <?php
		foreach ($alsoViewedCourses as $courseId=>$recommendationData){
	    ?>
	    <li class="trendtuple" onclick="goToCoursePage(this,event)" style="width:302px;">
	    	<div class="caption">
			    <strong><a href="<?php echo $recommendationData['universityURL'];?>"><?=$recommendationData['universityName']?></a></strong>
			    <p><?=$recommendationData['universityLocation']?></p>
			</div>
		    <div class="figure">
			<a href="<?=$recommendationData['courseURL']?>" class="courseLinkImg">
                            <img class="lazy" src="<?=$recommendationData['universityImageURL']?>" width="300" height="200" alt="univ-img">
			</a>
		    </div>
		    <div class="univ-details">
			<strong style="font-size: 15px;"><?=htmlentities(formatArticleTitle($recommendationData['courseName'],30))?></strong>
		    </div>
		    <ol class="univ-info">
				<li>
					<label>Duration</label>
					<p><?=$recommendationData['courseDuration']?></p>
				</li>
			    <li>
			    <label>1st Year Total Fees</label>
			    <?php
				$courseFees = explode(' ',$recommendationData['courseFees']);
			    ?>
			    <p><?=$courseFees[0]?> <big><?=$courseFees[1]?></big> <?=$courseFees[2]?></p>
			    </li>
			    <li style="border-bottom:1px solid #d6d6d6;">
					<label style="vertical-align: top;">Eligibility</label>
					
					<?php
					$courseExam = explode('|', $recommendationData['courseExam']);
					$examCounter = 0;
					?>
					<p>
					<?php for($examCounter = 0; $examCounter < 2; $examCounter++){ ?>
						<?php $examInfo = explode(' ', $courseExam[$examCounter]); ?>
						<?php if($examCounter == 0 && !empty($examInfo[0])){ ?>
							<span style="color:#000; display:inline-block; width:44px; margin:0 0 4px 0"><?=substr($examInfo[0],0,-1)?></span> <big> : <?=$examInfo[1]?></big>
						<?php }else if($examCounter == 1 && !empty($examInfo[0])){ ?>
							<br/>
							<span style="color:#000; display:inline-block; width:44px; margin:0 0 4px 0"><?=substr($examInfo[0],0,-1)?></span> <big> : <?=$examInfo[1]?></big>
						<?php }else if($examCounter == 1 && empty($examInfo[0])){ ?>
								<br/><span style="color:#000; display:inline-block; width:44px; margin:0 0 4px 0">&nbsp;</span><big>&nbsp;</big>
						<?php }else{ break; } ?>
					<?php } ?>
					</p>
			    </li>
		    </ol>
			<?php if($recommendationData['showRmcButton'] == 1){ ?>
				<?php
					$rmcDataObject = array(
						'courseId'=>$courseId,
						'userRmcCourses'=>$userRmcCourses,
						'sourcePage' => 'course',
						'widget'=>'alsoViewed',
						'pageTitle' => $courseObj->getName(),
						'trackingPageKeyId' => 464
					);
					$rateMyChanceCtlr->loadRateMyChanceButton($rmcDataObject);
				?>
			<?php }else{ ?>
				<div style="height:48px;"></div>
			<?php } ?>
		</li>
	    <?php }
	    ?>
	    
	</ul>
    </div>
	<style>
		.trendtuple .rateMyChanceButtonDiv {margin: 8px 0 8px 0;text-align:center}
		.trendtuple .rateMyChanceButtonDiv .rate-change-button {width:232px;float:none; margin:auto;}
	</style>
</section>
