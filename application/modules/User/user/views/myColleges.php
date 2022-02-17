<!--Start_College-->
<input type="hidden" autocomplete="off" id="collegeCountOffset" value="3"/>
<input type="hidden" autocomplete="off" id="collegestartFrom" value="0"/>
<input type = "hidden" autocomplete="off" id = "collegeNetworkMethod" value = "myCollegeNetworkShow"/>
<input type = "hidden" autocomplete="off" id = "collegeCount" value = "<?php echo $totalcollegeCount; ?>"/>
<div class="row hideComponent" id="myShikshaCollgenetworkHidden"></div>
<div id="myShikshaCollgenetworkShow">
	<div class="raised_lgraynoBG" id="myShikshaCollgenetworkBox"> 
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
		<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p OrgangeFont">
			<?php if(strcmp($editFlag,'true')==0): ?>
			<div class="myProfile float_R mar_right_5p">
				<a href="#" onClick="return showAddCollegeOverlay('addCollegeOverlay');" title="Add Institute">Add Institute</a>
			</div>
			<?php endif; ?>
			<div class="bld"><img src="/public/images/view_icon.gif" align="absmiddle" class="mar_full_5p" />My Institutes</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="mar_full_10p">
			<div class="bld normaltxt_11p_blk lineSpace_20" id="collegeCountShow">&nbsp;</div>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div class="mar_full_10p">
			<div class="pagingID" align="right" id="collegePagination"></div>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div>
			<div id="collegeNetworkShow" class="mar_left_5p fontSize_12p"></div>
		</div>
		<?php if(strcmp($editFlag,'true')==0): ?>	
		<div class="myProfile normaltxt_11p_blk mar_full_10p">
		<input type="checkbox" checked=true id="myShikshaCollgenetwork"  name="myShikshaCollgenetwork" value="2" onClick="updateComponent(this);"/> Display in Public profile
		</div>	
		<?php endif; ?>						
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>
<!--End_College-->
<script>
myCollegeNetworkShow();
</script>
