<?php 
	$urlForQuestion = getSeoUrl($main_message['threadId'],'question',$main_message['msgTxt']); 
?>            
			<!--Start_Share_this_Question-->
            <div class="bgBlueTitleBar">
            	<div class="bld OrgangeFont fontSize_14p">Share this Question</div>
                <div class="lineSpace_10">&nbsp;</div>
			<?php 
				$userInfoArray = explode('|',$validateuser[0]['cookiestr']);
				$email = $userInfoArray[0];
				echo $this->ajax->form_remote_tag( array(

                            'url' => '/search/sendMail',
                            'update' => '',
			    'before' => 'if(!validateEmailForm()){return false;}',  	
                            'success' => 'javascript:mailSentForShareQuestion(request.responseText)'
) 
                    ); 
				$NameOFUser = (trim($validateuser[0]['firstname']) == '')?$validateuser[0]['firstname']:$validateuser[0]['displayname'];
				$extraParams = base64_encode(serialize(array('NameOFUser' => $NameOFUser)));
			?>	
				<div class="fontSize_12p" style="padding-bottom:7px;">Enter email addresses separated by comma or semicolon to email this question to a friend who might know the answer</div>
				<input type="hidden" name="extraParams" value="<?php echo $extraParams; ?>" />
				<input type="hidden" name="fromAddress" id="fromAddressForShare" value="<?php echo $email; ?>"/>
				<input type="hidden" name="subject" id="subjectForShare" value="Shiksha Q & A: <?php echo $NameOFUser; ?> sent you a question"/>
				<input type="hidden" name="listingTypeForMail" value="qnaShareQuestion"/>
				<input type="hidden" name="listingUrlForMail" value="<?php echo $urlForQuestion; ?>"/>				
                <div><textarea style="width:60%; height:100px" name="searchEmailAddr" id="emailIdsForShare"></textarea></div>
					<div id="emailIdsForShare_error" class="mar_left_10p errorMsg"></div>
                <div class="lineSpace_10">&nbsp;</div>
                <div style="line-height:30px"><input type="Submit" id="emailIdsForShareButton" class="submitGlobal" value="Send" /></div>
			</form>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="mar_full_10p"><div class="showMessages" style="display:none;" id="confirmMsg_forShare"></div></div>
			<div class="lineSpace_10">&nbsp;</div>
            </div>
            <!--End_Share_this_Question-->
            <div class="lineSpace_10">&nbsp;</div>