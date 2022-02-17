<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<th colspan="2" class="heading-bg">
        	<div class="compare-detail-content">
       	    	<h2><strong>About University</strong></h2>
            </div>
        </th>
    </tr>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Univesity Highlights</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $whyJoin = $universityObject->getWhyJoin(); ?>
    	<td>
             <?php if(!empty($whyJoin)) { ?>
        	<div class="compare-detail-content smallData<?php echo $courseObj->getId().$univId;?>">
        	<?php echo $whyJoin;?>
            </div>
            <div style="display: none;" class="compare-detail-content fullData<?php echo $courseObj->getId().$univId;?>">
                    <?php echo $whyJoin; ?>
            </div>
            <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>University Type</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $universityType = $universityObject->getTypeOfInstitute(); ?>
    	<td>
            <?php if(!empty($universityType)) { ?>
            <div class="compare-detail-content"><p><?php  echo ($universityType == "not_for_profit")?"Not for profit":ucfirst($universityObject->getTypeOfInstitute()); ?> University</p></div>
             <?php } else{ ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php if($yearEstdFlag) { ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Established</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $establishedYear = $universityObject->getEstablishedYear(); ?>
    	<td>
            <?php if(!empty($establishedYear)) { ?>
        	<div class="compare-detail-content"><p><?php echo $universityObject->getEstablishedYear();?></p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($accreditationFlag) { ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Accreditation</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $univAccreditation = $universityObject->getAccreditation(); ?>
    	<td>
            <?php if(!empty($univAccreditation)) { ?>
        	<div class="compare-detail-content"><p><?php echo $univAccreditation; ?></p></div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($accomodationFlag) { ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Accomodation</strong>
            </div>
        </td>
    </tr>
    <tr>
        <?php foreach ($courseDataObjs as $courseObj) {
                            $univId   = $courseObj->getUniversityId();
                            $universityObject  = $univDataObjs[$univId];
                            $campusAccomodation = $universityObject->getCampusAccommodation();?>
    	<td>
            <?php if($campusAccomodation->getAccommodationDetails()!=""){?>
        	<div class="compare-detail-content"><p><span class="NA-mark-2">&#10004;</span>Available</p></div>
            <?php } else{ ?>
            <div class="compare-detail-content"><p><span class="NA-mark">&times;</span>Not Available</p></div>
            <?php } ?>
        </td>
        <?php } ?>
        <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
    <?php if($scholarshipFlag) { ?>
    <tr>
    	<td colspan="2">
        	<div class="compare-detail-content">
        		<strong>Scholarship</strong>
            </div>
        </td>
    </tr>
    <tr>
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
        		<?php if($courseLevelLink!='') {?><a target="_blank" rel="nofollow" href="<?php echo $courseLevelLink; ?>">Course Level <i class="sprite arrow-icon"></i></a><?php }?>
				<?php if($deptLevelLink!='')   {?><a target="_blank" rel="nofollow"  href="<?php echo $deptLevelLink;   ?>">Department Level <i class="sprite arrow-icon"></i></a><?php }?>
				<?php if($univLevelLink!='')   {?><a target="_blank" rel="nofollow"  href="<?php echo $univLevelLink;   ?>">University Level <i class="sprite arrow-icon"></i></a><?php }?>
            </div>
            <?php } else { ?><span style="display:block;">-&nbsp;-&nbsp;-&nbsp;-&nbsp;-&nbsp;-</span><?php }?>
        </td>
       <?php } ?>
       <?php if($coursesCount == 1){?><td></td><?php } ?>
    </tr>
    <?php } ?>
</table>