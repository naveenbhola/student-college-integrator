<div class="dateLayer" style="width:450px; top:75px;display:none;" id="documentLayer">
                    <div class="layerContent">
                    	<p class="ieFix">Request documents from the applicant, this action will send a message
to the student asking for the following documents
						</p>
                        <div class="spacer15 clearFix"></div>
                        <div class="ieFix" id="requestDocumentContentOverlay"></div>
                        <div class="spacer15 clearFix"></div>
                        <h4 class="ieFix">How do you want to receive this documents?</h4>
                        <p class="ieFix">
                        	<span id="emailDocument" class="inputRadioSpacer"><input type="radio" name="mstatus" checked="checked" class="radio" onclick="setRequestDocumentType('email');"> <span>Email</span></span>
                        	<span id="postDocument" class="inputRadioSpacer"><input type="radio" name="mstatus" class="radio" onclick="setRequestDocumentType('post');"> <span>By post</span></span>
                        	<span id="emailpostDocument" class="inputRadioSpacer"><input type="radio" name="mstatus" class="radio" onclick="setRequestDocumentType('both');"> <span>Both</span></span>
                        </p>
						<div class="spacer15 clearFix"></div>
                        <input type="button" value="Send Message" title="Send Message" class="attachButton" id="8" onclick="x=sendAlerts();x(8);"/> &nbsp;
                        <a href="javascript:void(0);" title="Cancel" onclick="hideOnlineFormLayer('documentLayer');">Cancel</a>
                    </div>

                </div>