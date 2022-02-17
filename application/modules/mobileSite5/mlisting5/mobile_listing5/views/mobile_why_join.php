<!-- Display Why join widget -->
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
$reasonJoin = unserialize(base64_decode($whyJoin));
$course = $institute->getFlagshipCourse();
$courses = $institute->getCourses();
if(!empty($reasonJoin['0']['0']['details'])){
	?>
	    <h2 class="ques-title">
		<p>Why Join <?=$institute->getName()?></p>
	    </h2>
	    <div class="notify-details">
		    <div class="tiny-contents"><?php echo ($reasonJoin['0']['0']['details']);?></div>
	    </div>
<?php }?>

<!-- Display Success message for Request E-Brochure -->
<?php 
/*
if (getTempUserData('confirmation_message_ins_page') && getTempUserData('REB_LOC')=='MOBILE5_INSTITUTE_DETAIL_PAGE'){?>
    <div class="notify-details" style="padding: 0px 0.9em;">
	    <section class="top-msg-row">
		    <div class="thnx-msg">
			<i class="icon-tick"></i>
			<p>
			    <?php echo getTempUserData('confirmation_message_ins_page'); ?>
			</p>
		    </div>
	    </section>
    </div>
<?php
}*/
//deleteTempUserData('confirmation_message_ins_page');
deleteTempUserData('confirmation_message');
//deleteTempUserData('REB_LOC');
?>

<!-- Display Request E-Brochure button if any of the course is Paid -->
<?php
	$addReqInfoVars = array();
	$showRequestButton = false;
	foreach($courses as $c){
		if(checkEBrochureFunctionality($c)){
			$showRequestButton = true;
			foreach($c->getLocations() as $course_location){
					$locality_name = $course_location->getLocality()->getName();
					if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
					$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
			}		
		}
	}
	$addReqInfoVars=serialize($addReqInfoVars);
	$addReqInfoVars=base64_encode($addReqInfoVars);
?>

<?php /*if($showRequestButton){?>
	    <p style="padding: 0 0.9em 0.9em;">
					<!--<a id="request_ebrochure" class="button blue small" href="javascript:void(0);" onClick="$('#brochureForm<?=$course->getId();?>').submit();"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>-->
					<!--<a class="button yellow small" style="color: #000;z-index:99"  id="request_ebrochure" class="button blue small" href="javascript:void(0);" onClick="$('#brochureForm<?=$course->getId();?>').submit();"><span>Request Free E-Brochure</span></a>-->
	    </p>
			<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId();?>">
			<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
			<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" />
			<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
			<input type="hidden" name="selected_course" value = "" />
			<input type="hidden" name="list" value="" />
			<input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
			<input type="hidden" name="pageName" value="INSTITUTE_DETAIL_PAGE" />
			<input type="hidden" name="from_where" value="MOBILE5_INSTITUTE_DETAIL_PAGE" />
	</form>
<?php } */?>


<!--<script>  
    $(document).ready(function(){
    $("#request_ebrochure").click(function(){
	try{
        var listing_id  = "<?php //echo $course->getId();?>";
        _gaq.push(['_trackEvent', 'HTML5_Listing_Page_Request_Ebrochure', 'click', listing_id]);
	}catch(e){}
      });
    });
  </script>-->
  
