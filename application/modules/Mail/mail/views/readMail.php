<?php foreach($mail['receivers_ids'] as $ids)
	$str .= $receiverDetails[$ids]['displayname'].",";
$str = substr($str,0,strlen($str)-1);
?>
<form action="delete/<?php echo $type; ?>" onsubmit="new Ajax.Request('delete/<?php echo $type;?>',{ onComplete: function () { getMails(document.getElementById('<?php echo $type."_link";?>'));}, parameters:Form.serialize(this)}); return false;" method="post" >
<input type="hidden" value="<?php echo addslashes($senderDetails[$mail['senders_id']]['displayname']);?>" id="sender">
<input type="hidden" value="<?php echo addslashes($str); ?>" id="receivers" >
<input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
<input type="hidden" name="mailId[]" value="<?php echo $mail['mail_ids'][0];?>">
<input type="hidden" name="flag" id="flag" value="">
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_inbox">
		<div style="display:inline; float:left; width:100%">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<?php $this->load->view('mail/mailPagination');?>
				<div class="buttr2">
					<button class="btn-submit5 w6" type="submit" onClick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" type="button" onclick="showReply()">
						<div class="btn-submit5"><p class="btn-submit6" >Reply</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" type="button" onclick="showForward()">
						<div class="btn-submit5"><p class="btn-submit6">Forward</p></div>
					</button>
				</div>
				<?php if ($type=="inbox"):?>
				<!--<div class="buttr2">
					<button class="btn-submit5 w3" type="button">
						<div class="btn-submit5"><p class="btn-submit6">Block User</p></div>
					</button>
				</div>-->
				<?php endif; ?>
				<?php if ($type=="trash"):?>
				<div class="buttr2">
					<button class="btn-submit5 w3" type="submit" onclick="restoreMails();">
						<div class="btn-submit5"><p class="btn-submit6">Restore</p></div>
					</button>
				</div>
				<?php endif; ?>
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="grayLine"></div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="normaltxt_11p_blk_arial fontSize_16p bld"><?php echo $mail['subject']; ?></div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="brdsky lineSpace_3" style="background-color:#ECF3FD">&nbsp;</div>
			
			<!--Inbox_Header-->
			<div>			
				<div class="brd_fullSky">
					<!--Start_From-->
					<div class="normaltxt_11p_blk_arial brdbottom">
						<div class="float_L brdright_s" style="width:75px;background-color:#ECF3FD; height:74px">							
							<span class="disBlock mar_top_4p mar_left_5p bld">From</span>						
						</div>
						<div class="float_L" style="width:85%">
							<div class="lineSpace_10">&nbsp;</div>							
							<div class="mar_left_10p">
								<img src="<?php echo $senderDetails[$mail['senders_id']]['avtarimageurl']; ?>" align="left" width="60" height="60"/> 
								<span class="mar_left_5p bld"><?php echo $senderDetails[$mail['senders_id']]['displayname'];?></span>
								<!---<span class="mar_left_30p"><a href="#">Show details</a> 1:08 PM (2 hours ago)</span>-->
							</div>
						</div>						
						<div class="clear_L"></div>
					</div>
					<!--End_From-->
					<!--Start_To-->								
					<div class="normaltxt_11p_blk_arial brdbottom">
						<div class="float_L brdright_s" style="width:75px;background-color:#ECF3FD;">							
							<span class="disBlock mar_top_4p mar_left_5p bld">To</span>						
						</div>
						<div class="float_L" style="width:85%">
							<div class="disBlock mar_top_4p mar_left_10p">
							<?php foreach($mail['receivers_ids'] as $ids): ?>
								<span class="mar_left_5p bld"><?php echo $receiverDetails[$ids]['displayname']; ?></span>
							<?php endforeach;?>
							</div>
						</div>						
						<div class="clear_L"></div> 						
					</div>
					<!--End_To-->
					<!--Start_Date-->								
					<div class="normaltxt_11p_blk_arial brdbottom">
						<div class="float_L brdright_s" style="width:75px;background-color:#ECF3FD;">							
							<span class="disBlock mar_top_4p mar_left_5p bld">Date</span>						
						</div>
						<div class="float_L" style="width:85%">
							<div class="mar_left_10p mar_top_4p">								
								<span class="mar_left_5p"><?php $t= strtotime($mail['time_created']); echo date('l, jS F, Y g:i:s A',$t); ?></span>
								<span class="mar_left_30p">&nbsp;</span>
							</div>
						</div>						
						<div class="clear_L"></div> 						
					</div>
					<!--End_Date-->	
					<!--Start_subject-->								
					<div class="normaltxt_11p_blk_arial brdbottom">
						<div class="float_L brdright_s" style="width:75px;background-color:#ECF3FD; height:34px">							
							<span class="disBlock mar_top_4p mar_left_5p bld">Subject</span>						
						</div>
						<div class="float_L" style="width:85%">
							<div class="lineSpace_10">&nbsp;</div>							
							<div class="mar_left_10p">								
								<span class="mar_left_5p" id="o_subject"><?php echo $mail['subject'];?></span>
								<span class="mar_left_30p">&nbsp;</span>
							</div>
						</div>						
						<div class="clear_L"></div> 						
					</div>
					<!--End_subject-->								
				</div>
				<div class="lineSpace_10">&nbsp;</div>
			</div>
			<!--End_Inbox_Header-->
			<!--Start_read_Mail_Container-->
			<div class="mar_full_10p normaltxt_11p_blk fontSize_12p" id="o_body">
			   <?php if($mail['senders_id']==1) { 
				 echo $mail['body'];
			      } else { 
				 echo nl2br_Shiksha($mail['body']);
			      } ?>
			</div>
			<div class="brdsky lineSpace_3" style="background-color:#ECF3FD">&nbsp;</div>
			<!--End_read_Mail_Container-->
			
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row" id="hideMenu">
				<?php $this->load->view('mail/mailPagination');?>
				<div class="buttr2">
					<button class="btn-submit5 w6" type="submit" onClick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="button" onclick="showReply()"> 
						<div class="btn-submit5"><p class="btn-submit6">Reply</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" value="" type="button" onclick="showForward()">
						<div class="btn-submit5"><p class="btn-submit6">Forward</p></div>
					</button>
				</div>
				<?php if($type=="inbox"):?>
				<!--<div class="buttr2">
					<button class="btn-submit5 w3" type="submit">
						<div class="btn-submit5"><p class="btn-submit6">Block User</p></div>
					</button>
				</div>-->
				<?php endif;?>
				<?php if ($type=="trash"):?>
				<div class="buttr2">
					<button class="btn-submit5 w3" type="submit" onclick="restoreMails();">
						<div class="btn-submit5"><p class="btn-submit6">Restore</p></div>
					</button>
				</div>
				<?php endif; ?>
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
	<!--End_Mid_Panel-->
