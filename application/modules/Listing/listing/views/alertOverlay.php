<!--Create Course/College Alert-->
<div id="alertform" class="w102"  style="display:none; width:500px;">
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="alertformTitle"></div>
	</div>
	<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk">Send me an alert if, <span class="mar_left_3p normaltxt_11p_blk" id="alertformCatTitle">similar information is added in any of these categories</span></div>
	</div>
	<div class="lineSpace_5">&nbsp;</div>
	
    <div id="error_msg" class="error_msg red mar_left_10p" style="color:red;" ></div>
	<div class="lineSpace_10">&nbsp;</div>
	<?php $url = site_url('alerts/Alerts/createUpdateAlert').'/12';
	     echo $this->ajax->form_remote_tag( array('url'=> $url,'before'=>'javascript:setcatidforalert();','success' => 'updateAlertResponse(request.responseText)'));  
	?>

	    <input type="hidden" name="productId" id="alertproductId"  value="<?php echo $details['alertProductId']; ?>"/>	
    	<input type="hidden" name="productName" id="alertproductName"  value="<?php  echo $details['alertProductName']; ?>"/>
    	<input type="hidden" name="alertType" id="alertType"  value="byCategory"/>
    	<input type="hidden" name="alertName" id="alertName"  value=""/>				
    	<input type="hidden" name="hiddencatidvalue" id="hiddencatidvalue"  value=""/>				
    	<input type="hidden" name="alertId" id="alertId"  value=""/>
    	<div class="row" id="alertcategoryPlaceform" class="formInput inline">
<div class="float_L mar_left_10p" id="All" style="width:89px;display: inline;">Category:
</div>
            <?php 
            echo $catOptString;
            ?>
            <div class="clear_L"></div>
    	</div>
		<div class="lineSpace_10">&nbsp;</div>						

    	<div class="row">
	    	<div class="float_L normaltxt_11p_blk pd_left_10p lineSpace_20" style="width:89px;">Frequency:</div>
		    <div class="float_L normaltxt_11p_blk">
			    <select class="w20" name="frequency" id="alertfrequency">
				    <option value="daily">Once a day</option>
    				<option value="weekly">Once a week</option>
	    			<option value="monthly">Once a month</option>
		    	</select>
    		</div>
	    	<br clear="left" />
    	</div>
		<div class="formField errorPlace">
            <div id="alertfrequency_error" class="errorMsg"></div>
        </div>
		<div class="lineSpace_10">&nbsp;</div>	

	    <div class="row">
		    <div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		    <div class="float_L normaltxt_11p_blk" id="alertdeliverto">
    			<div> 
                    <input type="checkbox" name="emailCheck" id="alertemailCheck" value="on"  checked disabled/> Email 
                    <?php if(isset($email)) {echo $email;} ?>
                </div> 
                <div class="lineSpace_5">&nbsp;</div>
			    <div> 
                    <input type="checkbox" name="smsCheck" id="alertsmsCheck" value="on" onchange="checkForMobile(this.checked)" onclick="checkForMobile(this.checked);" /> Mobile 
                    <?php
                        $mobile =(isset($validateuser[0]) && isset($validateuser[0]['mobile']) && $validateuser[0]['mobile'] != 0) ? $validateuser[0]['mobile'] : '';
                    ?>
                    &nbsp;
                        <input type="text" readonly id="mobile" name="loggedInUserMobile" value="<?php echo $mobile;?>"  maxlength="15" style="border:none; background:transparent;" minlength="4" caption="Mobile Number"/>
                </div> 
                <div class="row errorPlace">
                    <div class="errorMsg" id="mobile_error" ></div>
                    <div class="clear_L"></div>
                </div>

                <div class="lineSpace_5">&nbsp;</div>
    		</div>
	    	<br clear="left" />
    	</div>
	    <div class="lineSpace_10">&nbsp;</div>								
		<!--[if IE 6]> 
			<style>
			* html .ieAlignBtn{position:relative;top:-7px}
			</style>
		<![endif]-->
		<!--[if lte IE 7]> 
			<style>
			.ie_leftAlignBtn{position:relative;left:-20px}
			</style>
		<![endif]-->
	    	<div align="center">
				<button type="Submit" name="Submit" onClick="return validateAlertFields(this.form);">
					<div class="shikshaEnabledBtn_sL" style="padding:0 0 0 3px;width:104px;cursor:pointer">
						<span class="shikshaEnabledBtn_sR" style="padding:0 5px" id="alertsubmitbutton">Create alerts</span>
					</div>
				</button>
				<button type="button" onClick="hideOverlay();" class="ieAlignBtn ie_leftAlignBtn">
					<div class="shikshaDisableBtn_sL" style="padding:0 0 0 3px;width:70px;cursor:pointer">
						<span class="shikshaDisableBtn_sR" style="padding:0 5px">Cancel</span>
					</div>
				</button>
					<!--<div class="buttr3">
						<button class="btn-submit13 w3" type="Submit" name="Submit" onClick="return validateAlertFields(this.form);">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" id="alertsubmitbutton">Create alerts</p></div>
						</button>
					</div>
					<div class="buttr2">
						<button class="btn-submit5 w3" type="button" onClick="hideOverlay();">
							<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
						</button>
					</div>
					<div class="clear_L"></div>-->
	    	</div>			
	</form>
	<div class="lineSpace_10">&nbsp;</div>	
