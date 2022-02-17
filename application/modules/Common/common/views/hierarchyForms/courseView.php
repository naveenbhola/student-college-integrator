<?php 
if(isset($formElements['fromWhere']) && $formElements['fromWhere'] == 'careerCms'){
	$name = 'course_'.$countHierarchy;
}
?>
<table class="uni-table" cellspacing="0" cellpadding="0">
<?php if(is_array($formElements['course'])): ?>
	<tr>
		<td>
			<div>
				<label class="label-width"><?php echo $formElements['course']['label']?></label>

					<?php 
					$name = ($name!='') ? $name : $formElements['course']['id'];
					$this->load->view('common/hierarchyForms/searchContainer',array('containerId'=>$name,'entity'=>'course','createtags'=>$prefilledData['courseView'][0]['selections'],'label'=>$formElements['course']['label'],'countHierarchy'=>$countHierarchy));?>
			</div>
		</td>
	</tr>
	<?php endif;?>
</table>	