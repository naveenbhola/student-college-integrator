<?php
	    $universityLocations = $universityObj->getLocations();
	    $universityState = reset($universityLocations)->getState();
	    $universityCity  = reset($universityLocations)->getCity();
	    //$universityTitle = $universityObj->getName().", ".$universityCity->getName().($universityState->getName()!=""?" (".$universityState->getName().")":"");
?>
<div id="course-title" class="course-title" style="position:relative">
	    <h1><?=htmlentities($universityObj->getName())?></h1>
		<script>
			var rmcPageTitle = "<?=base64_encode($universityTitle)?>";
		</script>
	    <?php // add widget type to brochure data
	        $brochureDataObj['widget'] = 'email_popout';
	        $brochureDataObj['trackingPageKeyId'] = 5;
	        $brochureDataObj['consultantTrackingPageKeyId'] = 388;
	    ?>
	    <a id="email-btn" class="flRt button-style" href="javascript:void('0');" style="height:28px;margin-right:-45px;"
	       onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'emailMeDetails'); loadBrochureDownloadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"
	       >
			<i class="listing-sprite email-icon" style="float: left; right: 2px; display: block"></i>
			<p id="email-txt" style="margin-left:30px;">Email me university details</p>
	    </a>
</div>

<?php $this->load->view('listing/abroad/widget/universityAnnouncement'); ?>