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
<form id="studentSearchFormMain" onSubmit="validatetimeRange();" style="<?php if($flag_manage_page == 'true') { echo "display:none;";} ?>" action="/enterprise/shikshaDB/formSubmit" method="POST">
<?php
      $this->load->view('enterprise/searchFormEducationalDetails');
      if($course_name != 'Study Abroad') {
      //$this->load->view('enterprise/searchFormEducationalQualification');
      }
      $this->load->view('enterprise/searchFormOtherFilter');
      $this->load->view('enterprise/searchFormDisplayOptions');
?>
<div style="height:80px;background:#e7f0f7;border-bottom:2px solid #2959a5">
	<div style="padding:30px 0 0 402px">
            <input type="button" value="Search Students" uniqueattr="Enterprise/SearchStudents" style="border:1px solid #2959a5; background:url(/public/images/cmsSearch_button.gif) repeat-x left top; height:30px;font-weight:700" onClick="doSubmitSearchStudents($('studentSearchFormMain'));return false;"/></div>
</div>
<input type="hidden" name="search_category_id" value='<?php echo $search_category_id; ?>' />
<?php if($course_name == 'Study Abroad') { ?>
<input type="hidden" name="sa_course" value='<?php echo $sa_course; ?>' />
<input type="hidden" name="sa_course_type" value='<?php echo $sa_course_type; ?>' />
<?php } ?>
</form>
<form id="searchFormSub" action="/enterprise/shikshaDB/searchResults" method="post" style="display:none">
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
<script>
var course_name_hack = '';
var course_name = '<?php $message = ($boolen_flag_to_apply_hack_on_mba_courses == 'false') ? $actual_course_name : $course_name; echo $message; ?>';
//alert(course_name);
<?php
if ($_REQUEST['course_name']  == 'Test Prep' || $_REQUEST['course_name']  == 'Test Prep (International Exams)')
{
?>
course_name_hack = 'testprep';
<?php
}
?>

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

    if(flag !== true)
    {
        $('sendEmailButton').disabled  = false;
        return false;
    }
    else
    {	if(invalidateFlag === true){
    		return false;
    	}

        $('sendEmailButton').disabled  = true;
        hideOverlay(false);
        closeMessage();
        if($j('#courseName').val() == 'Study Abroad'){
			return submitEmailRequestForm();
	    } else {
	     	searchCMSBinder.showLoader();
	     	return searchCMSBinder.submitEmailSmsRequestForm();
	    }
    }
}

function validateSendSMS(objForm)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
    var flag = validateFields(objForm);
    if(flag !== true)
    {
		$('sendSMSButton').disabled  = false;
        return false;
    }
    else {
		$('sendSMSButton').disabled  = true;
        hideOverlay(false);
        closeMessage();
		if($j('#courseName').val() == 'Study Abroad'){
			return submitEmailRequestForm();
	    } else {
	     	searchCMSBinder.showLoader();
	     	return searchCMSBinder.submitEmailSmsRequestForm();
	    }
    }
}

