</div>

<!--Exam Page Ends-->
<?php
	$this->load->view ( 'common/footerNew', array (
			'loadJQUERY' => 'YES' 
	) );
?>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('examPages'); ?>"></script>
<?php if($mmp_details['display_on_page'] == 'newmmpexam') { ?>
	<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('nationalCourses'); ?>" type="text/css" rel="stylesheet" />
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('newmmp'); ?>" type="text/css" rel="stylesheet" />
<?php } ?>


<?php if($mmp_details['page_id'] != '') { ?>


<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: none; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

<div id="mmpOverlayForm" class="Overlay" style="display:none; position: fixed; top:20px;"></div>

<style>
    html.noscroll {
    position: fixed; 
    overflow-y: scroll;
    width: 100%;
}    
</style>

<script>
	var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id']?>';
	var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
	var showpopup = '<?php echo $showpopup;?>';

	if(mmp_form_id_on_popup != '') {
	
		if(mmp_display_on_page == 'newmmpexam') {
	
			var mmp_form_heading = '<?php echo $mmp_details['form_heading']?>';
			var displayName = '';
			var user_id = '';
			
			<?php
			if(is_array($validateuser)) {?>
			   displayName = escape("<?php echo addslashes($validateuser[0]['displayname']); ?>");
			   user_id = '<?php echo $validateuser[0]['userid'];?>';
			<?php }  ?>
			
			$j(document).ready(function(){
				disable_scroll();
				setTimeout(loadmmpform,1000);
			});
		}
		
	}
	
	function loadmmpform() {
		var form_data = '';
		form_data += 'mmp_id='+mmp_form_id_on_popup;
		form_data += '&mmp_form_heading='+mmp_form_heading;
		form_data += '&isUserLoggedIn='+isUserLoggedIn;
		form_data += '&displayName='+displayName;
		form_data += '&user_id='+user_id;
		form_data += '&mmp_display_on_page='+mmp_display_on_page;
		form_data += '&exam_name='+'<?php echo $examName;?>';
		form_data += '&showpopup='+showpopup;

		$j.ajax({
			url: "/registration/Forms/loadmmponpopup",
			type: 'POST',
			async:false,
			data:form_data,
			success:function(result) {
				showMMPOverlay('530','860','',result);
				ajax_parseJs($('mmpOverlayForm'));
				setTimeout(enable_scroll,1000);
			}
		});
	}
 
    function showMMPOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
            
        if(trim(overlayContent) == '')
                return false;
        
        var body = document.getElementsByTagName('body')[0];
        
        $('iframe_div1').style.height = body.offsetHeight+'px';
        $('iframe_div1').style.width = body.offsetWidth+20+'px';
		$('iframe_div1').style.display = 'block';            
        
        $('mmpOverlayForm').innerHTML = overlayContent;
        $('mmpOverlayForm').style.width = overlayWidth + 'px';
        $('mmpOverlayForm').style.height = overlayHeight + 'px';

        var divX;                
        if(typeof left != 'undefined') {
           divX = left;
        } else {
           divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
        } 

        $('mmpOverlayForm').style.left = divX + 'px';
        $('mmpOverlayForm').style.top =  '20px';

        overlayHackLayerForIE('mmpOverlayForm', body);
        $('mmpOverlayForm').style.display = 'block';
    }

</script>

<?php } ?>

<script>
    $j(document).ready(function() {
        // put all your js code inside this function(examPages.js)
        setScrollbarForMsNotificationLayer();
       <?php if($validateuser == 'false') { ?>
            var formLength = regFormLoad.length;
            for(var i=0; i<formLength;i++) {
                window[regFormLoad[i]]();
            }
        <?php } ?>
	    if(typeof($j('._exam-hm-scroll')) !='undefined'){
	    	$j('._exam-hm-scroll').on('click',function(){
	    		scrollToSection('wiki-sec-0');
	    	});
	    }
    });
	
	/*
	 * PARAMS:
	 * 		currentPageName => eg. EXAM_PAGE_CAT, EXAM_PAGE_MAT or EXAM_PAGE_XAT, etc.
	 * 		eventAction => eg. viewContactNumber, resetAllFilters or SortByFeesAsc, etc.
	 * 		opt_eventLabel(optional) => eg. add, delete, show, hide, enable or disable, etc.
	 * 		opt_value(optional) => Some numeric value eg. 5 or 10.4, etc.
	 * 		opt_noninteraction => true (when event is hit, it will not be used in bounce-rate calculation) or false (vice-versa)
	 */
	function examPageTrackEventByGA(currentPageName, eventAction, opt_eventLabel, opt_value, opt_noninteraction) {
		//set optional variables in case they are undefined
		if(typeof(opt_eventLabel) == 'undefined') {
			opt_eventLabel = "";
		}
		if(typeof(opt_value) == 'undefined') {
			opt_value = 0;
		}
		if(typeof(opt_noninteraction) == 'undefined') {
			opt_noninteraction = true; //by default _trackEvent consider it as false
		}
		if(typeof(pageTracker)!='undefined') {
			pageTracker._trackEventNonInteractive(currentPageName, eventAction, opt_eventLabel, opt_value, opt_noninteraction);
		}
		
		return true;
    }
	
	/*
	 * Trackings on page load
	 */
	
	//set tracking params
	var gaTrackCategory 	 = '<?=$examPageData->getCategoryName()?>';
	var gaTrackExamName 	 = '<?=$trackMainExamName?>';
	var gaTrackSection	 	 = '<?=$pageType?>';
	
	var gaTrackSimilarExams = new Array();
	<?php foreach($similarExams as $exam) { ?>
		//gaTrackSimilarExams.push("<?=$exam['exam_name']?>");
	<?php } ?>
	
	if(typeof pageTracker != "undefined") {
		var trackingParam = gaTrackCategory + "/" + gaTrackExamName + "/" + gaTrackSection;
		
		//show message -- ga tracking started
		var object = new showTrackingMessage("GA tracking Started tracking for " + trackingParam);
		logJSErrors(object);
		
		//set session(2) tracking on key 1 to send category, exam name and section of the exam page loaded
		pageTracker._setCustomVar(1, "NationalExamPageTrack", trackingParam, 2);
		pageTracker._setCustomVar(5, "NationalExamPageTrack", trackingParam, 3);
		pageTracker._trackEventNonInteractive('dummyExamPageTracking', gaTrackCategory, gaTrackExamName + '-' + gaTrackSection, 0, true);
		
		//set event tracking for similar exam page widget (to know how many times each exam has appeared on similar exam widget)
		for(var i = 0; i < gaTrackSimilarExams.length; i++) {
			//examPageTrackEventByGA('NATIONAL_EXAM_PAGE_' + gaTrackExamName, 'similar_exam_appeared', gaTrackSimilarExams[i]);
		}
		
		//show message -- ga tracking ended
		var object = new showTrackingMessage("GA tracking Ended tracking for " + trackingParam);
		logJSErrors(object);
	}
	
	//to show tracking message (for visibility/debug purpose)
	function showTrackingMessage(message) {
		this.message = message;
		this.browserInfo = window.navigator.appCodeName;
	}
</script>
<?php $this->load->view('common/newMMPForm'); ?>