		</div>
		<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >	
	<!--Course content ends here-->
	</div>	
</div>
<?php
   if($call_back_yes == 1):
   ?>
   <a  href="#callbackpopup" data-position-to="window" data-inline="true" data-rel="popup" id="callbacklink" data-transition="pop" ></a>
   <div data-role="popup" id="callbackpopup">
      <?php $this->load->view("contact-layer");?>
   </div>
   <?php
   endif;
   ?>
<?php
if($call_back_yes == 1):
?>
<script>
//call back patching  
$(document).ready(function() {
  $('#callbacklink').click();
});
</script>
<?php
endif;
?>
<!--------end----------->

<?php
$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>
<?php echo $phoneCallbackData; ?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.Placeholders"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<?php if($mmp_details['display_on_page'] == 'newmmpcourse') { ?>
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

var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id'];?>';
var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
var showpopup = '<?php echo $showpopup;?>';

if(mmp_form_id_on_popup != '') {
   
   if (mmp_display_on_page == 'listing') {  
   
	  $j(document).ready(function(){
		 registrationOverlayComponent.showOverlay('/customizedmmp/mmp/templateform/'+mmp_form_id_on_popup,800,260,'');
		 $('DHTMLSuite_modalBox_transparentDiv').style.height = $('DHTMLSuite_modalBox_transparentDiv').offsetHeight+2500+'px';
		 $j(window).bind("scroll",function() {makeOverlayFormSticky();});
	         setTimeout(enable_scroll,1000);
	  });
   
	  
   } else if(mmp_display_on_page == 'newmmpcourse') {
	  
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

function loadmmpform(){
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

function makeOverlayFormSticky () {
	
   if ($j(window).width() <= 800) {
	   return false;
   }
   
   var scrollTop = national_listings_obj.scrollTop();    
   var stickyDivId = 'DHTMLSuite_modalBox_contentDiv';
   console.log(scrollTop+"__"+$j('#'+stickyDivId).offset().top);
   if (scrollTop > $j('#'+stickyDivId).offset().top) {
	   
   $j('#DHTMLSuite_modalBox_contentDiv').css({
	   position : "fixed",
	   top: "10px"
   });
   
   }	

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

//Download e-brochure when redirecing from mailer

var queryStringParameter = 'download';
var regularExp = new RegExp("[\\?&]" + queryStringParameter + "=([^&#]*)");
var queryStringSearchResults = regularExp.exec(location.search);
var startBrochureDownload = queryStringSearchResults == null ? "" : decodeURIComponent(queryStringSearchResults[1].replace(/\+/g, " "));

if (startBrochureDownload == '1') {
   startDownload(<?php echo $course->getId(); ?>);
}
</script>


<!-- 
Changes to load Badges for Campus Reps.
Added by Rahul 
-->
<script>

   $j('.scrollbar').css("visibility", 'visible');
   $j('.scrollbar_h').css("visibility", 'visible');

	var floatingRegistrationSource = 'LISTING_DETAIL_PAGE_NEW_BOTTOM';
	var courseClientId = '<?=$course->getClientId();?>';
	if(typeof caPresent  != 'undefined' ) {
		loadBadges();
	}
</script> 	
	         
<script>
    var naukri_integration_data_base_url = "<?php echo '/listing/Naukri_Data_Integration_Controller/getDataForNaukriSalaryWidget/'.$course->getInstId().'/'.$course->getId()?>";
    var universal_selected_naukri_splz = '';
    var universal_number_of_funcional = 5;
    var universal_no_of_componies = 5;	
    var listings_with_localities = <?php echo $listings_with_localities; ?>;
    var page_load_happened = "no";
    if (typeof(reloadWidgetAskQuestionCity) != 'undefined' && reloadWidgetAskQuestionCity) {
        if ('<?=$pageType?>' == 'course') {
            $j.each(localityArrayAskQuestion,function(index,element){
                    custom_localities[index] = element;
            });
            reloadWidgetAskQuestion(reloadWidgetAskQuestionCity_widget);
        }
    }

    function getNaukriIntegrationWidget(splz,number_of_funcional,no_of_componies) {
    var url = naukri_integration_data_base_url;
		universal_selected_naukri_splz = splz;
		universal_number_of_funcional = number_of_funcional;
                universal_no_of_componies = no_of_componies;
		if(splz) {			
			url = naukri_integration_data_base_url +"/"+ base64_encode(splz)+"/"+number_of_funcional+"/"+no_of_componies; 
		} else {
			url = naukri_integration_data_base_url +"/0/"+number_of_funcional+"/"+no_of_componies; 
                } 	

    
		// load naukri data
		new Ajax.Request(url, {
				method : 'get',
				onSuccess : function(response) {
					 $('naukri_widget_data').innerHTML = base64_decode(response.responseText);
          ajax_parseJs($('naukri_widget_data'));
					evaluateCss($('naukri_widget_data'));
					if(window.location.hash == "#naukri_widget_data") {
					     if( jQuery("#alumni_side_widget").length > 0 && jQuery("#naukri_widget_data").length > 0) {
						  jQuery("#alumni_side_widget > a").click();
					     }
					}
					
					if($j("#naukri-charts").length > 0) {
					     $j("#alumni_side_widget").show();
					}
				}
		                }
		);

     }

 function initializeGraphData(){
  jQuery("#naukri_widget_data").hide();
  google.load("visualization", "1", {packages:["corechart"],"callback": drawChart});
  ajax_parseJs($('naukri_widget_data'));
  evaluateCss($('naukri_widget_data'));
  if(window.location.hash == "#naukri_widget_data") {
       if( jQuery("#alumni_side_widget").length > 0 && jQuery("#naukri_widget_data").length > 0) {
      jQuery("#alumni_side_widget > a").click();
       }
  }
  
  if($j("#naukri-charts").length > 0) {
       $j("#alumni_side_widget").show();
  }
 
 }    


    $j(document).ready(function()
    {        

    	setScrollbarForMsNotificationLayer();
      getNaukriIntegrationWidget('',5,5);
		if($j('#course_page_response_bottom').length > 0){
        	$j('#course_page_response_bottom').show();
        }
		if($j('#rebCrsPgBtm').length > 0){
        	national_listings_obj.responseFromCoursePageBottom('<?php echo $course->getId();?>');
        }
        if($j("#bebtechExmScrlr").length > 0)
        {
        	 $j("#bebtechExmScrlr").tinyscrollbar(); 
        }

	if (typeof($j("#new_course_page_right_sticky1")[0])!= 'undefined') {
            $j("#new_course_page_right_sticky1").show();
        }

	//hide if imp-info widget is not available
	if ($j(".insitute-criteria-box").length==0) {
		$j(".important-info-jump").hide();
	}
	
	//scroll to course name on load
	// scrollToSpecifiedElement('management-left');
	national_listings_obj.initializeJumpToNavigationWidget();
	//intialize scrollbar for mba's top widget
	$j(".soft-scroller").tinyscrollbar();
	
	// for scroll bar on eligibility	
        if($j("#scrollbar_eligibility").length >0)
	{
		var overviewWidth = $j('#scrollbar_eligibility').find('.overview_h').width();
                var horizontalScroll = false;
                if($j('#scrollbar_eligibility').find('.overview_h').find('table').length >0)
                {
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_eligibility').find('.overview_h').width();       
                }
                if($j('#scrollbar_eligibility').find('.overview_h').find('img').length >0)
                {
                        
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_eligibility').find('.overview_h').width();       
                }
                if( horizontalScroll && overviewWidth > 482)
                {       
                        $j('#scrollbar_eligibility').find('.viewport_h').width(482);
			$j('#scrollbar_eligibility').tinyscrollbar_h({ axis: "x",wheel: false});
                }
                else if(!horizontalScroll)
                { 
                        $j('#scrollbar_eligibility').find('.scrollbar_h').remove();
                        $j('#scrollbar_eligibility').find('.overview_h').width(482);
                        $j('#scrollbar_eligibility').find('.viewport_h').width(482+2);
                        $j('#scrollbar_eligibility').find('.overview_h').css({ 'word-wrap': 'break-word' });
                }

	        var heightOverview = $j('#scrollbar_eligibility').find(".overview").height();
		var heightViewport = 120;
		if(heightOverview > 240)
		{  
			heightViewport = heightOverview/2;
			$j('#scrollbar_eligibility').find(".viewport").height(heightViewport);
		}
		else if (heightOverview <120) {
			$j('#scrollbar_eligibility').find(".viewport").height(heightOverview);
		}
		
		$j("#scrollbar_eligibility").tinyscrollbar();
	}// for scroll bar on eligibility :END
	
	// for scroll bar on eligibility on default template
        if($j("#scrollbar_defaultPage").length >0)
	{
		var overviewWidth = $j('#scrollbar_defaultPage').find('.overview_h').width();
                var horizontalScroll = false;
                if($j('#scrollbar_defaultPage').find('.overview_h').find('table').length >0)
                {
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_defaultPage').find('.overview_h').width();       
                }
                if($j('#scrollbar_admissionProcedure').find('.overview_h').find('img').length >0)
                {
                        
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_defaultPage').find('.overview_h').width();       
                }
                if( horizontalScroll && overviewWidth > 482)
                {       
                        $j('#scrollbar_defaultPage').find('.viewport_h').width(482+2);
                }
                else if(!horizontalScroll)
                {
                        $j('#scrollbar_defaultPage').find('.overview_h').width(482);
                        $j('#scrollbar_defaultPage').find('.viewport_h').width(482+2);
                        $j('#scrollbar_defaultPage').find('.overview_h').css({ 'word-wrap': 'break-word' });
                }
		$j('#scrollbar_defaultPage').tinyscrollbar_h({ axis: "x",wheel: false});

		var heightOverview = $j('#scrollbar_defaultPage').find(".overview").height();
		var heightViewport = 120;
		if(heightOverview > 240)
		{  
			heightViewport = heightOverview/2;
			$j('#scrollbar_defaultPage').find(".viewport").height(heightViewport);
		}
		else if (heightOverview <120) {
			$j('#scrollbar_defaultPage').find(".viewport").height(heightOverview);
		}
		
		$j("#scrollbar_defaultPage").tinyscrollbar();
	}// for scroll bar on eligibility on default template :END
	
	// for scroll bar on admission procedure 
        if($j("#scrollbar_admissionProcedure").length >0)
	{
		var overviewWidth = $j('#scrollbar_admissionProcedure').find('.overview_h').width();
                var horizontalScroll = false;
                if($j('#scrollbar_admissionProcedure').find('.overview_h').find('table').length >0)
                {
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_admissionProcedure').find('.overview_h').width();       
                }
                if($j('#scrollbar_admissionProcedure').find('.overview_h').find('img').length >0)
                {
                        
                        horizontalScroll = true;
                        overviewWidth = $j('#scrollbar_admissionProcedure').find('.overview_h').width();       
                }
                if( horizontalScroll && overviewWidth > 482)
                {       
                        $j('#scrollbar_admissionProcedure').find('.viewport_h').width(482+2);
                }
                else if(!horizontalScroll)
                {
                        $j('#scrollbar_admissionProcedure').find('.overview_h').width(482);
                        $j('#scrollbar_admissionProcedure').find('.viewport_h').width(482+2);
                        $j('#scrollbar_admissionProcedure').find('.overview_h').css({ 'word-wrap': 'break-word' });
                }
		$j('#scrollbar_admissionProcedure').tinyscrollbar_h({ axis: "x",wheel: false});

		var heightOverview = $j('#scrollbar_admissionProcedure').find(".overview").height();
		var heightViewport = 120;
		if(heightOverview > 240)
		{  
			heightViewport = heightOverview/2;
			$j('#scrollbar_admissionProcedure').find(".viewport").height(heightViewport);
		}
		else if (heightOverview <120) {
			$j('#scrollbar_admissionProcedure').find(".viewport").height(heightOverview);
		}
		
		$j("#scrollbar_admissionProcedure").tinyscrollbar();
	}// for scroll bar on admission procedure : END

	$j('.sorting-options p span').click(function() {
		sortCollegeReviews(this,'<?php echo $course->getId();?>');
	});

    });
    
	$j(document).ready(function($j) {
	  
	  var pushIntoRNRListingPageVisitedBucket = '<?php echo $pushIntoRNRListingPageVisitedBucket;?>';
	  var listingPageInstituteId = '<?php echo $course->getInstId();?>';
	  if(pushIntoRNRListingPageVisitedBucket == "true"){
		 pushToRNRVisitedListingBucket(listingPageInstituteId);
	  }

		page_load_happened = "yes";	
		
                if(load_registration == 'true') {
			national_listings_obj.loadRegistrationWidgets();			
		} 
		
		$j(window).bind("scroll",national_listings_obj.nationalSticky);		
		$j('#new_course_page_right_sticky1').children().eq(0).children().eq(0).bind('click',national_listings_obj.toggleFirstStickyBox);
		//below line commented as the request e-brochure is moved from page load to ajax
		//national_listings_obj.initializeResponseWidget();
		disableAllCourseButtons(course_id);	
	});
    
	(function($j) {
	loadFormsOnListingPage(<?php echo $typeId; ?>,'<?php echo $pageType; ?>','<?php echo $_REQUEST['city']; ?>','<?php echo $_REQUEST['locality'];?>');
	})($j);

	$j(document).ready(function($j) {

if($j('#seeallbranches_layer_inline').length >0)
{
 var overviewHeight = $j('#seeallbranches_layer_inline').find(".overview").height();
        if(overviewHeight < 365)
     {  
         //no scroll condition  
        //increasing viewport height slightly to get no scroll condition and set height of viewport whatever  is of overview.
        // $j('#seeallbranches_layer_inline').find(".viewport").height(overviewHeight+2);
        	if($j('#seeallbranches_layer_inline').find("#outer_scrollbar_inline").length >0)
        	{ 	
        		$j('#seeallbranches_layer_inline').find("#outer_scrollbar_inline").remove();
        		$j('#seeallbranches_layer_inline').find("#viewport_outer_inline").removeClass("viewport");
        		$j('#seeallbranches_layer_inline').find("#overview_outer_inline").removeClass("overview");
           	}	
     }else
     {
        //scroll condition and fix viewport to 365 height.
         $j('#seeallbranches_layer_inline').find(".viewport").height(365);   
		 $j("#seeallbranches_layer_inline").tinyscrollbar();     
	}       
       
	 }


		$j('#shikshaAnalyticsForNationalPage').load('/listing/ListingPageWidgets/shikshaAnalyticsForNationalPage/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$pageType?>/<?=$updatedDate ?>/'+ "?rand=" + (Math.random()*99999),function() {
			<?php if($courseType == "paidCourse" ) {	?>
			if(typeof(responseCount)!='undefined' && responseCount >0) {
				if($j('#response_nationallistingPageBottomNational').length>0 ) {
					$j('#response_nationallistingPageBottomNational').html(responseCount+' students have downloaded brochure.');
				}
	
				if($j('#response_nationallistingPageRightNational').length>0 ) {
					$j('#response_nationallistingPageRightNational').html(responseCount+' students have downloaded brochure.');
				}
			}
			<?php } ?>
		});
		var img = document.getElementById('beacon_img');
		var randNum = Math.floor(Math.random()*Math.pow(10,16));
		img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$typeId?>+<?=$pageType?>';
	
	});
	if(typeof(showAlsoViewedInstitutesRecommendation) !== 'undefined' && showAlsoViewedInstitutesRecommendation == 1){
		if(recommendedInstitutesCount > 0){
			pushCustomVariable('LP_Reco_Load_AlsoViewed');
		}
		if(typeof(showSimilarInstitutesRecommendation) !== 'undefined' && showSimilarInstitutesRecommendation == 1){
			pushCustomVariable('LP_Reco_Load_SimilarInsti');
		}
	}
	
	if(typeof(onlineFormRecommendation) !== 'undefined' && onlineFormRecommendation== 1){
		//new Ajax.Request('/listing/ListingPage/onlineFormWidget/<?=$course->getId()?>/<?=$dominantSubcatData['dominant']?>', { method:'post', parameters: '', onSuccess:function (request){
		//	onlineFormResponse = eval('('+request.responseText+')');
		//	$j('#onlineFormReco').html(onlineFormResponse.recommendationHTML);
		
	}

</script>

<input type="hidden" name="getmeCurrentCourseCity" id="getmeCurrentCourseCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
<input type="hidden" name="getmeCurrentCourseLocaLity" id="getmeCurrentCourseLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>


<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinycarousel.min"); ?>"></script>

<script>

LazyLoad.loadOnce(['/public/js/<?php echo getJSWithVersion("jquery.royalslider.min"); ?>'],null);

  
                  
$j(document).ready(function($j) {

 var allPanels = new Array();
 var allPanelsTop = new Array();
 
 //this code is using for campusRep slider widget
 //@ akhter
 if($j('#inst-crWidgetSlider').length >0) {
	    $j('#inst-crWidgetSlider').tinycarousel({ display: 3 });
 }

 $j('#accordion').find(".course-accordian").each(function(index){

    var overViewWidth = $j(this).find('.overview_h').width();
    var horizontalScroll = false;
    if($j(this).find(".overview_h").find('table').length >0)
	{  
	    horizontalScroll = true;
	   overViewWidth = $j(this).find('.overview_h').width();
	}
	if($j(this).find('.overview_h').find('img').length >0)
    {	
	 horizontalScroll = true;
	 overViewWidth = $j(this).find('.overview_h').width();
	}
	if( horizontalScroll && overViewWidth > 630)
    {       
           $j(this).find('.viewport_h').width(630+2);
    }
    else if(!horizontalScroll)
    {
                   
           $j(this).find('.overview_h').width(630);
           $j(this).find('.viewport_h').width(630+2);
           $j(this).find('.overview_h').css({ 'word-wrap': 'break-word' });
    }

          
	if($j(this).find(".overview").height() > 200){
		$j(this).height(200);
		}
	else
	{  var heightOverview = $j(this).find(".overview").height();
		$j(this).height(heightOverview);	
	}
	});

	$j('.accordion_scrollbar').tinyscrollbar_h({ axis: "x",wheel: false});

 $j('.accordion_scrollbar').tinyscrollbar();
 allPanels = $j('#accordion').find("div.detail").hide();
 $j('#accordion').find("div.detail:first").show();
 allPanelsTop = $j('#accordion').find("h3");
 $j('#accordion').find("h3:first").css('cursor','default');
 $j('#accordion').find("h3:first").children('i').removeClass('up-arrow').addClass('dwn-arrow');
 $j('#accordion').find("h3").click(function() {
	 var currentClass = $j(this).children('i').attr("class");
		if(currentClass.indexOf('dwn-arrow') >= 0){
			return false;
		}
 allPanels.slideUp('fast');
$j(this).next().slideDown('slow',function(){

});

allPanelsTop.children('i').removeClass('dwn-arrow').addClass('up-arrow');

$j(this).children('i').removeClass('up-arrow').addClass('dwn-arrow');

allPanelsTop.css('cursor','pointer');
$j(this).css('cursor','default');
	 
 });
 
var makeViewedResponse = false;

<?php
   if( $makeAutoResponse && !$reponse_already_created  ){
?>
      makeViewedResponse = true;
<?php
   }
?>
      <?php if(is_array($validateuser)):?>
      if (makeViewedResponse || startBrochureDownload == '1') {
	 if(!(getCookie('<?php echo "applied_".$course->getId()?>') == 1 && isUserLoggedIn)){
		 var getmeCurrentCity = $('getmeCurrentCourseCity').value;
		 var getmeCurrentLocaLity = $('getmeCurrentCourseLocaLity').value;
		 var loggedUserFirstName = escape("<?php echo addslashes($validateuser[0]['firstname']); ?>");
		 var loggedUserLastName = escape("<?php echo addslashes($validateuser[0]['lastname']); ?>");
		 var loggedUserMobile = '<?php echo $validateuser[0]['mobile']; ?>';
		 var loggedUserEMail = '<?php if(!empty($validateuser[0]['cookiestr'])) { $a = $validateuser[0]['cookiestr']; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";}?>';
		 var reco_mailer_download=0;
		 if(startBrochureDownload == '1')
		 {
			 reco_mailer_download = 1;
		 }
		 makeAutoResponse(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,'',"'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'",reco_mailer_download,'<?php echo $viewedListingTrackingPageKeyId; ?>');
	 }
      }
     <?php endif;?>
});

$j('#photoVideoForNationalPage').load('/listing/ListingPage/nationalPagePhotoVideo/<?=$institute->getId()?>/true/<?php echo $course->getId();?>');


</script>

<!-- 
Scroll to Campus Connect Widget after page reloads
Added by Rahul 
-->
<script>
var hash = location.hash.replace("#","");
if(hash == 'ask-question'){
    $j('#ask_question_askAQuestion').focus();
    $('askQuestionFormDiv').scrollIntoView(false);
}
if(hash == 'photoVideoForNationalPage'){
    setTimeout(function(){$j('html, body').animate({scrollTop: $j('#photoVideoForNationalPage').offset().top}, 500)}, 4500);
}
if(hash == 'campus-connect-sec-id'){
    setTimeout(function(){$j('html, body').animate({scrollTop: $j('#campus-connect-sec-id').offset().top}, 500)}, 4500);
}
if(hash == 'naukri_widget_data'){
    setTimeout(function(){$j('html, body').animate({scrollTop: $j('#naukri_widget_data').offset().top}, 500)}, 4500);
}

if(hash == 'course-reviews'){
	if($j('#naukri_widget_data').length > 0){
		$j('#naukri_widget_data').before($j('#course-reviews').detach());
	}
    setTimeout(function(){$j('html, body').animate({scrollTop: $j('#course-reviews').offset().top}, 500)}, 1000);  	
}
if(hash == 'connect-wrapp'){
	 $j('html, body').animate({
	    scrollTop: $j('#connect-wrapp').offset().top 
	}, 500);
}

if(typeof(isfbGoogleAdWidgetAppearing)!='undefined' && isfbGoogleAdWidgetAppearing == 1) {
	var fbGoogleIntervalRepeatCount = 0; 
	var fbGoogleIntervalID = setInterval(showGoogleAdFBWidgetPosition, 2000);
	//setTimeout(showGoogleAdFBWidgetPosition, 4000);
}

// to load inline forms
$j(document).ready(function() {
   var func = window['regFormLoadlistingPageBottomNational'];
   if(typeof func === 'function') {
      regFormLoadlistingPageBottomNational();
   }
   var func = window['regFormLoadlistingPageRightNational'];
   if(typeof func === 'function') {
      regFormLoadlistingPageRightNational();
   }
   var func = window['regFormLoadaskAQuestion'];
   if(typeof func === 'function') {
      regFormLoadaskAQuestion();
   }
   var func = window['regFormLoadnewListingsAnABottom'];
   if(typeof func === 'function') {
     regFormLoadnewListingsAnABottom();
   }
});

$j(window).scroll(function() {
    clearTimeout($j.data(this, 'scrollTimer'));
    $j.data(this, 'scrollTimer', setTimeout(function() {
        //Check which all Review Divs are in Focus. Whichever of them are in focus, we will have to make a view call at Backend.
        //Also, this call should not happen for Same session-review pair / Same user-review pair
        $j("div[id^='reviewNo']").each (function(ind){
                if( isElementInViewport ($j(this)) && $j(this).css('display')!="none" ){
                        divId =  $j(this).attr('id');
                        res = divId.split('No');
                        reviewId = res[1];
                        //Check if this Review Id has not been added already
                        if(jQuery.inArray(reviewId,reviewsAdded) == -1){
                                //Make Ajax Call to mark this review as Read by this user
                                markReviewRead(reviewId, 'desktop', 'courseDetailPage');
                                //Add in Div array
                                reviewsAdded.push(reviewId);
                        }
                }
        });
        console.log("Haven't scrolled in 5sec!");
    }, collegeReviewScrollTimer));
});

</script>
