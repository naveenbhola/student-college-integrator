<div class="lineSpace_10">&nbsp;</div>

<div style="float:left;width:75%">	
    <form autocomplete="off" action="saveClientCourseMatchingCriteria" onSubmit="return validateMatchingCriteria();" method="POST">        
        <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id; ?>" />
        <?php  $this->load->view('enterprise/searchFormMatchedResponseSelectCourses'); ?> 
        
        <div class="clearFix spacer20"></div>
        <div class="clearFix spacer20"></div>
        
        <label>Quality Percentage:</label>
        <div style="float:left;width:100%;">
            <div style="float:left;width:50%;">
                <input type="text" name="qualitypercentage" id="qualitypercentage" value="" />               
            </div>
            <div style="float:left;width:50%;">
                <div style="width:100px;float:left"><input type="button" value="Get Count" onclick="getMatchedResponsesCount()"/></div><br/>
                <div id="matchedresponsescount" style="float:right;width:250px;font-size:18px"></div>
                 <label style="float:left;width:100%;">* &nbsp;Last 30 days</lable>          
            </div>
        </div>
        <div class="clearFix spacer20"></div>
        <input type="submit" value="Submit" class="orange-button"  />
        <div class="lineSpace_10">&nbsp;</div>
    </form>
</div>

<div style="float:left;width:25%">
	<div class="select-courses-head"><strong style="text-align: left; margin-left:18px;">Client Existing Criteria</strong></div>
    <?php
    foreach($clientallcriteria as $course_id=>$details) { ?>
        <div class="lineSpace_10">&nbsp;</div>
        <label><b>Course Name : </b><?php echo $all_courses_data[$course_id]['courseTitle'];?></label><br/>
        <!-- <label>Institute Name : <?php echo $all_courses_data[$course_id]['institute_name'];?></label><br/> -->
        <!-- <label>Course Id : <?php echo $course_id;?><label><br/> -->
        <label><b>Quality Percentage: </b><?php echo $details['qualitypercentage'];?></label>
            
    <?php } ?>
</div>

