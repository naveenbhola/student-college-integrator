<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>

<div id="content-wrapper">
    <div class="wrapperFxd">
    	<div id="content-child-wrap">
            <div id="smart-content">
                <div class="ent-box full-width">
		    <div class="ent-content" style="height:auto; border:none;">
                        <form id="expectation_Form" action = "/smart/SmartMis/setExpectation" method="POST" autocomplete="off" onsubmit="return false;" >
                            <table width="100%" class="table-style2" cellpadding="5" cellspacing="0" border="1" bordercolor="#d8d8d8" style="color:#5e5e5e;">
                                    <tr>
                                        <th width="150">Client</th>
                                        <th width="250">Institute Name</th>
                                        <th width="120">Set Expectation</th>
                                        <th width="125">Start Date</th>
                                        <th width="125">End Date</th>
                                        <th width="95">Daily Run Rate</th>
                                    </tr>
                                    
                                    <?php echo $ClientExpectationTable; ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding:12px 0"><input type="button" value="Submit" uniqueattr="ClientExpectation/SubmitButton" class="orange-button" onclick="validateForm() == true ? setExpectation() : '';"/></td>
                                    </tr>
                            </table>
                        </form>
                    </div>
                </div>	
            </div>
        </div>
    </div>
</div>

<?php
if(is_array($footerContentaarray) && count($footerContentaarray)>0) {
	foreach ($footerContentaarray as $content) {
		echo $content;
	}
}
?>

<script>
function timerangeFrom(id)
{
	var fromId = 'timerange_from_' + id;
	var calId = 'timerange_from_img_' + id;
	var calMain = new CalendarPopup('calendardiv');
	calMain.select($(fromId), calId, 'yyyy-mm-dd');
	return false;
}

function timerangeTo(id)
{
	var toId = 'timerange_to_' + id;
	var calId = 'timerange_to_img_' + id;
	var calMain = new CalendarPopup('calendardiv');
	calMain.select($(toId), calId, 'yyyy-mm-dd');
	return false;
}

function removevalue()
{
        oInputId.value = 'yyyy-mm-dd';
        closeCalendar();
}

function closeCalendar()
{
	openerClick=true;
	document.getElementById("yearDropDown").style.display="none";
	document.getElementById("monthDropDown").style.display="none";
	document.getElementById("hourDropDown").style.display="none";
	document.getElementById("minuteDropDown").style.display="none";
	calendarDiv.style.display="none";
	if(iframeObj){
		iframeObj.style.display="none";
		EIS_Hide_Frame();
	}
	if(activeSelectBoxMonth){
		activeSelectBoxMonth.className="";
	}
	if(activeSelectBoxYear){
		activeSelectBoxYear.className="";
	}
	$j(oInputId).change();
}

function validateExpectation(id)
{
        var expectationHTMLobj = document.getElementById('expectation_field_' + id);
        var expectation = $j.trim(expectationHTMLobj.value);
        var errorMsg = '';
        
        if (expectation == '') {
                errorMsg = 'Enter an expectation';
        }
        else if (isNaN(expectation)) {
                errorMsg = 'Enter a numeric value';
        }
        else if (expectation % 1 != 0) {
                errorMsg = 'Enter a decimal value';
        }
	else if (expectation < 1) {
                errorMsg = 'Enter a value > 0';
        }
        else {
                return errorMsg;
        }
        
        return errorMsg;
}

function validateFromDate(id)
{
        showError(id, 'expectation', validateExpectation(id));
        var fromDateHTMLobj = document.getElementById('timerange_from_' + id);
        var fromDateString = fromDateHTMLobj.value;
        var errorMsg = '';
        
        if (fromDateString == 'yyyy-mm-dd') {
                errorMsg = "Enter a 'FROM' date";
        }
        else {
                return errorMsg;
        }
        
        return errorMsg;
}

function validateToDate(id)
{
        showError(id, 'expectation', validateExpectation(id));
        showError(id, 'from_date', validateFromDate(id));
        var toDateHTMLobj = document.getElementById('timerange_to_' + id);
        var toDateString = toDateHTMLobj.value;
        var errorMsg = '';
        
        if (toDateString == 'yyyy-mm-dd') {
                errorMsg = "Enter a 'TO' date";
        }
        else if (validateFromDate(id) == '') {
                var fromDateHTMLobj = document.getElementById('timerange_from_' + id);
                var fromDateString = fromDateHTMLobj.value;
                var toDate = convertStringToDate(toDateString);
                var fromDate = convertStringToDate(fromDateString);
                var timeDiff = toDate.getTime() - fromDate.getTime();
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (diffDays < 0) {
                        errorMsg = "'TO' date > 'FROM' date";
                }
                else {
                        return errorMsg;
                }
        }
        else {
                return errorMsg;
        }
        
        return errorMsg;
}

