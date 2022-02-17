<?php   
    $i = 1;
    foreach ($courseBucket as $courseId => $value) {
        if(empty($courseObj[$courseId])){
            continue;
        }
        $course = $courseObj[$courseId];  
        $instituteName = $course->getInstituteName();
        $instTitle = $instituteName;
        if(strlen($instituteName) > 45){
            $instituteName = substr($instituteName,0,40).' ...';
        }    
        $closeid = '_close_'.$i;
    ?>
    <div class="added-clgs _ldr" id="b_<?php echo $i;?>">
        <a class="ready-to-compare" href="<?php echo $course->getUrl();?>" title="<?php echo $instTitle;?>">
        <?php echo $instituteName;?></a>
        <a class="close-icon" href="javascript:void(0);" id="<?php echo $closeid; ?>" data-courseId="<?php echo $courseId;?>" data-instituteId="<?php echo $value['instituteId'];?>">&times;</a>
    </div>
<?php $i++;} for($j=$i;$j<=4;$j++){?>          
    <div class="added-clgs">
        <div class="num-to-add"><?php echo $j;?></div>
    </div>
<?php }?>