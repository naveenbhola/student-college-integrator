<?php //code for initiatedownloadbrochure can be removed from here if we start providing belly link on deptpage for download brochure
    $initiateBrochureDownload = $_REQUEST['initiateBrochureDownload'];
?>
<div id="dept-title" class="course-title" style="position:relative">
    <h1><?=htmlentities($departmentObj->getName())?></h1>
	<script>
		var rmcPageTitle = "<?=base64_encode($departmentObj->getName())?>";
	</script>
    <?php // add widget type to brochure data
        //$brochureDataObj['widget'] = 'email_popout';
        //$brochureDataObj['trackingPageKeyId'] = 45;
        //$brochureDataObj['consultantTrackingPageKeyId'] =405;
    ?>
    <?php /*<a id="email-btn" class="email-btn flRt button-style" href="javascript:void('0');" class="flRt button-style" style="height:28px;"
       onclick="studyAbroadTrackEventByGA('ABROAD_DEPARTMENT_PAGE', 'emailMeDetails'); loadStudyAbroadForm('<?=base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');">
         <i class="listing-sprite email-icon" style="float: left; right: 2px; display: block"></i>
         <p id="email-txt" style="margin-left:30px;">Email me department details</p>
    </a>*/ ?>
</div>

<?php $this->load->view('listing/abroad/widget/universityAnnouncement'); ?>

<script> // to facilitate continuation of download-brochure flow when user explicitly logs in
    var initiateBrochureDownload  = <?=($initiateBrochureDownload==1?$initiateBrochureDownload:0)?>;
</script>