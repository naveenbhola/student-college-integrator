<?php if(!intval($blockNum)) { $blockNum = 1; } ?>
<div style="width:100%">
	<div class="cmsSearch_RowLeft">
		<div style="width:100%">
			<div class="txt_align_r" style="<?php if($isMMM) { echo "font:bold 13px arial; padding-top:2px;"; } else { echo "padding-right:5px;"; } ?>">Preferred Study Localities:</div>
		</div>
	</div>
	<div class="cmsSearch_RowRight" style="<?php if($isMMM) { echo "width:480px; margin-left:10px;"; } ?>">
	<div style="width:100%">
			<select style="width:157px" onchange="load_preference_zone(this,<?php echo $blockNum; ?>);" id="perference_<?php echo $blockNum; ?>" name="prefLocArr[]">
				<option value="">Select City</option>
				<?php echo $select_city_list;?>
			</select>&nbsp;
			<span style="display:none;" id="zone_container_<?php echo $blockNum; ?>" >
				<select onchange="load_preference_localities(this,<?php echo $blockNum; ?>);" id="zone_id_<?php echo $blockNum; ?>">
					<option value="">Select Zone</option>
				</select>
			</span>
		</div>
	</div>
	<div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
</div>
<div style="line-height:6px">&nbsp;</div>
<div style="display:none;width:100%; margin-bottom: 10px;" id="parent_div_localities_<?php echo $blockNum; ?>">
	<div class="cmsSearch_RowLeft">
		<div style="width:100%">
			<div class="txt_align_r" style="<?php if($isMMM) { echo "font:normal 13px arial; padding-top:2px;"; } else { echo "padding-right:5px;"; } ?>">Localities:</div>
		</div>
	</div>
	<div class="cmsSearch_RowRight" style="<?php if($isMMM) { echo "width:480px; margin-left:10px;"; } ?>">
		<div style="width:100%">
			<div>
				<input type="checkbox" id="flag_select_all_<?php echo $blockNum; ?>" name="flag_select_all" onclick="selectAllCheckBoxes(1,<?php echo $blockNum; ?>);" checked value="flag_select_all" /> Select All
			</div>
			<div style="line-height:3px;overflow:hidden">&nbsp;</div>
			<div style="width:<?php echo $isMMM ? 460 : 500; ?>px;height:100px;overflow:auto;padding:5px;border:1px solid #999">
				<div id="zone_name_placeholder_<?php echo $blockNum; ?>"></div>
				<div id="result_set_data_<?php echo $blockNum; ?>"></div>
				<div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
			</div>
		</div>
	</div>
	<div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
</div>
<input type="hidden" id="hiddenpreferedMainCity_<?php echo $blockNum; ?>" name="hiddenpreferedMainCity[]" value="" />
<input type="hidden" id="hiddenpreferedCity_<?php echo $blockNum; ?>" name="hiddenpreferedCity[]" value="" />