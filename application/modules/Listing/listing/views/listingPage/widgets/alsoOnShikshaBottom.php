<script>
var track_tocken = universal_page_type+'_'+universal_page_type_id;
</script>
<!--Starts: Left Bottom Content-->
<div class="shadow-box recomm-box" style="margin-top:35px;">
	<div class="pink-box header">
    	<h4>Your details have been successfully sent to:</h4>
        <p><?=$extra_info;?></p>
    </div>
    <?php
	/*
    $count_institute = count($institutes);
    ?>
   <input type="hidden" name="total_reco_bottom" value="<?php echo count($institutes);?>" id="total_reco_bottom"/> 
    <?php if(is_array($institutes) && count($institutes)>0):?>
	<div class="content-details" style="padding:20px 10px 0 10px;" id="scrollbar-box">
    	<h5>Students who showed interest in this institute also looked at:</h5>
        <div class="spacer5 clearFix"></div>
        <div class="scrollbar"><div class="track"><div class="thumb"></div></div></div>
			<div class="viewport" style="height:<?php if($count_institute > 2) {echo "310px";} else {echo ($count_institute*160)."px";}?>">
				<div class="overview">
            	<ol class="ist-list">
            	<?php
            	$count = 0;
            	$localityArray = array();
            	foreach($institutes as $institute) {
            		$courses = $institute->getCourses();
    				$course = $courses[0]; 
            		$displayLocation = $course->getCurrentMainLocation();
            		$courseLocations = $course->getCurrentLocations();
            		if(!$courseLocations || count($courseLocations) == 0){
            			$courseLocations = $course->getLocations();
            		}
            		if(!$displayLocation){
            			$displayLocation = $course->getMainLocation();
            		}
            		$courses = $institute->getCourses(); 
            		?>
                    <li <?php if($count == ($count_institute-1) ) echo "class='last'";?>>
                        <h6 style="font-size:16px"><a 
	                       onclick="trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.($count+1).'_'.'Insti'.'_'.$institute->getId();?>');" href="<?php echo $course->getURL();?>"><?php echo html_escape($institute->getName()); ?>,</a>
			<span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span>			
			</h6>
                        <div class="inst-pic">
						<?php
						if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){
							echo '<img src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="124" height="104"/>';
						}else{
							echo '<img src="/public/images/avatar.gif" />';
						}
						?>
                        </div>
                        <div class="inst-details">
                        <?php if($institute->getAIMARating()) : ?>
                            <div class="aimaRating" onmouseover="catPageToolTip('aima','',this,30,-10);" onmouseout="hidetip();">
                                <span>AIMA Rating:</span>
                                <span class="ratingBox"><?=$institute->getAIMARating()?></span>
                            </div>
                         <?php endif;?>   
                         <?php if($institute->getAlumniRating()) : ?>
                            <div class="alumniRating">
                                <span>Alumni Rating:</span>
                                <span> 
                                <?php
									$i = 1;
									while($i <= $institute->getAlumniRating()){
										?> <img border="0" src="/public/images/nlt_str_full.gif"> <?php
										$i++;
									}
								?>
                                </span>
                                <span class="rateNum">&nbsp;<?=$institute->getAlumniRating()?>/5</span>
                            </div>
                           <?php endif;?> 
                            <div class="section-row">
                            <p><a onclick="trackEventByCategory('Listingpage_Reco',track_tocken,'<?php echo 'Bottom_'.($count+1).'_'.'Course'.'_'.$course->getId();?>');" href="<?php echo $course->getURL(); ?>"><?php echo html_escape($course->getName()); ?></a>
								-
								<?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?>
								<?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
								<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
							</p>
                            
                            <div class="feeStructure">
								<?php
								$exams = $course->getEligibilityExams();
								if($course->getFees()->getValue()){ ?>
								<label>Fees: </label> <span><?=$course->getFees()?> </span>
								<?php }else{
									?>
								<label>Fees: </label> <span>Not Available</span>
								<?php
								}
								if(count($exams) > 0){
									echo '<b>|</b>';
								}
								if(count($exams) > 0){
									if($institute->getInstituteType() == "Test_Preparatory_Institute"){
										?>
								<label>Exams Prepared for: </label> <span> <?php
									}else{
										?> <label>Eligibility: </label> <span> <?php
									}
									$examAcronyms = array();
									foreach($exams as $exam) {
										$examAcronyms[] = $exam->getAcronym();
									}
									echo implode(', ',$examAcronyms); ?> </span> <?php } ?>
							</div>
                            	
							<?php
							if(count($salientFeatures = $course->getSalientFeatures(4))){
							?>
                            <ul class="bullet-items">
                            <?php
								foreach($salientFeatures as $sf){
							?>
                                <li><p><?=str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue()))?></p></li>                             
                            <?php }?>    
                            </ul>
                            <?php }?>
                            <div class="clearFix spacer5"></div>
                             <?php if($course->isPaid()):?>
				            <div class="apply_confirmation" id="apply_confirmation<?php echo $institute->getId(); ?>"
							<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "style='display:block;'"; ?> >
								E-brochure successfully mailed
							<input type='hidden' id="apply_status<?php echo $institute->getId(); ?>" value='<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "1"; else echo "0"; ?>' />
							<input type='hidden' id='params<?php echo $institute->getId(); ?>' value='<?php echo getParametersForApply($validateuser,$course,$responsecity,$responselocality); ?>' />
							</div>
				            <input onClick = "doAjaxApplyListings('<?php echo $institute->getId(); ?>','<?php echo $course->getId(); ?>','<?php echo $displayLocation->getCity()->getId(); ?>','<?php echo $displayLocation->getLocality()->getId();?>','<?php echo $responsecity ?>','LISTING_PAGE_BOTTOM_RECOMMENDATION')" class="orangeButtonStyle<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "_disabled"; ?> mr15" id="apply_button<?php echo $institute->getId(); ?>" type="button"  value="Request Free E-brochure"  />
				            <?php endif;?>
                            </div>
                         </div>
                    </li>
 <?php $count++;}?>                   
            	</ol>
            </div>
            </div>
            
            <div class="clearFix"></div>
			</div>
		<?php endif;?>	
        <div class="clearFix"></div>
	<?php */ ?>
</div>
<!--Ends: Left Bottom Content-->
