<?php 
	$userProfile = site_url('getUserProfile').'/';        
	$entityTypeShown = isset($entityTypeShown)?$entityTypeShown:'';
	$url = site_url("messageBoard/MsgBoard/replyMsg");
?>

<div class="comment-wrapp">
<div class="comment-details">
<div class="fbkBx" id="completeMsgContent<?php echo $msgId; ?>" style="background:none; width:600px">
	<div>
		<div class="float_L wdh100">
			<div class="imgBx">
			    <img id="userProfileImageForComment<?php echo $msgId.rand(0, 10000);?>" src="<?php echo getSmallImage($userProfileImage);?>" style="cursor:pointer;" onClick="window.location=('<?php echo site_url('getUserProfile').'/'.$displayName; ?>');" />
			</div>
			<div class="cntBx">
				<div class="wdh100 float_L">
					<div>
	                                  <span class="lquote"></span>
                                          <div style="margin-left:30px;font:14px/19px Tahoma,Geneva,sans-serif !important;">					  
						  <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $displayNameId; ?>');" ><strong><a href=""><?php echo $displayName; ?></a></strong></span>
						  <span title="click to change your display name"  style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp;
						  <span class="fcdGya" style="font-size:12px">a few secs ago</span>
						  <div style="padding-bottom:10px;position:relative" id="msgTxtContent<?php echo $msgId; ?>" class="lineSpace_18">
						  <?php 
								  $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
								  echo formatQNAforQuestionDetailPage($text,30);
						  ?>
						  </div>
					  </div>
					</div>
					<div class="clear_B">&nbsp;</div>				

					<div style="line-height:1px;clear:both">&nbsp;</div>
					<div class="showMessages" style="display:none;" id="confirmMsg<?php  echo $msgId; ?>"></div>
				</div>
			</div>
		</div>
		<s>&nbsp;</s>
	</div>
</div>

<div id="<?php echo 'repliesContainer'.$msgId; ?>" style="display:block;"></div>
<?php
	$functionToCall = isset($functionToCall)?$functionToCall:'-1';
	$dataArray = array('userId'=>$displayNameId,'userImageURL'=>$userProfileImage,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 0,'detailPageUrl' =>'','functionToCall' => '-1', 'fromOthers' => 'blog', 'msgId' => $msgId, 'mainAnsId' => $msgId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'messageToShow'=>'Reply...');
	$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
	
?>
<div id="replyPlace<?php echo $msgId;  ?>" style="margin-left:50px;"></div>
<div style="width:92%;margin-left:96px;margin-bottom:10px;">
  <?php echo $inlineFormHtml; ?>
</div>

</div>
<div class="comment-pointer">&nbsp;</div>
</div>


<div class="grayLine"></div>