</form>
	<div id="show" style="display:none">
	<form id="replyMail" action="save" onsubmit="new Ajax.Request('save',{ onComplete: function(transport) { parseSaveMail((eval(transport.responseText)));}, parameters:Form.serialize(this)}); return false;" method="post" >
	<input type="hidden" value="" name="contentId" id="contentId" autocomplete="off">
	<input type="hidden" value="" name="mailtype" id="mailtype_for_inbox" autocomplete="off">
		<div style="display:inline; float:left; width:100%">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">				
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return sendMail();">
						<div class="btn-submit5"><p class="btn-submit6">Send</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return saveMail();">
						<div class="btn-submit5"><p class="btn-submit6">Save</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" value="" type="button" onClick="cancelShow();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
					</button>
				</div>
				<div id="status"></div>	
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>		
		
		
			<div class="lineSpace_5">&nbsp;</div>
			<div class="brd_full bg_compose" id="to" >
				<div class="row" id="receivers">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial bld fontSize_13p txt_align_r float_L" style="width:70px">To:&nbsp;</div>
					<div><input id="names" type="text" size="50%" style="height:15px" name="names" autocomplete="off"/></div>
					<div id="addressbook" class="autocomplete" ></div>
					<script>var auto = new Ajax.Autocompleter('names','addressbook','getAddressbook', {callback: beforeAjax, updateElement: afterAjax});</script>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_1">&nbsp;</div>
				<div class="row">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial bld fontSize_13p txt_align_r float_L" style="width:70px">Subject:&nbsp;</div>
					<div><input type="text" size="50%" style="height:15px" name="subject" value="" id="subject"/></div>
					<div class="clear_L"></div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
			</div>
			<div class="bgcolor_div_sky">
				<div class="pd_5">
					<textarea style="width:99%; height:200px" name="body" id="body"></textarea>
				</div>
			</div>
			
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">			
 
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return sendMail();">
						<div class="btn-submit5"><p class="btn-submit6">Send</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick="return saveMail();">
						<div class="btn-submit5"><p class="btn-submit6">Save</p></div>
					</button>
				</div>
				<div class="buttr2">
					<button class="btn-submit5 w12" value="" type="button" onClick="cancelShow();">
						<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
					</button>
				</div>
				<div id="status"></div>	
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		
		</div>
	</form>					
	</div>
	</div>
