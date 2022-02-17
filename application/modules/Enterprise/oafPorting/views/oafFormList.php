<div>
	<li>
		<?php 
		if (!empty($formList)){
			?>
			<label>Form List</label>
			<div class="form-fields_v1">
				<div class="course-box" id="FormList">
					<ol>
						<li><input type="checkbox" name="AllForm" id="AllList" onClick="checkUncheckChilds1(this,'FormList')" value="All" 
							<?php if (count($formList) == count($checkFormList)) {
								echo 'checked';
							}
							?>/>All</li>
						<?php foreach($formList as $formId => $formName) { ?>
						<li>
							<input 
							type="checkbox" 
							name="singleForm[]" 
							onClick="uncheckElement1(this,'AllList','FormList');" 
							id  = <?php echo $formId; ?> 
							<?php if (in_array($formId, $checkFormList)) {
								echo 'checked';
							}?> 
							value="<?php echo $formId; ?>"/> 
							<?php echo $formName; ?> 
						</li>
						<?php } ?>
						
					</ol>
				</div>
			</div>
			<li>
				<input type="button" class="orange-button" value="Get Fields" onClick="getFieldMapping(this,'FormList');" />
			</li>
			<?php
		} else {
			?>
			<div>
				<div class="form-fields"> No Forms List for this Client Id</div>
			</div>
			<?php 
		}
		?>
	</li>
	
</div>
