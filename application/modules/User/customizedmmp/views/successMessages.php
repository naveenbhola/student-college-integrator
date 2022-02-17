<?php
	// Backend side form validation failed or success message
	if(is_array($mmp_custom_params) && !empty($mmp_custom_params)) {
		$errorFlag = $mmp_custom_params['error'];
		$successFlag = $mmp_custom_params['success'];
		if($errorFlag){
			?>
			<div class="mmp_message_container">
				<?php
				if(isset($mmp_custom_params['error_header'])){
				?>
					<p><em><?php echo $mmp_custom_params['error_header']; ?></em></p>
				<?php
				}
				if(isset($mmp_custom_params['error_text']) && count($mmp_custom_params['error_text']) > 0) {
				?>
					<ul>
					<?php
						foreach($mmp_custom_params['error_text'] as $key => $value){
						?>
							<li><?php echo $value;?></li>
						<?php
						}
					?>
					</ul>
					<?php
				}
				?>
			</div>
			<?php
		} else if($successFlag){
			?>
			<div class="mmp_success_message_container">
				<?php
				if(isset($mmp_custom_params['success_header'])){
				?>
					<p><em><?php echo $mmp_custom_params['success_header']; ?></em></p>
				<?php
				}
				if(isset($mmp_custom_params['success_text']) && count($mmp_custom_params['success_text']) > 0) {
				?>
					<ul>
					<?php
						foreach($mmp_custom_params['success_text'] as $key => $value){
						?>
							<li><?php echo $value;?></li>
						<?php
						}
					?>
					</ul>
					<?php	
				}
				?>
			</div>
			<?php
		}
	} else {
		$mmp_custom_params = array();
	}
	?>