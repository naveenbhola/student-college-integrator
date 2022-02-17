<div class="clear-width commonTags">
	<h3 class="section-title article">Article Related To</h3>
	<h3 class="section-title guide" style="display: none">Guide Related To</h3>
	<h3 class="section-title examPage" style="display: none">Exam Page Related To</h3>
	<div class="cms-form-wrap ">
		<ul>
			<li>
			<label>Exam: </label>
			<div class="cms-fields">
			   <div id="examContDiv">
					<?php if(empty($content['exam_info'])) { $content['exam_info'][0] = ""; }
						foreach($content['exam_info'] as $key=>$examArr) { ?>
						<div class="add-more-sec examDiv">
							<select class="universal-select cms-field" name="exam[]" id="exam_<?=$key+1?>">                         
								<option value="">Select a exam</option>
								<?php foreach($abroadExamsMasterList as $exam)
								{	
									if($exam['examId'] == $examArr['exam_id']){
										$selected = "selected";
									} else {
										$selected = "";
									} ?>
									<option <?=$selected?> value="<?=$exam['examId']?>"><?=$exam['exam']?></option>
								<?php } ?>
							</select>
							
							<a class="remove-link-2" href="javascript:void(0);" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeElementChunk(this, 'exam', 5);setImageContainer();">
								<i class="abroad-cms-sprite remove-icon"></i>Remove Exam
							</a>
						</div>
					<?php } ?>
				</div>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam_error">error</div>
				<a href="javascript:void(0);" <?php if(count($content['exam_info']) >= 5) { ?> style="display: none" <?php } ?> id="exam_addMore" onclick="addMoreElementChunk('exam', 5);setImageContainer();">[+] Add Another Exam</a>
			</div>
			</li>

			<li>
				<label>Related Date: </label>
				<div class="cms-fields">
					<script language="javascript" src="//<?php echo JSURL; ?>/public/js/CalendarPopup.js"></script>
					<?php $this->load->view('common/calendardiv'); ?>
												<?php
												if(isset($content['basic_info']['relatedDate']) && $content['basic_info']['relatedDate']!="0000-00-00 00:00:00" && $content['basic_info']['relatedDate']!=""){
														$arrayDate = explode(" ",$content['basic_info']['relatedDate']);
														$relatedDate = $arrayDate[0];
												}
												else{
														$relatedDate = 'YYYY-MM-DD';
												}
												?>
					<input type="text" name="relatedDate" id="relatedDate" value="<?=$relatedDate?>" class="universal-txt-field cms-text-field" readonly/> <img name="relatedDate_img" id="relatedDate_img" src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('relatedDate'),'relatedDate_img','YYYY-MM-DD'); return false;"/>

				</div>
			</li>
		</ul>
	</div>
</div>