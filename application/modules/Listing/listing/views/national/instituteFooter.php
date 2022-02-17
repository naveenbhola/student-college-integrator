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
$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>
<input type="hidden" name="getmeCurrentCourseCity" id="getmeCurrentCourseCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
<input type="hidden" name="getmeCurrentCourseLocaLity" id="getmeCurrentCourseLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinycarousel.min"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.Placeholders"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>

<?php if($mmp_details['display_on_page'] == 'newmmpinstitute') { ?>
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
<?php
if($call_back_yes == 1):
?>
<script>
//call back patching  
$(document).ready(function() {
    //alert('1');
//$('#callbackpopup').html(call_back_data);
//document.getElementById('callbackpopup').innerHTML = call_back_data;
$('#callbacklink').click();
});
</script>
<?php
endif;
?>

<script>
var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id'];?>';
var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
var showpopup = '<?php echo $showpopup;?>';

if(mmp_form_id_on_popup != '') {

if(mmp_display_on_page == 'newmmpinstitute') {
      
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
$j('.scrollbar').css("visibility", 'visible');
$j('.scrollbar_h').css("visibility", 'visible');

LazyLoad.loadOnce([
                                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.royalslider.min");?>',
                                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.actual.min");?>',
                                    ],null);

            var listings_with_localities = <?php echo $listings_with_localities; ?>;
            (function($j) {
            loadFormsOnListingPage(<?=$typeId?>,'<?=$pageType?>','<?=$_REQUEST['city']?>','<?=$_REQUEST['locality']?>');
    })($j);
    $j(document).ready(function($j) {

        //this code is using for campusRep slider widget
        //@ akhter
	  if($j('#inst-crWidgetSlider').length >0) {
			   $j('#inst-crWidgetSlider').tinycarousel({ display: 3 });
      }
		
            if (typeof($j("#new_institute_page_right_sticky1")[0])!= 'undefined') {
                    $j("#new_institute_page_right_sticky1").show();
                }
        
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

		//scroll to institute name on load
		// scrollToSpecifiedElement('management-left');

                national_listings_obj.initializeJumpToNavigationWidget();
                
		if(ICS_defaultMainCatOpen && ICS_defaultSubCategoryId) {
			setDynamicScrollBarForCourseBrowseTab(ICS_defaultSubCategoryId);
		}
                 /* Wiki Section :Starts */
                 if( $j('.accordion-ins-wiki').length >0)
            {
    		 var allPanels = new Array();
    		 var allPanelsTop = new Array();
    		 $j('.accordion-ins-wiki').find(".ins-detl").each(function(index){
					
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
             if( $j('.accordion_scrollbar').length >0)
             {
				$j('.accordion_scrollbar').tinyscrollbar_h({ axis: "x",wheel: false,trackSize: 645});
    			 $j('.accordion_scrollbar').tinyscrollbar();
             }
    		 allPanels = $j('.accordion-ins-wiki').find("div.detail").hide();
    		 $j('.accordion-ins-wiki').find("div.detail:first").show();
    		 allPanelsTop = $j('.accordion-ins-wiki').find("h3");
    		 $j('.accordion-ins-wiki').find("h3:first").css('cursor','default');
    		 $j('.accordion-ins-wiki').find("h3:first").children('i').removeClass('up-arrow').addClass('dwn-arrow');
    		 $j('.accordion-ins-wiki').find("h3").click(function() {
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
           }
           /*Wiki Section :Ends */
               
				if($j('#inst-slider').length >0) {
            		$j('#inst-slider').tinycarousel({ display: 2 });
                   }
                $j('#shikshaAnalyticsForInstituteNationalPage').load('/listing/ListingPageWidgets/shikshaAnalyticsForNationalPage/<?=$institute->getId()?>/<?=$course->getId()?>/<?=$pageType?>/<?=$updatedDate ?>/'+ "?rand=" + (Math.random()*99999),function() {
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
                
                $j('#new_institute_page_right_sticky1').children().eq(0).children().eq(0).bind('click', institute_national_listings_obj.toggleFirstStickyBoxInstitutePage);
                
                $j(window).bind("scroll",institute_national_listings_obj.nationalStickyBehaviour);
                institute_national_listings_obj.initializeResponseWidgetForInstitutePage();
				/*load registration widgets on institute pages*/
				page_load_happened = "yes";	
				
		                if(load_registration == 'true') {
					institute_national_listings_obj.loadRegistrationWidgets();			
				} 
                //  code for making institute_viewed response for institute page
                <?php
                        if( $makeAutoResponse && !$reponse_already_created  ){
                ?>
                        if(!(getCookie('<?php echo "applied_".$course->getId()?>') == 1 && isUserLoggedIn)){
                
                                var getmeCurrentCity = $('getmeCurrentCourseCity').value;
                                var getmeCurrentLocaLity = $('getmeCurrentCourseLocaLity').value;
                                var loggedUserFirstName = escape("<?php echo addslashes($validateuser[0]['firstname']); ?>");
                                var loggedUserLastName = escape("<?php echo addslashes($validateuser[0]['lastname']); ?>");
                                var loggedUserMobile = '<?php echo $validateuser[0]['mobile']; ?>';
                                var loggedUserEMail = '<?php if(!empty($validateuser[0]['cookiestr'])) { $a = $validateuser[0]['cookiestr']; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";}?>';
                                makeAutoResponseForInstitutePage(loggedUserFirstName,loggedUserLastName,loggedUserMobile,loggedUserEMail,<?=$institute->getId()?>, '<?=html_escape($institute->getName())?>',<?=$course->getId()?>,'',"'"+getmeCurrentCity+"'","'"+getmeCurrentLocaLity+"'", '<?php echo $instituteViewedTrackingPageKeyId;?>');
                        }
                <?php
                        }
                ?>
              
        });
        
		if(typeof(showAlsoViewedInstitutesRecommendation) !== 'undefined' && showAlsoViewedInstitutesRecommendation == 1){
			new Ajax.Request('/listing/ListingPage/alsoOnShiksha/<?=$course->getId()?>/institute', { method:'post', parameters: '', onSuccess:function (request){
				alsoViewedResponse = eval('('+request.responseText+')');
				$j('#alsoOnShiksha').html(alsoViewedResponse.recommendationHTML);
				alsoViewedRecommendedInstitutes = alsoViewedResponse.recommendedInstitutes.join(',');
				
				
				if (alsoViewedResponse.recommendedInstitutes.length) {
					//pageTracker._setCustomVar(1, "GATrackingVariable", 'LP_Reco_Load_AlsoViewed', 1);
					//pageTracker._trackPageview();
					pushCustomVariable('LP_Reco_Load_AlsoViewed');
				}
			if(typeof(showSimilarInstitutesRecommendation) !== 'undefined' && showSimilarInstitutesRecommendation == 1){ 
				new Ajax.Request('/listing/ListingPage/similarOnShiksha/<?=$course->getId()?>/<?php echo intval($_REQUEST['city']); ?>/'+(alsoViewedRecommendedInstitutes == '' ? "''" : alsoViewedRecommendedInstitutes)+'<?php if($pageSubcategoryId) { echo '/'.$pageSubcategoryId; } else { echo '/0' ;}?>/institute', { method:'post', parameters: '', onSuccess:function (srequest){
					similarResponse = eval('('+srequest.responseText+')');
					$j('#similarOnShiksha').html(similarResponse.recommendationHTML);
					
					//Commented out GA Tracking
					
					if (similarResponse.recommendedInstitutes.length) {
						//pageTracker._setCustomVar(1, "GATrackingVariable", 'LP_Reco_Load_SimilarInsti', 1);
						//pageTracker._trackPageview();
						pushCustomVariable('LP_Reco_Load_SimilarInsti');
					}
				}});
			}
			}});
		}	


if(typeof(isfbGoogleAdWidgetAppearing)!='undefined' && isfbGoogleAdWidgetAppearing == 1) {
		var fbGoogleIntervalRepeatCount = 0; 
		var fbGoogleIntervalID = setInterval(showGoogleAdFBWidgetPosition, 2000);
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
  var func = window['regFormLoadnewListingsAnABottom'];
  if(typeof func === 'function') {
    regFormLoadnewListingsAnABottom();
  }
  if (course_browse_section_data_flag) {
   $j( "div[id^='cds_scroll_']").hide();
  }else {
   $j( "div[id^='cds_scroll_']").not(':first').hide();
  }  
});
</script>
