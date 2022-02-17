<div class="admissionTuple" id="admission-process">
        <div class="gap">
            <h2 class="head-L2 head-gap">Admission Process for Specific Courses</h2>
            <div class="lcard">
                <p class="bs-font gap">To find out information on eligibility, process and important dates, select a course</p>
                <div class="slct-multi">
                    <div class="selct-type">
                        <div class="reg-form signup-fld filled" ga-attr="STREAM_FILTER" onclick="openSingleSelectLayer('streamFilters','dropDownStream_input')" >
                            <?php if(!empty($coursesData['sortedStreams']) && count($coursesData['sortedStreams']) > 0) {?>
                                <div class="ngPlaceholder">Stream</div>
                                <a class="multiinput" id="dropDownStream_input"><?php echo htmlentities($coursesData['streams'][$stream_id]->getName());?></a>
                                <div class="select-Class">
                                <select name="dropDownStream" class="streamFilters" id="dropDownStream" style="display:none;" onchange="updateAdmissionAllContent(this,'selectedStreamId')">
                                            <?php foreach($coursesData['sortedStreams'] as $key => $value){ ?>
                                            <option disabled="disabled"></option>
                                                    <option value="<?php echo $value->getId();?>"><?php echo htmlentities($value->getName());?></option>
                                            <?php } ?>
                                            
                                    </select>                     
                                </div>
                            <?php } ?>
                        </div>
                        <div class="reg-form signup-fld filled" ga-attr="COURSE_FILTER" onclick="openSingleSelectLayer('courseFilters','dropDownCourse_input')">
                            <div class="ngPlaceholder">Course</div>
                            <a class="multiinput" id="dropDownCourse_input"><?php echo htmlentities($coursesData['courseObjects'][$courseId]->getName());?></a>

                                <div class="select-Class">  
                                <select show-search="1" name="dropDownCourse" class="courseFilters" id="dropDownCourse" style="display:none;" onchange="updateAdmissionAllContent(this,'selectedCourseId')">
                           <?php
                                foreach ($coursesData['courseCollegeGrouping'] as $collegeName => $coursesList) {          
                                    if($collegeName != 'other'){
                                        $instName = explode('__',htmlentities($collegeName));
                                        $instName = $instName[0];    
                                    }else if($collegeName == 'other' && count($coursesData['courseCollegeGrouping']) != 1){
                                         $instName = 'Other Academic Units';  
                                    }  
                            ?> <?php if($instName != 'Other Academic Units1' && count($coursesData['courseCollegeGrouping']) != 1){?>
                                         <optgroup label="<?php echo $instName;?>">
                                    <?php } ?>
                                        <?php foreach($coursesList as $courseObj){ 
                                            ?>
                                                <option value="<?php echo $courseObj->getId();?>"><?php echo htmlentities($courseObj->getName());?></option>
                                        <?php } ?>
                                        </optgroup>
                            <?php } ?>
                                    </select>                     
                                </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <?php 
            if(!empty($eligibility['showEligibilityWidget']))
                $this->load->view('mobile_listing5/course/widgets/courseEligibilityWidget',array('pageName' => 'Admission'));
            if(!empty($admissions) || !empty($importantDatesData['importantDates']))
                $this->load->view('mobile_listing5/course/widgets/courseAdmissionWidget',array('pageName' => 'Admission'));
        ?>
</div>

	<!--
        <div class="gap">
            <div class="lcard end-col">
                <h2 class="head-L2">Send query to the college</h2>
                <a href="javascript:void(0);" class="btn secondary">Send Enquiry</a>
            </div>
        </div>
	-->
        <!---->


        <div class="gap">
            <div class="lcard end-col">
                <h2 class="head-L2">Interested in this course ? </h2>
                <?php if(!empty($onlineFormData) && !empty($onlineFormData['date'])) {?>
                    <div class="dot-div">
                        <h3>Applications close on</h3>
                        <?php 
                            $ctaLink = "emailResults('".$selectedCourseId."', '".base64_encode($coursesData['courseObjects'][$courseId]->getName())."' , '".$onlineFormData['internalFlag']."' , '1085');"; 
                            $ctaId = "startApp".$courseId;
                        ?>
                        <p class="apply-t"><?=$onlineFormData['date'];?><a id="<?=$ctaId;?>" class="link" href="" onclick="<?php echo $ctaLink;?>" ga-attr="APPLY_ONLINE_FORM">Apply Now</a></p>
                    </div>
                <?php } ?>
                <div class="btn-max">
                    <div class="com-col"><a class="btn secondary addToShortlist" data-trackid="1086" id="" ga-attr="COURSE_SHORTLIST">Shortlist</a></div>
                    <div class="com-col"><a class="btn primary" href="<?=$courseListingUrl;?>" ga-attr="VIEW_COURSE_LINK">View Details</a></div>
                </div>
            </div>
        </div>
        <!----> 
        <input type="hidden" name="selectedCourseId" id="selectedCourseId" value="<?php echo $courseId;?>">
        <input type="hidden" name="selectedStreamId" id="selectedStreamId" value="<?php echo $stream_id?>" >
        <input type="hidden" name="base_url" id="base_url" value="<?php echo $base_url?>" >
