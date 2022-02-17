<div class="find-field-row" id = "sourceOfFunding" style="display : none;">
<label>How do you plan to fund your education:</label>
	<div class="formCont">
    	<div>
            <input type="checkbox" id="UserFundsOwn" name="UserFundsOwn" onclick ="checkSourcesOfFunding();" value="yes"/> Own funds &nbsp; 
            <input type="checkbox" id="UserFundsBank" name="UserFundsBank" onclick ="checkSourcesOfFunding();" value="yes"/> Education Loan &nbsp; 
            <div class="clearFix spacer5"></div>
            <input type="checkbox" id="UserFundsNone" name="UserFundsNone" value="yes" onclick="$('otherFundingDetails').style.display = this.checked ?  '' : 'none'; checkSourcesOfFunding();"/> Other &nbsp; 
            <input type="text" class="form-txt-field" name="otherFundingDetails" id="otherFundingDetails" value="" style="display:none; width:120px;" maxlength="50" size="15"/>
    	</div>
		
        <div>
        	<div class="errorPlace" style="display:none;">
            	<div class="errorMsg" id= "sourceFunding_error"></div>
        	</div>
    	</div>
	</div>
    </div>
    
