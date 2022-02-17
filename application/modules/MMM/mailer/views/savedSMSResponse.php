<script src="/public/js/CalendarPopup.js" language="javascript"> </script>
<?php
			$this->load->view('common/calendardiv');
		?>

<div style="display:<?php if(count($savedCampaign)) echo "block"; else echo "none"; ?> ">
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;margin-left:210px;">
	    <strong> Start Campaign </strong>
	</div>
	<div id="campaignHolder" style="padding-bottom:30px;margin-left:210px;">
	<table>
		<?php foreach($savedCampaign as $row=>$value) {?>
		<tr>
			<td style="width:15px"><input type="checkbox" value="<?php echo str_replace(" ", '_', $value['campaignName']); ?>" id="<?php echo str_replace(" ", '_', $value['campaignName']); ?>" class="check-selector" name="clientCampaign" campaign = "<?php echo $value['campaignName'];?>"/></td>
			<td style="width:auto"><label for="<?php echo $value['campaignName']; ?>" style="font-size:14px;" > <?php echo $value['campaignName']; ?></label></td>
			<td><input id="time_at_<?php echo str_replace(" ", '_', $value['campaignName']);?>" type="text" name="startTime" readonly="true" style="width: 130px; vertical-align: middle; color: #7c7c7c"></td>
			<td><img id="timerange_from_img" style="vertical-align: middle; cursor:pointer;" onclick="campaignTime(true,'time_at_<?php echo str_replace(" ", '_', $value['campaignName']);?>');" src="/public/images/cal-icn.gif" /></td>
			<td><label style="font-size:14px;padding-left:10px"><a href="javascript:void(0);" onclick="deleteCampaign('<?php echo $value['campaignName']; ?>')" id="<?php echo 'del_'.$value['clientId']; ?>"> Delete </a></label></td>	
		</tr>
	<?php }	?>

		<tr>
			<td></td>
			<td><input type="button" value="Start" class="submit_Button" onclick="startSMSCampaign()" /> </td>
		</tr>
	</table>
	</div>
</div>

<div style="display:<?php if(count($scheduledCampaign)) echo "block"; else echo "none"; ?> ">
<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;margin-left:210px;">
	    <strong> Saved Campaign </strong>
	</div>
	<div id="savedCampaignHolder" style="padding-bottom:150px;margin-left:210px;">
	<table>
		<?php foreach($scheduledCampaign as $row=>$value) {?>
		<tr>
			<td style="width:auto"><label for="<?php echo $value['campaignName']; ?>" style="font-size:14px;" > <?php echo $value['campaignName']; ?></label></td>
			<td style="width:auto"><label for="<?php echo $value['savedDate']; ?>" style="font-size:14px;margin-left:20px;" > <?php echo $value['savedDate']; ?></label></td>
		</tr>
	<?php }	?>

	</table>
	</div>

</div>	