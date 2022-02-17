<?php 
if($actionType == 'edit')
{
	$disabledAttr = 'disabled';
}
else
{
	$hideClass = "hide";	
}
?>

<div class="clear-width" id="exam_basic_section">
    <div id="basicInfo_<?=$formName?>" class="cms-form-wrap">
	    <ul>
			<li>
			        <label>Exam Name* : </label>
			        <div class="cms-fields">
			        	<?php 
			        	if($actionType == 'edit' && !empty($beforeEditExamName)){
			        		echo "<input type='hidden' name='beforeEditExamName' value='{$beforeEditExamName}'>";
			        	}
			        	?>
			            <select id="examList_<?=$formName?>" name="examName" class="universal-select cms-field" onchange ="getExamGroupMapping();showErrorMessage(this, '<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Exam Name" required="true" validationtype="select" <?=$disabledAttr;?>>
				            <option value="">Select an Exam</option>
							<?php
								if($actionType == 'edit'){
									echo "<option value='".$examId.'@#'.$examList[$examId]."' selected>".$examList[$examId]."</option>";
									foreach($examList as $Id => $exam){
										if($exam != $examList[$examId]){
											echo "<option value='".$Id.'@#'.$exam."'>".$exam."</option>";
										}
									}
								}else{
									foreach ($examList as $Id => $name) {
										echo "<option value='".$Id.'@#'.$name."'>".$name."</option>";
									}
								}
							?>
			            </select>
			    		<div id="examList_<?=$formName?>_error" class="errorMsg" style="display:none;"></div>
			        </div>
			</li>
			<li id="exam_group_list" class="<?=$hideClass;?>">
				 <label>Course Group Name* : </label>
			        <div class="cms-fields">
						  <select id="group_list_<?=$formName?>" name="group_name" class="universal-select cms-field <?=$hideClass;?>" onchange="getContentBasedOnGroup();" onblur="" caption="Group Name" required="true" validationtype="select" <?=$disabledAttr;?>>
						            <option value="">Select an Group</option>
									<?php
										if($actionType == 'edit'){
											echo "<option value='".$groupId.'@#'.$groupName."' selected>".$groupName."</option>";
										}
									?>
					       </select>
			            <div id="groupList_<?=$formName?>_error" class="errorMsg" style="display:none;"></div>
			            </div>
			</li>
		</ul>
	</div>
</div>