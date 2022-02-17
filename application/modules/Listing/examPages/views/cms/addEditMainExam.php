<?php 
if($actionType == 'edit'){
	$examName   = htmlentities($examData[0]['examName']);
    $fullName   = htmlentities($examData[0]['fullName']);
    $conductedBy= htmlentities($examData[0]['conductedBy']);
    $conductedByName = $examData[0]['conductedByName'];
    $rootURL = $examData[0]['rootUrl'];
    $editExamId = $examData[0]['examId'];
    $isConductingBody = $examData[0]['isConductingBody'];
    $formShow = '';
}else{
	$formShow = 'display:none;';
}
?>
<div class="abroad-cms-wrapper" style="margin: 0px;">
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="abroad-cms-head" style="width: 99%;">
            <?php if($this->session->flashdata('errorMsg')){?>
            <span id="_exmerrorMsg" style="background-color: #b3ff99;font-size:13px;margin-left: -275px;margin-top: 5px;padding: 5px 10px 5px 10px;width: 225px;"><?php echo $this->session->flashdata('errorMsg');?></span>
            <?php }?>
                <h1 class="abroad-title addMainExamLink"><?php echo $actionType == 'edit' ? "Edit Exam" : "Showing All Exams"?></h1>
                <?php if($actionType == 'add'){?>
                    <h1 class="abroad-title addMainExamLink" style="display:none;">Add Exam</h1>
                    <div class="flRt"><a href="javascript:;" id="" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">+ Add Exam</a></div>
                    <div class="flRt"><a href="javascript:;" id="addMainExamLinkHide" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px; display:none;">- Add Exam</a></div>
                    <div class="flRt"><a href="/examPages/ExamMainCMS/manageGroups" class="orange-btn" style="padding:6px 7px 8px">Manage Groups</a></div>
                    <div class="flRt"><a href="/examPages/ExamPagesCMS/orderExamPages" class="orange-btn" style="padding:6px 7px 8px">Order Exams</a></div>
                <?php }else if($actionType == 'edit'){?>
                    <div class="flRt"><a href="/examPages/ExamMainCMS/showMainExamList" class="orange-btn" style="   padding:6px 7px 8px">< Back</a></div>
                <?php } ?>
            </div>

            <div class="cms-form-wrapper clear-width addMainExamContent" id="" style="<?php echo $formShow?> border-top:1px solid #ccc; margin-bottom:25px;">
            	<form id ="form_<?php echo $formName?>" name="<?php echo $formName?>" action="/examPages/ExamMainCMS/submitExamData" method="POST" enctype="multipart/form-data">
	            	<table class="uni-table" cellspacing="0" cellpadding="0">
	            		<tr>
	            			<td>
	            				<div>
	            					<label class="label-width">Exam Name*</label>
	            					<div class="findSrCnt customSelect multi-slct alignBox">
	            						<div class="custom-srch" style="position:static;height:30px;display:block;">
	            							<input minlength="2" autocomplete="off" maxlength="50" caption="exam name" required="1" validate="validateStr" type="text" class="locations" placeholder=" Enter Exam Name" id="examName_<?php echo $formName?>" name="examName" value="<?php echo $examName;?>" />
	            							<?php 
	            							if($actionType == 'edit'){
	            							?>
	            							<input type="hidden" name="editExamId" value="<?php echo $editExamId?>" />
                                            <input type="hidden" name="examOldName" value="<?php echo $examName?>" />
	            							<?php 
	            							}
	            							?>
	            							<div><div id="examName_<?php echo $formName?>_error" class="errorMsg" style="display:none; padding-top:5px;">&nbsp;</div></div>
	            						</div>
	            					</div>
			            		</div>
                                    <br>
                                <div>
                                    <label class="label-width">Exam Full Name</label>
                                    <div class="findSrCnt customSelect multi-slct alignBox">
                                        <div class="custom-srch" style="position:static;height:30px;display:block;">
                                            <input minlength="2" autocomplete="off" maxlength="100" caption="exam full name" type="text" class="locations" placeholder=" Enter Exam Full Name" id="examFullName<?php echo $formName?>" name="examFullName" value="<?php echo $fullName?>" />
                            
                                        </div>
                                    </div>
                                </div>
                                    <br>
                                 <div>
                                    <label class="label-width">Exam Conducted Body<br><span style="font-size:10px;color:#7c7a79;font-weight:normal;">Specify University / College / Organization etc. conduction this exam</span></label>
                                    <div class="findSrCnt customSelect multi-slct alignBox">
                                        <div class="custom-srch" style="position:static;height:30px;display:block;">
                                            <input minlength="2" maxlength="100" caption="conducted by" type="text" class="locations" placeholder=" Search University / College or Enter Custom Name" id="conductedBy" <?php if(isset($conductedBy) && !empty($conductedBy)){?> disabled="disabled" <?php }?> autocomplete="off" name="inputConductedBy" value="" style="width: 350px;"/>
                                            <div class="search-college-layer" id="institute-list-container" style="display:none; width:334px;border:1px solid #ccc; top:35px;height: auto;max-height: 300px;overflow-y:auto;" ></div>
                                        </div>
                                    </div>
                                
                                    <div id="_selectConductedBy" style=" margin-left: 197px;margin-top: -3px;">
                                    <?php if(isset($conductedBy) && !empty($conductedBy)){?>
                                        <div class="slct-bx totalAttr" id="_ctb-college"><?php echo ($conductedByName) ? $conductedByName : $conductedBy;?> &nbsp;&nbsp;<a href="javascript:void(0);" class="rmvSlctn" onclick="removeConductedBy()">X</a>
                                            <input name="searchConductedBy" value="<?php echo $conductedBy?>" type="hidden">
                                        </div>
                                        <?php 
                                        if(is_numeric($conductedBy)){
                                        ?>
					<div class="clearFix"></div>
					<div id="excludeConductingBodyHtml"><input type="checkbox" name="excludeConductingBodyInUrl" <?php echo ($isConductingBody=='Yes')?'checked':'';?> value="Yes"/><span>Exclude <strong><?php echo $conductedByName?></strong> from URL</span></div>
                                        <?php } ?>    
                                    <?php }?>    
                                    </div>
                                    <div class="clearFix"></div>
                                </div>
                               <br>
                                <div>
                                    <label class="label-width">Create Exam URL At Root<br></label>
                                        <div class="custom-srch" style="margin-left: 192px;margin-top: -20px;display:block;">
                                            <input type="checkbox" id="rootURL" name="rootURL" value="Yes"/>
                                            <span style="font-size:11px;color:#7c7a79;font-weight:normal;">If you select this, the URL of this exam shall be: shiksha.com/exams/<?php echo htmlentities('<exam_name>');?> </span><br><span style="font-size:11px;color:#7c7a79;font-weight:normal;margin-left: 26px;">Please use this feature wisely.</span>
                                        </div>
                                </div>
	            			</td>
	            		</tr>
	            	</table>
            		
					<div id="exampage_comment_btn_section">			   
						<div class="button-wrap" style="margin-left:190px;">
							<a  href="JavaScript:void(0);" onclick ="examMainObj.submitMainExamData('<?php echo $formName?>');" class="orange-btn _btnClick_">Save</a>
							<a  href="/examPages/ExamMainCMS/showMainExamList" class="cancel-btn">Cancel</a>
						</div>
						<div class="clearFix"></div>
					</div>
				</form>
            </div>

            <?php 
            if(is_array($examList) && count($examList) > 0){
            ?>
                <div style="display: block;margin:20px 0px 10px;width: 100%;position: relative;" class="_container_">
                <?php if(!empty($streams)){ ?>
                    <select  id="streamSelect">
                    <option value="0">Select a stream</option>
                    <?php
                    foreach($streams as $key=>$val){?>
                        <option value=<?=$val['id']?>><?php echo $val['name']; ?></option>
                    <?php } ?>
                    </select>
                    <button type="button" id="filterStreams">Filter</button>
                    <div class="_container_" style="display: inline-block;font-size: 14px;margin: 10px 17px;text-align: center;width: 5%;">OR</div>
                     <div style="margin-bottom: 20px;position: relative;display: inline-block;width: 35%;" class="_container_">
                        <input type="text" name="examField" id="examField" class="input-txt" onkeyup="searchExam('examSearchOptions','examField')" placeholder="Search for exams.." minlength="1" maxlength="50" caption="exam" validate="validateStr" autocomplete="off" style="width:100%">
                        <ul id="examSearchOptions" style="display:none;width: 100%;" class="examSearchOptions">
                            <?php foreach($examListForSuggestor as $id=>$name){?>
                                <li><a onclick="showSelectedExam('<?=$id?>',this)" ><?=$name?></a></li>
                            <?php } ?>

                        </ul>     
                </div>
                    <a style="font-size: 15px;margin-left: 40px;" href="javascript:void(0);" id="resetFltr">Reset</a>
                <?php } ?>
                </div>
                
               
                <div class="search-section _container_" id='examList'>
            	<table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0">
            		<tr>
            			<th>S.No.</th>
            			<th>Exam Name</th>
                        <th>No of Course Groups</th>
            			<th>Is Exam Published?</th>
            			<th>Action</th>
            		</tr>
            		<?php 
            		$num = 0;
            		foreach ($examList as $key => $value) {
            			$num++;
            		?>
            		<tr>
            			<td><?php echo $num;?></td>
            			<td><?php echo htmlentities($value['examName']);?></td>
                        <td><?php echo $value['groupCount'];?></td>
            			<td><?php echo ($value['exampage_id'] > 0)? 'Yes':'No';?></td>
            			<td><a href="/examPages/ExamMainCMS/showMainExamList/<?php echo $value['examId']?>" id="editExam">Edit</a></td>
            		</tr>
            		<?php 
            		}
            		if(count($examList) < 1)
            		{
            			echo '<tr>';
            			echo '<td colspan="10">No exam found.</td>';
            			echo '</tr>';
            		}
            		?>
            	</table>
            </div>
            <?php } ?>
		</div>	   
    </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<script type="text/javascript">
	var examMainObj = new examMainClass();
    var rootURL     = '<?php echo $rootURL;?>';
    examMainObj.actionType = "<?php echo $actionType;?>";
    
    $j(document).mouseup(function(e) 
    {
        var container = $j('#examSearchOptions');
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.hide();
        }
    });
	$j(document).ready(function(){
        if(rootURL == 'Yes'){
            $j('#rootURL').prop('checked',true);
        }
		examMainObj.DOMReadyCalls();
		hierarchyMappingForm.hierarchyMappingFormDOMReadyCalls();
	});
if($j('#_exmerrorMsg')){setTimeout(function(){$j('#_exmerrorMsg').fadeOut();},3000);}
</script>
