<script>
var track_tocken_new = universal_page_type+'_'+universal_page_type_id;
</script>
<!--Starts: Right Content-->
<div class="shadow-box recomm-box">
	<div class="pink-box header">
    	<h4>Your details have been successfully sent to:</h4>
        <p><?=$extra_info;?></p>
    </div>
    <input type="hidden" name="total_reco_right" value="<?php echo count($institutes);?>" id="total_reco_right"/>  
    <?php if(is_array($institutes) && count($institutes)>0):?>
    <div class="content-details">
    	<h5>Students who showed interest in this institute also looked at:</h5>
    <ul <?php if(count($institutes)>1):?>id="bxsliderListingRecommendation" <?php endif;?>>	
    <?php
    $count = 0;
    $localityArray = array();
    foreach($institutes as $institute) {
    	$count++;
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
    	?>
       <li>
        <div class="slideable-content">
            <h6><a  onclick="trackEventByCategory('Listingpage_Reco',track_tocken_new,'<?php echo 'Right_'.($count).'_'.'Insti'.'_'.$institute->getId();?>');" href="<?php echo $course->getURL();?>"><?php echo html_escape($institute->getName()); ?>,</a>
	   <span><?php echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName()?></span>
	   </h6>
            <div class="figure">
            <?php
				if($institute->getMainHeaderImage() && $institute->getMainHeaderImage()->getThumbURL()){
					echo '<img src="'.$institute->getMainHeaderImage()->getThumbURL().'" width="222" height="62"/>';
				}else{
					echo '<img src="/public/images/avatar.gif" width="222" height="62"/>';
				}
			?>
            </div>
            <?php if($institute->getAlumniRating()) :?>
            <p> Alumni Rating: 
            <?php
				$i = 1;
				while($i <= $institute->getAlumniRating()){
				?>
					<img border="0" class="vam" src="/public/images/nlt_str_full.gif">
				<?php
					$i++;
				}
			?>
            <?=$institute->getAlumniRating()?>/5</p>
            <?php endif;?>
            <div class="spacer3 clearFix"></div>
            <p><a onclick="trackEventByCategory('Listingpage_Reco',track_tocken_new,'<?php echo 'Right_'.($count).'_'.'Course'.'_'.$course->getId();?>');" href="<?php echo $course->getURL(); ?>"><?php echo html_escape($course->getName()); ?></a>  
            - <?php echo $course->getDuration()->getDisplayValue()?$course->getDuration()->getDisplayValue():""; ?>
			<?php echo ($course->getDuration()->getDisplayValue()&&$course->getCourseType())?", ".$course->getCourseType():($course->getCourseType()?$course->getCourseType():""); ?>
			<?php echo ($course->getCourseLevel()&&($course->getCourseType()||$course->getDuration()->getDisplayValue()))?", ".$course->getCourseLevel():($course->getCourseLevel()?$course->getCourseLevel():""); ?>
            </p>
            <?php
				if(count($salientFeatures = $course->getSalientFeatures(4))):
			?>
            <ul class="bullet-items">
            <?php
				foreach($salientFeatures as $sf):
			?>
                <li><p><?=str_ireplace(" ","&nbsp;",langStr('feature_'.$sf->getName().'_'.$sf->getValue()))?></p></li>
             <?php endforeach;?>   
            </ul>
            <?php endif;?>
            <div class="spacer10 clearFix"></div>
            <?php if($course->isPaid()):?>
            <div class="apply_confirmation" id="apply_confirmation<?php echo $institute->getId(); ?>"
			<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "style='display:block;'"; ?> >
				E-brochure successfully mailed
			<input type='hidden' id="apply_status<?php echo $institute->getId(); ?>" value='<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "1"; else echo "0"; ?>' />
			<input type='hidden' id='params<?php echo $institute->getId(); ?>' value='<?php echo getParametersForApply($validateuser,$course,$responsecity,$responselocality); ?>' />
			</div>
            <input onClick = "doAjaxApplyListings('<?php echo $institute->getId(); ?>','<?php echo $course->getId(); ?>','<?php echo $displayLocation->getCity()->getId(); ?>','<?php echo $displayLocation->getLocality()->getId();?>','<?php echo $responsecity ?>','LISTING_PAGE_RIGHT_RECOMMENDATION')" class="orangeButtonStyle<?php if(in_array($institute->getId(),$recommendationsApplied)) echo "_disabled"; ?> mr15" id="apply_button<?php echo $institute->getId(); ?>" type="button"  value="Request Free E-brochure"  />
            <?php endif;?>
        </div>
        </li>
<?php }?> 
        </ul>  
        <?php if(count($institutes)>1):?>     
        <div class="flRt slider-control">
        	<div class="prev-btn" onclick="bxsliderListingRecommendation.goToPreviousSlide();">Previous</div>
            <div class="next-btn" onclick="bxsliderListingRecommendation.goToNextSlide();">Next</div>
        </div>
        <div class="clearFix"></div>
        <?php endif;?>
    </div>
    <?php endif;?>
</div>
<!--Ends: Right Content-->
