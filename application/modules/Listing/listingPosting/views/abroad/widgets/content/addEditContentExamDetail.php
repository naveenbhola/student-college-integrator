<div id="examPageExamCont" class="content-type-head clear-width examPage" style="display: none;border: 1px solid #ccc;padding: 0;padding-top: 5px;padding-bottom: 5px;">
	<div class="cms-form-wrap" style="margin:0;">
		<ul>
			<li>
				<label>Select Exam* : </label>
				<div class="cms-fields" style="font-size:15px;">
					<?php if($disabled !=''){?>
					<input type="hidden" name="examPage_examid" value="<?= $content['basic_info']['exam_type'];?>" />	
					<?php }?>
					<select class="universal-select cms-field" name="examPage_examid" id="examPage_examid" caption="exam" validationType="select" required="true" onchange="changeSecTitles()" <?= $disabled;?>>                         
						<option value="">Select a exam</option>
						<?php foreach($abroadExamsMasterList as $exam)
						{
							if(!in_array($exam['examId'],$examWithExamPage)){
							if($exam['examId'] == $content['basic_info']['exam_type']){
								$selected = "selected";
							} else {
								$selected = "";
							}?>
							<option <?=$selected?> value="<?=$exam['examId']?>"><?=$exam['exam']?></option>
						<?php }} ?>
					</select>
					<div id="examPage_examid_error" class="errorMsg" style="margin-bottom: 5px;"></div>
				</div>
			</li>
		</ul>
	</div>
</div>