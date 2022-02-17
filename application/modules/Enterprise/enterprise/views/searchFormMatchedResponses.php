<div style="position: absolute; display: none; " id="dataLoaderPanel">
      <img src="/public/images/loader.gif"/>
</div>
<?php
if (isset($flag_manage_page) && ($flag_manage_page == 'true')) {
	$flag_manage_page = 'true';
} else {
	$flag_manage_page = '';
}
?>
<input id="flag_manage_page" type="hidden" name="flag_manage_page" value="<?php echo $flag_manage_page; ?>"/>
<input id="selectedUsers" type="hidden" value=""/>
<form id="studentSearchFormMain" onSubmit="validatetimeRange();" style="<?php if($flag_manage_page == 'true') { echo "display:none;";} ?>" action="/enterprise/enterpriseSearch/formSubmitMR" method="POST">
      <?php
	    $this->load->view('enterprise/searchFormEducationDetailsDesiredCourse');
	    $this->load->view('enterprise/searchFormMatchedResponseSelectCourses');
	    $this->load->view('enterprise/searchFormMatchedResponseStudentDetails');
      ?>
      <div style="height:80px;background:#e7f0f7;border-bottom:2px solid #2959a5">
	    <div style="padding:30px 0 0 402px">
		  <input type="button" value="Search Students" uniqueattr="Enterprise/SearchStudentsMR" style="border:1px solid #2959a5; background:url(/public/images/cmsSearch_button.gif) repeat-x left top; height:30px;font-weight:700" onClick="doSubmitSearchStudents($('studentSearchFormMain'));return false;"/>
	    </div>
      </div>
      <input type="hidden" name="streamId" value="<?php echo $streamId;?>" />
</form>
<form id="searchFormSub" action="/enterprise/enterpriseSearch/searchResults" method="post" style="display:none">
</form>
<form id="csvdownloadform" method="post" action="/enterprise/shikshaDB/ajax_submit_csv_activity_post">
	<input type="hidden" id="csvActivationIdUserActions" name="csvActivationIdUserActions" value=""/>
	<input type="hidden" id="csvldb_course_type_csv_download" name="csvldb_course_type_csv_download" value=""/>
	<input type="hidden" id="csvfilename_download" name="csvfilename_download" value=""/>
	<input type="hidden" id="csvinputData" name="csvinputData" value=""/>
	<input type="hidden" id="csvdisplayData" name="csvdisplayData" value=""/>
	<input type="hidden" id="csvinputDataMR" name="csvinputDataMR" value=""/>
	<input type="hidden" id="currentURL" name="currentURL" value=""/>
	<input type="hidden" id="csvTabFlag" name="csvTabFlag" value=""/>
</form>
<div id="searchResultDiv">
</div>

<script type="text/javascript">

    var course_name_hack = null;
    var course_name = '<?php echo $course_name; ?>';

    function doSubmitSearchStudents(objForm)
    {
	    setTimeout(function(){
		  	var formobj = $('studentSearchFormMain');
		  	var formStringData = $j(formobj).serialize();
			if (!validatetimeRange()) {
				return false;
			}
			var checks = document.getElementsByName('course_id[]');
			var boxLength = checks.length;
			var atleastonechecked = false;
			for ( i=0; i < boxLength; i++ ) {
				if ( checks[i].checked == true ) {
			    	atleastonechecked = true;
				}
		  	}
		  	if (!atleastonechecked) {
				alert("Please Select Course(s).");
				return false;
		  	}
			window.scrollTo('0 ','0');
			new Ajax.Request( objForm.action,
				{
				    onBeforeAjax:showSearchDataLoader(),
				    onSuccess:function(request){
						javascript:showResponseForSearchStudentForm(request.responseText);
					},
					evalScripts:true,
					parameters:formStringData
				}
			);
		 	return true;
	    },300);

    }
      
    function showSearchDataLoader()
    {
    	var searchCMSBinder = {};
		searchCMSBinder =  new SearchCMSBinder();
    	searchCMSBinder.showLoader();
	    $('download_cvs_msg').innerHTML= '&nbsp;';
	    $('studentSearchFormMain').style.display='none';
	    $('searchResultDiv').innerHTML = '';
	    // showDataLoader($('searchResultDiv'));
	    $('searchResultDiv').innerHTML = "<div style='padding-bottom:200px;'>&nbsp;</div>";
    }

