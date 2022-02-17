<div class="new-row">
    <div class="group-card no__pad gap listingTuple" id="structure">
        <h2 class="head-1 gap">Course Structure</span></h2>
        <?php 
        $counter = 0;
        $ViewAllInFirstCourseStructure = false;
        foreach ($courseStructure as $key => $value) {
            $hiddenString = '';
            if($counter >= 1){
                $hiddenString = 'hid';
            }

            $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';
            ?>
            <?php 
                if($value['period'] != 'program'){?>
            <h3 class="prt-title <?=$hiddenString?>"><?=ucfirst($value['period'])." ".$key." ".$courseTextString?></h3>
            <?php } ?>
            <ul class="partner-clgs flex-ul <?=$hiddenString?>">
            
                <?php
                $courseCountInStructure = 0;
                 foreach ($value['structure'] as $key => $val) { 
                    $courseCountInStructure++;
                    $hiddenString = '';
                    if($courseCountInStructure >= 15){
                        $ViewAllInFirstCourseStructure = true;
                        $hiddenString = 'hid';
                    } ?>

                     <li class="<?=$hiddenString?>"><p><?=makeURLAsHyperlink($val['courses_offered'])?></p></li>            
                <?php } ?>                     
            </ul>
        <?php $counter++; } ?>

        <?php if(count($courseStructure) > 1 || $ViewAllInFirstCourseStructure){ ?>
        <a href='javascript:void(0);' id="view-more-course-structure" ga-track="COURSE_STRUCTURE_COURSEDETAIL_DESKTOP" class="view-m">View Complete Course Structure</a>
        <?php } ?>
    </div>
</div>