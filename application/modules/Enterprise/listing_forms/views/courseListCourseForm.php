<!-- The detail page query to give following data -->
<?php 

if($flow != 'add'){
    $j = 0;
    $tempCoursesAlreadyAdded = array();
    for($i= 0 ; $i < count($coursesAlreadyAdded); $i++){
        if($coursesAlreadyAdded[$i]['courseID'] != $courseId){
            $tempCoursesAlreadyAdded[$j] =  $coursesAlreadyAdded[$i];
            $j++;
        }
    }
    $coursesAlreadyAdded = $tempCoursesAlreadyAdded;
}
?>
<div class="float_R" style="width:209px;padding:7px">
<?php if(count($score_array) >0):?>
<div class="completion-box" style="margin-bottom:10px;width:190px;">
<div class="completion-bar">
<span style="width:<?php echo $score_array['percentage_completion'];?>%"></span>
</div>
<strong class="completion-title">Institute Details are <?php echo $score_array['percentage_completion'];?>% complete</strong>
<?php if($score_array['percentage_completion'] != 100): ?>
<p>Top 5 fields to increase % completeness:</p>
<ul style="list-style-type: disc;">
	<?php foreach($score_array['fields_list'] as $key=>$value): 
	$split_array= explode('%**===################&&&&&&@@@@@',$key);
				$label = $value['label'];	
	if(is_array($split_array) && count($split_array)>0 && !empty($split_array[1])) {
				$label = $label." - "."<strong>".$split_array[1]."</strong>";
	}
	?>
	<li style="list-style-type: disc;"><p><?php echo $label;?></p></li>
	<?php endforeach;?>
</ul>
<?php endif;?>
</div>
<?php endif;?>
       <?php if(count($coursesAlreadyAdded) >=1){ ?>
	<div style="background:#f6f6f6;">
        <b>Other courses you have added</b>
        <?php for($i = 0; $i< count($coursesAlreadyAdded) ; $i++){ ?>
        <!--div style="padding:5px 0"><?php  $j=$i+1;echo "$j. ".$coursesAlreadyAdded[$i]['courseName']; ?><br /><div id="courseNum_<?php echo $coursesAlreadyAdded[$i]['courseID']; ?>">[ <a href="#" onClick="window.open('/enterprise/ShowForms/fetchPreviewPage/course/<?php echo $coursesAlreadyAdded[$i]['courseID']; ?>'); return false;">Preview</a> &nbsp; | &nbsp; <a href="#" onClick=" try{ ListingOnBeforeUnload.prompt = true;location.replace('/enterprise/ShowForms/showCourseEditForm/<?php echo $coursesAlreadyAdded[$i]['courseID'];?>'); } catch(err) { }" >Edit</a> &nbsp; <?php if(false) {if(stripos($coursesAlreadyAdded[$i]['status'],'draft') !==false) { ?> | &nbsp; <a href="#" onClick="deleteCourse(this,'<?php echo $coursesAlreadyAdded[$i]['courseID']; ?>','draft','<?php echo $coursesAlreadyAdded[$i]['courseName']; ?>');return false;">Remove Draft</a><?php }} ?> ]</div></div-->
        <div style="padding:5px 0"><?php  $j=$i+1;echo "$j. ".$coursesAlreadyAdded[$i]['courseName']; ?><br /><div id="courseNum_<?php echo $coursesAlreadyAdded[$i]['courseID']; ?>">[ <a href="#" onClick=" try{ ListingOnBeforeUnload.prompt = true; window.location = '/enterprise/ShowForms/showCourseEditForm/<?php echo $coursesAlreadyAdded[$i]['courseID'];?>'; } catch(err) { }" >Edit</a> &nbsp; <?php if(false) {if(stripos($coursesAlreadyAdded[$i]['status'],'draft') !==false) { ?> | &nbsp; <a href="#" onClick="deleteCourse(this,'<?php echo $coursesAlreadyAdded[$i]['courseID']; ?>','draft','<?php echo $coursesAlreadyAdded[$i]['courseName']; ?>');return false;">Remove Draft</a><?php }} ?> ]</div></div>
        <?php } ?>
      </div>
<?php }?>
    </div>
