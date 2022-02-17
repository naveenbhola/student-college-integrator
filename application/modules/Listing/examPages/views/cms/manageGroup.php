<?php 
if($actionType == 'edit'){
	$groupId = $examData[0]['groupId'];
    $groupName = htmlentities($examData[0]['groupName']);
	$editExamId = $examData[0]['examId'];
    $examName   = $examData[0]['examName'];
    $isPrimary = $examData[0]['isPrimary'];
    $groupYear = $examData[0]['year'];
    
	$formShow = '';
}else{
	$formShow = 'display:none;';
}
?>
<div class="abroad-cms-wrapper" style="margin: 0px;">
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            
            <div class="abroad-cms-head" style="width: 99%;margin-left: 0px;">
            <?php if($this->session->flashdata('errorMsg')){?>
            <span id="_exmerrorMsg" style="background-color: #b3ff99;font-size:13px;margin-left: -275px;margin-top: 5px;padding: 5px 10px 5px 10px;width: 225px;"><?php echo $this->session->flashdata('errorMsg');?></span>
            <?php }?>

                <h1 class="abroad-title addMainExamLink"><?php echo $actionType == 'edit' ? "Edit Group" : "Manage Groups"?></h1>
                <?php if($actionType == 'add'){?>
                    <h1 class="abroad-title addMainExamLink" style="display:none;">Add Group</h1>
                    <div class="flRt"><a href="/examPages/ExamMainCMS/showMainExamList" class="orange-btn" style="   padding:6px 7px 8px">< Back</a></div>
                    <div class="flRt"><a href="javascript:;" id="" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">+ Add Group</a></div>
                    <div class="flRt"><a href="javascript:;" id="addMainExamLinkHide" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px; display:none;">- Add Group</a></div>
                <?php }else if($actionType == 'edit'){?>
                    <div class="flRt"><a href="/examPages/ExamMainCMS/manageGroups" class="orange-btn" style="   padding:6px 7px 8px">< Back</a></div>
                <?php } ?>
            </div>

            <div class="cms-form-wrapper clear-width addMainExamContent" id="" style="<?php echo $formShow?> border-top:1px solid #ccc; margin-bottom:5px;">
            	<form id ="form_<?php echo $formName?>" name="<?php echo $formName?>" action="/examPages/ExamMainCMS/submitExamGroupData" method="POST" enctype="multipart/form-data">
	            	<table class="uni-table" cellspacing="0" cellpadding="0">
	            		<tr>
	            			<td><div>
                                    <label class="label-width">Exam*</label>
                                    <div class="findSrCnt customSelect multi-slct alignBox">
                                    <?php if($isPrimary == 0){?>
                                        <div id="examDropDown" class="custom-srch" style="position:static;height:30px;display:block;">
                                            <select name="examId" id="examName_<?php echo $formName?>"  caption="exam" required="true" validate="validateSelect">
                                                <option value="">-Select Exam Name-</option>
                                                <?php foreach($allExam as $row){?>
                                                <option value="<?php echo $row['examId'];?>"><?php echo htmlentities($row['examName']);?></option>
                                                <?php }?>
                                            </select>
                                            <div><div id="examName_<?php echo $formName?>_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
                                        </div>
                                        <?php }else{ echo $examName;?>
                                            <input type="hidden" name="examId" id="examName_<?php echo $formName?>" value="<?php echo $editExamId;?>">
                                        <?php }?>
                                    </div>
                                </div><br>
	            				<div>
	            					<label class="label-width">Group Name*</label>
	            					<div class="findSrCnt customSelect multi-slct alignBox">
	            						<div class="custom-srch" style="position:static;height:30px;display:block;">
	            							<input minlength="2" maxlength="50" caption="group name" required="1" validate="validateStr" type="text" class="locations" placeholder=" Enter Group Name" autocomplete="off" id="groupName_<?php echo $formName?>" name="groupName" value="<?php echo $groupName?>" />
	            							<div><div id="groupName_<?php echo $formName?>_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
	            						</div>
                                        
                                        <div style="float: right;margin-right: -120px;margin-top: -26px;">
                                            <?php if($isPrimary == 0){?>
                                                <select id="primaryOrder" name="primaryOrder">
                                                    <option value="1" selected="selected">Primary</option>
                                                    <option value="0">No Primary</option>
                                                </select>
                                            <?php }else{?>
                                        <span style="color:blue;margin-right: 35px;"> ( Primary )</span>
                                        <input type="hidden" id="primaryOrder" name="primaryOrder" value="1">
                                        <?php }?>
                                        </div>
                                            
                                    <?php if($actionType == 'edit'){?>
                                        <input type="hidden" name="editGroupId" value="<?php echo $groupId?>"/>
                                        <input type="hidden" name="editExamId" id="editExamId" value="<?php echo $editExamId?>"/>
                                    <?php }?>
	            					</div>
			            		</div>
                                    <br>
                                 <div>
                                    <label class="label-width">Year<br><span style="font-size:10px;color:#7c7a79;font-weight:normal;">The year will be appended to headings on the front end.</span></label>
                                    <div class="findSrCnt customSelect multi-slct alignBox">
                                        <div class="custom-srch" style="position:static;height:30px;display:block;">
                                            <select name="groupYear[0][]" id="groupYear">
                                            <option value="">-Select Year-</option>
                                            <?php $start = date('Y');for($i=($start+1);$i>=($start-2);$i--){?>
                                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
	            			</td>
	            		</tr>
	            	</table>
            		<?php 
            		$prefilledData = array();
            		if(!empty($editExamId) && !empty($groupId)){
						$prefilledData = Modules::run('common/commonHierarchyForm/getPrefilledData', 'examCMS', array('examId' => $editExamId, 'groupId'=>$groupId));
					}
            		echo Modules::run('common/commonHierarchyForm/getHierarchyMappingForm', 'examCMS', $prefilledData);
            		?>
                    
                    <div id="course_error" class="errorMsg" style="font-size: 12px;margin-left: 197px;margin-top: -25px;display: none;"></div>
                    <div id="otherAttr_error" class="errorMsg" style="font-size: 12px;margin-left: 197px;margin-top: 5px;display: none;"></div>

					<div id="exampage_comment_btn_section">			   
						<div class="button-wrap" style="margin-left:190px;">
							<a  href="JavaScript:void(0);" onclick ="examMainObj.submitExamGroupData('<?php echo $formName?>');" class="orange-btn _btnClick_">Save</a>
							<a  href="/examPages/ExamMainCMS/manageGroups" class="cancel-btn">Cancel</a>
						</div>
						<div class="clearFix"></div>
					</div>
				</form>
            </div>
            <div class="_container_" style="font-size: 14px;padding-bottom: 25px;margin-top:35px;"> Select Exam : 
                &nbsp; <select name="filterByExam" id="filterByExam">
                    <option data-eq="0" value="">-Select Exam-</option>
                    <?php $eq=1;foreach($allExam as $row){?>
                    <option data-eq="<?php echo $eq;?>" value="<?php echo $row['examId'];?>"><?php echo htmlentities($row['examName']);?></option>
                    <?php $eq++;}?>
                </select> &nbsp; <span id="grpWait" style="display: none;">Loading...</span>
            </div>
            <div class="search-section _container_" id="groupList"></div>
		</div>	   
    </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>

<script type="text/javascript">
	var examMainObj = new examMainClass();
        examMainObj.actionType = "<?php echo $actionType;?>";
    var isPrimary = '<?php echo $isPrimary;?>';
    var actionType   = '<?php echo $actionType;?>';
    $j('#filterByExam').val('');
	$j(document).ready(function(){
        $j('#examName_addEditGroupForm').val('<?php echo $editExamId;?>');
        $j('#groupYear').val('<?php echo $groupYear;?>');
        $j('#primaryOrder').val(isPrimary);
		examMainObj.DOMReadyCalls();
		hierarchyMappingForm.hierarchyMappingFormDOMReadyCalls();
        if(actionType == 'edit'){
            $j('._container_').addClass('hideCntr');
        }

        if(actionType != 'edit' && getCookie('_groupFilter') !=''){ // retain filter data
            var eq = getCookie('_groupFilter');
            $j('#filterByExam>option:eq('+eq+')').prop('selected', 'selected').trigger('change');
        }
	});
    $j(window).load(function(){
    	hierarchyMappingForm.hierarchyMappingFormDOMLoadCalls();
    });
if($j('#_exmerrorMsg')){setTimeout(function(){$j('#_exmerrorMsg').fadeOut();},3000);} 
var cmsPageName = 'examCMS';
</script>