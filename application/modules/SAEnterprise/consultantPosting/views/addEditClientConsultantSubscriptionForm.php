<?php
//_p($mappingData);   
?>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    
    <?php
        $displayData["breadCrumb"] 	= array(array("text" => "All Consultant & Client Mapping", "url" => ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT ),
                                                array("text" => ($formName==ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION?"Add New":"Edit")." Mapping", "url" => "") );
        $displayData["pageTitle"]  	= "Upgrade Consultant";
        $disabledField = '';
        if($formName != ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION){
	    $displayData["pageTitle"]  	= "Edit Upgraded Consultant";
            $displayData["lastUpdatedInfo"] = array("title"    => "Last modified ",
                                                    "date"     => date_format(date_create_from_format('Y-m-d H:i:s',$mappingData['modifiedAt']),'d-m-Y'),
                                                    "username" => $mappingData['modifiedByName']);
        $disabledField = "disabled";
	}
        // load the title section
        $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>

    <form id ="form_<?=$formName?>" name="<?=$formName?>" action="<?=ENT_SA_CMS_CONSULTANT_PATH?>saveClientConsultantSubscriptionData" method="POST" enctype="multipart/form-data">
        <div class="cms-form-wrapper clear-width">
                <div class="clear-width">
                    <h3 style="cursor:pointer;" class="section-title"><i class="abroad-cms-sprite minus-icon"></i>Consultant & Client Details</h3>
                    <div style="margin-bottom:0" class="cms-form-wrap">
                        <ul>
                           <li>
                                <label>Consultant ID* : </label>
                                <div class="cms-fields">
                                  <div style="margin-bottom:0" class="add-more-sec clear-width">
                                    <input type="text" name="consultantId" id="consultantId" style="width:150px !important;" class="universal-txt-field cms-text-field flLt" onblur="showErrorMessage(this,'<?=$formName?>')" onchange="showErrorMessage(this,'<?=$formName?>')" required="true" caption="Consultant ID" maxlength="20" validationType="numeric" value="<?= $mappingData['consultantId']?>" <?= $disabledField;?>>
                                    <a style="margin-right:5px;" href="javascript:void(0);" <?php if($disabledField==''){?>onclick="validateAndFetchConsultantName(this);"<?php }?> class="edit-search-box flLt"><i class="abroad-cms-sprite <?php if($disabledField==''){echo "rank-search-icon";}else{ echo "edit-icon";}?>"></i></a>
                                    <span style="padding-top:5px; display:block;"><?php if($disabledField!=''){ echo htmlentities($mappingData['consultantName']);}else{ echo "Not Mapped";}?></span>
                                  </div>
                                <div style="display: none" class="errorMsg" id="consultantId_error"></div>  
                                </div>
                            </li>
                            <li>
                                <label>Client ID* : </label>
                                <div class="cms-fields">
                                  <div style="margin-bottom:0" class="add-more-sec clear-width">
                                    <input type="text" name="clientId" id="clientId" style="width:150px !important;" class="universal-txt-field cms-text-field flLt"  onblur="showErrorMessage(this,'<?=$formName?>')" onchange="showErrorMessage(this,'<?=$formName?>')" required="true" caption="Client ID" maxlength="20" validationType="numeric" value="<?= $mappingData['clientId']?>" <?= $disabledField;?>>
                                    <a style="margin-right:5px;" href="javascript:void(0);" onclick="validateAndFetchClientName(this);" class="edit-search-box flLt"><i class="abroad-cms-sprite <?php if($disabledField==''){echo "rank-search-icon";}else{ echo "edit-icon";}?>"></i></a>
                                    <span style="padding-top:5px; display:block;"><?php if($disabledField!=''){ echo htmlentities($clientName);}else{ echo "Not Mapped";}?></span>
                                  </div>
                                <div style="display: none" class="errorMsg" id="clientId_error"></div>    
                                </div>
                            </li>
                            <li>
                            	<label>Subscriptions* : </label>
                            	<div class="cms-fields">
                                    <select name="subscriptionId" id="subscriptionId" class="universal-select cms-field" onblur="showErrorMessage(this,'<?=$formName?>');" onchange="populateCreditTableData();showErrorMessage(this,'<?=$formName?>');" required="true" caption="Subscription Type" validationType="select">                         
                                        <option value="">Select a Subscription</option>
					<?php
					$subscriptionOptions = $mappingData['subscriptionData']['data']['SubscriptionArray'];
					$startDate = "N/A";
					$endDate   = "N/A";
					$creditLeft= "N/A";
					foreach($subscriptionOptions as $key=>$value){
					    $select = "";
					    if($value['SubscriptionId']== $mappingData['subscriptionId']){
						$select = 'selected';
						$startDate = date('d-M-Y',strtotime($value['SubscriptionStartDate']));
						$endDate   = date('d-M-Y',strtotime($value['SubscriptionEndDate']));
						$creditLeft= $value['BaseProdRemainingQuantity'];
					    }
					    if(CONSULTANT_BASE_PRODUCT_ID == $value['BaseProductId']){
					    ?>
					<option value="<?= $value['SubscriptionId'];?>" <?= $select?>><?= $value['BaseProdSubCategory'];?></option>    
					<?php }}?>
					
                                    </select>
				    <div style="display: none" class="errorMsg" id="subscriptionId_error"></div>
                                </div>
                            </li>
                            <li>
                            	<label>Cost per response (in Rs.)*:</label>
                                <div class="cms-fields">
                                    <input type="text" name="costPerResponse" id="costPerResponse" style="width:150px !important;" class="universal-txt-field cms-text-field flLt"  onchange="showErrorMessage(this,'<?=$formName?>')" required="true" caption="Cost Per Response " maxlength="20" validationType="numeric" value="<?= $mappingData['costPerResponse']?>">
                                    <p style="margin-left:170px; padding-top:3px;">Minimum response price is Rs.<?= CONSULTANT_CLIENT_MIN_RESPONSE_PRICE?>/- per response.</p>
				    <br/><div style="display: none" class="errorMsg" id="costPerResponse_error"></div>
				    <div style="display: none" class="errorMsg" id="costPerResponse_error2"></div>
                                </div>
                            </li>
                         </ul>
                    </div>
                </div>
                <div style="margin:0" class="cms-form-wrap">
                    <div class="cms-fields">
                        <strong>Subscription details</strong>
                    </div>
                    <div class="add-more-sec2 clear-width">
                     <ul>
                        <li style="margin-bottom:0;">
                            <div class="subscription-detail-sec">
                                <p>Start Date : <span id="startDateSpan"><?= $startDate?></span></p>
                                <p>End Date : <span id="endDateSpan"><?= $endDate?></span></p>
                                <p>Credit Left : <span id="creditLeftSpan"><?= $creditLeft?></span></p>
                            </div>
                        </li>
                      </ul>
                    </div>
                </div>
        <div id="overAll_error" class="errorMsg" style="display: none;">Fields marked in RED are mandatory while saving the form.</div>   
            
        </div><!-- end:: cms-form-wrapper -->
        <input type = "hidden" id = "createdAt" name = "createdAt" value = "<?=($mappingData['createdAt'])?>" />
        <input type = "hidden" id = "createdBy" name = "createdBy" value = "<?=($mappingData['createdBy'])?>" />
        <input type = "hidden" id = "modifiedBy" name = "modifiedBy" value = "<?=($userid)?>" />
        <input type = "hidden" id = "mappingId" name ="mappingId" value="<?=$mappingData['mappingId'];?>"/>
        <div class="button-wrap">
                <a href="JavaScript:void(0);" onclick = "submitClientConsultantMappingFormData(this,'<?=$formName?>');" class="orange-btn">Submit</a>
                <a href="JavaScript:void(0);" onclick = "confirmRedirection();" class="cancel-btn">Cancel</a>
        </div><!-- end:: button-wrap -->
        <?php
            if($selectedConsultantName != ''){
                $this->load->view('consultantCitySubscriptionTable',$tableDataArray);
            }
        ?>
        <div class="clearFix"></div>
    </form>
