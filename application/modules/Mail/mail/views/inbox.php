<form action="delete/<?php echo $type; ?>" id="mainMailForm" onsubmit="return confirmAction();" method="post" >
	<div id="mid_Panel_inbox">
	<input type="hidden" value="" id="flag" name="flag">
	<input type="hidden" value="<?php echo $type; ?>" id="type">
	<input type="hidden" id="pageNo" value="<?php echo $pageNo; ?>">
		<div style="display:inline; float:left; width:100%">
		   <div class="normaltxt_11p_blk_arial">
		      <span class="fontSize_18p OrgangeFont bld">Inbox</span>&nbsp;
		      <?php if($noOfUnread > 1) { ?>
		      <span class="grayDarkFont">(You have <?php echo $noOfUnread; ?> unread messages)</span>
		      <?php } else if ($noOfUnread==1) { ?>
		      <span class="grayDarkFont">(You have <?php echo $noOfUnread; ?> unread message)</span>
		      <?php } else { ?>
		      <span class="grayDarkFont">(You have no unread message)</span>
		      <?php } ?>
		   </div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="grayLine"></div>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<?php $this->load->view('mail/pagination'); ?>
				<div class="buttr2">
					<button class="btn-submit5 w6" type="button" onclick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<!--<div class="buttr2">
					<button class="btn-submit5 w3" type="submit" onclick="blockSender();">
						<div class="btn-submit5"><p class="btn-submit6">Block User</p></div>
					</button>
				</div>-->
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<!--Inbox_Header-->
			<div>
				<div class="brdpink_box">
					<div class="normaltxt_11p_blk_arial">
						<div class="float_L brdright_b h28 bgpink_box" style="width:27px;"><span class="disBlock mar_top_4p mar_left_5p"><input type="checkbox" onclick="check(this,'mail_container');" value="yes"  />&nbsp;</span></div>
						<div class="float_L brdright_b h28 bgpink_box" style="width:30%;"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">From</span></span></div>
						<div class="float_L brdright_b h28 bgpink_box" style="width:47%;"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">Subject</span></span></div>
						   <div class="float_L h28" style="background-color:#FEFDF8"><span class="disBlock mar_top_6p">&nbsp; &nbsp;<span class="bld bluefont">Date<!--<img src="/public/images/down_arrowdate.gif" align="absmiddle" />--></span></span></div>
						<div class="clear_L"></div>
					</div>								
				</div>
			</div>
			<!--End_Inbox_Header-->
			<!--Start_Inbox_Mail_Container-->
			<div id="mail_container">
				<!--Mail_Row-->
				<?php if (count($mails)>0) :?>
				<?php foreach($mails as $mail): $t = strtotime($mail['time_created']); ?>
				<?php $readClass = ($mail['read_flag']=='unread')? "bld" : "";?>
				<?php $readClassBg = ($mail['read_flag']=='read')? "bgcolor_inbox" :""; ?>
				<div>
					   <div class="<?php echo $readClassBg; ?>">
						<div class="lineSpace_10">&nbsp;</div>
						<div class="normaltxt_11p_blk_arial">
							<div class="float_L" style="width:36px;"><span class="disBlock mar_top_4p mar_left_5p"><input type="checkbox" name="mailId[]" value="<?php echo $mail['mail_id'];?>" />&nbsp;</span></div>
							<div class="float_L" style="width:29%;">
								<div><img src="<?php echo $userDetails[$mail['senders_id']]['avtarimageurl'];?>" align="left" height="52" width="58"/><span class="mar_left_5p <?php echo $readClass;?>"><a href="<?php echo site_url()."getUserProfile/".$userDetails[$mail['senders_id']]['displayname']; ?>"><?php echo $userDetails[$mail['senders_id']]['displayname'];?></a></span></div>
							</div>
							<div class="float_L" style="width:48%;">													
								<div class="disBlock mar_full_8p"><a href="#" class="<?php echo $readClass;?>" onClick="showMail('inbox',<?php echo $mail['mail_id'];?>);"><?php $s=($mail['subject']!="")? $mail['subject']: "(no subject)";echo $s; ?></a></div>
								<div class="lineSpace_5"></div>
								<div class="disBlock mar_full_8p"><span class="grayFont"><?php echo $mail['preview']; ?> </span></div>
							</div>
							<div class="float_L">&nbsp;<span class="<?php echo $readClass; ?>"><?php echo date('D j M Y',$t); ?></span></div>
							<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>										
					<div class="grayLine"></div>													
				</div>
				<!--End_Mail_Row-->
				<?php endforeach; ?>
				<?php else: ?>
				<span class="normaltxt_11p_blk_arial fontSize_18p OrgangeFont bld" style="margin:10px 0px 10px 30px">You have no messages in the Inbox</span>
				<?php endif;?>
			</div>
			<div class="pinkLine"></div>
			<!--End_Inbox_Mail_Container-->
			
			<div class="lineSpace_5">&nbsp;</div>
			<div class="row">
				<?php $this->load->view('mail/pagination'); ?>
				<div class="buttr2">
					<button class="btn-submit5 w6" type="button" onclick="deleteMails();">
						<div class="btn-submit5"><p class="btn-submit6">Delete</p></div>
					</button>
				</div>
				<!--<div class="buttr2">
					<button class="btn-submit5 w3" type="button" onclick="blockSender();">
						<div class="btn-submit5"><p class="btn-submit6">Block User</p></div>
					</button>
				</div>-->
				<div class="clear_L"></div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>
        <div class="clear_L"></div>
	</div>
</form>
