<div id="showSearchListingLeads"></div>
<br><br>
<div id="showLeadsBySearch">
	<?php if(count($results)>0) { ?>
        <div class="lineSpace_20">&nbsp;</div>
        <div style="float:right;margin-right:100px;"><a href="<?php echo $csvURL; ?>" target="_blank">Get Last 1000 Responses (if any)</a></div>
        <div class="OrgangeFont fontSize_14p bld"><strong class="mar_left_10p">Responses:</strong></div>
		<div class="raised_sky">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_skySearch">
				<table border="0" align="center" cellpadding="0" cellspacing="2" style="font-size:13px;">
					<tr>
						<th width="10%" height="25" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Display Name </th>
						<th width="6%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Contact Email </th>
						<th width="8%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Contact Cell </th>
						<th width="8%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Time of Request </th>
						<th width="10%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Listing Title </th>
						<th width="15%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Institute Name</th>
						<th width="8%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">User City</th>
						<th width="5%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">User Action</th>
						<th width="7%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Category of Interest</th>
						<th width="7%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">Country of Interest</th>
						<th width="20%" align="left" bgcolor="#F9F9F9" style="padding:0 10px">User Query(if any)</th>
					</tr>
				<?php foreach($results as $result)
				{
					$strdate = $result['submit_date'];
					$timestamp= strtotime($strdate);
					$realDate = date('d F - h:i A', $timestamp);

				?>   
				 <tr>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['displayName']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['email']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['contact_cell']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo $realDate;  ?></td>
						<td align="left" valign="top" style="padding:0 10px"><a href="<?php echo $result['url']; ?>" target="_blank" ><?php echo  $result['listing_title']; ?></a></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['institute_name']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['city']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['action']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['categoriesOfInterest']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['countriesOfInterest']; ?></td>
						<td align="left" valign="top" style="padding:0 10px"><?php echo  $result['query']; ?></td>
					</tr>
				<?php
				}?>
				</table>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		  </div>      
	<?php }else{ ?>
        <span>No Responses </span>
	<?php } ?>
</div>