</div><!-- abroad-cms-rt-box -->

<script>
    window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var minResponsePrice = <?= CONSULTANT_CLIENT_MIN_RESPONSE_PRICE;?>;
    var consultantBaseProductId = <?= CONSULTANT_BASE_PRODUCT_ID;?>;
    var saveInitiated = false;  
		function confirmExit()
		{
			if(preventOnUnload == false){
				return 'Any unsaved change will be lost.';
			}
            //return '';
		}     
    
    function startCallback() {
        // make something useful before submit (onStart)
        //alert("Going to submit");
        return true;
    }

    function completeCallback(response) {
        saveInitiated = false;
        // check response
        var respData;
        if (response != 0) {
            respData = JSON.parse(response);
            //console.log("res:::"+respData);
        }
        
        if (typeof respData != 'undefined' &&typeof respData.errorFlag != 'undefined' && respData.errorFlag==true) {
            preventOnUnload = true;
            alert(respData.errorMsg);
           window.location.reload(true);
        }
        else{
            alert("Subscription assigned successfully.");
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT?>";
        }
        return true;
    }
	
    function initFormPosting() {
                    AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
            document.body.onload = initFormPosting;
    } else {
            initFormPosting();
    }
    function confirmRedirection()
    {   var choice = confirm("Are you sure you want to cancel? All data changes will be lost.");
        if (choice) {
            preventOnUnload = true;
            window.location.href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_UPGRADE_CONSULTANT?>";
        }
        else{
            preventOnUnload = true;
        }
    }
    
