<?php

?>
<div class="formHeader"><a class="formHeader" name="wikicontent" style="text-decoration:none">Comments</a></div>
    <div class="line_1"></div>
    <div class="spacer10 clearFix"></div>
    <div class="row">
            <div style="float:left;width:200px;line-height:18px; padding-right:5px"><div class="txt_align_r bld">Enter Comments<span class="redcolor fontSize_13p">*</span>:</div></div>
            <div style="float:left;width:405px"><div>
            <textarea style="background-color: #FFF;" class="mceNoEditor" name="mandatory_comments" id="mandatory_comments" maxlength="256" caption="Remarks"></textarea>
            <input type="hidden" id="cmsTrackUserId" name="cmsTrackUserId" value="<?php echo $userid;?>"/>
            <input type="hidden" id="cmsTrackListingId" name="cmsTrackListingId" value="<?php echo $listing_id;?>"/>
            <input type="hidden" id="cmsTrackTabUpdated" name="cmsTrackTabUpdated" value="<?php echo $tab;?>"/>
            </div>
            <div style="display:none"><div class="errorMsg" id="remarksErrorMsg">Please Enter Comments</div></div>
            </div>
            <div class="clearFix"></div>
    </div>
<div class="spacer10 clearFix"></div>