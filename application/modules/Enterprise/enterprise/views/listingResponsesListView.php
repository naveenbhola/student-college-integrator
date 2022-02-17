<?php
//echo "<pre>".print_r($resultResponse,true)."</pre>";

?>
            <div class="featured-article-tab">
                <ul>
                    <li onclick="clickTab('activatedTab');" class="active">
                        <a id="activatedTab" href="/enterprise/Enterprise/getListingsResponsesForClient/live">Active Listings</a>
                    </li>
                    <li onclick="clickTab('deletedTab');">
                        <a id="deletedTab" href="/enterprise/Enterprise/getListingsResponsesForClient/deleted">Deleted Listings</a>
                    </li>
                </ul>
            </div>
            
<div style="width:100%">
    	<div style="width:100%">
        	<!--Start_Margin_10px-->
        	<div style="margin:0 10px">
            <div id="searchFormSubContents" style="display:none">
			<input type="hidden" id="locationId" name="locationId" value="<?php echo $locationId;?>"/>
            <input type="hidden" id="startOffSetSearch" name="startOffSetSearch" value="<?php echo $startOffset;?>"/>
            <input type="hidden" id="countOffsetSearch" name="countOffsetSearch" value="<?php echo $countOffset;?>"/>
            <input type="hidden" id="requestTime" name="requestTime" value="<?php echo isset($resultResponse['requestTime'])?$resultResponse['requestTime']:'';?>"/>
            <input type="hidden" id="methodName" name="methodName" value="<?php echo isset($_POST['methodName'])?$_POST['methodName']:'getResponsesForCriteria';?>"/>
            <input type="hidden" id="selectedUsers" name="selectedUsers" value=""/>
            </div>
            <!--Start_ShowResutlCount-->
            <div style="width:100%">
                <div style="font-size:18px">Shiksha Response Viewer</div>
				<div class="lineSpace_10">&nbsp;</div>
                <div style="width:100%">
                    <?php if(isset($resultResponse['numrows'])) {
                            $studentCount = 'Only 1 responses';
                            if($resultResponse['numrows'] > 1) {
                                $studentCount = 'Total <span class="OrgangeFont">'. $resultResponse['numrows'] .'</span> responses';
                            }
                            if($resultResponse['numrows'] == 0) {
                                $studentCount = 'No responses';
                            }
                    ?>
                        <div>
							<div style="width:70%">
								<div style="width:100%">
									<div class="fontSize_1xi68p" style="padding-bottom:7px"><span id="resultCount" style="font-size:18px"><?php echo $studentCount; ?> found</span></div>
								</div>
							</div>
                        </div>
                    <?php }
                    elseif(isset($resultResponse['error']))
                    {
                        echo '<div class="fontSize_18p" style="padding-bottom:7px">'.$resultResponse['error'].'</div>';
                    }
                    else { ?>
                        <div class="fontSize_18p" style="padding-bottom:7px">There are no matching students as per your criteria.</div>
                    <?php } ?>
					<div class="lineSpace_5">&nbsp;</div>
                    <div class="dandaSepGray"><b>Listing:</span> <span class="OrgangeFont"><?php echo $instituteName . ($courseName == '' ? '' : ' :: '. $courseName);?></span></b>
					<?php if($locationDetails['cityName']) { ?>
					<div class="dandaSepGray"><b>Location:</span> <span class="OrgangeFont"><?php echo ($locationDetails['localityName'] ? $locationDetails['localityName'].', ' : '').$locationDetails['cityName'];?></span></b>
					<?php } ?>
					</div>
                </div>
            </div>
            <!--End_ShowResutlCount-->

            <div class="lineSpace_10">&nbsp;</div>
			<div style="width:100%">
				<div style="line-height:7px;height:7px;overflow:hidden">&nbsp;</div>
				<div style="height:22px">
					<span> 
                                                <b>Generated in:</b> 
                                                <select id="changeRegdateFilter_DD1" onChange="filterByTime(this.options.selectedIndex);"> 
                                                <option value="7 day">Last 7 Days</option> 
                                                <option value="15 day">Last 15 Days</option> 
                                                <option value="1 month">Last 1 Month</option> 
                                                <option value="3 month">Last 3 Months</option> 
                                                <option value="6 month">Last 6 Months</option> 
                                                <option value="none">All</option> 
                                                </select> 
                                        </span> 
					
					<span style="padding-left:25px">
						<b>Filter by response to:</b>
						<select id="filterSelection" onChange="changeFilters();" style="width:425px">
						<option value="both" listingId="<?php echo $instituteId; ?>" <?php if($searchCriteria =='both') echo 'selected'; ?>>None</option>
						<?php
						if($instituteLocation == 'india') {
						?>
						<!-- <option value="instituteOnly" listingId="<?php echo $instituteId; ?>" <?php if($searchCriteria =='instituteOnly') echo 'selected'; ?>>Institute Only</option> -->
						<?php
						}
							foreach($courses as $course) {
						?>
						<option value="courseOnly" listingId="<?php echo $course['courseID']; ?>" <?php if($searchCriteria =='courseOnly' && $listingId == $course['courseID']) echo 'selected'; ?>>Course - <?php echo $course['courseName']; ?></option>
						<?php }
						?>
						</select>
					</span>
				</div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
            <!--Start_NavigationBar-->
            
            <div style="width:100%;<?php if(isset($resultResponse['numrows']) && $resultResponse['numrows'] <1) { echo 'display:none';} ?>">
            	<div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">
                    	<div style="line-height:6px">&nbsp;</div>
                    	<div style="width:100%">
                        	<div class="float_L">
                                <div style="width:100%"><div style="height:22px">
                                <span>
                                <span class="pagingID" id="paginationPlace1"></span>
                                </span>
                                </div></div>
                            </div>
                            <div class="float_R" style="width:25%">
                            	<div style="width:100%">
                                    <div style="height:22px" class="txt_align_r">
                                    <span class="normaltxt_11p_blk bld pd_Right_6p">View:
                                    <select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
                                    <!-- <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
									<option value="75">75</option>-->
                                    <option value="100">100</option> 
									<option value="500">500</option>
                                    </select>
                                    </span>
                                    </div></div>
                            </div>
                            <div class="cmsClear">&nbsp;</div>
                    </div>
                </div>
            </div>
            <!--End_NavigationBar-->
            
            <!--Start_SendMail_SendSMS-->
            <div style="width:100%;background:#fffbff;height:35px">
                <div style="margin:0 10px">
                    <div style="line-height:6px">&nbsp;</div>
                    <div style="width:100%">
                        <div class="float_L" style="width:50%">
                            <div style="width:100%">
                                <div style="height:23px">
                                    <input type="checkbox" id="checkAllUsers_1" onClick="checkAllUsers(this);"/> 
                                    <input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" onClick="communicateUser('Email');;"/>&nbsp;&nbsp;
                                    <input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" onClick="communicateUser('Sms');"/>&nbsp;&nbsp;
                                    <a class="download_btn_response" target="_blank">
                                        <img src="/public/images/dcvs.gif" border="0" style="cursor:pointer;position:relative;top:6px" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="float_L" style="width:49%">
                            <div style="width:100%"><div style="height:23px;font-size:11px" class="txt_align_r">&nbsp;</div></div>
                        </div>
                        <div class="cmsClear">&nbsp;</div>
                    </div>
                </div>
            </div>
            <!--End_SendMail_SendSMS-->
            <div class="lineSpace_10">&nbsp;</div>
            
			
            <!--Start_MainDateRowContainer------------------------------------------>
            <div style="width:100%" id="searchResultContainer" >
            <form id="courseResponseViewerDownload" action="<?php echo $csvURL;?>" method="post" style="display:none"> 
                <input type="hidden" name="allocationIds">     
            </form>
                <!--Start_DateRow_1------------------------------------------>
                <?php $rowCount=0; ?>
                <?php foreach($resultResponse['responses'] as $response) {
                    $dataX = array("response" => $response,"rowCount" => $rowCount);
		    if($instituteLocation == "india"){
			$this->load->view("enterprise/unitResponseViewIndia",$dataX);
		    } else {
			$this->load->view("enterprise/unitResponseView",$dataX);
		    }
                ?>
                
                <?php
                $rowCount++;
                }
                ?>
                <!--End_DateRow_1------------------------------------------>

            </div>
            <!--End_MainContainerDateRow------------------------------------------>
            
            <div class="lineSpace_10">&nbsp;</div>
            <!--Start_SendMail_SendSMS-->
            <div style="width:100%;background:#fffbff;height:35px">
                <div style="margin:0 10px">
                    <div style="line-height:6px">&nbsp;</div>
                    <div style="width:100%">
                        <div class="float_L" style="width:50%">
                            <div style="width:100%">
                                <div style="height:23px">
                                    <input type="checkbox" id="checkAllUsers_2" onClick="checkAllUsers(this)"/> 
                                    <input type="button" value="Send Mail" class="cmsSResult_sendMailBtn" onClick ="communicateUser('Email');"/>&nbsp;&nbsp;
                                    <input type="button" value="Send SMS" class="cmsSResult_sendSMSBtn" onClick="communicateUser('Sms');"/>&nbsp;&nbsp;
                                    <a class="download_btn_response" target="_blank">
                                        <img src="/public/images/dcvs.gif" border="0" style="cursor:pointer;position:relative;top:6px" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="float_L" style="width:49%">
                            <div style="width:100%"><div style="height:23px;font-size:11px" class="txt_align_r">&nbsp;</div></div>
                        </div>
                        <div class="cmsClear">&nbsp;</div>
                    </div>
                </div>
            </div>
            <!--End_SendMail_SendSMS-->
			<div class="lineSpace_10">&nbsp;</div>
            <!--Start_NavigationBar-->
            <div style="width:100%">
            	<div class="cmsSResult_pagingBg">
                	<div style="margin:0 10px">
                    	<div style="line-height:6px">&nbsp;</div>
                    	<div style="width:100%">
                        	<div class="float_L" style="width:41%">
                                <div style="width:100%"><div style="height:22px">
                                <span>
                                <span class="pagingID" id="paginationPlace2"></span>
                                </span>
                                </div></div>
                            </div>
                            <div class="float_L" style="width:33%">
				<div style="width:100%"><div style="height:22px" class="txt_align_c"><b>Generated in:</b> 
                                    <select id="changeRegdateFilter_DD2" onChange="filterByTime(this.options.selectedIndex);"> 
                                    <option value="7 day">Last 7 Days</option> 
                                    <option value="15 day">Last 15 Days</option> 
                                    <option value="1 month">Last 1 Month</option> 
                                    <option value="3 month">Last 3 Months</option> 
                                    <option value="6 month">Last 6 Months</option> 
                                    <option value="none">All</option> 
                                    </select> 
                                </div></div> 
				
				
                            </div>
                            <div class="float_L" style="width:25%">
                            	<div style="width:100%">
                                    <div style="height:22px" class="txt_align_r">
                                    <span style=""> &nbsp; View:
                                    <select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
					<option value="100">100</option>
                                    <option value="500">500</option>
                                    </select>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="cmsClear">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
                        
            <!--End_NavigationBar-->
        </div>
