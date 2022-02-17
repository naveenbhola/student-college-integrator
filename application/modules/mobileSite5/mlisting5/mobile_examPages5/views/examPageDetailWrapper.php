<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;">	
<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
      echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>
<?php $this->load->view('mobile_examPages5/examPageHeader'); ?>
<?php $sectionNamesMapping = $this->config->item("sectionNamesMapping");?>
<?php foreach($examPageData->getHompageData() as $key=>$value){
		if($value->getLabel()=='Exam Title'){
			$examName = 	$value->getDescription();
		}
}
?>
<div data-role="content">
	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
		<!----subheader--->
	       <?php $this->load->view('mobile_examPages5/examPageSubHeader');?>
		<!--end-subheader-->
	<div>
<section class="content-wrap2 clearfix">
	<?php if($pageType == 'home'){?>
	<h1 class="exam-name"><?php echo $examName;?> <br>
	</h1>
<?php }else{?>
	<strong class="exam-name"><?php echo $examName;?> </strong>
<?php }?>
    <?php
    	$tracking_keyid_topWidget = 0;
		$tracking_keyid_bellyWidget = 0;
	switch ($pageType) {
		case "home" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_HOME_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_HOME_BELLY_REG; 
			$this->load->view ( 'mobile_examPages5/examPageTileNavigation' );
			$this->load->view ( 'mobile_examPages5/examPageHomeContent' );
			break;
		case "imp_dates" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_IMP_DATES_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_IMP_DATES_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/examPageImportantDateContent' );
			break;
		case "syllabus" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_SYLLABUS_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_SYLLABUS_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/examPageSyllabusContent' );
			break;
		case "results" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_RESULTS_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_RESULTS_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/examPageResults' );
			break;
		case "article" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_NEWS_N_ARTICLE_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_NEWS_N_ARTICLE_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/articlePageWrapper' );
			break;
		case "preptips" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_PREPTIP_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_PREPTIP_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/prepTipPageWrapper' );
			break;
		case "discussion" :
			$tracking_keyid_topWidget = MOBILE_NL_EXAM_PAGE_DISCUSSION_TOP_REG;
			$tracking_keyid_bellyWidget = MOBILE_NL_EXAM_PAGE_DISCUSSION_BELLY_REG;
			$this->load->view ( 'mobile_examPages5/examPageDiscussionMainForMobile' );
			//$this->load->view ( 'mobile_examPages5/examPageDiscussionContent' );
			break;
		default :
			//$this->load->view ( 'mobile_examPages5/examPageMenu' );
			$this->load->view ( 'mobile_examPages5/examPageHomeContent' );
	}
	
	global $user_logged_in;
	if($user_logged_in!='true'){?>
	<div class="btn-section login-btn">
	<a onclick = "trackEventByGAMobile('HTML5_REGISTRATION_<?php echo $pageType;?>_<?php echo $examPageData->getExamName();?>');
	var formData = {
		        'trackingKeyId': '<?php echo $tracking_keyid_topWidget; ?>',
		        'callbackFunctionParams': {'subscribeForExam' : '<?php echo $examPageData->getExamName(); ?>', 'examStream' : '<?php echo $streamCheck; ?>','mbaStream':'<?php echo MANAGEMENT_STREAM ?>','mbaCourse':'<?php echo MANAGEMENT_COURSE ?>','enggStream':'<?php echo ENGINEERING_STREAM ?>','enggCourse':'<?php echo ENGINEERING_COURSE ?>','educationType':'<?php echo FULL_TIME_MODE ?>','trackingKeyId':'<?php echo $tracking_keyid_topWidget ?>'},
		        'callbackFunction': 'examPageRegnCallback',
		        'submitButtonText': '',
		        'httpReferer': ''
		    	};registrationForm.showRegistrationForm(formData);" class="l-btn"><?php echo $regWigData;?></a>
	</div>
	<?php } ?>
	</section>

	<?php 
		if(!in_array($pageType, array("article", "results")))
			$this->load->view("widgets/newsArticleSliderWidget"); 
	?>

<section class="content-wrap2 clearfix">
	
	<?php echo Modules::run('mobile_examPages5/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
        <?php
        if($user_logged_in!='true'): ?>
            <h2 style="font-weight:normal;"><a onclick = "trackEventByGAMobile('HTML5_REGISTRATION_<?php echo $pageType;?>_<?php echo $examPageData->getExamName();?>');var formData = {
		        'trackingKeyId': '<?php echo $tracking_keyid_bellyWidget; ?>',
		        'callbackFunctionParams': {'subscribeForExam' : '<?php echo $examPageData->getExamName(); ?>', 'examStream' : '<?php echo $streamCheck; ?>','mbaStream':'<?php echo MANAGEMENT_STREAM ?>','mbaCourse':'<?php echo MANAGEMENT_COURSE ?>','enggStream':'<?php echo ENGINEERING_STREAM ?>','enggCourse':'<?php echo ENGINEERING_COURSE ?>','educationType':'<?php echo FULL_TIME_MODE ?>','trackingKeyId':'<?php echo $tracking_keyid_bellyWidget ?>'},
		        'callbackFunction': 'examPageRegnCallback',
		        'submitButtonText': '',
		        'httpReferer': ''
		    	};registrationForm.showRegistrationForm(formData);" href="javascript:void(0);" class="imp-date-btn">Get Important Updates on <?php echo $examPageData->getExamName();?></a></h2>
        <?php endif;?>
               
</section>
</div>
<?php $this->load->view('/mcommon5/footerLinks'); ?>
</div>
<?php $this->load->view ( 'mobile_examPages5/examPageMenu' );?>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<script async src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>";></script>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.zebra.owlSlider.min","nationalMobileVendor"); ?>"></script>

<?php $this->load->view('/mcommon5/footer');?>
<script>
<?php if($pageType=='home' || $pageType=='syllabus' || $pageType=='results') { ?>
$(document).ready(function(){
	if(typeof($.Zebra_Accordion) == 'function'){
		$.Zebra_Accordion('.examDetails',{
		'hide_speed' : 1,
		'show_speed' : 1,
		'scroll_speed': 1,
		'onClose' : function(id){ $('#desc'+id).addClass('exam-plus-icon');$('#desc'+id).removeClass('exam-minus-icon');},
		'onOpen' : function(id){$('#desc'+id).addClass('exam-minus-icon');$('#desc'+id).removeClass('exam-plus-icon');}
        });	
	}
});
<?php } ?>
	$(document).ready(function(){
	   $('table').each(function() {
		    var element = $(this);
		    // Create the wrapper element
		    var scrollWrapper = $('<div />', {
		        'class': 'scrollable',
		        'html': '<div />' // The inner div is needed for styling
		    }).insertBefore(element);
		    // Store a reference to the wrapper element
		    element.data('scrollWrapper', scrollWrapper);
		    // Move the scrollable element inside the wrapper element
		    element.appendTo(scrollWrapper.find('div'));
		    // Check if the element is wider than its parent and thus needs to be scrollable
		    if (element.outerWidth() > element.parent().outerWidth()) {
		        element.data('scrollWrapper').addClass('has-scroll');
		    }
		    // When the viewport size is changed, check again if the element needs to be scrollable
		            element.data('scrollWrapper').addClass('has-scroll');
		});
 		
 		if(typeof(initializeNewsArticleWidget) == 'function'){
  			initializeNewsArticleWidget("<?php echo $newsArticleWidgetData['initializeWidgetName']?>");
 		}

   		if($('.examDetails').length <= 1){
   			$('.Zebra_Accordion_Expanded > .exam-minus-icon').hide();
   		}
	});
</script>
