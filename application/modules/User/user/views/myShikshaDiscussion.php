<!-- Discussion Started -->
<div class="row hideComponent" id="myShikshaDiscussionHidden"></div>
<div id="myShikshaDiscussionShow">
	<div id="myShikshaDiscussionBox" style="width:100%;">
		<div class="raised_lgraynoBG"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
				<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p OrgangeFont">
						<?php if(strcmp($editFlag,'true')==0): ?>		
						<div class="float_R mar_right_10p  myProfile"><a href="<?php echo SHIKSHA_ASK_HOME; ?>" title="Post question">Post question</a></div>
						<?php endif; ?>
						<div class="bld"><img src="/public/images/album.gif" width="28" height="15" align="absmiddle" />My Questions and Answers</div>
				</div>
				<div class="lineSpace_5">&nbsp;</div>
					<!-- Listing will come here -->
					<div id="myTopics" class="mar_left_10p fontSize_12p"></div>
					<div id="discussion_error_place" class="mar_left_10p fontSize_12p"></div>		
					<div class="row">
					<?php if(strcmp($editFlag,'true')==0): ?>	
					<div class="float_R myProfile mar_right_10p" id="discussionViewAllLink">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/1/4" title="View all">View all</a>
					</div>
					<div class="myProfile normaltxt_11p_blk mar_left_10p">
						<input type="checkbox" checked=true id="myShikshaDiscussion"  name="myShikshaDiscussion" value="3" onClick="updateComponent(this);"/> Display in Public profile
					</div>
					<?php endif; ?>
						<div class="clear_L">&nbsp;</div>
					</div>
			</div>
		  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>		
	<div class="lineSpace_10">&nbsp;</div>	
</div>
<!-- Discussion finished -->

<script>
	var myTopics = eval('<?php echo $myTopics;?>');
	createMyTopicsList(myTopics, 'myTopics');
</script>
