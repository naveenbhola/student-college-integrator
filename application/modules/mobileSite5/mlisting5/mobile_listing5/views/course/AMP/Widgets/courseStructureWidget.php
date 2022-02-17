<!--course structure-->
         <section>
             <div class="data-card m-5btm">
                 <h2 class="color-3 f16 heading-gap font-w6">Course Structure</h2>
                 <div class="card-cmn color-w">
                    <?php       
                        $counter = 0;
                        $ViewAllInFirstCourseStructure = false;  
                        foreach($courseStructure as $key => $value) {
                            if($counter >= 1){
                                break;
                            }
                            $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';            
                            ?>
                     <div class="block m-5btm">
                      <div class="pad-all">
                        <?php if($value['period'] != 'program'){?>
                            <h3 class="font-w6 m-3btm f14 color-3"><?=ucfirst($value['period'])." ".$key." ".$courseTextString?></h3>
                        <?php } ?>
                         <ul class="cs-ul ct-l cs">
                                <?php
                                $courseCountInStructure = 0;
                                foreach ($value['structure'] as $key => $val) { 
                                    $courseCountInStructure++;
                                    if($courseCountInStructure >= 8){
                                        $ViewAllInFirstCourseStructure = true;
                                        break;                           
                                } ?>
                                 <li class="f13 color-3 font-w4"><?=makeURLAsHyperlink($val['courses_offered'],true)?></li>
                            <?php } ?>
                         </ul>
                        </div> 
                     </div>
                <?php $counter++; } ?>
                    <?php if(count($courseStructure) > 1 || $ViewAllInFirstCourseStructure){ ?>
                        <a class="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" on="tap:course-struct" role="button" tabindex="0" data-vars-event-name="COURSE_STRUCTURE_VIEWCOMPLETE">View complete course structure</a>
                    <?php } ?>
                 </div>
             </div>
         </section>
<?php if(count($courseStructure) > 1 || $ViewAllInFirstCourseStructure) {?>
<amp-lightbox id="course-struct" class="" layout="nodisplay" scrollable>
      <div class="lightbox" >
          <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:course-struct.close" role="button" tabindex="0">&times;</a>
          <div class="m-layer">
            <div class="min-div color-w course-lt">
                 <?php  
                        foreach($courseStructure as $key => $value) {
                            $courseTextString = (count($value['structure']) > 1)? 'Courses':'Course';            
                            ?>
                     <div class="block str-block">
                     <div class="pad-all">
                        <?php if($value['period'] != 'program'){?>
                            <h3 class="font-w6 m-3btm f14 color-3"><?=ucfirst($value['period'])." ".$key." ".$courseTextString?></h3>
                        <?php } ?>
                         <ul class="cs-ul ct-l">
                            <?php foreach ($value['structure'] as $key => $val) { ?>
                                 <li class="f13 color-3 font-w4"><?=makeURLAsHyperlink($val['courses_offered'],true)?></li>
                            <?php } ?>
                         </ul>
                     </div>
                     </div>
                <?php } ?>
            </div>
          </div>
      </div>
  </amp-lightbox>
  <?php } ?>