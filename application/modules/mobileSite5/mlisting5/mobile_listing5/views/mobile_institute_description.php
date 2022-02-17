<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
$collegeOrInstituteRNR = ucfirst($collegeOrInstituteRNR);
$contentTitleMapping = array("Institute Description" => "$collegeOrInstituteRNR Description",
					  		 "Partner Institutes"	 => "Partner {$collegeOrInstituteRNR}s",
					  		 "Institute Awards"	 	 => "$collegeOrInstituteRNR Awards",
					  		 "Institute History"	 => "$collegeOrInstituteRNR History");
?>
<?php if($instituteComplete->getDescriptionAttributes()){ ?>

<article class="inst-details" style="margin:0">
	<h2 class="ques-title">
		<p>College Details</p>
	</h2>
	<div class="notify-details" >
		<div class="inst-detail-list" id="instituteDesc">
	
	<?php
			$instituteDescriptions = array();
			$i=0;
			foreach($instituteComplete->getDescriptionAttributes() as $attribute){
				$contentTitle = $attribute->getName();
				if($contentTitle == "College Description"){
					$instituteDescription = array($attribute);
				}else{
					$instituteDescriptions[] = $attribute;
				}
			}
			if($instituteDescription){
				$instituteDescriptions = array_merge($instituteDescription,$instituteDescriptions);
			}
			foreach($instituteDescriptions as $attribute){
				
				$contentTitle = $attribute->getName();
				if(strlen($contentTitle)>30){
					$contentTitle = preg_replace('/\s+?(\S+)?$/', '', substr($contentTitle, 0, 30))."...";
				}
				if(isset($contentTitleMapping[$contentTitle]) && $contentTitleMapping[$contentTitle] != '') {
					$contentTitle = $contentTitleMapping[$contentTitle];
				}
			?>
			
			<!--<li>-->
				<dt>
					<a href="javascript:void(0)">
						
					    <h3><?=$contentTitle?></h3>
					    <i id="desc<?=$i?>" class="icon-arrow-up"></i>
					</a>
				</dt>
				<dd>
					<div class="tiny-contents">
					    <?php
						    $summary = new tidy();
						    $summary->parseString($attribute->getValue(),array('show-body-only'=>true),'utf8');
						    $summary->cleanRepair();
					    ?>
					    <?=$summary?>
					</div>
				</dd>
			<!--</li>-->
			
			<?php
				$i++;
			}
	?>
	</div>
    </div>
<!-- Display Success message for Request E-Brochure -->
<?php 
if (getTempUserData('confirmation_message_ins_page') && getTempUserData('REB_LOC')=='MOBILE5_INSTITUTE_DETAIL_PAGE'){?>
    <div class="notify-details" style="padding: 0px 0.9em;display: none" id="stickyThanksMsg">
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
}
deleteTempUserData('confirmation_message_ins_page');
deleteTempUserData('confirmation_message');
deleteTempUserData('REB_LOC');
?>



<?php if($showRequestButton){
	echo "<script>var courseId = ".$course->getId().";</script>";
	?>
	
	    <div>
		<!--<a id="request_ebrochure_details" class="button blue small" href="javascript:void(0);" onClick="$('#brochureForm<?=$course->getId();?>').submit();"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>-->
		<!--<a class="button yellow small" style="color: #000;" id="request_ebrochure_details" href="javascript:void(0);" onClick="$('#brochureForm<?=$course->getId();?>').submit();"><span>Request Free E-Brochure</span></a>-->
	    </div>

<?php } ?>
    
</article>
<?php } ?>
    
<!-- Display Request E-Brochure button if any of the course is Paid -->
<?php
	$courses = $institute->getCourses();
	$course = $institute->getFlagshipCourse();
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
<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId();?>">
			<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
			<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" />
			<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
			<input type="hidden" name="selected_course" value = "" />
			<input type="hidden" name="list" value="" />
			<input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
			<input type="hidden" name="pageName" value="INSTITUTE_DETAIL_PAGE" />
			<input type="hidden" name="from_where" value="MOBILE5_INSTITUTE_DETAIL_PAGE" />
			<input type="hidden" name="tracking_keyid" id="tracking_keyid<?=$course->getId();?>" value=''>
	</form>
<script>  
    $(document).ready(function(){
    $("#request_ebrochure_details").click(function(){
	try{
        var listing_id  = "<?php echo $course->getId();?>";
        _gaq.push(['_trackEvent', 'HTML5_Listing_Page_Request_Ebrochure', 'click', listing_id]);
	}catch(e){}
      });
    });
  </script>