</div>

<?php
echo '<pre>';
//print_r($resultResponse);
echo '</pre>';
?>
<script>
window.onload=function(){
    $j('.download_btn_response').on('click',function(e){
        e.preventDefault();

        if(getAllCheckedValue() == true){
            $j('#courseResponseViewerDownload').submit();    
        }else{
            alert("Please select at least one user to perform this Action!");
        }
    });
};



function getAllCheckedValue(){
    var allocationIds = [],
    isCheckedCounter = 0;
    $j('.allo_check').each(function(){  
        if($j(this).attr('checked') == 'checked'){
            allocationIds.push($j(this).attr('allocationid'));  
            isCheckedCounter++ ;          
        }
    })
    if(allocationIds.length <=0){
        return false;
    }else{
        var allocationIdString = JSON.stringify(allocationIds);
        $j("#searchResultContainer input[name='allocationIds']").val(allocationIdString);    
    }
    return true;
    if(isCheckedCounter > 0){
        return true;
    }else{
        return false;
    }
}

function checkAllUsers(obj)
{
    var searchResultContainer = document.getElementById('searchResultContainer').getElementsByTagName("input");
    for(var i = 0; i< searchResultContainer.length ; i++)
    {
        if(searchResultContainer[i].name == "userCheckList[]")
        {
            searchResultContainer[i].checked = obj.checked;
        }
    }
    document.getElementById('checkAllUsers_1').checked = obj.checked;
    document.getElementById('checkAllUsers_2').checked = obj.checked;
}