function validateSendEmail(objForm)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
    var flag = validateFields(objForm);
    invalidateFlag = invalidateShikshaEmailId($j('#fromEmail').val());

	if(invalidateFlag === true){
		$('fromEmail_error').parentNode.style.display = 'inline';
		$('fromEmail_error').innerHTML = 'Please enter non-Shiksha domain email Id (Email Id ending with "@shiksha.com" not allowed).';
	}

    if(flag !== true) {
        $('sendEmailButton').disabled  = false;
        return false;
    }
    else {

    	if(invalidateFlag === true){
    		return false;
    	}

        $('sendEmailButton').disabled  = true;
        searchCMSBinder.showLoader();
        hideOverlay(false);
        closeMessage();
        return searchCMSBinder.submitEmailSmsRequestForm();
    }
}

function validateSendSMS(objForm)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
    var flag = validateFields(objForm);
    if(flag !== true) {
		$('sendSMSButton').disabled  = false;
        return false;
    }
    else {
		$('sendSMSButton').disabled  = true;
		searchCMSBinder.showLoader();
        hideOverlay(false);
        closeMessage();
		return searchCMSBinder.submitEmailSmsRequestForm();
    }

}

/* Modify Search click on Top */
function showSearchForm()
{
    $('searchResultDiv').innerHTML = '';
    $('studentSearchFormMain').style.display='block';
    // Reset filters LDB 2.0
    FlagLDBSearchResultFilterValue = 'unviewed';
    resetLDBGlobalVars();
    reset_Other_LDB_Global_Vars();
}

