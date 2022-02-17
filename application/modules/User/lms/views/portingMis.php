<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
    foreach ($headerContentaarray as $content) {
        echo $content;
    }
}
?>
<script type="text/javascript">
function testPositiveInt(number) {
	var intRegex = /^\d+$/;
	if(number > 0 && intRegex.test(number)) {
		return true;
	}
	else {
		return false;
	}
}

function searchPortingsByClient() {
	var clientid = $('client_id').value;
	$('misForm-div').innerHTML = '';
	if (testPositiveInt(clientid)) {
		new Ajax.Request( '/lms/Porting/portingMisForm',
			{	method:'post',
				onSuccess:function(request){
					if(request.responseText) {
						$('misForm-div').innerHTML = request.responseText;
					}
					else {
						alert('No portings exists for this user');
					}
				},
				onFailure: function(){ alert('Something went wrong...'); },
				evalScripts:true,
				parameters:'clientid='+clientid
			}
		);
	}
	else {
		alert("Please give valid Client ID.");
	}
}

function checkUncheckChilds1(obj, checkBoxesHolder)
{
	var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
	for(var i=1;i<checkBoxes.length;i++) {
		if(checkBoxes[i].checked!=obj.checked) {
			checkBoxes[i].checked = obj.checked;
		}
	}
}

function uncheckElement1(obj ,id, holderId)
{
	var allChecked = false;
	if(!obj.checked) {
		if(document.getElementById(id).checked) {
			document.getElementById(id).checked = false;
		}
	}
	var checks =  document.getElementById(holderId).getElementsByTagName("input");
	var boxLength = checks.length;
	var chkAll = document.getElementById(id);
	for ( i=1; i < boxLength; i++ ) {
		if( checks[i].checked == true ) {
			allChecked = true;
			continue;
		}
		else {
			allChecked = false;
			break;
		}
	}
	if( allChecked == true ) {
		chkAll.checked = true;
	}
	else {
		chkAll.checked = false;
	}
}

function timerangeFrom()
{
	var calMain = new CalendarPopup('calendardiv');
	disDate = null;
	frmdisDate = new Date();
	isresponseViewer = 1;
	calMain.select($('timerange_from'),'timerange_from_img','yyyy-mm-dd');
	return false;
}

function timerangeTo(id)
{
	var calMain = new CalendarPopup('calendardiv');
	var mindate = $('timerange_from').value; //yyyy-mm-dd
	var dateStr = mindate.split("-");
	var passedDate = dateStr[2]%32;
	var passedMonth = dateStr[1]%13;
	var passedYear = dateStr[0];
	disDate = new Date(passedYear,passedMonth-1,passedDate);
	frmdisDate = new Date();
	isresponseViewer = 1;
	calMain.select($('timerange_to'),'timerange_to_img','yyyy-mm-dd',mindate);
	return false;
}

function validateTimeRange()
{
        if($('timerange_from').value == "" || $('timerange_from').value == "yyyy-mm-dd" || $('timerange_to').value == "" || $('timerange_to').value == "yyyy-mm-dd") {
            alert("Please select a date range");
            return false;
        }
        else {
                //convert yyyy-mm-dd to mm/dd/yyyy
                var fromdate = $('timerange_from').value;
                fromdate = fromdate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
                var todate = $('timerange_to').value
                todate = todate.replace( /(\d{4})-(\d{2})-(\d{2})/, "$2/$3/$1");
                if (Date.parse(todate) >= Date.parse(fromdate)) {
                        return true;
                }
                else {
                        alert("Please select a date greater than from date");
                        $('timerange_from').value = $('timerange_to').value;
                        return false;
                }
        }
        return true;
}

function select_report_type(obj)
{
    if (obj.value == 'number') {
        $('report_days').disabled = false;
    }
    else {
        $('report_days').disabled = true;
    }
}

function doSubmitPortingMisForm()
{
	var formobj = $('portingMisForm');
	var formData = $j(formobj).serializeArray();
	var formStringData = $j(formobj).serialize();
	var formDataObj = {};
	$j.each(formData, function() {
		if (formDataObj[this.name] !== undefined) {
			if (!formDataObj[this.name].push) {
				formDataObj[this.name] = [formDataObj[this.name]];
			}
			formDataObj[this.name].push(this.value || '');
		} else {
			formDataObj[this.name] = this.value || '';
		}
	});
	
	if(!testPositiveInt(formDataObj['clientid'])) {
		alert("Please give valid Client ID.");
		return false;
	}
	
	if(formDataObj['portings[]'] == undefined) {
		alert("Please select atleast one porting.");
		return false;
	}
	
	if(!validateTimeRange()) {
		return false;
	}
	
	if(formDataObj['report_type'] == undefined) {
		alert("Please Select Report Type.");
		return false;
	}
	else if(formDataObj['report_type'] == "number") {
		if(formDataObj['report_days'] == "") {
			alert("Please Select Report Days.");
			return false;
		}
	}
	
	$('portingMisForm').submit();
}

</script>

        <!--Code Starts form here-->
        <div id="lms-port-wrapper">
            <div class="page-title">
                <h2>MIS Reports</h2>
            </div>
            <div id="lms-port-content">
                <div class="form-section">
                    <ul <?php echo $usergroup == 'enterprise' && $userId != LMS_PORTING_USER_ID ? 'style="display:none"' : ''; ?>>
                        <li>
                            <label> Client ID:</label>
                            <div class="form-fields">
                                <input type="text" style="width:200px; float:left" id="client_id" value="<?php echo $usergroup == 'enterprise' && $userId != LMS_PORTING_USER_ID ? $userId : ''; ?>" />
                                <div class="spacer10 clearFix"></div>
                                <input type="button" value="Go" class="gray-button" onclick="searchPortingsByClient()"/>
                            </div>
                        </li>
                    </ul>
                    
                    <div id="misForm-div"></div>
                </div>
                <div class="clearFix"></div>
            </div>
        </div>
        <!--Code Ends here-->
<?php $this->load->view('common/footer');?>

<?php 
if($usergroup == 'enterprise' && $userId != LMS_PORTING_USER_ID){
	?>
	<script>
	$j(document).ready(function(){
		searchPortingsByClient();
	});
	</script>
	<?php
}
?>