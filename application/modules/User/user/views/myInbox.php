<!--Start_Inbox-->
<div class="myProfile raised_lgraynoBG"> 
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
			<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont"><img src="/public/images/inbox_icon.gif" align="absmiddle" class="mar_full_5p" />Messages</div>
			<div style="height:70px;">
			<div class="lineSpace_15">&nbsp;</div>
			<div class="normaltxt_11p_blk mar_full_5p">Hello <?php echo $userDetails['displayname']; ?> !!</div>
			<div class="normaltxt_11p_blk mar_full_5p"><img src="/public/images/newmail.gif" align="absmiddle" /> You have <span class="bld"><?php echo $unreadMailCount;  ?> unread message(s) in your Inbox</span></div>
			<div class="mar_right_10p txt_align_r"><img src="/public/images/eventArrow.gif" width="6" height="6" /> <a href="<?php echo site_url('mail/Mail/mailbox'); ?>">Go to Inbox</a></div>
			</div>
		</div>
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	<div class="lineSpace_10">&nbsp;</div>
</div>
<!--End_Inbox-->
						
