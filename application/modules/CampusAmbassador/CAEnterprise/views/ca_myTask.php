<?php $this->load->view('CAEnterprise/subTabsCA');?>
<div class="container">
        <div class="new-task-form clear-width" id="taskHeading">
            <h2 class="clear-width">Filters</h2>
            <div class="taskForm-details clear-width">
                <ul>
                    <li>
                        <label class="flLt">
                            <span>*</span> Select Program : 
                        </label>
                            <div class="flLt" style="width:40%">
                                <select id="taskFilters" style="margin-bottom:3px;" onchange="showTaskListByCategory(this.value)" class="task-textfield">
                                    <option value ="0">All</option>
                                    <?php foreach($taskConfig as $key=>$res){ 
                                        if($res['programName'] == 'All'){
                                            $programName =  'Generic';
                                        }else {
                                            $programName = $res['programName'];
                                        }
                                        ?>
                                    <option value ="<?php echo $res['programId'];?>"><?php echo $programName; ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                    </li>
                </ul>
           </div> 
        </div>
        <div class="enterprise-table-sec clear-width">
            <div id="taskListContainer"></div>
        </div>
        <form id="RegistrationForm" name="RegistrationForm" action="/CAEnterprise/CampusAmbassadorEnterprise/processMyTaskData" method="post">
    	<div class="new-task-form clear-width" id="taskHeading">
        	<h2 class="clear-width"><?php echo (isset($taskData)) ? 'Edit Task' : 'New Task';?></h2>
            <div class="taskForm-details clear-width">
            	<ul>
                    <li>
                        <label class="flLt">
                            <span>*</span> Select Program : 
                        </label>
                            <div class="flLt" style="width:40%">
                                <select id="taskProgram" style="margin-bottom:3px;" caption="Task Program" required="true" validate = "validateSelect" name="program" autocomplete="off" class="task-textfield">
                                    <option value="">Please select a program</option>
                                    <?php foreach($taskConfig as $key=>$res){ ?>
                                    <option value ="<?php echo $res['programId'];?>" <?php if($taskData[0]['programId'] == $res['programId']) { echo 'selected="selected"'; } ?>><?php echo $res['programName']; ?></option>
                                    <?php } ?>
                                </select>
                                <div><div id="taskProgram_error" class="errorMsg"></div></div>
                            </div>
                    </li>
                	<li>
	                    <label class="flLt">
                        	<span>*</span> Enter task name : 
                        </label>
                            <div class="flLt" style="width:40%">
                                <input id="taskName" style="margin-bottom:3px;" caption="Task Name" required="true" validate="validateStr" minlength="1" maxlength="100" type="text" class="task-textfield" name="name" value="<?php if(isset($taskData[0]['name'])) echo htmlspecialchars ($taskData[0]['name']);?>" autocomplete="off"/>
                                <div ><div id="taskName_error" class="errorMsg"></div></div>
                            </div>
                    </li>
                    <li>
	                    <label class="flLt">
                        	<span>*</span> Enter task Description : 
                        </label>
                        <div class="flLt" style="width:80%">
                             <textarea id="taskDescription" caption="Task Description" required="true"  minlength="1" maxlength="1000" class="task-txtarea tinymce-textarea" name="description" autocomplete="off"><?php if(isset($taskData[0]['description'])) echo $taskData[0]['description'];?></textarea>
                             <div><div id="taskDescription_error" class="errorMsg"></div></div>
                        </div>
                    </li>
                    <li>
	                    <label class="flLt">
                        	<span>*</span> Enter task start date : 
                        </label>
                        <div style="position:relative; float: left">
                            <input id="taskStartDate" caption="Task Start Date" required="true" validate="validateStr" minlength="1" maxlength="10" type="text" class="task-textfield date-width" name="startDate" value="<?php echo (isset($taskData[0]['start_date'])) ? date('Y-m-d',strtotime($taskData[0]['start_date'])) : '';?>" readonly="true" autocomplete="off"/>
                        	<span id='taskStartDateImg' onclick='task.getStartDate();'class="calendar-icon"></span>
                                <div style="display: inline;"><div id="taskStartDate_error" class="errorMsg"></div></div>
                        </div>
                    </li>
                    <li>
	                    <label class="flLt">
                        	Enter task end date : 
                        </label>
                        <div style="position:relative; float: left">
                            <input id="taskEndDate" caption="Task End Date" type="text" class="flLt task-textfield date-width" name="endDate" value="<?php if(isset($taskData[0]['end_date'])) echo date('Y-m-d',strtotime($taskData[0]['end_date']));?>" readonly="true" autocomplete="off"/>
                                <span id="taskEndDateImg" onclick="task.getEndDate();"class="calendar-icon"></span>
                                <div style="display: inline;"><div id="taskEndDate_error" class="errorMsg"></div></div>
                        </div>
                    </li>
                    <li>
                    	<div class="flLt" style="width:40%;">
                            <label class="flLt">
                              <span>*</span> Enter Prize Name : 
                            </label>
                            <input caption="Task End Date" type="text"  class="flLt task-textfield date-width" id="prizeName" />
                            <div style="display: inline;"><div id="prizeName_error" class="errorMsg"></div></div>
                            <input type="hidden"  value='<?php echo isset($taskData) ? (count($taskData)) : 0;?>' id="prizesArrayCounter" />
                            <div class="prize-list">
                            	<ul>
                                    <?php
                                    if(isset($taskData)) {
                                        foreach($taskData as $key=>$val) {
                                            echo "<li rel='prizes".$key."'>Prize Name : ".$val['prize_name']."</li>";
                                            echo "<input type='hidden' value='{$val['prize_name']}' name='prizes[$key][prizeName]' rel='prizes$key'>";
                                        }
                                    }
                                    ?>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="flLt" style="width:43%;">
                            <label class="flLt">
                              <span>*</span> Enter Prize Amount : 
                            </label>
                            <input type="text" class="flLt task-textfield date-width" style="margin-right:10px;" id="prizeAmount"/>
                            <a href="javascript:void(0);" onclick="task.addPrizes('prize-list','amount-list');" class="orange-btn flLt">Add</a>
                            
                            <div class="amount-list">
                            	<ul>
                                    <?php
                                    if(isset($taskData)) {
                                        foreach($taskData as $key=>$val) {
                                            echo "<li rel='prizes".$key."'>Prize Amount : ".$val['prize_amount']."<a class='remove-link' href='javascript:void(0);' onclick='task.removePrizes(". $key.");'>&nbsp;Remove</a></li>";
                                            echo "<input type='hidden' value='{$val['prize_amount']}' name='prizes[$key][prizeAmount]' rel='prizes$key'>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li>
	                    <label class="flLt">
                        	<span>*</span> Submission Type : 
                        </label>
                        <input type="radio" name="allowSubmissionType" <?php if($taskData[0]['allow_submission_type'] != 'allow_external_links') echo 'checked="checked"';?> value="allow_file_upload"/>Allow file upload
                        <input type="radio" name="allowSubmissionType" <?php if(isset($taskData[0]['allow_submission_type']) && $taskData[0]['allow_submission_type'] == 'allow_external_links') echo 'checked="checked"';?> value="allow_external_links"/>Allow posting external link
                    </li>
                    <li>
                    	<a id="submitForm" href="javascript: void(0);" class="orange-btn flLt"  style="margin:10px 0 10px 175px;" onclick="return task.submitForm('create',<?php echo isset($taskData) ? $taskData[0]['id'] : 0; ?>);">Create Task</a>
                    </li>
                </ul>
            </div>
        </div>
        </form>
      </div>
<?php $this->load->view('common/calendardiv'); ?>
<script>
    var task = new myTask();
    task.initializeMyTask();
    
    <?php if(isset($taskData)) { ?>  
    task.scroll();
    <?php } ?>
</script>