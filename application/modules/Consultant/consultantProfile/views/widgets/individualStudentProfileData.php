<?php
//_p($tupleData);
//_p($studentAdmittedMappingUniversityData);
?>
<div id="studentProfileDetails<?= $tupleData->getId();?>" class="studentDetails" style="display: none;">
                    <?php if($tupleData->getProfileGraduationMapping() != NULL || $tupleData->getClassXPercentage()!=0 || $tupleData->getClassXIIPercentage()!=0){ ?>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list">
                    <tr>
                    <td width="80px" class="profile-title"><strong>Education</strong></td>
                    <td>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list2 graduationRow">
                            <tr>
                            <?php if($tupleData->getClassXPercentage()!=0 || $tupleData->getClassXIIPercentage()!=0){ ?>
                            <td width="120px" class="graduationRow1">
                                <?php if($tupleData->getClassXPercentage()!=0){?>
                                <p><strong>Class 10th: <?= (int)$tupleData->getClassXPercentage();?>%</strong></p>
                                <?php } ?>
                                <?php if($tupleData->getClassXIIPercentage()!=0){?>
                                <p><strong>Class 12th: <?= (int)$tupleData->getClassXIIPercentage();?>%</strong></p>
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <td width="" class="graduationRow2 noBorder">
                               <?php $graduationData = $tupleData->getProfileGraduationMapping();
                               foreach($graduationData as $key=>$value){
                                        if($value['graduationGPA'] >0 || $value['graduationPercentage'] >0){
                                        ?>          
                                          <div>
                                             <strong>Graduation: <?php if($value['graduationGPA'] >0){ echo $value['graduationGPA']." GPA";}else{ echo $value['graduationPercentage']."%";}?></strong>
                                             <?php  if($value['universityName'] !='' || $value['collegeName'] !=''){?>
                                             <p><?= htmlentities($value['collegeName']);?><?= (($value['universityName']!='' && $value['collegeName'] !='')?", ":"").htmlentities($value['universityName']);?><?= ($value['graduationCityId'] >0)?", ".$studentAdmittedCityData[$value['graduationCityId']]->getName():"";?></p>
                                             <?php if($value['description']!=''){?>
                                             <p onclick="showMoreData(this);"><a href="javascript:void(0);">+ more</a></p>
                                             <p onclick="showLessData(this);" style="display: none;"><a href="javascript:void(0);">show less</a></p>
                                              <div class="show-more-box"  style="display: none;">	
                                                     <i class="consultant-sprite show-more-pointer"></i>
                                                     <p><?= htmlentities($value['description']);?></p>
                                             </div>
                                              <?php }}?>
                                         </div>
                                        <?php }
                                        } ?>
                             </td>
                         </tr>
                    </table>
                    </td>
                </tr>
            </table>
            <div class="seperator"></div>
            <?php } ?>
            <?php //echo "asd".$tupleData->getTotalWorkExperienceInMonths();
            $firstCompanyMapping = reset($tupleData->getProfileCompanyMapping());
            //_p($firstCompanyMapping);
            if($tupleData->getTotalWorkExperienceInMonths() != '' &&
               ($firstCompanyMapping['companyName']!='' ||
                $firstCompanyMapping['companyDomain']!='' ||
                $firstCompanyMapping['startYear']!=0 )
               ) { ?>
            <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list">
                    <tr>
                    <td width="80px" class="profile-title"><strong>Work Exp</strong>
                    <?php if($tupleData->getTotalWorkExperienceInMonths() != ''){ ?>
                    <p><?= $tupleData->getTotalWorkExperienceInMonths();?></p>
                    <?php } ?>
                    </td>
                    <?php if(count($tupleData->getProfileCompanyMapping()) != 0){ ?>
                    <td>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list2">
                            <tr>
                            <?php
                            $profileCompanyData = $tupleData->getProfileCompanyMapping();
                            $count=0;
                            foreach($profileCompanyData as $key=>$companyData){
                                $count++;
                                ?>    
                            <td width="120px" <?= ($count == count($profileCompanyData))?'class="noBorder"':''?>>
                                <p><strong><?= htmlentities($companyData['companyName'])?></strong></p>
                                <p><?php if($companyData['startYear']>0 && $companyData['endYear']>0){ $workEx = $companyData['endYear'] - $companyData['startYear'];echo ($workEx>1)?$workEx." years":"1 year";}?><?=(($companyData['startYear']==0 || $companyData['companyDomain']==''?'':', '))?><?= htmlentities($companyData['companyDomain']==''?'':$companyData['companyDomain'])?> </p>
                            </td>
                            <?php }?>
                         </tr>
                         <?php if(!(count($tupleData->getProfileCompanyMapping()) == 1 && ($firstCompanyMapping['startYear'] == 0 && $firstCompanyMapping['endYear'] == 0))){ ?>
                         <tr class="year-info">
                           <?php
                           $count=0;
                           foreach($profileCompanyData as $key=>$companyData){
                            $count++;
                            ?> 
                            <td <?= ($count == count($profileCompanyData))?'class="noBorder"':''?>><?php if($companyData['startYear']>0){ echo $companyData['startYear']." - ".$companyData['endYear'];}?></td>
                            <?php } ?>
                         </tr>
                         <?php } ?>
                    </table>
                    </td>
                    <?php } ?>
                </tr>
            </table>
            <div class="seperator"></div>
            <?php } ?>
            <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list">
                    <tr>
                    <td width="80px" class="profile-title"><strong>Exam</strong></td>
                    <td>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list2">
                            <tr>
                           <?php
                           $count = 0;
                           $examList = $tupleData->getProfileExamMapping();
                           foreach($examList as $key=>$value)
                           {
                              $count++;
                             ?>             
                            <td width="120px" <?= ($count== count($examList))?'class="noBorder"':''?>>
                                <p><strong><?= $value['examName']?><?= ($value['examScore']!='')?": ".htmlentities($value['examScore']):"";?></strong></p>
                            </td>
                            <?php } ?>
                         </tr>
                    </table>
                    </td>
                </tr>
            </table>
            <?php if($tupleData->getextraCurricularActivities() !=''){?>
            <div class="seperator"></div>
            <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list">
                    <tr>
                    <td width="80px" class="profile-title"><strong>Extra curricular activities</strong></td>
                    <td>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list2">
                            <tr>
                            <td width="auto" class="noBorder">
                                <p><?= htmlentities($tupleData->getextraCurricularActivities());?></p>
                            </td>
                         </tr>
                         
                    </table>
                    </td>
                </tr>
            </table>
            <?php }?>
            <div class="seperator"></div>
            <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list">
                    <tr>
                    <td width="80px" class="profile-title"><strong>Admission</strong></td>
                    <td>
                    <table cellpadding="0" cellspacing="0" border="1" class="student-profile-detail-list2">
                    <?php $admissionUniversity  = $tupleData->getProfileUniversityMapping();
                    foreach($admissionUniversity as $key=>$value){
                    ?>
                    <tr>
                    <td width="auto" class="noBorder">
                        <p><strong><?= htmlentities($value['courseName']);?></strong></p>
                        <p><?= htmlentities($studentAdmittedMappingUniversityData[$value['universityId']]['universityName']);?>, <?= htmlentities($studentAdmittedMappingUniversityData[$value['universityId']]['country']);?></p>
                        <?php if(strtolower($value['scholarshipRecieved'])=='yes'){?>
                        <p><strong>Scholarship</strong></p>
                        <p><?= ($value['scholarshipDetails']=='')?"Scholarship Received":$value['scholarshipDetails'];?></p>
                        <?php } ?>
                    </td>
                    </tr>
                    <?php }?>
                    <tr class="year-info">
                      <td class="noBorder">Got admission in <?= date('Y',strtotime($tupleData->getAdmissionDate()));?></td>
                    </tr>
                    </table>
                    </td>
                </tr>
                
            </table>
            <p style="margin:5px 0 10px; font-size:11px;"><strong><?= htmlentities($tupleData->getStudentName());?> is from <?=$studentAdmittedCityData[$tupleData->getResidenceCityId()]->getName();?>.</strong></p>
</div>
