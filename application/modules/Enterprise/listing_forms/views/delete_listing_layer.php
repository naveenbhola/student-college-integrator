<div id="delete_listing_layer" style="display: inline;" class="row">
           <div class="row" style="font-size:14px;"><b>You are about to delete Listing <span id="replace_delete_id"></span> from Shiksha. Please enter the following details (optional):</b></div>
           <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1" style="width:475px">Enter new Listing ID if you want to redirect the users from the deleted Listing ID:</div>
                <div class="row2">
                    <input type="text" style="width:150px" class="inputBorder" id="deleted_listing_redirect_id" value="" name="deleted_listing_redirect_id">
                </div>
            </div>
            <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1" style="width:475px">Enter new Listing ID if you want to merge the questions asked on the deleted Listing ID:</div>
                <div class="row2">
                    <input type="text" caption="phone number" tip="course_contact_phone" style="width:150px" class="inputBorder" minlength="10" maxlength="15" validate="validateext" id="deleted_listing_qna_id" value="" name="deleted_listing_qna_id" profanity="true">
                </div>
            </div>
	 <div style="line-height:9px;clear:both">&nbsp;</div>
            <div class="row">
                <div class="row1" style="width:475px">Enter new Listing ID if you want to merge the alumni feedback on the deleted Listing ID:</div>
                <div class="row2">
                    <input type="text" caption="phone number" tip="course_contact_phone" style="width:150px" class="inputBorder" minlength="10" maxlength="15" validate="validateext" id="deleted_listing_alumni_id" value="" name="deleted_listing_alumni_id" profanity="true">
                </div>
            </div>
           <div class="row2"><input type="button" value="Save & Delete" onclick="saveAndDeleteListing();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="hideOverlayAnA();"/></div>
        </div>