function checkAllInput(){
	inputFlag = true;
	var objInput = document.getElementById('searchResultContainer').getElementsByTagName('input');
	var flag;
	for(var i=0; i<objInput.length; i++) {
		if(objInput.type != 'checkbox') continue;
		if(objInput[i].checked){
			flag = true;
		} else {
			flag = false;
			break;
		}
	}
	if(flag === true){
		document.getElementById('checkAllUsers_1').checked = true;
		document.getElementById('checkAllUsers_2').checked = true;
	}else{
		document.getElementById('checkAllUsers_1').checked = false;
                document.getElementById('checkAllUsers_2').checked = false;
	}
}

function filterByTime(filterIndex) { 
    document.getElementById('changeRegdateFilter_DD1').options[filterIndex].selected = true; 
    document.getElementById('changeRegdateFilter_DD2').options[filterIndex].selected = true; 
    document.getElementById('startOffSetSearch').value = 0; 
    getResponsesForCriteria(); 
} 

function getResponsesForCriteria() {
    var locationId = document.getElementById('locationId').value;
    var startOffset = document.getElementById('startOffSetSearch').value;
    var countOffset = document.getElementById('countOffsetSearch').value;
    var url = '/enterprise/Enterprise/getResponsesForListing/';
    var filterElement = document.getElementById('filterSelection');
    var selection = filterElement.value;
    var timeInterval = document.getElementById('changeRegdateFilter_DD1').value;
    var listingId = filterElement.options[filterElement.selectedIndex].getAttribute('listingId');
    switch(selection) {
        case 'instituteOnly':  url +=  listingId +'/institute/instituteOnly/'; break;
        case 'courseOnly':  url +=  listingId +'/course/courseOnly/'; break;
        default:  url +=  listingId + '/<?php if($instituteLocation == "india") { echo "institute"; } else { echo "university"; } ?>/both/';
		break;
    }
    url += locationId+'/'+timeInterval +'/'+ startOffset +'/'+ countOffset;
    location.replace(url);
}