function doSubmitSearchStudents(objForm)
{
      setTimeout(function(){
      if (!validatetimeRange()) {
		return false;
	}
      
  if (course_name == 'IT Courses' || course_name == 'Science & Engineering Degrees'
    || course_name=='Aircraft Maintenance Engineering' || course_name == 'Marine Engineering' || course_name
    == 'Medicine, Beauty & Health Care Degrees' || course_name == 'Integrated MBA Courses') {
	if (course_name_hack == 'testprep') {
		var checks = document.getElementsByName('testPrep_blogid[]');
	} else {
		var checks = document.getElementsByName('course_specialization[]');
	}
	var boxLength = checks.length;
	var atleastonechecked = false;
	for ( i=0; i < boxLength; i++ )
	{
	    if ( checks[i].checked == true )
	    {
		atleastonechecked = true;
	    }
	}
	if (!atleastonechecked) {
	    alert("Please select Desired Course(s).");
	    return false;
	}
    }
    if (course_name == 'IT Degrees') {
	var checks = document.getElementsByName('desiredCourse[]');
	var boxLength = checks.length;
	var atleastonechecked = false;
	for ( i=0; i < boxLength; i++ )
	{
	    if ( checks[i].checked == true )
	    {
		atleastonechecked = true;
	    }
	}
	if (!atleastonechecked) {
	    alert("Please select Desired Course.");
	    return false;
	}
    }
    if (course_name == 'Study Abroad') {
//	alert($('fieldOfInterest[]').value);
//	if (($('fieldOfInterest').value == '')) {
//	    alert("Please select field of interest.");
//	    return false;
//	}
//	    var checks = document.getElementsByName('board_id[]');
//        var boxLength = checks.length;
//        var atleastonechecked = false;
//        for ( i=0; i < boxLength; i++ )
//        {
//            if ( checks[i].checked == true )
//            {
//                atleastonechecked = true;
//            }
//        }
//        if (!atleastonechecked) {
//            alert("Please select field of interest.");
//            return false;
//        }
    }
    window.scrollTo('0 ','0');

   new Ajax.Request(objForm.action,{onBeforeAjax:showSearchDataLoader(), onSuccess:function(request){javascript:showResponseForSearchStudentForm(request.responseText);}, evalScripts:true, parameters:Form.serialize(objForm)});
   },300);
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
function showSearchDataLoader()
{
    $('download_cvs_msg').innerHTML= '&nbsp;';
    $('studentSearchFormMain').style.display='none';
    $('searchResultDiv').innerHTML = '';
    showDataLoader($('searchResultDiv'));
	$('searchResultDiv').innerHTML = "<div style='padding-bottom:200px;'>&nbsp;</div>";
}
function showResponseForSearchStudentForm(responseText)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();	
   /* Reset all global vars when form is submit again */
    resetLDBGlobalVars();
   /* Reset flag value */
    hideDataLoader($('searchResultDiv'));
    $j('#searchResultDiv').html(responseText);

    //$('searchResultDiv').innerHTML=responseText;
    $('searchFormSub').innerHTML = $('searchFormSubContents').innerHTML;
    $('searchFormSubContents').innerHTML = "";
    $('studentSearchFormMain').style.display='none';

    if($j('#courseName').val() == 'Study Abroad'){
		$j('#searchFormSub').attr('action','/enterprise/shikshaDB/searchResults');
    } else {
    	$j('#searchFormSub').attr('action','/enterprise/enterpriseSearch/searchResults');
    }

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
				if($j('#courseName').val() == 'Study Abroad'){		
					UpdateUsersCreditConsumedStatus();
					UpdateUsersRequiredStatus();
    			} else {
    				searchCMSBinder.UpdateUsersCreditConsumedStatus();
    			}
			}
		}
	} catch(e) {}
     //check_search_agent();
}
function showUserContactDetails(userId, actual_course_id)
{
	var ExtraFlag = 'false';
	if (course_name_hack == 'testprep')
	{
		ExtraFlag= 'true';
	}
	
	if (typeof(actual_course_id) == 'undefined') {
	    actual_course_id = '';
	}
	
    $('view_ldb_contact_detail_'+userId).disabled = true;
    var url = '/enterprise/shikshaDB/showContactDetails/'+userId+'/'+ExtraFlag+'/'+actual_course_id;
    new Ajax.Request(url, { method:'get', onSuccess:displayContactDetails});
    return false;
}

