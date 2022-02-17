<?php echo $this->ajax->form_remote_tag( array(                      
'url' =>
'/network/Network/submitSchoolComment',
                           'success' =>
'addSchoolcommentResponse(request.responseText,this.form);',

'failure'=>'javascript:addSchoolcommentResponse(request.responseText,this.form)')
                    );
?>
<input type = "hidden" id = "success" value = "showrecentComments"/>
<input type ="hidden" name = "collegeId" id ="collegeId" value = "<?php echo $collegeId ?>"/>
<input type ="hidden" name = "userIdComment" id ="userIdComment" value = "<?php echo $userId?>"/>

				  <div class="mar_full_10p">
						<div class="lineSpace_5">&nbsp;</div>
						<div class="normaltxt_11p_blk arial" align="center"><span class="fontSize_12p bld OrgangeFont">Message Board</span></div>
						<div class="lineSpace_10">&nbsp;</div>
					<div id = "newTitle">	
						<div class="normaltxt_11p_blk arial"><span>Title</span></div>
<input type="text" style="width:220px" id = "titleofComment" name = "titleofComment"/>
<div class="errorPlace" style="margin-top:2px;">
				    <div id="titleofComment_error" class="errorMsg"></div>
				</div>
						</div>
<div class="lineSpace_10">&nbsp;</div>


						<div class="normaltxt_11p_blk arial"><span>Leave a comment</span></div>
						<div><textarea style="width:220px" id = "comment" name = "comment"></textarea></div>
<div class="errorPlace" style="margin-top:2px;">
				    <div id="comment_error" class="errorMsg"></div>
				</div>

<div class="lineSpace_10">&nbsp;</div>
<!--<div class="normaltxt_11p_blk arial">0 of 1000 character used</div>-->
<div class="lineSpace_5">&nbsp;</div>
<div class="normaltxt_11p_blk arial"><span>Enter the text as it is shown in the box below</span></div>
<img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>" id = "registerCaptacha"/>
<input type="text" style="width:100px" id = "securityCode" name = "securityCode"/>


				<div class="errorPlace" style="margin-top:2px;">
				    <div id="securityCode_error" class="errorMsg"></div>
				</div>



		<div class="lineSpace_5">&nbsp;</div>				
						<div class="row">
								<div class="buttr2" style="padding-left:75px">
<?php if(!(is_array($validateuser) && $validateuser != "false")) { ?>
	<button class="btn-submit5 w6" value="" type="submit" onclick = "return showuserOverlay(this,'addComment','<?php echo site_url('network/Network/schoolNetwork/'.$collegeId)?>',1)">
<?php } else{?>
									<button class="btn-submit5 w6" value="" type="submit" onclick = "return validateSchoolComment(this.form);">
<?php  } ?>
<!--<button class="btn-submit5 w6" value="" type="submit" onclick = "return validateSchoolComment(this.form);">-->
									<div class="btn-submit5"><p class="btn-submit6">Submit</p></div>
									</button>
								</div>
								<div class="clear_L"></div>
						</div>
				  </div>
				<div class="lineSpace_15">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="mar_full_10p">
					<div class="normaltxt_11p_blk arial" align="center"><span class="fontSize_12p bld OrgangeFont">Recent Comments</span></div>
				</div>
</form>