</div>
<script>
    function validateAlertFields(FormObject){
        if((FormObject.loggedInUserMobile) && (FormObject.smsCheck.checked==true))
        {
            document.getElementById('mobile_error').parentNode.style.display = 'none';	
            var result = validateInteger(FormObject.loggedInUserMobile.value,'mobile no.',15,4);
            if(result != true)
            {
                document.getElementById('mobile_error').parentNode.style.display = 'inline';	
                document.getElementById('mobile_error').innerHTML=result;
                return false;
            }
            else
            {	
                loggedInUserMobileNo = FormObject.loggedInUserMobile.value;
            }
        }
        var flag = validateFields(FormObject);
        return flag;
    }

var mobileNumber =  document.getElementById('mobile').value;
function checkForMobile(showMobileFlag){
    if(mobileNumber == "") {    
        if(showMobileFlag){
            document.getElementById('mobile').style.border= 'solid 1px #000';
            document.getElementById('mobile').readOnly= false;
            document.getElementById('mobile').focus();
        }else{
            document.getElementById('mobile').style.border= 'none';
            document.getElementById('mobile').readOnly= true;
            document.getElementById('mobile').value = '';
        }
    }
}

function showAlertListingOverlay(){
    var overlayWidth = 550;
    var overlayHeight = window.screen.height/2;
    var overlayTitle = 'Create an Alert';
    var overlayContent = document.getElementById('alertform').innerHTML;
    overlayParent = document.getElementById('alertform');
    showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent);
}

function updateAlertResponse(responseText)
{ 
    var response = eval("eval("+responseText+")");  

    var errorMsgId = 'error_msg';
    if(response.result != 0)
    {   
        document.getElementById(errorMsgId).innerHTML = response.error_msg; 
        document.getElementById(errorMsgId).style.display = 'inline'; 
    }
    else
    {
        document.getElementById('alertSuccessMsg').innerHTML = 'Successfully subscribed to '+document.getElementById('listingalertCategory').options[document.getElementById('listingalertCategory').selectedIndex].innerHTML+'. ';
        document.getElementById(errorMsgId).style.display = 'none'; 
        document.getElementById('alertSuccessMsg').style.display = "block"; 
        var selectElem = document.getElementById('listingalertCategory'); 
        selectElem.removeChild(selectElem.options[selectElem.selectedIndex]);
        if(selectElem.length <= 0){
            document.getElementById('alertsubscribebutton').style.display="none";
            document.getElementById('alertSubscribeString').innerHTML='';   
            document.getElementById('alertSubscribeString').style.display="none";   
        }
        hideOverlay();
    }
}

function setcatidforalert(){
    document.getElementById('hiddencatidvalue').value = document.getElementById('listingalertCategory').options[document.getElementById('listingalertCategory').selectedIndex] ;
}


</script>
<!--Create Course/College Alert -->