function checkAllUsers(obj)
{
    var chkAll = obj;
    var checks = document.getElementsByName('userCheckList[]');
    var boxLength = checks.length;
    var allChecked = false;
    totalChecked = 0;
    if ( chkAll.checked == true )
    {
	if (boxLength !== 0 ) {
	    for ( i=0; i < boxLength; i++ )
	    checks[i].checked = true;
	}
    }
    else
    {
	for ( i=0; i < boxLength; i++ )
	checks[i].checked = false;
    }
    for ( i=0; i < boxLength; i++ )
    {
	if ( checks[i].checked == true )
	{
	    allChecked = true;
	    continue;
	}
	else
	{
	    allChecked = false;
	    break;
	}
    }
    if ( allChecked == true )
    chkAll.checked = true;
    else
    chkAll.checked = false;
    for ( j=0; j < boxLength; j++ )
    {
        if ( checks[j].checked == true )
        totalChecked++;
    }
    $('checkAllUsers_1').checked = obj.checked;
    $('checkAllUsers_2').checked = obj.checked;
}
function checktotalChecked()
{
	var checks = document.getElementsByName('userCheckList[]');
	var boxLength = checks.length;
	totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
}
function checkAllInput(){
	inputFlag = true;
	totalChecked = 0;
	var checks = document.getElementsByName('userCheckList[]');
	var flag = true;
	for ( i=0; i < checks.length; i++ )
	{
		if ( checks[i].checked == true )
		{
		    flag = true;
		    continue;
		}
		else
		{
		    flag = false;
		    break;
		}
	}
	for ( j=0; j < checks.length; j++ )
	{
	    if ( checks[j].checked == true )
	    totalChecked++;
	}
	if(flag === true){
		$('checkAllUsers_1').checked = true;
		$('checkAllUsers_2').checked = true;
	}else{
		$('checkAllUsers_1').checked = false;
                $('checkAllUsers_2').checked = false;
	}
}
function getCheckedUserIdList()
{
    var checks = document.getElementsByName('userCheckList[]');
    var searchResultContainer = checks.length;
    var userIdList = "";
    for(var i = 0; i< searchResultContainer; i++)
    {
        if(checks[i].name == "userCheckList[]")
        {
            if(checks[i].checked)
            {
                if(userIdList == "")
                {
                    userIdList = checks[i].value;
                }
                else
                {
                    userIdList= userIdList+","+checks[i].value;
                }
            }
        }
    }
    return userIdList;
}
function singlecheckboxEMAIL(id)
{
	try{
		$(id).checked = true;
	} catch(e) {

	}
	sendMail();
	return false;
}
function singlecheckboxSMS(id)
{
	try{
		$(id).checked = true;
	} catch(e) {

	}
	sendSms();
	return false;
}
function sendSms()
{
	if ( Number($('ldb_total_result_count').value) == 0) { return false;}
	checktotalChecked();
	  var inputDataMR = 0;
	if ($('inputDataMR').value) {
			inputDataMR = 1;
	}
	  var inputData = 0;
	if ($('inputData').value) {
			inputData = 1;
	}
	  var displayData = 0;
	if ($('displayData').value) {
			displayData = 1;
	}
	var QueryStr = 'form/'+'SendSMS/'+ FlagLDBSearchResultFilterValue+'/'+totalChecked+'/'+$('ldb_total_result_count').value+'/'+'500'+'/null/null/null/null/null/'+inputData+'/'+displayData+'/'+inputDataMR;
	displayMessage('/enterprise/shikshaDB/displayoverlay/'+QueryStr,480,220);
	return false;
}
function sendMail()
{
	if ( Number($('ldb_total_result_count').value) == 0) { return false;}
	checktotalChecked();
	  var inputDataMR = 0;
	if ($('inputDataMR').value) {
			inputDataMR = 1;
	}
	  var inputData = 0;
	if ($('inputData').value) {
			inputData = 1;
	}
	  var displayData = 0;
	if ($('displayData').value) {
			displayData = 1;
	}
	var QueryStr = 'form/'+'SendEmail/'+ FlagLDBSearchResultFilterValue+'/'+totalChecked+'/'+$('ldb_total_result_count').value+'/'+'500'+'/null/null/null/null/null/'+inputData+'/'+displayData+'/'+inputDataMR;
	displayMessage('/enterprise/shikshaDB/displayoverlay/'+QueryStr,480,220);
	return false;
}

function downloadCSV()
{
	if ( Number($('ldb_total_result_count').value) == 0) { return false;}
	try{
		$('ifrm_csv_download').src = '';
	} catch(e) {}
	checktotalChecked();
	var inputDataMR = 0;
	if ($('inputDataMR').value) {
			inputDataMR = 1;
	}
	  var inputData = 0;
	if ($('inputData').value) {
			inputData = 1;
	}
	  var displayData = 0;
	if ($('displayData').value) {
			displayData = 1;
	}
	
	var QueryStr = 'form/'+'DownloadCSV/'+ FlagLDBSearchResultFilterValue+'/'+totalChecked+'/'+$('ldb_total_result_count').value+'/'+'500'+'/null/null/null/null/null/'+inputData+'/'+displayData+'/'+inputDataMR;
	displayMessage('/enterprise/shikshaDB/displayoverlay/'+QueryStr,480,220);
	return false;
}