var saveInitiated = false;

function submitClientConsultantMappingFormData(elem,formName)
{
    if (saveInitiated == false) {
	saveInitiated = true;
    }else{
	return ;
    }
    $j('#overAll_error').hide();
    
    $j(elem).prop('disabled',true);
    var errorFlag = false;
    if(showErrorMessage(elem, formName, true)) {
	    errorFlag = true;
    }
    
    if (validateblankClientandConsultantID()) {
		errorFlag = true;
	}
    $j('#costPerResponse_error2').html();
    if (validateCostPerResponse()) {
		errorFlag = true;
	}	
    
    
    if (errorFlag) {
	    $j(elem).prop('disabled',false);
	    $j('#overAll_error').show();
	    saveInitiated = false;
	    return false;
    }
    
    if (!errorFlag) {
        $j('#consultantId').prop('disabled',false);
	$j('#clientId').prop('disabled',false);
       $j('#form_'+formName).submit();
	return true;	
    }
}

function validateblankClientandConsultantID(){
	var error= false;
	$j('#consultantId_error').html('');
	$j('#clientId_error').html('');
	if ($j('#consultantId').prop('disabled')==false || $j('#consultantId').val()=='') {
	    $j('#consultantId_error').html('Enter consultant ID and search first').show();
	    error= true;
	}
	
	if ($j('#clientId').prop('disabled')==false || $j('#clientId').val()=='') {
	    $j('#clientId_error').html('Enter client ID and search first').show();
	    error= true;
	}
	return error;
    }

function validateCostPerResponse(){
    var error= false;
    if ($j('#costPerResponse').val() !='')
    {
	$j('#costPerResponse_error2').html('');
	if ($j('#costPerResponse').val()< minResponsePrice)
	{
	    $j('#costPerResponse_error2').html('Value can\'t be less than minimum value').show();
	    error= true;
	}
	else if($j('#costPerResponse').val()> 2*minResponsePrice){
	    $j('#costPerResponse_error2').html('Value Can\'t be Greater than 2 times the minimum value').show();
	    error= true;
	}
    }
    return error;
}
    
function validateAndFetchConsultantName(obj)
{
    var tupleObject = $j(obj).find('i');
    $j('#consultantId_error').html('');
    if(tupleObject.hasClass('edit-icon')){
	    tupleObject.removeClass('edit-icon').addClass('rank-search-icon'); 
	    $j('#consultantId').prop('disabled',false);
	    $j(obj).next('span').text('Not Mapped');
	    return ;
    }
    
    var consultantId = $j('#consultantId').val();
    var mappingId = $j('#mappingId').val();
	
     var numericRegx = /^(\d)+$/;
     if(!numericRegx.test(consultantId)){
    	
    	alert("Please enter a numeric value.");
    	return;
     }
     
    var ajaxURL = "/consultantPosting/ConsultantPosting/fetchConsultantDetailforClientMapping";
	   $j.ajax({
		 type: 'POST',
		 url : ajaxURL,	
		 data: { 
		        'consultantId'	: consultantId, 
		        'mappingId'	: mappingId
		    },
		 success : function(res){
			var response = JSON.parse(res);
			if (response.error==true) {
			    tupleObject.removeClass('edit-icon').addClass('rank-search-icon');
			    $j('#consultantId_error').html(response.errorMsg).show(); 
			    $j('#consultantId').prop('disabled',false); 
			}else{
			    tupleObject.addClass('edit-icon').removeClass('rank-search-icon');
			    $j(obj).next('span').text(response.data.name);			    
			    $j('#consultantId').prop('disabled',true); 
			}
		 }
			 
	  });
	   	
}

