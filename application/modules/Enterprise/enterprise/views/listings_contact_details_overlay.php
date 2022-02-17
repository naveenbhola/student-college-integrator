<div id="contact_details_div" style="display: inline;" class="row">
        <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1"><b>Name of person:</b></div>
                <div class="row2">
                    <input type="text" caption="Person Name" tip="course_contact_name" style="width:150px" class="inputBorder" minlength="5" maxlength="100" validate="validateStr" id="contact_name_location" value="" name="contact_name_location" profanity="true" onBlur="hideErrorMsgParentDiv(this.id);">
                    <div style="display:none"><div id="contact_name_location_error" class="errorMsg"></div></div>
                </div>
            </div>
            <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1"><b>Main Phone:</b></div>
                <div class="row2">
                    <input type="text" caption="phone number" tip="course_contact_phone" style="width:150px" class="inputBorder" minlength="10" maxlength="15" validate="validateext" id="contact_phone_location" value="" name="contact_phone_location" profanity="true" onBlur="hideErrorMsgParentDiv(this.id);">
                    <div style="display:none"><div id="contact_phone_location_error" class="errorMsg"></div></div>
                </div>
            </div>
            <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1"><b>Mobile number:</b></div>
                <div class="row2">
                    <input type="text" caption="mobile number" tip="course_contact_mobile" style="width:150px" class="inputBorder" minlength="10" maxlength="20" validate="validateext" id="contact_mobile_location" value="" name="contact_mobile_location" profanity="true" onBlur="hideErrorMsgParentDiv(this.id);">
                    <div style="display:none"><div id="contact_mobile_location_error" class="errorMsg"></div></div>
                </div>
            </div>
            <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1"><b>Email address:</b></div>
                <div class="row2">
                    <input type="text" caption="contact email" style="width:150px" class="inputBorder" minlength="0" maxlength="125" validate="validateEmail" id="contact_email_location" value="" name="contact_email_location" profanity="true" onBlur="hideErrorMsgParentDiv(this.id);">
                    <div style="display:none"><div id="contact_email_location_error" class="errorMsg">show</div></div>
                </div>
            </div>
            <div style="line-height:9px;clear:both">&nbsp;</div>
            <div id="bttnContainerDiv">
                <div class=row2>
                            <a href="javascript:void(0);" onclick="$('contact_name_location').value ='';$('contact_phone_location').value ='';$('contact_mobile_location').value ='';$('contact_email_location').value ='';">Reset All Fields</a>
                </div>
                <div class="row2">
                            <input type="button" value="Save" name="bttnSave" id="bttnSave" onclick="updateListingsContactInfo();$('contact_details_div').style.display='none';"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="bttnCancel" id="bttnCancel" value="Cancel" onclick="hideOverlayAnA();$('contact_details_div').style.display='none';"/>
                </div>
            </div>
        </div>