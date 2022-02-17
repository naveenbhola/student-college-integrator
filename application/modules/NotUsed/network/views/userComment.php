<?php echo $this->ajax->form_remote_tag( array(                      
'url' =>
'/messageBoard/MsgBoard/replyMsg/'.$topicId,
                           'success' => 'addComment('.$topicId.',request.responseText);',
'failure'=>'javascript:addComment('.$topicId.',request.responseText,this.form)')
                    );
?>
<script>
var threadId = <?php echo $topicId ;?>;
</script>
<script type="text/javascript">

function stopRKey(evt) {
  var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
      }

      document.onkeypress = stopRKey;

      </script>
<?php
if($flag == "college")
$mem = 'collegeNetwork';
else
$mem = 'schoolNetwork';
?>
<input type = "hidden" id = "success" value = "showrecentComments"/>
<input type ="hidden" name = "collegeId" id ="collegeId" value = "<?php echo $collegeId ?>"/>
<input type ="hidden" name = "userIdComment" id ="userIdComment" value = "<?php echo $userId?>"/>
<input type = "hidden" id = "parent" name = "parent" value = ""/>
<input type = "hidden" id = "thread" name = "thread" value = ""/>
<input type = "hidden" id = "countryId" name = "countryId" value = ""/>
<input type ="hidden" name = "category" id = "category" value = ""/>
<input type ="hidden" name = "MsgId" id = "MsgId" value = ""/>
<input type ="hidden" name = "threadid<?php echo $topicId?>" id = "threadid<?php echo $topicId?>" value = "<?php echo $topicId?>"/>
<input type ="hidden" name = "dotCount<?php echo $topicId?>" id = "dotCount<?php echo $topicId?>" value = "0"/>
<input type ="hidden" name = "secCodeIndex" id = "secCodeIndex" value = "seccode"/>
<input type="hidden" name="fromOthers<?php echo $topicId?>" value="<?php echo $fromOthers; ?>" />

			  <div class="mar_full_10p">
						<div class="lineSpace_15">&nbsp;</div>
						<div class="normaltxt_11p_blk arial" align="left"><span class="fontSize_12p " style = "padding-left:15px">Have something to share or a question is bothering you? Discuss with college group</span></div>
						<div class="lineSpace_10">&nbsp;</div>
<!--						<div class="normaltxt_11p_blk arial"><span class = "fontSize_12p" style = "padding-left:15px">Leave a comment</span></div>-->
						<div style = "padding-left:15px"><textarea style="width:550px;height:60px;"  onkeyup="textKey(this)" name="replyText<?php echo $topicId?>" maxlength = "1000" id = "replyText<?php echo $topicId?>"></textarea></div>
                              <div class="mar_left_15p">
                                 <span id="replyText<?php echo $topicId?>_counter">0</span>&nbsp;out of 1000 characters
                              </div>
<div class="errorPlace" style="margin-top:2px;">
				    <div id="replyText<?php echo $topicId?>_error" class="errorMsg"></div>
				</div>

<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="normaltxt_11p_blk arial"><span style = "padding-left:15px">Type in the characters you see in the picture below :</span></div>
<img style = "padding-left:15px" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode" id = "networkCaptacha"/>
<input type="text" style="width:150px" id = "seccode<?php echo $topicId?>" name = "seccode<?php echo $topicId?>"/>


				<div class="errorPlace" style="margin-top:2px;">
				    <div id="seccode<?php echo $topicId?>_error" class="errorMsg"></div>
				</div>



		<div class="lineSpace_5">&nbsp;</div>				
						<div class="row">
								<div class="buttr2" style="padding-left:145px">
<?php if(!(is_array($validateuser) && $validateuser != "false")) {?>
<button class="btn-submit5 w6" value="" type="submit" onclick = "return showuserLoginOverLay(this,'GROUPS_SCHOOLGROUPDETAIL_MIDDLEPANEL_SUBMITCOMMENT','jsfunction','checkmember',this.form,<?php echo $topicId?>,'<?php echo $flag?>',<?php echo $collegeId?>,'school','Only members are allowed to add comment.Please join the group to participate in discussions','validateComment')">
<?php } else{?>
									<button class="btn-submit5 w6" value="" type="submit" onClick = "return checkmember(this.form,<?php echo $topicId?>,'<?php echo $flag?>',<?php echo $collegeId?>,'school','Only members are allowed to add comment. Please join the group to participate in discussions','validateComment')">
<?php } ?>
									<div class="btn-submit5"><p class="btn-submit6">Submit</p></div>
									</button>
								</div>
								<div class="clear_L"></div>
						</div>
				  </div>
				<div class="lineSpace_15">&nbsp;</div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="mar_full_10p">
				</div>
<?php echo "</form>"?>












