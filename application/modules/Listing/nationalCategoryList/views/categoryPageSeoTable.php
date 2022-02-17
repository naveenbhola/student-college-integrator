<?php 
if($totalInstituteCount == 1) {
    $heading = str_replace('colleges', 'college', $heading);
    $heading = str_replace('courses', 'course', $heading);
    $heading = str_replace('institutes', 'institute', $heading);
}
?>
<div class="ctp-tbl hid">
    <div class="ctp-popUp">
        <div class="ctp-hed">
            <div class="ctp-tl titl-ellp"><h1 class="ctp-titl"><?php echo $totalInstituteCount; ?> <?php echo $heading; ?> Offering <?php echo $totalCourseCountForAllInstitutes; ?> Courses</h1></div>
            <div class="ctp-cls"><a href="javascript:void(0);"class="ctpclsnglyr">&times;</a></div>
        </div>
    
        <div class="ctp-cont">
            <div class="tbl-seo">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="40%">Institute Name</th>
                        <th width="40%">Course Name</th>
                        <th width="20%">Fees</th>
                    </tr>
                    <?php 
                        foreach ($institutes['instituteData'] as $instituteId => $instituteObj) {
                            $courseObj = reset($instituteObj->getCourses());
                            if(empty($courseObj) || $courseObj->getId() == ''){
                                continue;
                            }
                            $displayLocationObj = $displayDataObject[$courseObj->getId()];
                            if(!empty($displayLocationObj)){
                                $displayLocation = $displayLocationObj->getDisplayLocation();
                                $feesObj = $courseObj->getFeesCategoryWise($displayLocation->getLocationId());
                            }
                            $courseFees = '-';
                            
                            if(!empty($feesObj['general'])){
                                $courseFees = getRupeesDisplableAmount($feesObj['general']->getFeesValue());
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlentities($instituteObj->getName()); ?></td>
                                <td><?php echo htmlentities($courseObj->getName()); ?></td>
                                <td><?php echo $courseFees; ?></td>
                            </tr>
                            <?php
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>