var clientSubsciptionArray = <?= json_encode($mappingData['subscriptionData']['data']['SubscriptionArray'])?>;

function validateAndFetchClientName(obj)
{
    var tupleObject = $j(obj).find('i');
    $j('#clientId_error').html('');
    if(tupleObject.hasClass('edit-icon')){
	    tupleObject.removeClass('edit-icon').addClass('rank-search-icon'); 
	    $j('#clientId').prop('disabled',false);
	    $j(obj).next('span').text('Not Mapped');
	    clientSubsciptionArray = '';
	    populateSubscriptionDropDown();
	    populateCreditTableData();
	    return ;
    }
    var clientId = $j('#clientId').val();
    var numericRegx = /^(\d)+$/;
    if(!numericRegx.test(clientId)){
    	alert("Please enter a numeric value.");
    	return;
    }
    var ajaxURL = "/consultantPosting/ConsultantPosting/fetchClientDetailAndSubscriptions";
	   $j.ajax({
		 type: 'POST',
		 url : ajaxURL,	
		 data: { 
		        'clientId'	: clientId
		    },
		 success : function(res){
			var response = JSON.parse(res);
			if (response.error==true) {
			    tupleObject.removeClass('edit-icon').addClass('rank-search-icon');
			    $j('#clientId_error').html(response.errorMsg).show();
			    $j('#clientId').prop('disabled',false); 
			}else{
			    tupleObject.addClass('edit-icon').removeClass('rank-search-icon');
			    $j(obj).next('span').text(response.data.userName);			    
			    $j('#clientId').prop('disabled',true);
			    clientSubsciptionArray = response.data.SubscriptionArray;
			    populateSubscriptionDropDown();
			    populateCreditTableData();
			}
		 }
			 
	  });
	   	
}

function populateSubscriptionDropDown(){
    
    var optionHtml = '<option value="">Select a Subscription</option>';
    $j.each(clientSubsciptionArray,function(key,value){
	if (consultantBaseProductId==value.BaseProductId) {
	    optionHtml += '<option value="'+value.SubscriptionId+'">'+value.BaseProdSubCategory+'</option>';
	    }
	})
    $j('#subscriptionId').html(optionHtml);
}

function populateCreditTableData() {
     var selectedKey = $j('#subscriptionId').val();
    if (selectedKey=='') {
	$j('#startDateSpan').html('N/A');
	$j('#endDateSpan').html('N/A');
	$j('#creditLeftSpan').html('N/A');
    }else{
	var val = clientSubsciptionArray[selectedKey];
	$j('#startDateSpan').html(dateFormatForCreditSection(val.SubscriptionStartDate));
	$j('#endDateSpan').html(dateFormatForCreditSection(val.SubscriptionEndDate));
	$j('#creditLeftSpan').html(val.BaseProdRemainingQuantity +' Credits');
    }
    
}

function dateFormatForCreditSection(str){

var a= str.split('-')
var b = a[2].split(' ');


var month = new Array();
month[1] = "Jan";
month[2] = "Feb";
month[3] = "Mar";
month[4] = "Apr";
month[5] = "May";
month[6] = "Jun";
month[7] = "Jul";
month[8] = "Aug";
month[9] = "Sep";
month[10] = "Oct";
month[11] = "Nov";
month[12] = "Dec";
var x= parseInt(a[1]);
var c = b[0]+'-'+month[x]+'-'+a[0];
return c;
}    
</script>
