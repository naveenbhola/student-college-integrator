
<form method="post" onsubmit="new Ajax.Request('<?php echo $url;?>',{onSuccess:function(request){javascript:showAbuseReportSuccess(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" name="reportAbuseForm" id="reportAbuseForm" action="<?php echo $url;?>">			
			<div style="margin-left:15px;margin-right:15px;margin-bottom:10px;">
				<div class="bld" id="messageForReply"></div>
				<div class="bld" style="padding-bottom:5px;padding-top:5px;" ><span >Kindly select reason(s) from below & cast your vote* for removal of this content</span> </div>
				<div style="line-height:5px">&nbsp;</div>
			<?php
			  if(isset($reportAbuseFields))
			  {
			    foreach ($reportAbuseFields as $reportAbuseField){
			    ?>
			      <div class="row">
			      <INPUT NAME="abuseReason" TYPE="CHECKBOX" VALUE="<?php echo $reportAbuseField['ID']; ?>" ID="<?php echo $reportAbuseField['ID']; ?>" <?php if($reportAbuseField['ID'] == 6){?>onchange="toggleOtherTextarea(<?php echo $reportAbuseField['ID']; ?>)"<?php }?>>
			      <span id="text<?php echo $reportAbuseField['ID']; ?>"><b><?php echo $reportAbuseField['Title']; ?></b><?php echo " - ".$reportAbuseField['Content']; ?></span><BR>
			      </div>
			      <div class="lineSpace_5">&nbsp;</div>
			    <?php
			    }
			  }
			  ?>
			<div class="row" id="otherAbuseReasonDiv" style="display:none">
			    <textarea minLength="5" maxLength="100" id="otherAbuseReasonTextarea" style="width: 400px; height: 50px; margin: 5px 0px 0px 22px;"></textarea>
			</div>
			  <div id="reputationPointValDiv" style="display:none;"><?php echo $reputationPoints;?></div>
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="checkboxreportAbuse_error"></div></div>
<!--			<div style="padding:10px 0 5px 0">Type in the characters you see in picture below:</div>
			<div>
				<img src="/public/images/blankImg.gif" onabort="reloadCaptcha(this.id)" onClick="reloadCaptcha(this.id)" id="secimg_reportAbuse" align="absmiddle" /> &nbsp; &nbsp;
				<input type="text" type="text" name="seccodereportAbuse" id="seccodereportAbuse" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" />
			</div>-->
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccodereportAbuse_error"></div></div>
			<div style="line-height:5px">&nbsp;</div>
			
			<input type="hidden" name="msgIdReportAbuse" id="msgIdReportAbuse" value="0" />
			<input type="hidden" name="ownerIdReportAbuse" id="ownerIdReportAbuse" value="0" />
			<input type="hidden" name="parentIdReportAbuse" id="parentIdReportAbuse" value="0" />
			<input type="hidden" name="threadIdReportAbuse" id="threadIdReportAbuse" value="0" />
			<input type="hidden" name="entityTypeReportAbuse" id="entityTypeReportAbuse" value="0" />
			<input type="hidden" name="eventIdReportAbuse" id="eventIdReportAbuse" value="0" />
			<input type="hidden" name="articleIdReportAbuse" id="articleIdReportAbuse" value="0" />
			<input type="hidden" name="chosenReasonList" id="chosenReasonList" value="" />
			<input type="hidden" name="chosenReasonText" id="chosenReasonText" value="" />
			<input type="hidden" name="isOverlayAbuseForm" id="isOverlayAbuseForm" value="0" />
			<input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'>
			<div style="padding-top:10px"><input type="Submit" value="Report Now" onclick = "if(!validateAbuseForm()){return false;}else{if(!isNewUserReportsAbuse()){ return false;}}" class="fbBtn" id="submitButtonreportAbuse" /> &nbsp; <a href="javascript:void(0);" onClick="$('genOverlayContentsAnA').innerHTML = '';hideOverlayAnA();">Cancel</a></div>
			<div style="line-height:5px">&nbsp;</div>
			<!--<div style="padding-bottom:5px" class="fcGya"><span>* Each level has different voting rights, vote of members with level Lead Advisor and above can alone remove objectionable content.</span> </div>-->
			</form>
