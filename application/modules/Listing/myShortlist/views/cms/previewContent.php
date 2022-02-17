<?php $errorFound = 0; ?>
<div class="layer-title">
	<a title="Close" class="flRt close" href="javascript:void(0);" onclick="closePreviewLayer();"></a>
	<div class="title">Subjects Preview</div>
</div>
<div class="preview-detail">
	<ul>
		<?php
			$validCount = 0;
			$invalidCount = 0;
			foreach($subject as $userId => $data) {
				if($userId == 'all' && !$data['valid']) { ?>
					<li>
						<p style="color: red;"><?php echo $data['reason']; ?></p>
					</li>
				<?php $errorFound = 1; break; }
				
				if($data['valid']) {
					$validCount++; ?>
					<li>
						<?php echo $validCount; ?>. <?php echo $data['parsedMsg']; ?>
					</li>
				<?php } else {
					$invalidCount++;
				}
			}
			if($invalidCount > 0) { ?>
				<li>
					<p style="color: red;">Count of invalid mails out of 10: <?php echo $invalidCount; ?></p>
				</li>
			<?php }
		?>
	</ul>
</div>

<div class="layer-title">
	<div class="title">Mails Preview</div>
</div>
<div class="preview-detail">
	<ul>
		<?php
			$validCount = 0;
			$invalidCount = 0;
			foreach($mail as $userId => $data) {
				if($userId == 'all' && !$data['valid']) { ?>
					<li>
						<p style="color: red;"><?php echo $data['reason']; ?></p>
					</li>
				<?php $errorFound = 1; break; }
				
				if($data['valid']) {
					$validCount++; ?>
					<li>
						<span style="float: left; display-inline-block; margin-right:4px;"><?php echo $validCount; ?>.</span><?php echo $data['parsedMsg']; ?>
					</li>
				<?php } else {
					$invalidCount++;
				}
			}
			if($invalidCount > 0) { ?>
				<li>
					<p style="color: red;">Count of invalid mails out of 10: <?php echo $invalidCount; ?></p>
				</li>
			<?php }
		?>
	</ul>
</div>

<div class="layer-title">
	<div class="title">Notfications Preview</div>
</div>
<div class="preview-detail">
	<ul>
		<?php
			$validCount = 0;
			$invalidCount = 0;
			foreach($notification as $userId => $data) {
				if($userId == 'all' && !$data['valid']) { ?>
					<li>
						<p style="color: red;"><?php echo $data['reason']; ?></p>
					</li>
				<?php $errorFound = 1; break; }
				
				if($data['valid']) {
					$validCount++; ?>
					<li>
						<span style="float: left; display-inline-block; margin-right:4px;"><?php echo $validCount; ?>.</span> <?php echo $data['parsedMsg']; ?>
					</li>
				<?php } else {
					$invalidCount++;
				}
			}
			if($invalidCount > 0) { ?>
				<li>
					<p style="color: red;">Count of invalid notifications out of 10: <?php echo $invalidCount; ?></p>
				</li>
			<?php }
		?>
	</ul>
</div>

<div class="layer-title">
	<div class="title">Mobile Notfications Preview</div>
</div>
<div class="preview-detail">
	<ul>
		<?php
			$validCount = 0;
			$invalidCount = 0;
			foreach($mobile_notification as $userId => $data) {
				if($userId == 'all' && !$data['valid']) { ?>
					<li>
						<p style="color: red;"><?php echo $data['reason']; ?></p>
					</li>
				<?php $errorFound = 1; break; }
				
				if($data['valid']) {
					$validCount++; ?>
					<li>
						<span style="float: left; display-inline-block; margin-right:4px;"><?php echo $validCount; ?>.</span> <?php echo $data['parsedMsg']; ?>
					</li>
				<?php } else {
					$invalidCount++;
				}
			}
			if($invalidCount > 0) { ?>
				<li>
					<p style="color: red;">Count of invalid notifications out of 10: <?php echo $invalidCount; ?></p>
				</li>
			<?php }
		?>
	</ul>
</div>
<?php if(!$errorFound) { ?>
<div style="position: relative;">
	<input type="button" value="Submit" onclick="submitNotificationTemplateData()" class="preview-sbmt-btn"/>
	<span id="submitLoader" style="display:none; position: absolute; top:3px; right:277px;"><img src="/public/images/loader_small_size.gif"></span>
</div>
<?php } ?>