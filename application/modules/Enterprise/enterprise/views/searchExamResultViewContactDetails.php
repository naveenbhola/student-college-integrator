<div id="contactInfoOverlay" style="display:none; position: absolute; left: 400px; top: 100px;z-index:100000001;">
    <div style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; width: 445px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;">
        <div style="border: 1px solid rgb(213, 211, 214);">
            <div style="background:#6391CC;height:29px">
                <div class="lineSpace_7">&nbsp;</div>
                <div style="width: 90%;" class="float_L">
                    <div style="width: 100%;">
                        <div class="redcolor" style="padding-left: 10px;font-size:16px;color:white;"><b>View Contact Details!</b></div>
                    </div>
                </div>
                <div style="width: 10%;" class="float_L">
                    <div style="width: 100%;">
                        <div style="padding-right: 10px;" class="txt_align_r"><img border="0" style="cursor: pointer;" src="/public/images/crossImg_16x14.gif" onClick="hideOverlay();"/></div>
                    </div>
                </div>
                <div class="cmsClear"> </div>
            </div>
            <div style="width: 100%;">
                <div style="padding: 10px;">
                    <div style="padding-bottom: 3px;" id="contactdiv">
                    </div>
                    <div align="center" style="padding-top: 15px;"><input onClick="hideOverlay();" type="button" value="OK" class="submitGlobal"/></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.blkRound .layer-outer{background:none !important; border:0 none !important; padding:0 !important}
</style>
<!--Start_GenricOverlay-->
<div style="position:absolute;left:400px;top:100px;display:none;z-index:100000001" id="genricOverlay">
	<div style="width:445px;background:#FFF">
    	<div style="border:1px solid #d5d3d6">
        	<div style="background:#6391CC;height:29px">
            	<div class="lineSpace_7">&nbsp;</div>
            	<div class="float_L" style="width:90%">
                	<div style="width:100%">
                    	<div style="padding-left:10px" class="redcolor"><b id="genricOverlayTitle">&nbsp;</b></div>
                    </div>
                </div>
                <div class="float_L" style="width:10%">
                	<div style="width:100%">
                    	<div class="txt_align_r" style="padding-right:10px"><img src="/public/images/crossImg_16x14.gif" style="cursor:pointer" border="0" onClick="hideOverlay();" /></div>
                    </div>
                </div>
                <div class="cmsClear">&nbsp;</div>
            </div>
            <div style="width:100%">
            	<div style="padding:10px">
					<div id="genricOverlayContent">&nbsp;</div>
                    <div align="center" style="padding-top:15px">
						<span id="genricOverlayButton"></span>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End_GenricOverlay-->
<!--Start_EmailOverlay-->
<div style="position:absolute;display:none;z-index:100000001;" id="searchResultEmailOverlay">
    <form name="emailOverlayForm" id="emailOverlayForm">
 	    <div class="layer-outer">
    	    <div>
        	    <div>
            	    <div class="lineSpace_7">&nbsp;</div>
            	    <div class="layer-title">
                        <a href="javascript:void(0)" onclick="hideOverlay();" class="close"></a>
                	<h4 class="layer-title">
                    	Send Mail
                    </h4>
                </div>               
                <div class="cmsClear">&nbsp;</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
                <div style="width:100%">
                    <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px"><b>From Email:</b> &nbsp;</div>
                        </div>
                    </div>
                    <div class="float_L" style="width:340px">
                        <div style="width:100%">
                        	<div>
                                <input type="text" profanity="true" style="width:285px" value="" id="fromEmail" name="fromEmail" validate="validateEmail" caption="email address" required="1" maxlength="80" />
                            </div>
                            <div class="errorPlace">
                                <div id="fromEmail_error" class="errorMsg">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
	            <div class="lineSpace_10">&nbsp;</div> 
	            <div style="width:100%">
		            <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px"><b>From Sender:</b> &nbsp;</div>
                        </div>
                    </div>
		            <div class="float_L" style="width:340px">
                        <div style="width:100%">
                        	<div>
                                <input type="text" profanity="true" style="width:285px" value="" id="fromSender" name="fromSender" validate="validateStr" caption="Sender Name" required="1" minlength="3" maxlength="255" />
                            </div>
    				        <div class="errorPlace">
                                <div id="fromSender_error" class="errorMsg">&nbsp;</div>
                            </div>
			            </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <div style="width:100%">
                    <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px"><b>Subject:</b> &nbsp;</div>
                        </div>
                    </div>
                    <div class="float_L" style="width:340px">
                        <div style="width:100%">
                        	<div>
                                <input type="text" profanity="true" style="width:285px" value="" id="emailSubject" name="emailSubject" validate="validateStr" caption="email subject" required="1" minlength="3" maxlength="255" />
                            </div>
                            <div class="errorPlace">
                                <div id="emailSubject_error" class="errorMsg">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
                <div class="lineSpace_10">&nbsp;</div>
                <div style="width:100%">
                    <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px"><b>Message:</b> &nbsp;</div>
                        </div>
                    </div>
                    <div class="float_L" style="width:340px">
                        <div style="width:100%">
                            <div>
                                <textarea profanity="true" style="width:340px;height:135px" id="emailContent" name="emailContent" validate="validateStr" caption="message" required="1" minlength="3" maxlength="5000"></textarea>
                            </div>
                            <div class="errorPlace">
                                <div id="emailContent_error" class="errorMsg">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
                <div class="lineSpace_1">&nbsp;</div>
                <div style="width:100%">
                    <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px">&nbsp;</div>
                        </div>
                    </div>
                    <div class="float_L" style="width:340px">
                        <div style="width:100%">
                        	<div style="margin-left:-4px"><input type="checkbox" id="rememberEmailTemplate"/> Remember the template for this session</div>
                        </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
                <div class="lineSpace_15">&nbsp;</div>
                <div style="width:100%">
                    <div class="float_L" style="width:100px">
                        <div style="width:100%">
                        	<div class="txt_align_r" style="line-height:20px">&nbsp;</div>
                        </div>
                    </div>
                    <div class="float_L" style="width:340px">
                        <div style="width:100%">
                        	<div>
                                <input id="sendEmailButton" type="submit" class="cmsSearch_sendMail" value="Send Mail" /> &nbsp; &nbsp; <a href="#" onClick="hideOverlay();return false;" class="Fnt14">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div class="cmsClear">&nbsp;</div>
                </div>
                <div class="lineSpace_15">&nbsp;</div>
            </div>
        </div>
    </form>
</div>
<!--End_EmailOverlay-->