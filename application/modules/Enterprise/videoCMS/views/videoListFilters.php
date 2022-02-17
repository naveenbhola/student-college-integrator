<div id="video_filter_section">
	<div>
		<input type="text" id="vcmsTagSearch" placeholder="Video Tags" />
		<input type="text" id="vcmsVideoTitle" placeholder="Video Title" />
		<span>Filter By:</span>
		<select id="vcmsVideoType">
			<option value="">Video Type</option>
			<?php 
			foreach ($filters['videoTypes'] as $value) {
			?>
				<option value="<?=$value?>"><?=$value?></option>
			<?php 
			}
			?>
		</select>
		<select id="vcmsVideoSubType">
			<option value="" vtype="">Video Sub-Type</option>
			<?php 
			foreach ($filters['videoSubTypes'] as $type => $subTypes) {
				foreach ($subTypes as $subType) {
					?>
						<option class="hide" vtype="<?=$type?>" value="<?=$subType?>"><?=$subType?></option>
					<?php 
				}
			}
			?>
		</select>
		<select id="vcmsVideoStream">
			<option value="">Stream</option>
			<?php 
			foreach ($filters['streams'] as $streamInfo) {
			?>
				<option value="<?=$streamInfo['id']?>"><?=$streamInfo['name']?></option>
			<?php 
			}
			?>
		</select>
		<select id="vcmsVideoLocation">
			<option value="">Location</option>
			<option value="2::country">India</option>
			<?php 
			foreach ($filters['states'] as $stateInfo) {
			?>
				<option value="<?=$stateInfo->getId()?>::state"><?=$stateInfo->getName()?></option>
			<?php 
			}
			?>
		</select>
		<input type="button" value="Submit" id="vcms_apply_filter" />
	</div>
	<div id="vcms-tag-list-container"></div>
	<div id="vcms-tag-slct-container"></div>
</div>