function displayCreditDetailsForMail(res)
{
   responseTextTemp = eval("eval("+res.responseText+")");
   if(responseTextTemp.CreditCount)
   {
       $('availableEmailCredit').innerHTML = responseTextTemp.CreditCount;
       $('requiredEmailCredit').innerHTML = responseTextTemp.CreditsForAction;
       clearMessages($('emailOverlayForm'));
       showSearchResultGenricOverlay('searchResultEmailOverlay');
   }
   else
   {
        $('genricOverlayContent').innerHTML =  responseTextTemp.result;
        $('genricOverlayButton').innerHTML = '<input type="button" class="cancelGlobal" value="OK" id="genricOverlayCancel" onClick="hideOverlay(false);" />';
        $('genricOverlayTitle').innerHTML = "Credit Information";
        showSearchResultGenricOverlay('genricOverlay');
   }
}
function displayCreditDetailsForSMS(res)
{
   responseTextTemp = eval("eval("+res.responseText+")");
   if(responseTextTemp.CreditCount)
   {
       $('availableSMSCredit').innerHTML = responseTextTemp.CreditCount;
       $('requiredSMSCredit').innerHTML = responseTextTemp.CreditsForAction;
       clearMessages($('smsOverlayForm'));
       showSearchResultGenricOverlay('searchResultSMSOverlay');
   }
   else
   {
        $('genricOverlayContent').innerHTML =  responseTextTemp.result;
        $('genricOverlayButton').innerHTML = '<input type="button" class="cancelGlobal" value="OK" id="genricOverlayCancel" onClick="hideOverlay(false);" />';
        $('genricOverlayTitle').innerHTML = "Credit Information";
        showSearchResultGenricOverlay('genricOverlay');
   }
}

