<?php
$optionalArgs = array();
for($i = 0; $i < count($details['locations']); $i++){
    $locations[$i]  = $details['locations'][$i]['address'];
    if(isset($locations[$i]) && (strlen($locations[$i]) >0)) {
        $locations[$i] .= ', '.$details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        else {
            $locations[$i] = $details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        $optionalArgs['location'][$i] = $details['locations'][$i]['city_name']."-".$details['locations'][$i]['country_name'];
}
if(count($courseList) > 0) {
?>
<div class="courseBT bld fontSize_14p">Courses</div>
                    <div class="lineSpace_7">&nbsp;</div>
<?php
}
?>
<?php for($i = 0 ; $i < count($courseList) && $i <2 ; $i++) { 
                        $tempOptionalArgs = $optionalArgs;
                        $tempOptionalArgs['institute'] = $details['title'];
                        $courseUrl = getSeoUrl($courseList[$i]['course_id'],"course",$courseList[$i]['courseTitle'],$tempOptionalArgs);
                        ?>
                    <div class="cbullets">
                        <div class="float_R txt_align_r" style="width:50px;padding-right:10px">&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo '/enterprise/ShowForms/showCourseEditForm/'.$courseList[$i]['course_id'] ; ?>" class="fontSize_12p">Edit</a> ]</span></div>
                        <?php
                            if ( $courseList[$i]['approvedBy'] == 'AICTE') {
                                $class = 'class="award"';
                            } else {
                                $class_style = ';padding-left:20px;';
                                $class = '';
                            }
                        ?>						
<div style="margin-right:70px;<?php echo $class_style;?>" <?php echo $class;?> id="<?php echo 'resolutionfor800Course' . $i ?>">						
<script>
if(document.body.offsetWidth<900){
var resname = 'resolutionfor800Course' + <?php echo $i ?>;
	document.getElementById(resname).style.marginRight='0';
	document.getElementById(resname).style.paddingLeft='0';
}
</script>
                        
                        <a href="<?php echo (($ListingMode == 'view') ?$courseUrl:'#'); ?>" title="<?php echo $courseList[$i]['courseTitle'].'-'.$details['title']; ?>"><?php echo $courseList[$i]['courseTitle']; ?></a>
                        <div>   
                            <?php echo $courseList[$i]['course_type']; ?> 
                            <?php if(isset($courseList[$i]['approvedBy']) && ($courseList[$i]['approvedBy'] != '0')){?>
                               , approved by <?php echo $courseList[$i]['approvedBy']; ?>
<?php } ?>
                                    </div>
                        </div>    
                    </div>
<?php } ?>
<?php if(count($courseList) > 2) { ?>
                    <div class="cbullets" style="display:none;" id="extendedCourseList">
<?php for($i = 2 ; $i < count($courseList); $i++) {
                        $tempOptionalArgs = $optionalArgs;
                        $tempOptionalArgs['institute'] = $details['title'];
                        $courseUrl = getSeoUrl($courseList[$i]['course_id'],"course",$courseList[$i]['courseTitle'],$tempOptionalArgs);

?>
                    <div class="cbullets">
                        <div class="float_R txt_align_r" style="width:50px;padding-right:10px">&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo '/enterprise/ShowForms/showCourseEditForm/'.$courseList[$i]['course_id'] ; ?>" class="fontSize_12p">Edit</a> ]</span></div>
                        <?php
                            if ( $courseList[$i]['approvedBy'] == 'AICTE') {
                                $class = 'class="award"';
                                $class_style = '';
                            } else {
                                $class_style = ';padding-left:20px;';
                                $class = '';
                            }
							
							
                        ?>						
						<div style="margin-right:70px;<?php echo $class_style;?>" <?php echo $class;?> id="<?php echo 'resolutionfor800Course' . $i ?>">
<script>
if(document.body.offsetWidth<900){
var resname = 'resolutionfor800Course' + <?php echo $i ?>;
	document.getElementById(resname).style.marginRight='0';
	
	document.getElementById(resname).style.paddingLeft='0';
}
</script>
                        <a href="<?php echo (($ListingMode == 'view') ?$courseUrl:'#'); ?>" title="<?php echo $courseList[$i]['courseTitle'].'-'.$details['title']; ?>"><?php echo $courseList[$i]['courseTitle']; ?></a>
                        <div>   
                            <?php echo $courseList[$i]['course_type']; ?> 
                            <?php if(isset($courseList[$i]['approvedBy']) && ($courseList[$i]['approvedBy'] != '0')){ ?>
                                approved by <?php echo $courseList[$i]['approvedBy']; ?>
<?php } ?>
                                    </div>
                        </div>    
                    </div>    
<?php } ?>
                    </div>
                    <div id="expandIcon" style="padding-left:20px"><img src="/public/images/plusSign.gif" /> <a href="#" onClick="javascript:showAllCourses();return false;">Show All Courses</a></div>
                    <div id="contractIcon" style="display:none;padding-left:20px"> <a href="#" onClick="javascript:hideAllCourses();return false;">Hide Courses</a></div>
<script>
if(document.body.offsetWidth<900){
	document.getElementById('expandIcon').style.paddingLeft='0';
	document.getElementById('contractIcon').style.paddingLeft='0';
}
</script>					
<?php } ?>


