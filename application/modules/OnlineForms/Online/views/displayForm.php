<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','user','onlineForms'),
				      'title'	=>	'',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'',	
				      'metaKeywords'	=>'',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHeader' => true
				   );

   $this->load->view('common/header', $headerComponents);
   $this->load->view('common/calendardiv');

   $inst_id = $instituteInfo[0]['instituteInfo'][0]['institute_id'];
?>


<div id="appsFormWrapper">

	<link rel="stylesheet" media="print" type="text/css" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion("print"); ?>" />
	<!--This Header code is only for Print Form-->
    <div class="shikshaAppsHeader" id="shikshaAppsHeaderPrint" style="padding:0 0 15px 0; display:none;">
            <h1 class="logo">
                <a style="background:none; text-indent:0; float:left" href="<?php echo $GLOBALS['SHIKSHA_ONLINE_FORMS_HOME']; ?>" title="Shiksha.com">
                    <img src="//<?php echo CSSURL;?>/public/images/nshik_ShikshaLogo1.gif" alt="Shiksha.com" /></a>
            </h1>
            <div class="customerSupport">
            <div class="figure" style="background:none"><img src="//<?php echo CSSURL;?>/public/images/customer-care-girl.gif" alt="Shiksha.com" /></div>
            <div class="details">
                <p>
                	For online form Assistance<br />
                    <span>Call : 011-4046-9621</span>
                    (between 09:30 AM to 06:30 PM, Monday to Friday)
                </p>
            </div>
            </div>
        </div>
	<div class="clearFix"></div>
	<!--<div class="print"><a title="Print Form" onclick="window.print();" href="javascript:void(0);"><span></span><b>Print Form</b><strong></strong></a></div>-->
        <div class="downloadPDF" style="width: 202px;"><a title="Download PDF and Print" href="<?php echo SHIKSHA_HOME;?>/Online/OnlineForms/createPDFFORUser/user/<?php echo $courseId;?>"><b>Download PDF and Print</b><strong></strong></a><span></span></div>

	<!--Starts: breadcrumb-->
    <?php $this->load->view('Online/showBreadCrumbs'); ?>
    <!--Ends: breadcrumb-->

    <div id="appsFormInnerWrapper" style="padding : 10px 0 0 0;">

    <!--Contents Starts here-->
    <div id="contentWrapper">

	<?php if($courseId>0){ ?>
	<?php
	    // Display the Institutes custom view form data
	    $this->load->view('Online/FormTemplate/course'.$courseId);
		
		if($showEdit == 'true') {
	?>
	<div class="buttonBlock buttonWrapper">
				<?php if($formStatus == 'started' || $formStatus == 'uncompleted' || $formStatus == 'completed') { ?>
            	<input type="button" value="Update and Submit" class="orangeButton" onClick="window.location.href='/Online/OnlineForms/showOnlineForms/<?php echo $courseId;?>';"/> &nbsp;
				<?php } ?>
				<input type="button" value="Exit to dashboard" class="orangeButton" onClick="window.location.href='/studentFormsDashBoard/StudentDashBoard/index';"/> &nbsp;
         </div>
	 <?php } } ?>

    </div>
    <!--Contents Ends here-->
	
    <div class="clearFix"></div>
   </div>
</div>

<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('Online/footerOnline',$bannerProperties1);
?>

<?php if($generate=='pdf'){?>
<style>
body{background:#fff}
#wrapperMainForCompleteShiksha .topBar, #top-bar, #logo-section, .shikshaAppsHeader,#breadcrumb,#footerText, #headerGradienthome,#header,#loginCommunication, .jser,#enableCookieMsg,.print, .entHeader,.tabBorder, .homeShik_footerBg, .buttonWrapper, .shikshaAppsHeader, #cmsHeader,.downloadPDF,.applicationFormEditLink{display:none}
.customerSupport{width:265px;}
#content-wrapper{background:none}
.customerSupport div.details{width:190px;}
#shikshaAppsHeaderPrint{display:block !important}
#appsFormInnerWrapper{border:1px solid #000000}
header{display: none;}
/*For AIMS Form only*/
.breakRows,.breakRows2{display:block !important}
.breakVels{height:280px; overflow:hidden; display:block !important; width:100%; clear:left}
.marginEducation{margin-top:500px;}
</style>
<?php }?>

