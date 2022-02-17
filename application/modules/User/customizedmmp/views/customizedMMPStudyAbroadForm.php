<?php
	$url = '/user/Userregistration/MultipleMarketingPageUserOperation';
?>
<form method="post" onsubmit="try{if(sendReqInfo(this)) { new Ajax.Request('<?php echo $url?>',{onSuccess:function(request){javascript:newuserresponse(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)});} return false;} catch(e) { alert(e);}" action="<?php echo $url?>" novalidate="novalidate" id = "frm1" name = "marketingUser">
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value = ""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName" value = ""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList" value = ""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName" value = ""/>
    <input type = "hidden" name = "mRegions" id = "mRegions" value = "#"/>
    <input type = "hidden" name = "mPageName" id = "mPageName" value = "<?php echo $pageName?>"/>
    <input type = "hidden" name = "mcourse" id = "mcourse" value = "<?php echo $course?>"/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value = ""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value = ""/>
    <input type = "hidden" name = "destination_url" id = "destination_url" value = "<?php echo $config_data_array['destination_url']?>"/>
	
	<!-- field of interests starts -->
	<?php $this->load->view('customizedmmp/customizedMMPFieldOfInterest');?>
	<!-- field of interests ends -->
	<!-- customizedMMPDesiredCourseLevel starts -->
	<?php $this->load->view('customizedmmp/customizedMMPDesiredCourseLevel');?>
	<!-- customizedMMPDesiredCourseLevel ends -->
	<!-- customizedMMPGraduationDetails starts -->
	<?php $this->load->view('customizedmmp/customizedMMPGraduationDetails');?>
	<!-- customizedMMPGraduationDetails ends -->
	<!-- customizedXIIDetailsField starts -->
	<?php $this->load->view('customizedmmp/customizedXIIDetailsField');?>
	<!-- customizedXIIDetailsField ends -->
	<!-- customizedDestinationCountryView starts -->
	<?php $this->load->view('customizedmmp/customizedDestinationCountryView');?>
	<!-- customizedDestinationCountryView ends -->
	<!-- customizedMMPPLanToStart starts -->
	<?php $this->load->view('customizedmmp/customizedMMPPLanToStart');?>
	<!-- customizedMMPPLanToStart ends -->
	<!-- customizedMMPFundingSource starts -->
	<?php $this->load->view('customizedmmp/customizedMMPFundingSource');?>
	<!-- customizedMMPFundingSource ends -->
	<!-- customizedMMPExamsTaken starts -->
	<?php $this->load->view('customizedmmp/customizedMMPExamsTaken');?>
	<!-- customizedMMPExamsTaken ends -->
	<!-- customizedMMPName starts -->
	<?php $this->load->view('customizedmmp/customizedMMPName');?>
	<!-- customizedMMPName ends -->
	<!-- customizedMMPEmaiPassword starts -->
	<?php $this->load->view('customizedmmp/customizedMMPEmaiPassword');?>
	<!-- customizedMMPEmaiPassword ends -->
	<!-- customizedMMPMobile starts -->
	<?php $this->load->view('customizedmmp/customizedMMPMobile');?>
	<!-- customizedMMPMobile ends -->
	<!-- customizedMMPResidenceLocation starts -->
	<?php $this->load->view('customizedmmp/customizedMMPResidenceLocation');?>
	<!-- customizedMMPResidenceLocation ends -->
	<!-- customizedMMPCallPreference starts -->
	<?php $this->load->view('customizedmmp/customizedMMPCallPreference');?>
	<!-- customizedMMPCallPreference ends -->
	<!-- customizedMMPCaptchaTNC starts -->
	<?php $this->load->view('customizedmmp/customizedMMPCaptchaTNC');?>
	<!-- customizedMMPCaptchaTNC ends -->
	<!-- customizedMMPStudyAbroadSubmit starts -->
	<?php $this->load->view('customizedmmp/customizedMMPStudyAbroadSubmit');?>
	<!-- customizedMMPStudyAbroadSubmit ends -->
</form>

<script>
	FLAG_LOCAL_COURSE_FORM_SELECTION = false;
	//getCitiesForCountry('',false,'_studyAbroad');
	<?php
	if(is_array($userData) && is_array($userData[0])) {
		// no need to show selected city
		//echo 'selectComboBox(document.getElementById("cities_studyAbroad"), "'.$userData[0]['city'] .'");';
	}
	?>
	if(typeof(setCustomizedVariableForTheWidget) == "function") {
		if (window.addEventListener) {
			window.addEventListener('click', setCustomizedVariableForTheWidget, false); 
		} else if (window.attachEvent) {
			document.attachEvent('onclick', setCustomizedVariableForTheWidget);
		}
	}
</script>
