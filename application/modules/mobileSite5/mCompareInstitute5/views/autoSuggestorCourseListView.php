<tr id="importantInfoDiv">
    <td colspan="2" class="compare-title"><h2>Course Name</h2></td>
</tr>
<tr id="courseDisplayDiv">
<?php 
$j = 0;
$courses = array();
foreach ($institutes as $key => $institute) {
    $instituteId = $institute->getId();
    $courses  = $institute->getCourse();
    $j++;
?>

    <td class="border-right">
        <div class="custome-compare-dropdown" id="courseNameDropDownList<?php echo $j; ?>" style="z-index:8;">
            <a href="javascript:void(0)" onClick="toggleCourseNameDropdown('<?php echo $j; ?>');">
                <div class="arrow"><i class="caret"></i></div>
                <span class="display-area" id="courseSelectCustom<?php echo $j; ?>">Select a Course</span>
            </a>
            <?php 
            if( isset( $courses ) && count($courses)>0 ){
                foreach ($courses as $courseData){ 
                    // primary institute of course
                    $instituteName = '';
                    if($courseData->getInstituteId() != $instituteId){
                        $instituteName = ($courseData->getInstituteShortName() !='') ? $courseData->getInstituteShortName() : $courseData->getInstituteName();
                        $instituteName = '&nbsp;('.$instituteName.')';   
                    }

                    $name = $courseData->getName().(($courseData->getOfferedById() != '' && $instituteId != $courseData->getOfferedById() && $courseData->getOfferedByShortName() != '') ? ', '.$courseData->getOfferedByShortName() : '');
                    $prepareCourse[$j][] = array('id'=>$courseData->getId().'_'.$j,'name'=>$name,'url'=>'javascript:void(0);');
                }
                $firstCourseList  = json_encode(array('popularList'=>$prepareCourse[1],'otherList'=>array()), JSON_HEX_APOS);
                $secondCourseList = json_encode(array('popularList'=>$prepareCourse[2],'otherList'=>array()), JSON_HEX_APOS);
            }
            ?>
        </div>
        <input type="hidden" id= "courseSelect<?php echo $j; ?>" value = "" />
    </td>
<?php 
}
if($empty_compares > 0)
{
    for($e = $filled_compares+1; $e<=$compare_count_max; $e++)
    {
        echo '<td></td>';
    }
}
?>
</tr>
<script>
var firstCourseList  = '<?php echo $firstCourseList;?>';
var secondCourseList = '<?php echo $secondCourseList;?>';
</script>