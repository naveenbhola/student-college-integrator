<div class="overview-details flLt studentAdmitted-tab" style="width:100%;">
    <h2>Following students were helped by <?= htmlentities($consultantObj->getName());?></h2>
    <div id="studentAdmitted-tab-scrollbar1" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
    <div class="cons-scrollbar scrollbar" style="visibility:hidden; left: 8px;">
        <div class="track">
            <div class="thumb"></div>
        </div>
    </div>
    <div class="viewport" style="height:400px">
        <div class="overview clearwidth" style="width:99%; margin-bottom:5px;">
        
            <div class="student-details-box" style="margin-bottom:10px">
                <div class="stu-prev-box flLt" onclick="moveSlider(this,'pre');"><i class="consultant-sprite con-prev-icon"></i></div>
                <div class="student-details-box-slider flLt">
                    <ul>
                        <li>  
                            <?php
                            $studentprofiles = $consultantObj->getConsultantStudentProfiles();
                            $count = 0;
                            $totalCount= count($studentprofiles);
                            foreach($studentprofiles as $studentTuple){
                                $count++;
                                $examMapping = $studentTuple->getProfileExamMapping();
                                $universityMappingData = $studentTuple->getProfileUniversityMapping();            
                            ?>     
                            <div class="student-profile <?= ($count==1?"active":"")?>" onclick="showStudentDetails(this,'<?= $studentTuple->getId()?>');">
                                <strong><?= htmlentities(formatArticleTitle($studentTuple->getStudentName(),25));?></strong>
                                <p><?= htmlentities(formatArticleTitle($universityMappingData[0]['courseName'],25));?></p>
                                <p><?= formatArticleTitle($studentAdmittedMappingUniversityData[$universityMappingData[0]['universityId']]['universityName'],28);?> </p>
                                <?php if(!in_array($examMapping[0]['examId'],array(9999,9998))){ ?>
                                <p> <?= $examMapping[0]['examName'];?><?= ($examMapping[0]['examScore']!='')?": ".htmlentities($examMapping[0]['examScore']):""?><?= (count($examMapping)>1)?", ".$examMapping[1]['examName']:""?><?= ($examMapping[1]['examScore']!='')?": ".htmlentities($examMapping[1]['examScore']):""?></p>
                                <?php } ?>
                                <p class="slide-count"><?= $count." of ".$totalCount;?></p>
                                <i class="consultant-sprite active-pointer"></i>
                            </div>
                            <?php }
                            if($count==1){?>
                            <div class="student-profile" style="cursor: auto;">
                            </div>    
                            <?php }?>
                        </li>
                    </ul>
                </div>
                <div class="stu-next-box flLt" onclick="moveSlider(this,'next');"><i class="consultant-sprite con-next-icon-a"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            <?php
            foreach($studentprofiles as $studentTuple)
            {
                $data['tupleData'] = $studentTuple;
                $this->load->view('widgets/individualStudentProfileData',$data);
            }
            ?>
            <p class="profile-note">Student names may have been changed to protect their identity. Direct contact with these 
            students is not permitted. This student information was shared by the consultant and 
            independently verified by Shiksha.com</p>
        </div>
    </div>
    </div>
</div>