function showResponseForSearchStudentForm(responseText)
{
   	/* Reset all global vars when form is submit again */
    resetLDBGlobalVars();
   	/* Reset flag value */
   	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
	searchCMSBinder.hideLoader();
   	$('searchResultDiv').style.display='block';
    // hideDataLoader($('searchResultDiv'));
    $('searchResultDiv').innerHTML=responseText;
    $('searchFormSub').innerHTML = $('searchFormSubContents').innerHTML;
    $('searchFormSubContents').innerHTML = "";
    $('studentSearchFormMain').style.display='none';

   	bindSearchFilters();

	try {
		selectComboBox($('countOffset_DD1'), $('countOffsetSearch').value);
		selectComboBox($('countOffset_DD2'), $('countOffsetSearch').value);
		doPagination($('resultCount').innerHTML,'startOffSetSearch','countOffsetSearch','paginationPlace1','paginationPlace2','methodName',4);
	} catch(e) {
		//alert(e);
	}
    if (FlagLDBSearchResultFilterValue !='') {
	if (FlagLDBSearchResultFilterValue == 'all') {
	    $('ldb_unviewed_flag').value= "";
	    $('ldb_viewed_flag').value= "";
	    $('ldb_emailed_flag').value= "";
	    $('ldb_smsed_flag').value= "";
	    $('switchLDBSearchFilter_all').className= "change";
	    $('switchLDBSearchFilter_unviewed').className= "";
	    $('switchLDBSearchFilter_viewed').className= "";
	    $('switchLDBSearchFilter_smsed').className= "";
	    $('switchLDBSearchFilter_emailed').className= "";
	}else if (FlagLDBSearchResultFilterValue == 'viewed') {
	    $('ldb_unviewed_flag').value= "";
	    $('ldb_viewed_flag').value="1";
	    $('ldb_emailed_flag').value= "";
	    $('ldb_smsed_flag').value="";
	    $('switchLDBSearchFilter_all').className= "";
	    $('switchLDBSearchFilter_unviewed').className= "";
	    $('switchLDBSearchFilter_viewed').className= "change";
	    $('switchLDBSearchFilter_smsed').className= "";
	    $('switchLDBSearchFilter_emailed').className= "";
	}else if (FlagLDBSearchResultFilterValue == 'unviewed') {
	    $('ldb_unviewed_flag').value= "1";
	    $('ldb_viewed_flag').value= "";
	    $('ldb_emailed_flag').value="";
	    $('ldb_smsed_flag').value="";
	    $('switchLDBSearchFilter_all').className= "";
	    $('switchLDBSearchFilter_unviewed').className= "change";
	    $('switchLDBSearchFilter_viewed').className= "";
	    $('switchLDBSearchFilter_smsed').className= "";
	    $('switchLDBSearchFilter_emailed').className= "";
	}else if (FlagLDBSearchResultFilterValue == 'emailed') {
	    $('ldb_unviewed_flag').value="";
	    $('ldb_viewed_flag').value="";
	    $('ldb_emailed_flag').value= "1";
	    $('ldb_smsed_flag').value="";
	    $('switchLDBSearchFilter_all').className= "";
	    $('switchLDBSearchFilter_unviewed').className= "";
	    $('switchLDBSearchFilter_viewed').className= "";
	    $('switchLDBSearchFilter_smsed').className= "";
	    $('switchLDBSearchFilter_emailed').className= "change";
	}else if (FlagLDBSearchResultFilterValue == 'smsed') {
	    $('ldb_unviewed_flag').value="";
	    $('ldb_viewed_flag').value="";
	    $('ldb_emailed_flag').value="";
	    $('ldb_smsed_flag').value= "1";
	    $('switchLDBSearchFilter_all').className= "";
	    $('switchLDBSearchFilter_unviewed').className= "";
	    $('switchLDBSearchFilter_viewed').className= "";
	    $('switchLDBSearchFilter_smsed').className= "change";
	    $('switchLDBSearchFilter_emailed').className= "";
	}
    }
    window.scrollTo(0,0);
    try{ $('dim_bg').style.height = document.body.scrollHeight+  'px'; $('iframe_div').style.height = document.body.scrollHeight+  'px'; } catch(e) {$('iframe_div').style.height = document.body.scrollHeight+  'px'; }
	try{
		if ($('ldb_total_result_count').value != 0 ) {
			if($('clientAccess').value == 1) {
				UpdateUsersCreditConsumedStatus();
				UpdateUsersRequiredStatus();
			}
		}
	} catch(e) {}
     //check_search_agent();
}

function resetLDBGlobalVars()
{
	/* RESET GLOBAL VARs. START */
	try {
		totalChecked = 0;
		msg_help_flag = 0;
		sActivationIdUserActions = 0;
		NoLeadsUser = 0;
		msg_help_flag = '';
	}
	catch(e)
	{ /* TODO:- */ }
	/* RESET GLOBAL VARs. END */
}
function reset_Other_LDB_Global_Vars()
{
	try {
		Serial_No_LDB_Downlaod = 1;
		ldb_result_start_email_flag_ajax = 0;
		ldb_result_start_sms_flag_ajax = 0;
		ldb_result_start_csv_flag_ajax = 0;
	}
	catch(e)
	{ /* TODO:-*/}
}

function populateSearchResults()
{
    var objForm = $('searchFormSub');
    new Ajax.Request(objForm.action,{onBeforeAjax:showSearchDataLoader(), onSuccess:function(request){javascript:showResponseForSearchStudentForm(request.responseText);$('download_cvs_msg').innerHTML='&nbsp;';}, evalScripts:true, parameters:Form.serialize(objForm)});
}

</script>
<?php
       $this->load->view('enterprise/searchResultViewContactDetails');
?>