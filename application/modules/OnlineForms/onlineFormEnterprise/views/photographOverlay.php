<div class="dateLayer" style="width:450px; top:75px;display:none;" id="photographLayer">
	<div class="layerContent">
    	<p class="ieFix">Request photographs from the applicant, this action will send a message to the student asking for the following photographs</p>
		<div class="spacer15 clearFix"></div>
		<div class="ieFix" id="requestPhotographContentOverlay"></div>
        <div class="spacer15 clearFix"></div>
        <h4 class="ieFix">How do you want to receive this photograph?</h4>
        <p class="ieFix">
        	<span id="emailPhotograph" class="inputRadioSpacer"><input type="radio" name="mstatus" checked="checked" class="radio" onclick="setRequestPhotographType('email');"> 				<span>Email</span>
            </span>
            <span id="postPhotograph" class="inputRadioSpacer"><input type="radio" name="mstatus" class="radio" onclick="setRequestPhotographType('post');"> 
            <span>By post</span></span>
            <span id="emailpostPhotograph" class="inputRadioSpacer"><input type="radio" name="mstatus" class="radio" onclick="setRequestPhotographType('both');"> 
            <span>Both</span></span>
        </p>
		<div class="spacer15 clearFix"></div>
        <input type="button" value="Send Message" title="Send Message" class="attachButton" id="8" onclick="x=sendAlerts();x(13);"/> &nbsp;
        <a href="javascript:void(0);" title="Cancel" onclick="hideOnlineFormLayer('documentLayer');">Cancel</a>
     </div>
</div>