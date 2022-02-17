<form id ="form_<?=$formName?>" name="<?=$formName?>"  method="POST" enctype="multipart/form-data">

<!-- <div class="abroad-cms-wrapper"> -->
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="abroad-cms-head" style="overflow: visible;">
                <h1 class="abroad-title">Manage Content</h1>
				<div class="last-uploaded-detail">
					<p>
						*Mandatory<br />
						PlaceHolders available: <br />#examName#, #year#, #examFullName#
						<br />						
					</p>
				</div>

            </div>
            <div class="cms-form-wrapper clear-width">
	              <div class="clear-width" id="exam_basic_section">
	                  <div id="basicInfo_<?=$formName?>" class="cms-form-wrap">
	              	    <ul>
	              			<li>
              			        <label>Exam Name* : </label>
              			        <div class="cms-fields">
              			            <select id="examList_<?=$formName?>" name="examName" class="universal-select cms-field" onchange ="getExamPageSeo();showErrorMessage(this, '<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Exam Name" required="true" validationtype="select" <?php echo $actionType == 'edit' ? 'disabled':''; ?>>
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
	              			<li>
              			        <label>Section Name* : </label>
              			        <div class="cms-fields">
              			            <select id="sectionList_<?=$formName?>" name="sectionName" class="universal-select cms-field" onchange ="getExamPageSeo();showErrorMessage(this, '<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Section Name" required="true" validationtype="select" <?php echo $actionType == 'edit' ? 'disabled':''; ?>>
              							<?php
              							foreach ($sectionList_ as $sectionKey => $sectioName) {
              								?>
              								<option value="<?php echo $sectionKey; ?>"><?php echo $sectioName; ?></option>
              								<?php
              							}
              							?>
              			            </select>
              			    		<div id="sectionList_<?=$formName?>_error" class="errorMsg" style="display:none;"></div>
              			        </div>
	              			</li>
	              			<li>
	              				<label>Meta Title:</label>
	              				<div class="cms-fields">
	              					<textarea  name="metaTitle" class="input-txt"></textarea>
	              				</div>
	              			</li>
	              			<li>
	              				<label>Meta Description:</label>
	              				<div class="cms-fields">
	              					<textarea  name="metaDescription" rows="5" class="input-txt"></textarea>
	              				</div>
	              			</li>
	              			<li>
	              				<label>H1 Tag:</label>
	              				<div class="cms-fields">
	              					<input type="text" name="h1Tag" class="input-txt">
	              				</div>
	              			</li>
	              			<li>
	              				<label>Url: </label>
	              				<div class="cms-fields">
	              					<textarea name="examUrl" class="input-txt" disabled></textarea>
	              				</div>
	              			</li>
	              		</ul>
	              	</div>
	              </div>
	             <div class="clear-width" id="cms-loader">
	             </div>
	             <div class="exam_content_form clear-width" id="exampages_cms_cont">
	             </div>
           	</div>
		</div>
		<div class="cms-form-wrapper clear-width" id="user_cmnts_text">
			<div>
				<input type="hidden" value="<?=$actionType;?>" id="actionType">
				<input type="hidden" value="<?php echo $examId; ?>" name="examId">
			   
				<div class="button-wrap">
					<a  href="JavaScript:void(0);" id="saveSEO" onclick="saveExamPageSeoFormData()"  class="orange-btn seobtn">Save</a>
					<a  href="JavaScript:void(0);" onclick="window.location.reload();"  class="orange-btn seobtn">Cancel</a>
				</div>
				<div class="clearFix"></div>
			</div>
		</div>	   
    </div>
<!-- </div> -->
</form>
<?php $this->load->view('common/footerNew'); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<script>
	var actionType = '<?php echo $actionType;?>';
	if(actionType !== 'edit'){
		$j('#examList_<?=$formName?>').val('');	
	}
	
    window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var saveInitiated = false;  
		function confirmExit()
		{//alert(saveInitiated);
			if(preventOnUnload == false)
				return 'Any unsaved change will be lost.';
		}
		
    var formname = "<?php echo $formName; ?>";
    var isTinymceEnabled = false;
	
	<?php if(!empty($examId) && !empty($groupId)) {?>
		$j(document).ready(function($j) {
			getContentBasedOnGroup();
		});	
	<?php }else{ 
			$preSelectExam = $_COOKIE['examcontentcms'];
			if(!empty($preSelectExam))
			{
				$preSelectExam = base64_decode($preSelectExam);	
			?>
				setCookie('examcontentcms','');
				$j("select[name='examName']").val('<?=$preSelectExam;?>').trigger('change');

	<?php } } ?>    
	window.onbeforeunload = null;
</script>
