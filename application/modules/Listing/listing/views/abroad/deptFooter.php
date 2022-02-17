<?php $footerComponents = array(
			   'js'=>array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
			   'asyncJs'=>array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents); ?>

<script crossorigin src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("studyAbroadListings"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>

<script>
$j(document).ready(function($j) {
    var scrollbars = ['scrollbar_deptHighlights', 'scrollbar_'+defaultActive];
    applyScrollBar(scrollbars);
    
    $j("#email-btn").mouseenter(function(e){
	    $j(this).stop();
            $j(this).animate({width:'225px'}, 500);
    });
    $j("#email-btn").mouseleave(function(e){
      $j(this).stop();
          $j(this).animate({width:'40px'}, 500);
    });
    // to facilitate continuation of download-brochure flow when user explicitly logs in
    if(initiateBrochureDownload == 1)
    {
          $j('#email-btn').trigger('click'); // to start download brochure
    }
    // for inline brochure form
    if (typeof(FormIdBottom)  != 'undefined') {
	if (!shikshaUserRegistrationForm[FormIdBottom]) {
	    shikshaUserRegistrationForm[FormIdBottom] = new ShikshaUserRegistrationForm(FormIdBottom);
	}
	shikshaUserRegistrationForm[FormIdBottom].twostepCountryDivTriggerInlineBrochure();
    }
    // for inline registration form
    $j("#twoStepCountrySelectInlineBrochure").one( "click", function() {
	$j(".courseCountryScrollbarHeight").height("135px");
	applyScrollBar(scrollbarId);
    } );
    // based on whether cookie was set for initiating brochure download.. we start the download
	  if (isUserLoggedIn != false) {
	    <?php if($initiateBrochureDownload == 1 && $initiateCallback != 1) { ?>
	      $j("#downloadBrochureBellyLink").trigger("click");
	    <?php } else if($initiateCallback == 1) { ?>
	      $j("#requestCallbackButton").trigger("click");
	    <?php } ?>
	  }
    // to prevent conflict with regular login
    $j(".close-icon").click(function(){engageDownloadBrochureWithLogin = 0;});
    // to hide request callback tooltip
    setTimeout(function(){ $j("#requestCallbackHelptext").fadeOut(3000); },15000);
    // To fix the UI of the consultant widget
    if (document.getElementById('listingConsultantWidgetVisibilityDiv') != null) {
	$j("#listingConsultantWidgetVisibilityDiv").children("ul").each(
	    function(){
		$j(this).children(":last").addClass("noBrder-bottom");
	    }
	);
    }
    if (typeof(loggedInUserCity) !='undefined') {
	    showConsultantLayerForm(FormIdBottom,loggedInUserCity);  
      }
});
</script>
<script>
var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011006/<?=$departmentObj->getId()?>+institute';
</script>
