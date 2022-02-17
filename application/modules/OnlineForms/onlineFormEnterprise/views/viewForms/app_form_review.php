<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','modal-message','online-styles','common'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','tooltip','onlineFormEnterprise','ana_common','json2'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
	'title' => 'Enterprise User Dashboard'
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<?php
$this->load->view('enterprise/cmsTabs');
?>
<input type="hidden" name="instituteId" id="instituteId" value="<?php echo $onlineFormEnterpriseInfo['instituteInfo'][0][0]['institute_id'];?>">
<?php $info = $onlineFormEnterpriseInfo['instituteDetails'][0][0];?>
<?php $formId = isset($_REQUEST['formId'])?$_REQUEST['formId']:''?>
<?php $userId = isset($_REQUEST['userId'])?$_REQUEST['userId']:''?>
<?php if(isset($info['GDPIDate']) && trim($info['GDPIDate'])!='' && trim($info['GDPIDate'])!='0000-00-00 00:00:00'){$gdpiDate = date("d/m/Y",strtotime($info['GDPIDate']));}else{$gdpiDate = date('d/m/Y');}?>
<div id="gdpiDateNew" style="display:none;"><?php echo $gdpiDate;?></div>
<div id="gdpiPlaceNew" style="display:none;"><?php echo $info['gdpiId'];?></div>
<div id="gdpiCityNames" style="display:none;"><?php echo $gdpiInfoCourseWise[$info['courseId']]['cityName'];?></div>
<div id="gdpiCityIds" style="display:none;"><?php echo $gdpiInfoCourseWise[$info['courseId']]['cityId'];?></div>
<div id="formId" style="display:none;"><?php echo $formId;?></div>
<div style="display:none;" id="requestDocumentContent"><?php echo $info['documentsRequired'];?></div>
<div style="display:none;" id="requestPhotographContent"><?php echo $info['imageSpecifications'];?></div>
<div id="UserFormId" style="display:none;"><?php echo $formId .'-'.$userId;?></div>
<div id="singleUserFormId" style="display:none;"></div>
<input type="checkbox" value="dummy" checked="checked" style="display:none;"/>
<div class="wrapperFxd">
<div id="appsFormWrapper">
<div id="departmentId" style="display:none;"><?php echo $_REQUEST['departmentId'];?></div>
<div id="courseId" style="display:none;"><?php echo $_REQUEST['courseId'];?></div>
<div id="totalNumberOfForms" style="display:none;"><?php echo $totalForm;?></div>
<div id="instituteSpecId" style="display:none;"><?php echo $info['instituteSpecId'];?></div>
<div id="mainOnlineFormEnterpriseDiv">
<link rel="stylesheet" media="print" type="text/css" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion("print"); ?>" />

<div class="shikshaAppsHeader" id="shikshaAppsHeaderPrint" style="padding:0 0 15px 0; display:none">
	    <h1 class="logo">
        	<a style="background:none; text-indent:0" href="<?php echo SHIKSHA_HOME; ?>" title="Shiksha.com">
        		<img src="//<?php echo CSSURL;?>/public/images/nshik_ShikshaLogo1.gif" alt="Shiksha.com" /></a>
        </h1>
	    <div class="customerSupport">
		<div class="figure" style="background:none"><img src="//<?php echo CSSURL;?>/public/images/customer-care-girl.gif" alt="Shiksha.com" /></div>
		<div class="details">
		    <p>
			    <span>Call : 011-4046-9621</span>
				(between 09:30 AM to 06:30 PM, Monday to Friday)
		    </p>
		</div>
	    </div>
	</div>
<div class="clearFix"></div>
<!--<div class="print"><a onclick="window.print();" href="javascript:void(0);"><span></span><b>Print Form</b><strong></strong></a></div>-->
<div class="downloadPDF" style="width: 200px;"><a title="Download PDF and Print" href="<?php echo SHIKSHA_HOME;?>/onlineFormEnterprise/OnlineFormEnterprise/createPDFEnterprise/enterprise/<?php if($_REQUEST['userId']!='') echo $_REQUEST['userId']; else echo '0';?>/<?php if($_REQUEST['formId']!='') echo $_REQUEST['formId']; else echo '0';?>/<?php echo $_REQUEST['cId'];?>"><b>Download PDF and Print</b><strong></strong></a><span></span></div>
<div id="appsFormInnerWrapper">
<div id="contentWrapper">
<?php $this->load->view('Online/FormTemplate/course'.$_REQUEST['cId']);?>
<div class="clearFix spacer15"></div>
    <?php if((isset($_REQUEST['formId']) && $_REQUEST['formId']!='') || (isset($_REQUEST['userId']) && $_REQUEST['userId']!='' )){?>
    <div class="buttonBlock buttonWrapper">
                <!--<input type="button" value="Draft Received" class="entOrangeButton" id="1"/> &nbsp;-->
                <input type="button" value="Request Photographs" class="entOrangeButton" id="2"/> &nbsp;
                <input type="button" value="Request Documents" class="entOrangeButton" id="3"/> &nbsp;
               <div class="spacer10 clearAll" ></div>
                                <input type="button" value="Confirm Acceptance" class="entOrangeButton" id="4"/> &nbsp;
                <input type="button" value="Update <?=$gdPiName?>" class="entOrangeButton" id="5"/> &nbsp;
                <input type="button" value="Reject Application" class="entOrangeButton" id="6"/>&nbsp;
                <input type="button" value="Shortlist Application" class="entOrangeButton" id="18"/>
           <div class="spacer10 clearAll" ></div>
               <!--  <input type="button" value="Cancel Application" class="cancelButton" id="17"/>
 -->
            </div>
    <?php } ?>
</div>
</div>
</div>
<!--Contents Ends here-->
<div class="clearFix"></div>
</div>
</div>
<?php
$this->load->view('enterprise/footer');
?>

<?php if($_REQUEST['generate']=='pdf'){?>
<style>
body{font-size: 10pt; color: black; font-family: Arial; background-color:white; width:100%; height:100%; overflow:visible}
#wrapperMainForCompleteShiksha .topBar, .shikshaAppsHeader,#breadcrumb,#footerText, #headerGradienthome,#header,#loginCommunication, .jser,#enableCookieMsg,.print, .entHeader,.tabBorder, .homeShik_footerBg, .buttonWrapper, .shikshaAppsHeader, #cmsHeader,.downloadPDF,#footer{display:none}
.customerSupport{width:265px;}
.n-footer1{display: none}
.n-footer2{display: none}
.n-footer3{display: none}
.customerSupport div.details{width:190px;}
#shikshaAppsHeaderPrint{display:block !important}
#appsFormInnerWrapper{border:1px solid #000000}
.breakVels{height:280px; overflow:hidden; display:block !important; width:100%; clear:left}
#content-wrapper{background:none;}
.marginEducation{margin-top:500px;}
</style>
<?php }?>
