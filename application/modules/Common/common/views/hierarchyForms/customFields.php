<?php 
$mandatoryStar = '<span style="color:#f00;">*</span>';
?>
<table class="uni-table" cellspacing="0" cellpadding="0">
	<?php if(is_array($formElements['exam'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['exam']['label']?></label>
				<?php $this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$formElements['exam']['id'],'entity'=>'exam','formatCreateTags'=>'','createtags'=>$prefilledData['examView'][0]['selections'],'combinedView' => '1','label'=>$formElements['exam']['label']));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
	
	<?php if(is_array($formElements['popularGrouping'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['popularGrouping']['label']?></label>
				<?php $this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$formElements['popularGrouping']['id'],'createtags'=>$prefilledData['popularGroupView'][0]['selections'],'formatCreateTags'=>'','combinedView' => '1','entity'=>'popularGroup','label'=>$formElements['popularGrouping']['label']));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['institute'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['institute']['label']?> <br><span style="font-size:10px;color:#7c7a79;font-weight:normal;">Group / University / College / Faculty / etc.</span></label>
				<div class="customSelect alignBox">
					<input type="text" class="uni-txtfld" name="institute[]" Placeholder="Search Institute" id="instiSearch" value="" spellcheck="false" autocomplete="off" />
	            	<div class="search-college-layer" id="institute-list-container" style="display:none; width:210px;border:1px solid #ccc; top:32px;" ></div>
				</div>
			</div>
				<?php $this->load->view('common/hierarchyForms/showSelectedValues',array('containerId'=>$formElements['institute']['id'],'createtags'=>$prefilledData['instituteView'][0]['selections'],'formatCreateTags'=>'','combinedView' => '1','label'=>$formElements['institute']['label']));?>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['tag'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['tag']['label']?><?php echo $formElements['tag']['mandatory'] == 'yes' ? $mandatoryStar : '' ?></label>
				<div class="customSelect alignBox ">
					<input type="text" class="uni-txtfld" name="tag[]" Placeholder="Search Tags" id="tagSearch" value="" spellcheck="false" autocomplete="off"/>
	            	<div id="tag-list-container" style="display:none; width:210px;border:1px solid #ccc; top:32px;" ></div>
	            	<div><div id="tagSearch_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
				</div>
			</div>
			<?php $this->load->view('common/hierarchyForms/showSelectedValues',array('containerId'=>$formElements['tag']['id'],'formatCreateTags'=>'','createtags'=>$prefilledData['tagView'][0]['selections'],'combinedView' => '1','label'=>$formElements['tag']['label']));?>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['location'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['location']['label']?><?php echo $formElements['tag']['mandatory'] == 'yes' ? $mandatoryStar : '' ?> <br><span style="font-size:10px;color:#7c7a79;font-weight:normal;">
				<?php if(isset($formElements['location']['subHeading'])){
					echo $formElements['location']['subHeading'];
				}else{
					echo "Country / State / City (including Virtual cities)";
				}
				?>
				</span></label>
				<?php $this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$formElements['location']['id'],'formatCreateTags'=>$prefilledData['locationView'][0]['selections'],'createtags'=>'','combinedView' => '1','entity'=>'location','label'=>$formElements['location']['label']));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['careers'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['careers']['label']?></label>
				<?php $this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$formElements['careers']['id'],'formatCreateTags'=>'','createtags'=>$prefilledData['careerView'][0]['selections'],'entity'=>'career','combinedView' => '1','label'=>$formElements['careers']['label']));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
	<?php if(is_array($formElements['otherAttributes'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['otherAttributes']['label']?></label>
				<?php $this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$formElements['otherAttributes']['id'],'combinedView' => '1','entity'=>'otherAttribute','formatCreateTags'=>'','createtags'=>$prefilledData['otherAttributeView'][0]['selections'],'label'=>$formElements['otherAttributes']['label']));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
</table>

<script>
	window.mobileSearch = 'true';
</script>
