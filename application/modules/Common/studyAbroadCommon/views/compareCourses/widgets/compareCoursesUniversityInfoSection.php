 <div>
    <h2 class="compare-detial-title">About university</h2>
    <table border="0" cellpadding="0" cellspacing="0" class="compare-head-table">
        <tr>
            <td width="25%"><div class="compare-detail-content"><strong>University highlights</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $whyJoin = $universityObject->getWhyJoin(); ?>
            <td width="25%">
                <?php if(!empty($whyJoin)) { ?>
                <div class="compare-detail-content smallData<?php echo $courseObj->getId().$univId;?>">
                        <?php echo $whyJoin;?>
                </div>
                <div style="display: none;" class="compare-detail-content fullData<?php echo $courseObj->getId().$univId;?>">
                    <?php echo $whyJoin; ?>
                </div>
                <?php } else{ echo "------"; } ?>                
             </td>
             <?php } ?>
             <?php if($coursesCount == 1){?>
            <td width="25%"></td>
            <td width="25%"></td>
            <?php } else if($coursesCount == 2){?>
            <td width="25%"></td>
            <?php } ?> 
        </tr>
        <tr>
            <td><div class="compare-detail-content"><strong>University type</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $universityType = $universityObject->getTypeOfInstitute(); ?>
            <td><?php if(!empty($universityType)) { ?>
                <div class="compare-detail-content">
                    <?php  echo ($universityType == "not_for_profit")?"Not for profit":ucfirst($universityObject->getTypeOfInstitute()); ?> university
                </div>
                <?php } else{ echo "------";}?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?> 
        </tr>
        <?php if($yearEstdFlag) { ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Establisted year</strong></div></td>
             <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $establishedYear = $universityObject->getEstablishedYear(); ?>
            <td>
                <?php if(!empty($establishedYear)) { ?>
                <div class="compare-detail-content"><?php echo $universityObject->getEstablishedYear();?></div>
                <?php } else { echo "------";}?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?> 
        </tr>
        <?php } ?>
        <?php if($accreditationFlag) { ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Accreditation</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $univAccreditation = $universityObject->getAccreditation(); ?>
            <td>
                <?php if(!empty($univAccreditation)) { ?>
                <div class="compare-detail-content"><?php echo $univAccreditation; ?></div>
                <?php } else { echo "------";}?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?> 
        </tr>
        <?php } ?>
        <?php if($accomodationFlag) { ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Accomodation at university campus</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $campusAccomodation = $universityObject->getCampusAccommodation();?>
            <td><div class="compare-detail-content">
                    <?php     if($campusAccomodation->getAccommodationDetails()!=""){echo "<span class='NA-mark'>&#10004;</span>".'Available';}
                                else { echo "<span class='NA-mark'>&times;</span>"."Not available"; } ?></div></td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?> 
        </tr>
        <?php } ?>
        <?php if($scholarshipFlag) { ?>
        <tr>
            <td><div class="compare-detail-content"><strong>Student scholarship</strong></div></td>
            <?php foreach ($courseDataObjs as $courseObj) { 
            $courseLevelLink    = $courseObj->getScholarshipLinkCourseLevel();
            $deptLevelLink      = $courseObj->getScholarshipLinkDeptLevel();
            $univLevelLink      = $courseObj->getScholarshipLinkUniversityLevel();
            
            if(!(0===strpos($courseLevelLink,'http')) && $courseLevelLink )
                  $courseLevelLink = "http://".$courseLevelLink;
            
            if(!(0===strpos($deptLevelLink,'http')) && $deptLevelLink )
                  $deptLevelLink = "http://".$deptLevelLink;
            
            if(!(0===strpos($univLevelLink,'http')) && $univLevelLink )
                  $univLevelLink = "http://".$univLevelLink; ?>
            <td>
                <?php if($courseLevelLink!=''|| $deptLevelLink!='' || $univLevelLink!='' ){ ?>
                <div class="compare-detail-content">
                    <?php if($courseLevelLink!='') {?><p><a target="_blank" rel="nofollow" href="<?php echo $courseLevelLink; ?>">Course level <i class="common-sprite ex-link-icon"></i></a></p> <?php }?>
                    <?php if($deptLevelLink!='')   {?><p><a target="_blank" rel="nofollow"  href="<?php echo $deptLevelLink;   ?>">Department level <i class="common-sprite ex-link-icon"></i></a></p><?php }?>
                    <?php if($univLevelLink!='')   {?><p><a target="_blank" rel="nofollow"  href="<?php echo $univLevelLink;   ?>">University level <i class="common-sprite ex-link-icon"></i></a></p><?php }?>
                </div>
                <?php } else { echo "------"; }?>
            </td>
            <?php } ?>
            <?php if($coursesCount == 1){?>
            <td></td>
            <td></td>
            <?php } else if($coursesCount == 2){?>
            <td></td>
            <?php } ?> 
        </tr>
        <?php } ?>
    </table>  
</div>