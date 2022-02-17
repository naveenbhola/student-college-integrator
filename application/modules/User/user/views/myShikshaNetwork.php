<!--Start_Network-->
<input type="hidden" autocomplete="off" id="usercountOffset" value="6"/>
<input type="hidden" autocomplete="off" id="userstartFrom" value="0"/>
<input type = "hidden" autocomplete="off" id = "userNetworkMethod" value = "myUserNetworkShow"/>
<input type = "hidden" autocomplete="off" id = "userCount" value = "<?php echo $totalUserCount; ?>"/>
<div class="row hideComponent" id="myShikshaNetworkHidden"></div>
<div id="myShikshaNetworkShow">
   <div class="raised_lgraynoBG" id="myShikshaNetworkBox" style="width:100%;">
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
		<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p OrgangeFont">
		<div class="bld"><img src="/public/images/view_icon.gif" align="absmiddle" class="mar_full_5p" />My Network</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="mar_full_10p">
			<div class="float_L bld normaltxt_11p_blk lineSpace_20" id="userNetworkCountShow"> </div>
			<div class="clear_L">&nbsp;</div>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		
		<div class="mar_full_10p">
			<div class="pagingID float_R" id="netWorkPagination" style="line-height:22px"> </div>
			<div class="clear_R"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div id="networkShow" class="mar_left_10p fontSize_12p"></div>
		<?php if(strcmp($editFlag,'true')==0): ?>
		<div class="myProfile normaltxt_11p_blk mar_full_10p">
		<input type="checkbox" checked=true id="myShikshaNetwork"  name="myShikshaNetwork" value="1" onClick="updateComponent(this);"/> Display in Public profile
		</div>							
		<?php endif; ?>
		</div>
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
<!--End_Network-->
<script>
myUserNetworkShow();
</script>