function submitEmailRequestForm() {
	var url = '';
	var clientId = $('ldb_client_id').value;
	var activityId = sActivationIdUserActions;
	var mysack = new sack();
	if (msg_help_flag == 'Send Email') {
		var subject = $('emailSubject').value;
		var fromEmail = $('fromEmail').value;
		var content = $('emailContent').value;
		var fromSender = $('fromSender').value;
		if(!$('rememberEmailTemplate').checked)
		{
			$('emailContent').value = "";
			$('emailSubject').value = "";
			$('fromEmail').value = "";
			$('fromSender').value = "";
		}
		url = '/enterprise/shikshaDB/ajax_submit_email_activity/';
		mysack.setVar( "clientId", clientId);
		mysack.setVar( "activityId",  activityId);
		mysack.setVar( "subject", base64_encode(subject));
		mysack.setVar( "content",  base64_encode(content));
		mysack.setVar( "fromEmail",  fromEmail);
		mysack.setVar( "fromSender",  fromSender);	
	}
	if (msg_help_flag == 'Send SMS') {
		url = '';
		var content = $('SMSContent').value;
		if(!$('rememberSMSTemplate').checked)
		{
			$('SMSContent').value = "";
		}
		url = '/enterprise/shikshaDB/ajax_submit_sms_activity/';
		mysack.setVar( "clientId", clientId);
		mysack.setVar( "activityId",  activityId);
		mysack.setVar( "content",base64_encode(content));
	}
	mysack.requestFile = url;
	mysack.method = 'POST';
	mysack.onError = function() { alert("An error was encountered.");window.location.href=window.location.href;};
	if (is_chrome) {
		mysack.onLoaded = function()
		{
			hideOverlay(false);
			closeMessage();
			showloader();
		};
	}
	mysack.onLoading = function()
	{
		hideOverlay(false);
		closeMessage();
		showloader();
	};
	mysack.onCompletion = function()
	{
		var response = mysack.response;
		if (msg_help_flag == 'Send SMS') {
			if ( type_action == 'textbox') {
				if (ldb_result_start_sms_flag_ajax == 0) {
					ldb_result_start_sms_flag_ajax = Number(NoLeadsUser);
				} else {
					ldb_result_start_sms_flag_ajax = Number(ldb_result_start_sms_flag_ajax) + Number(NoLeadsUser);
				}
			}
			closeMessage();
			var objForm = $('searchFormSub');
			new Ajax.Request(objForm.action,{onBeforeAjax:showSearchDataLoader(), onSuccess:function(request){javascript:newShowResponseOnOverlay(response,'SMS');showResponseForSearchStudentForm(request.responseText);$('download_cvs_msg').innerHTML='&nbsp;';}, evalScripts:true, parameters:Form.serialize(objForm)});
		} else if(msg_help_flag == 'Send Email') {
			if ( type_action == 'textbox') {
				if (ldb_result_start_email_flag_ajax == 0) {
					ldb_result_start_email_flag_ajax = Number(NoLeadsUser);
				} else {
					ldb_result_start_email_flag_ajax = Number(ldb_result_start_email_flag_ajax) + Number(NoLeadsUser);
				}
			}
			closeMessage();
			var objForm = $('searchFormSub');
			new Ajax.Request(objForm.action,{onBeforeAjax:showSearchDataLoader(), onSuccess:function(request){javascript:newShowResponseOnOverlay(response,'email');showResponseForSearchStudentForm(request.responseText);$('download_cvs_msg').innerHTML='&nbsp;';}, evalScripts:true, parameters:Form.serialize(objForm)});
		}
		resetLDBGlobalVars();
	};
	mysack.runAJAX();
	return false;
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
function downloadCSV_AJAX()
{
	showloader();
	if ( type_action == 'textbox') {
		if (ldb_result_start_csv_flag_ajax == 0) {
			ldb_result_start_csv_flag_ajax = Number(NoLeadsUser);
		} else {
			ldb_result_start_csv_flag_ajax = Number(ldb_result_start_csv_flag_ajax) + Number(NoLeadsUser);
		}
	}
	var filename_download = '';
	filename_download = ldb_result_course_name+"_"+FlagLDBSearchResultFilterValue+"_"+sTodayDateLDBSearch+"_"+ (Serial_No_LDB_Downlaod);
	// var requestFile = '< ?php echo "http://". $_SERVER['SERVER_NAME'];?>' +'/enterprise/shikshaDB/ajax_submit_csv_activity/'+sActivationIdUserActions+'/'+ldb_course_type_csv_download+'/'+filename_download;
	//$('ifrm_csv_download').src = requestFile;
	var csvinputData = $('inputData').value;
	var csvdisplayData = $('displayData').value;
	var csvinputDataMR = $('inputDataMR').value;
	var csvTabFlag = $('tabFlag').value;
	var currentURL = window.location.href;
	setDownloadCSVVariables(sActivationIdUserActions, ldb_course_type_csv_download, filename_download, csvinputData, csvdisplayData, csvinputDataMR, currentURL, csvTabFlag);
	resetLDBGlobalVars();
	populateSearchResults();
	Serial_No_LDB_Downlaod = Serial_No_LDB_Downlaod + 1;
	closeMessage();
	return false;
}
/* Unused API */
function submitSmsRequestForm()
{
    new Ajax.Request('/enterprise/shikshaDB/sendSMStoSelectedContacts',{ method:"post", parameters:('userIdCSV='+$('selectedUsers').value+'&content='+ escape($('SMSContent').value) ), onSuccess:function(request){javascript:if(!$('rememberSMSTemplate').checked){$('SMSContent').value = ""; };showResponseOnOverlay(request.responseText,'SMS');$('dim_bg').style.height = document.body.scrollHeight+  'px';}, evalScripts:true});
    return false;
}
function newShowResponseOnOverlay(responseText,type)
{
	var mysack = new sack();
	mysack.requestFile = '/enterprise/shikshaDB/display_static_overlay/1/';
	mysack.method = 'POST';
	mysack.onCompletion = function() {
		var response = mysack.response;
		messageObj.close();
		messageObj.setShadowDivVisible(false);
		messageObj.setFlagShowLoaderAjax(false);
		messageObj.setCssClassMessageBox('modalDialog_contentDiv');
		messageObj.setSource(false);
		messageObj.setShadowDivVisible(false);
		messageObj.setSize(480,160);
		messageObj.setHtmlContent(response);
		messageObj.display();
		$('DHTMLSuite_modalBox_contentDiv').style.background = 'none';
		$('DHTMLSuite_modalBox_contentDiv').style.background = '';
		responseTextTemp = eval("eval("+responseText+")");
		if(responseTextTemp['result'])
		{
			$('msg_body').innerHTML =  "Credits Used :"+responseTextTemp['CreditsForAction']+"<br/> Credits Remaining : "+responseTextTemp['CreditCount'];
			$('msg_title').innerHTML = responseTextTemp['result'].length+" "+type+ (responseTextTemp['result'].length == 1? "":"s") +" sent sucessfully ";
		}
		try {
			var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
			var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
			window.scrollTo(sl,st);
			setTimeout('window.scrollTo(' + sl + ',' + st + ');',10);
			messageObj.__resizeDivs();
		} catch(e) {}
/*
		window.onscroll = function (e) {
			messageObj.__resizeDivs();
		};
		window.onresize = function (e) {
			messageObj.__resizeDivs();
		};
*/
		return false;
	};
	mysack.runAJAX();
}
function showResponseOnOverlay(responseText, type)
{
    closeMessage();
    responseTextTemp = eval("eval("+responseText+")");
    if(responseTextTemp['result'])
    {
	populateSearchResults();
        $('genricOverlayContent').innerHTML =  "Credits Used : "+responseTextTemp['CreditsForAction']+"<br/> Credits Remaining : "+responseTextTemp['CreditCount'];
        $('genricOverlayButton').innerHTML = '<input type="button" class="cancelGlobal" value="OK" id="genricOverlayCancel" onClick="hideOverlay(false);" />';
        $('genricOverlayTitle').innerHTML = responseTextTemp['result'].length+" "+type+ (responseTextTemp['result'].length == 1? "":"s") +" sent sucessfully ";
        showSearchResultGenricOverlay('genricOverlay');
    }
}

function displayContactDetails(res)
{
   responseTextTemp = eval("eval("+res.responseText+")");
   if (responseTextTemp.result == 'You dont have sufficient credit to perform the given action') {
	alert('You do not have sufficient credits to perform this action.');
	try{
		$('view_ldb_contact_detail_'+res.responseText[0].userid).disabled = false;
	} catch(e) {}
	return false;
   }
   if (responseTextTemp.result == 'noview') {
	alert('Limit to View Contact Details for the lead has been reached, it cannot be viewed further.');
	try{
		$('view_ldb_contact_detail_'+res.responseText[0].userid).disabled = false;
	} catch(e) {}
	return false;
   }
   responseText =responseTextTemp.result[0];
   var tempHtml ="";
   if($("userContactDetailDiv_"+responseText[0].userid))
   {
    if(responseText[0].mobile != null)
    {
        tempHtml += "<div class='cmsSResult_pdBtm7'>Mobile: "+responseText[0].mobile+"</div>";
    }
    if(responseText[0].landline != null && responseText[0].landline!="")
    {
        tempHtml += "<div class='cmsSResult_pdBtm7'>Landline: "+responseText[0].landline+"</div>";
    }
    if(responseText[0].email != null)
    {
        tempHtml += "<div class='cmsSResult_pdBtm7'>Email: "+responseText[0].email+"</div>";
    }
   }
   $("userContactDetailDiv_"+responseText[0].userid).innerHTML = '<div class="redcolor" style="padding-bottom:7px"><img align="absbottom" src="/public/images/cmsSResult_Checked.gif"/> Contact viewed on '+responseText[0]['viewArray'][0]['FormattedContactDate']+'</div>'+tempHtml;
    var divX = document.body.offsetWidth/2 - 150;
    var scrollTop = (document.documentElement.scrollTop) ? (document.documentElement.scrollTop) : (document.body.scrollTop);
    var divY =  (screen.height)/2 + scrollTop - 200;
    var message = "Name : "+responseText[0].firstname+"<br/> Mobile : "+responseText[0].mobile+"<br/> Email: "+responseText[0].email;
	/* Update view count RR */
	var spanid = 'viewed_times_'+responseText[0].userid;
	try {
		$(spanid).innerHTML = Number($(spanid).innerHTML) + 1;
	}
	catch(e)
	{
		// do it later
	}
    if(responseTextTemp.CreditCount)
    {
        message += "<br/><br/>"+responseTextTemp.CreditsForAction+" credits have been deducted.You have "+responseTextTemp.CreditCount+" credits remaining in your Account.";
    }
	try{
		var userId = responseText[0].userid;
		$('creditRequired_'+userId).innerHTML = '';$('creditRequired_'+userId).style.display='none';
		$('creditConsumed_'+userId).style.display='';
		var str = '';
		str   = '<b>Credit Consumed: '+responseTextTemp.CreditsForAction+'</b><br />';
		str  += ' You can now Email/SMS this student for free.';
		$('creditConsumed_'+userId).innerHTML = '';
		$('creditConsumed_'+userId).innerHTML = str;
	} catch(e) {
		//alert(e);
	}

    showViewContactOverlay(divX, divY , message);
}

function populateSearchResults()
{
    var objForm = $('searchFormSub');
    new Ajax.Request(objForm.action,{onBeforeAjax:showSearchDataLoader(), onSuccess:function(request){javascript:showResponseForSearchStudentForm(request.responseText);$('download_cvs_msg').innerHTML='&nbsp;';}, evalScripts:true, parameters:Form.serialize(objForm)});
}
function showInterestHistory(aDiv,userid)
{
    objDiv=$('parentDiv_'+userid);
    if(objDiv.style.display != "none")
    {
        objDiv.style.display = "none";
        aDiv.className = "cmsSearch_plusImg";
    }
    else
    {
        objDiv.style.display = "block";
        aDiv.className = "cmsSearch_minImg";
    }
}
function setFilterOption(obj)
{
    $('filterOverride').value = obj.value;
    populateSearchResults();
}
function showCreditDetailsForView(userId){
	new Ajax.Request('/enterprise/shikshaDB/confirmShowContactDetails/'+userId, { method:'get',  onSuccess:function(request){javascript:showOverlayWithContactDetails(request,userId);}, evalScripts:true});
}

function showOverlayWithContactDetails(request, userId)
{

	//Title Code
	$('genricOverlayTitle').innerHTML = "Credit Information";

	//Button Code
	$('genricOverlayButton').innerHTML = '<input type="button" class="submitGlobal" value="OK" id="genricOverlayOKBtn" onClick="hideOverlay(false);showUserContactDetails('+userId+')" />&nbsp;<input type="button" class="cancelGlobal" value="Cancel" id="genricOverlayCancel" onClick="hideOverlay(false);" />';

	//Message Code
	setContent = eval("eval("+request.responseText+")");
	$('genricOverlayContent').innerHTML =  setContent.result;


	//Call Function
	showSearchResultGenricOverlay('genricOverlay');

}

function showSearchResultGenricOverlay(gOverlayId)
{

	messageObj.close();
	var Xposition = document.body.offsetWidth/2 - 150;
	var scrollTop = (document.documentElement.scrollTop) ? (document.documentElement.scrollTop) : (document.body.scrollTop);
	var Yposition =  (screen.height)/2 + scrollTop - 200;
	objElementDiv = $(gOverlayId);
	objElementDiv.style.display =  '';
	objElementDiv.style.left = Xposition +  'px';
	objElementDiv.style.top = Yposition  +  'px';
	$('dim_bg').style.height = document.body.scrollHeight+  'px';
	$('dim_bg').style.width = document.body.scrollWidth +  'px';
	$('dim_bg').style.display = 'inline';
	overlayHackLayerForIE(gOverlayId, document.body);
}
function showViewContactOverlay(Xposition, Yposition, Message)
{
        $('contactInfoOverlay').style.left = Xposition +  'px';
        $('contactInfoOverlay').style.top = Yposition  +  'px';
        $('contactdiv').innerHTML = Message;
        $('dim_bg').style.height = document.body.offsetHeight +  'px';
        $('dim_bg').style.display = 'inline';
        $('contactdiv').style.display = '';
        $('contactInfoOverlay').style.display = '';
        overlayHackLayerForIE('contactInfoOverlay', document.body);
}
</script>
<?php
       $this->load->view('enterprise/searchResultViewContactDetails');
?>

