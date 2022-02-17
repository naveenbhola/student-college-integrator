<?php
$hasRecommendations = (is_array($institutes) && count($institutes) > 0) ? TRUE : FALSE;
?>
<div class="layer-outer" style="width:700px; <?php if(!$hasRecommendations) echo "margin-top:100px;"; ?>">
	<div class="layer-title">
        <a title="Close" href="javascript:void(0);" class="close" onClick="window.location.reload(); return false;"></a>
	
	<?php if($showHeading == 'true') { ?>
        <h4>
			Download E-Brochure for
			<?php
			foreach($appliedInstitutes as $institute) {
				$courses = $institute->getCourses();
				$course = $courses[0];
				echo $course->getName()." - ".$institute->getName();	
				break;	
			}
			?>
		</h4>
        <?php } ?>
	
    </div>
    <div class="layer-contents" style="<?php if($hasRecommendations) echo "height:500px;"; ?> overflow: auto;">
	<?php if($showHeading == 'true') { ?>
	<div class="confirm-bro-msg" style="background-position:0px 7px; padding-left:30px; margin-bottom: 15px;">
	    <?php if($thanks == 'NO'):?>
		<h4 style="margin:0; padding:0; font:600 16px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#333">Sorry, brochure is currently not available.</h4>
		<?php else:?>
		<h4 style="margin:0; padding:0; font:600 16px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#333">Thank you for your request. Your E-brochure has been successfully mailed to you.</h4>
		<?php endif;?>
		<?php if($course_brochure){?>
		<a href="<?=$course_brochure?>" target="_blank">View brochure now &gt;</a>
		<?php }?>
	</div>
	<?php } ?>
	<?php if($hasRecommendations) { ?>
	<div class='recommendation_page_outer'>
	<div class='recommendation_page_left'>
		<h2 style="background: #f6f6f6; font-size:15px; font-family:Trebuchet MS, Arial, Helvetica, sans-serif; padding:7px; color:#444;"><?php echo $headingVerbiage; ?></h2>
		<div class="instituteLists">
			<?php $this->load->view('categoryList/categoryPageSnippets',array('sourcePage' => 'LP_Reco_ShowRecoLayer')); ?>
		</div>
	</div>
	<div style='clear:both;'></div>
	</div>
	<?php } else { ?>
		<div style="text-align:center;">
			<input type="button" value="Ok" title="Ok" class="orange-button" onclick="window.location.reload();">
		</div>
	<?php } ?>
<br />
</div>
</div>
<script>
	//Commented out GA Tracking
	
	<?php if($hasRecommendations) { ?>
		//pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_ShowRecoLayer', 1);
		//pageTracker._trackPageview();
                pushCustomVariable('CP_Reco_Load_ShowRecoLayer');
	<?php } ?>
	
	//<?php if($hasRecommendations) { ?>
	//pageTracker._setCustomVar(1, "GATrackingVariable", 'CP_Reco_Load_ShowRecoLayer', 1);
	//pageTracker._trackPageview();
	//<?php } ?>
	
	$j(document).keyup(function(e) {
		if(e.keyCode == 27) {
		    window.location.reload();
		}
	});
	
	<?php
	if($recoAlgo) {
		echo "trackEventByCategory('Reco-".$courseSubcat."','RecommendationShown','".$recoAlgo."');";
	}
?>
</script>
