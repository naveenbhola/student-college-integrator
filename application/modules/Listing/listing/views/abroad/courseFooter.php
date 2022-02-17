<?php 	$footerComponents = array(
			   'js'=>array('studyAbroadSignup','jquery.royalslider.min','jquery.tinycarouselV2.min'),
			   'asyncJs'=>array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents); ?>
<script crossorigin src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("studyAbroadListings"); ?>"></script>
<script crossorigin src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery-ui.min"); ?>"></script>
<script>
$j(window).load(function(){
	 initializeCoursePage();
	 initializeCoursePageAfterLoad();
	 initializeInlineForm("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['currentSchool']->getValues()))); ?>");
	 //getCoursePageAlsoViewRecommendation('<?=$courseObj->getId()?>');var
	 <?php if(!empty($universityObj)) {?>
	 loadMediaforCoursePage('<?=$universityObj->getId()?>');	 
	 <?php } ?>

    <?php if(!(empty($courseObj))&&($courseObj->getId()>0)) {?>
    loadAdmittedCardsForCoursePage  ('<?php echo $courseObj->getId(); ?>');
    <?php } ?>
	 // based on whether cookie was set for initiating brochure download.. we start the download
	 if (isUserLoggedIn != false) {
	    <?php if($initiateBrochureDownload == 1 && $initiateCallback != 1) { ?>
	      $j("#downloadBrochureBellyLink").trigger("click");
	    <?php } else if($initiateCallback == 1) { ?>
	      $j("#requestCallbackButton").trigger("click");
	    <?php } ?>
	 }
	 
	  // create course viewed_listing response
      <?php if( $makeAutoResponse && !$reponse_already_created  ){
      ?>
	    var queryStringParameter = 'utm_medium';
	    var regularExp = new RegExp("[\\?&]" + queryStringParameter + "=([^&#]*)");
	    var queryStringSearchResults = regularExp.exec(location.search);
	    var isSourceMailer = queryStringSearchResults == null ? "" : decodeURIComponent(queryStringSearchResults[1].replace(/\+/g, " "));
	    var trackingKeyId ='';
	    if (isSourceMailer == 'email') {
		  action = 'reco_widget_mailer';
		  trackingKeyId = 704;
	    }
	    else {
		  action = 'Viewed_Listing';
		  trackingKeyId = 44;
	    }
      
	    makeAbroadAutoResponse('course',<?=$courseObj->getId()?>,action,trackingKeyId);
	    // To track using GA the time spent on the page by users who are creating viewed responses
	    pushCustomVariable("viewedResponseCourse/<?=$courseObj->getId()?>_PAGELEVEL");
      <?php    
      } ?>
	 
	 var img = document.getElementById('beacon_img');
	 var randNum = Math.floor(Math.random()*Math.pow(10,16));
	 img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011006/<?=$courseObj->getId()?>+<?=$listingType?>';
});
</script>
