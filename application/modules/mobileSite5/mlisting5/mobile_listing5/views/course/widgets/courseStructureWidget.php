<div class="crs-widget gap listingTuple" id="structure">
    <h2 class="head-L2">Course Structure</h2>
    <div class="lcard">
        <?php       
        $counter = 0;  
        $ViewAllInFirstCourseStructure = false;
        foreach($courseStructure as $key => $value) {
            if($counter >= 1){
                break;
            }
            $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';            
            ?>
            <div class="hide-d">
                <?php if($value['period'] != 'program'){?>
                <h3 class="admisn"><?=ucfirst($value['period'])." ".$key." ".$courseTextString?></h3>
                <?php } ?>
                <ul class="cs-ul">
                    <?php
                    $courseCountInStructure = 0;
                    foreach ($value['structure'] as $key => $val) { 
                        $courseCountInStructure++;
                        if($courseCountInStructure >= 8){
                            $ViewAllInFirstCourseStructure = true;
                            break;                           
                    } ?>
                         <li><?=makeURLAsHyperlink($val['courses_offered'])?></li>            
                    <?php } ?>                     
                </ul>
            </div>
        <?php $counter++; } ?>
        
        <?php if(count($courseStructure) > 1 || $ViewAllInFirstCourseStructure) { ?>
        <a href="javascript:void(0)" id="csDetail" class="link-blue-medium v-more" ga-attr="COURSE_STRUCTURE_COURSEDETAIL_MOBILE">View complete course structure</a>
        <?php } ?>
    </div>
    <div id="csLayer" style="display:none;">        
        <?php   
        foreach($courseStructure as $key => $value) {    
            $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';            
            ?>
            <div class="hide-d">
                <?php if($value['period'] != 'program'){?>
                <h3 class="admisn"><?=ucfirst($value['period'])." ".$key." ".$courseTextString?></h3>
                <?php } ?>
                <ul class="cs-ul">
                    <?php foreach ($value['structure'] as $key => $val) { ?>
                         <li><?=makeURLAsHyperlink($val['courses_offered'])?></li>            
                    <?php } ?>                     
                </ul>
            </div>
        <?php } ?>        
    </div>

</div>
