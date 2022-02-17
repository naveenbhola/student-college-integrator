<div class="float_L" style="width:35%;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">
        How do you plan to fund your education<b class="redcolor">*</b>:
        <div style="color:#6b6b6b;font-size:11px">*approx 10 - 15 lacs</div>
    </div>
</div>
<div style="width:63%;float:right;text-align:left;">
    <div class="float_L">
        <div>
            <div style="display:block;">
                <input style="margin-left:0px;"  blurMethod="educationFundsOnBlur();" type="checkbox" id="UserFundsOwn" name="UserFundsOwn" value="yes"/> Own funds &nbsp; 
                <input type="checkbox" blurMethod="educationFundsOnBlur();" id="UserFundsBank" name="UserFundsBank" value="yes"/> Education Loan &nbsp;<br/> 
            </div>
            <div style="display:block;">
                <input style="margin-left:0px;"  blurMethod="educationFundsOnBlur();" type="checkbox" id="UserFundsNone" name="UserFundsNone" value="yes" onclick="$('otherFundingDetails').style.display = this.checked ?  '' : 'none'; "/> Other &nbsp; 
                <input type="text" name="otherFundingDetails" id="otherFundingDetails" value="" style="display:none;width:50%;" maxlength="50" size="15"/>
            </div>    
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
            <div class="errorMsg" id= "sourceFunding_error"></div>
        </div>
    </div>
</div>
<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>

<script type="text/javascript">
    function educationFundsOnBlur() {
		if((document.getElementById("UserFundsOwn").checked == false ) && (document.getElementById("UserFundsOwn").checked == false) && (document.getElementById("UserFundsNone").checked == false) ) {
				document.getElementById("sourceFunding_error").innerHTML = "Please specify your source(s) of funding.";
				document.getElementById("sourceFunding_error").parentNode.style.display = "inline";
				return false;
		} else {
			document.getElementById("sourceFunding_error").innerHTML = "";
			document.getElementById("sourceFunding_error").parentNode.style.display = "none";
			return true;
		}
    }
</script>