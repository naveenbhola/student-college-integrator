<!--Start_Request-->	
<?php
echo "<script language=\"javascript\"> ";
echo "var noOfPendingRequest = ".$pendingRequestCount.";";
echo "</script> ";		
?>
<div class="myProfile raised_lgraynoBG"> 
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
			<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont">
			<img src="/public/images/request_icon.gif" align="absmiddle" class="mar_full_5p" />Requests</div>
			<div style="height:70px;">
			<div class="lineSpace_15">&nbsp;</div>								
			<div class="normaltxt_11p_blk mar_full_5p bld">
				<img src="/public/images/request_picon.gif" align="absmiddle" />&nbsp;
				<?php if($pendingRequestCount > 0): ?>
					<a href="#" onClick = "showPendingRequests(<?php echo $userId?>)" id="pendingLink">
					<?php echo $pendingRequestCount; ?> Pending Requests</a>
				<?php else:  
					echo $pendingRequestCount." Pending Requests."; 
				      endif; ?>
			</div>
			</div>
		</div>
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	<div class="lineSpace_10">&nbsp;</div>
</div>
<!--End_Request-->

<div id="pendingRequests" style="position:absolute;display:none;z-index:2050;">
<b class = "b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<div>
<br /><br /><br />
<table width="450" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="10" height="10"><img src="/public/images/gadget1.gif" /></td>
    <td background="/public/images/gadgetTop.gif"><img src="/public/images/gadgetTop.gif" /></td>
    <td width="10" height="10"><img src="/public/images/gadget2.gif" /></td>
  </tr>
  <tr>
    <td background="/public/images/gadgetLeft.gif"><img src="/public/images/gadgetLeft.gif" /></td>
    <td style = "background:white" >
<div style="width:98%">
<div align="right"><img src="/public/images/crossImg.gif" onClick="javascript:hidePendingRequests();" /></div>
		<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont"><span class="mar_left_10p">Pending Request</span></div>
		<div class="lineSpace_10">&nbsp;</div>		
		<!--FirstRow-->
		<div id = "newRequests">
		</div>
			  
    
</div>

</td>
    <td background="/public/images/gadgetRight.gif"><img src="/public/images/gadgetRight.gif" /></td>
  </tr>
  <tr>
    <td><img src="/public/images/gadget3.gif" /></td>
    <td background="/public/images/gadgetButtom.gif"><img src="/public/images/gadgetButtom.gif" /></td>
    <td><img src="/public/images/gadget4.gif" /></td>
  </tr>

</table>
</div>
</div>