function changeFilters() {
    document.getElementById('startOffSetSearch').value= 0;
    getResponsesForCriteria();
}

function communicateIndividualUser(user, mode) {
    if(!user) return false;
    uncheckAllUsers();
    user.checked = true;
    return communicateUser(mode);
}

function communicateUser(mode) {
    var selectedUsers = getCheckedUserIdList();
    if(trim(selectedUsers) == "") {
        alert("Please select at least one user to perform this Action!");
        return false;
    }
    document.getElementById('selectedUsers').value = selectedUsers;
    if(mode == 'Sms'){
        mode = 'SMS';
    }
    if(document.getElementById('required'+ mode +'Credit')) {
        document.getElementById('required'+ mode +'Credit').parentNode.parentNode.innerHTML = '';
    }
    overlayParent = document.getElementById('searchResult'+ mode +'Overlay');
    showOverlay(480,400,'', overlayParent.innerHTML);
    document.getElementById('dim_bg').style.height = $j(document).height()+"px";
    //alert($j(document).height()); 
    document.getElementById('genOverlayTitleCross_hack').style.display = 'none';
    overlayParent.innerHTML = '';
    /*if(mode == 'Email') {
        initTMCEEditor();
    }*/
    var modeCookie = getCookie('remember'+ mode);
    if(modeCookie != "" && modeCookie !=  null) {
        modeCookie = base64_decode(modeCookie);
        document.getElementById('remember'+ mode +'Template').checked =  true;
        var modeContent = (mode == 'SMS') ? 'SMSContent': 'emailContent' ;
        modeCookie = eval('eval('+ modeCookie.replace(/\n/g,'@@@###@') +')');
        document.getElementById(modeContent).value = decodeURI(modeCookie.content.replace(/@@@###@/g,'\n'));
        if(mode == 'Email') {
            document.getElementById('emailSubject').value = decodeURI(modeCookie.subject);
            document.getElementById('fromEmail').value = decodeURI(modeCookie.from);
        }
    }
    document.getElementById('send'+mode+'Button').onclick=  function() {
        $j('#send'+mode+'Button').attr('disabled','disabled');
        return validateContactForm(this);
    };
    return false;
}

function invalidateShikshaEmailId(emailId){
    shiksha_email = '@shiksha.com';

    if(emailId.indexOf(shiksha_email) > 0){
        return true;
    }

    return false;
}

function validateContactForm(obj) {

    var objForm = obj.form;
    var mode = obj.id.replace('send','').replace('Button','');
    /*if(mode == 'Email') {
        document.getElementById(tinyMCE.activeEditor.id).value = tinyMCE.activeEditor.getContent();
        if(document.getElementById(tinyMCE.activeEditor.id).getAttribute('required')) {
            var message = document.getElementById(tinyMCE.activeEditor.id).value;
            message = stripHtmlTags(message);
            message = message.replace(/&nbsp;/gi,"");
            message = trim(message);
            if(message == '') {
                document.getElementById(tinyMCE.activeEditor.id +'_error').parentNode.style.display = 'inline';
                document.getElementById(tinyMCE.activeEditor.id +'_error').innerHTML = 'Message cannot be empty';
                return false;
            }
        }
    }*/
    var sender = (mode == 'SMS' ? 'SMSSender' : 'fromEmail');
    var subject = (mode == 'SMS') ? '' : document.getElementById('emailSubject').value ;
    var modeContent = (mode == 'SMS') ? 'SMSContent': 'emailContent' ;
    var flag = validateFields(objForm);

    invalidateFlag = invalidateShikshaEmailId($j('#fromEmail').val());

    if(invalidateFlag === true){
        flag = false;
        $('fromEmail_error').parentNode.style.display = 'inline';
        $('fromEmail_error').innerHTML = 'Please enter non-Shiksha domain email Id (Email Id ending with "@shiksha.com" not allowed).';
    }

    if(flag === true) {
        try{
            new Ajax.Request('/misc/Misc/contactUsers',{ method:"post", parameters:('userIdCSV='+document.getElementById('selectedUsers').value+'&mode='+ mode +'&product=responseViewer&senderDetail='+ document.getElementById(sender).value +'&subject='+ escape(subject) +'&content='+ escape(document.getElementById(modeContent).value) ), onSuccess:function(request){ var response = eval('eval('+ request.responseText +')'); updateUserCommunicationStatus(mode, response);}});
        } catch(e) {
            alert(e);
        }
    }
    $j('#send'+mode+'Button').removeAttr('disabled');
    return false;
}


function updateUserCommunicationStatus(mode, response) {
    var today = response.today;
    var modeContent = (mode == 'SMS') ? 'SMSContent': 'emailContent' ;
    if(!document.getElementById('remember'+ mode +'Template').checked){
        document.getElementById(modeContent).value = "";
        setCookie('remember'+ mode,null,-100);
    } else {
        var modeCookie = '';
        if(mode == 'SMS') {
            modeCookie = '"content":"' +  encodeURI(document.getElementById(modeContent).value.replace(/"/g,'\"')) +'"';
        }
        if(mode == 'Email') {
            modeCookie = '"content":"' +  encodeURI(document.getElementById(modeContent).value.replace(/"/g,'\"')) +'"';
            modeCookie += ',"subject":"' +  encodeURI(document.getElementById('emailSubject').value.replace(/"/g,'\"')) +'"';
            modeCookie += ',"from": "' + encodeURI(document.getElementById('fromEmail').value.replace(/"/g,'\"')) +'"' ;
        }
        modeCookie = '{'+ modeCookie +'}';
        setCookie('remember'+ mode,base64_encode(modeCookie));
    }
    hideOverlay();
    alert(response.msg);
    var searchResultContainer = document.getElementById('searchResultContainer').getElementsByTagName("input");
	var userIdList = '"' + document.getElementById('selectedUsers').value.replace(/,/g,'"') + '"';
	for(var i = 0; i< searchResultContainer.length ; i++) {
		if(searchResultContainer[i].name == "userCheckList[]") {
            if(userIdList.indexOf('"'+ searchResultContainer[i].value +'"') > -1) {
				searchResultContainer[i].checked = false;
				var recordId = searchResultContainer[i].id.replace('rowName_','');
				if(mode == 'SMS') {
					$('smsUser_'+ recordId).innerHTML = '<img align="absbottom" src="/public/images/cmsSResult_mobile.gif"/> SMSed on ' + (today);
                    $('smsUser_'+ recordId).className = 'redcolor';
				} else {
					$('emailUser_'+ recordId).innerHTML = '<img align="absbottom" src="/public/images/cmsSResult_mailCheck.gif"/> Emailed on ' + (today);
                    $('emailUser_'+ recordId).className = 'redcolor';
				}
			}
		}
	}
    checkAllInput();
}

function uncheckAllUsers(){
    $j('input[name="userCheckList[]"]').each(function() { 
        this.checked = false; 
    });
    checkAllInput();
}

function getCheckedUserIdList() {
    var searchResultContainer = document.getElementById('searchResultContainer').getElementsByTagName("input");
    var userIdList = "";
    for(var i = 0; i< searchResultContainer.length ; i++) {
        if(searchResultContainer[i].name == "userCheckList[]") {
            if(searchResultContainer[i].checked && (searchResultContainer[i].value != "")) {
                if(userIdList == "") {
                    userIdList = searchResultContainer[i].value;
                } else {
                    userIdList= userIdList+","+searchResultContainer[i].value;
                }
            }
        }
    }
    return userIdList;
}



function showlayer(layer){
	var myLayer = document.getElementById(layer).style.display;
	if(myLayer=="none"){
	document.getElementById(layer).style.display="block";
	} else {
	document.getElementById(layer).style.display="none";
	}
}


function crm_form(param)
{
	/* $clientId */
	var crm_clientid = $('crm_clientid_'+param).value;
	var crm_counslarid = $('crm_counslarid_'+param).value;
	var crm_lead_id = $('crm_lead_id_'+param).value;
	var comments = $('comments_'+param).value;
	var score = $('score').value;


	/* url of ajax call */
	var url = '/crm/CRM/EnterpriseUserRegisterFeedback/';

	comments = encodeURIComponent(comments);

	var mysack = new sack();
	mysack.requestFile = url;
	mysack.method = 'POST';
	mysack.setVar( "crm_clientid", crm_clientid );
	mysack.setVar( "crm_counslarid", crm_counslarid );
	mysack.setVar( "crm_lead_id", crm_lead_id );
	mysack.setVar( "comments", comments );
	mysack.setVar( "score", score );
	       
		mysack.onCompletion = function() {
                     $('success_msg_'+param).style.display = '';
		     showlayer('feedback_submit_'+param).style.display ="none" ;
		}
	
	mysack.runAJAX();
}


doPagination('<?php echo $resultResponse['numrows']; ?>','startOffSetSearch','countOffsetSearch','paginationPlace1','paginationPlace2','methodName',4);

selectComboBox(document.getElementById('changeRegdateFilter_DD1'), '<?php echo $timeInterval; ?>'); 
selectComboBox(document.getElementById('changeRegdateFilter_DD2'), '<?php echo $timeInterval; ?>'); 

selectComboBox(document.getElementById('countOffset_DD1'), '<?php echo $countOffset;?>');
selectComboBox(document.getElementById('countOffset_DD2'), '<?php echo $countOffset;?>');
</script>