function showError(id, type, errorMsg) {
        var HTMLobj;
        var errorHTMLobj;
        
        if (type == 'expectation') {
                HTMLobj = document.getElementById('expectation_field_' + id);
                errorHTMLobj = document.getElementById('expectation_error_' + id);
        }
        else if (type == 'from_date') {
                HTMLobj = document.getElementById('timerange_from_' + id);
                errorHTMLobj = document.getElementById('from_date_error_' + id);
        }
        else if (type == 'to_date') {
                HTMLobj = document.getElementById('timerange_to_' + id);
                errorHTMLobj = document.getElementById('to_date_error_' + id);
        }
        
        if (errorMsg == '') {
                HTMLobj.style.boxShadow = '';
                errorHTMLobj.style.display = 'none';
                errorHTMLobj.innerHTML = '';
                return true;
        }
        else {
                HTMLobj.style.boxShadow = '0px 0px 2px 1px #ff0000';
                errorHTMLobj.style.display = '';
                errorHTMLobj.innerHTML = errorMsg;
                return false;
        }
}

function showRunRate(id)
{
        if (validateExpectation(id) == '' && validateFromDate(id) == '' && validateToDate(id) == '') {
                var expectation = document.getElementById('expectation_field_' + id).value;
                var fromDate = convertStringToDate(document.getElementById('timerange_from_' + id).value);
                var toDate = convertStringToDate(document.getElementById('timerange_to_' + id).value);
                var timeDiff = toDate.getTime() - fromDate.getTime();
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                var runRate = expectation/(diffDays + 1);
                var runRateHTMLObj = document.getElementById('run_rate_' + id);
                var runRateHTMLFieldObj = document.getElementById('run_rate_field_' + id);
                runRateHTMLObj.innerHTML = Math.round(runRate);
                runRateHTMLFieldObj.value = runRate;
        }
        else {
                var runRateHTMLObj = document.getElementById('run_rate_' + id);
                var runRateHTMLFieldObj = document.getElementById('run_rate_field_' + id);
                runRateHTMLObj.innerHTML = '';
                runRateHTMLFieldObj.value = '';
        }
}

function validateForm()
{
        var isValid = true;
        var isformFilled = false;
        var instituteHTMLObjs = getDOMElementsByClassName(document.body,'institute');
        var expectationHTMLObjs = getDOMElementsByClassName(document.body,'expec-field');
        var fromDateHTMLObjs = getDOMElementsByClassName(document.body,'timerange_from');
        var toDateHTMLObjs = getDOMElementsByClassName(document.body,'timerange_to');
        
        for (var index = 0; index < instituteHTMLObjs.length; index++) {
                var expectation = expectationHTMLObjs[index].value;
		var fromDate = fromDateHTMLObjs[index].value;
		var toDate = toDateHTMLObjs[index].value;
		if (expectation != '' || fromDate != 'yyyy-mm-dd' || toDate != 'yyyy-mm-dd') {
                        var instituteId = instituteHTMLObjs[index].value;
			var expectationErrorMsg = validateExpectation(instituteId);
			var fromDateErrorMsg = validateFromDate(instituteId);
			var toDateErrorMsg = validateToDate(instituteId);
                        if (expectationErrorMsg != '' || fromDateErrorMsg != '' || toDateErrorMsg != '') {
                                isValid = false;
				showError(instituteId, 'to_date', toDateErrorMsg);
                        }
                        isformFilled = true;
                }
        }
        
        if (!isformFilled) {
            alert('Fill the form and submit.');
        }
        else if (!isValid) {
            alert('Enter vaild form details.');
        }
        
        return isValid & isformFilled;
}

function setExpectation()
{
        var formStringData = $j("#expectation_Form").serialize();
	var formDataObj = objectifyForm('expectation_Form');
        $j.post("/smart/SmartMis/setExpectation", formStringData,
								function(response) {
									var overlayContent = '<div>Thank you for submitting your request</div><div align="center" style="margin-top:10px;"><input type="button" value="Ok" class="orange-button" onClick="hideOverlayAnA();"/></div>';
									showOverlayAnA(300, 150, 'Confirmation', overlayContent);
								});
}
</script>