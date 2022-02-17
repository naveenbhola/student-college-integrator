</div>

<div id="naukri_data_container" style="display: none;z-index:1001;width:700px;height: 520px;background: white;position: absolute;padding: 15px;border: 2px solid #808080;">
    <a onclick="hideNaukriDataOverlay(); return false;" href="#" title="Close" class="common-sprite close flRt"></a>
    <div id="naukri_widget_data"> </div>
</div>
<div class="abroad-layer" id="overlayContainer" style="display: none;">
	<div class="abroad-layer-head clearfix">
    	<div class="abroad-layer-logo flLt"><i alt="shiksha.com" class="layer-logo"></i></div>
        <a href="JavaScript:void(0);" onclick="hideAbroadOverlay();" title="close" class="common-sprite close-icon flRt"></a>
    </div>
    
    <div class="abroad-layer-content clearfix">
    	<div class="abroad-layer-title" id="overlayTitle"></div>
		<div id="overlayContent"></div>
    </div>
</div>

<?php 
$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
?>
<script>
	var RANKING_PAGE_MODULE = "rankingV2";
        var isShowGlobalNav = false;
</script>
<script src="//<?php echo JSURL; ?>/public/js/jquery.lazyload.min.js"></script>
<script type="text/javascript" src="//www.google.com/jsapi"></script>

<?php if($mmp_details['display_on_page'] == 'newmmpranking') { ?>
	
	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
	<!-- <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('nationalCourses'); ?>" type="text/css" rel="stylesheet" /> -->
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
		
		if(mmp_display_on_page == 'newmmpranking') {
		
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
// var printRankingCSSURL = "//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('ranking-print'); ?>";

$j(document).ready(function($j) {
bindHoverEventOnRankingRows();
// bindClickEventOnRankNumbers();
// setScrollbarForMsNotificationLayer();
initializeTinyScrollBar();
document.cookie="categoryWidgetRanking=hide";
document.cookie="categoryWidgetFlag=0";

$j("body").bind("click",function(event){
	if(($j(event.target).closest(".locationDropdownLayer").length == 0) && ($j(".locationDropdownLayer").is(':visible')))
	{
		hideLocationDropDown();
	}
	if(($j(event.target).closest(".specialisationDropdownLayer").length == 0) && ($j(".specialisationDropdownLayer").is(':visible')))
	{
		hideSpecialisationDropDown();
	}
	if(($j(event.target).closest(".examDropdownLayer").length == 0) && ($j(".examDropdownLayer").is(':visible')))
	{
		hideExamDropDown();
	}
	if($j(".compare-bot-sticky").is(':visible')){
		    $j('#ranking_category_sticky_widget').hide();
		}
  	
});

makeRankingTableHeaderSticky();
$j(document).scroll(function(){
	
	makeRankingTableHeaderSticky();
	makeCategoryWidgetSticky();
   });

	// showTooltipOnRankHover();
    bindEventOnDocumentKeyUp();
    myCompareObj = new myCompareClass();
    myCompareObj.setRemoveAllCallBack('rankingCompareCallback');
    initializeComparedTuples();
});
</script>
<script>
var destination_url = '<?php echo $mmpData['mmp_details']['destination_url']; ?>';
var mmp_form_id_on_popup = '<?php echo $mmpData['mmp_details']['page_id'];?>';
var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';

if(mmp_form_id_on_popup != '') {
    var customFields = {'mmpFormId':mmp_form_id_on_popup};
    var formData = {
        'trackingKeyId' : '<?php echo $mmpData['trackingKeyId'];?>',
        'customFields':customFields,
        'callbackFunction':'registrationFromMMPCallback',
        'submitButtonText':'<?php echo $mmpData['submitButtonText'];?>',
        'httpReferer':'',
        'formHelpText':'<?php echo $mmpData['customHelpText'];?>'
    };
    registrationForm.showRegistrationForm(formData);
	function registrationFromMMPCallback() {
        if(destination_url != '') {
            window.location = destination_url;
        }  else {
            window.location = JSURL;
        }
    }
}
searchCompareCTAEventAttach();
</script>