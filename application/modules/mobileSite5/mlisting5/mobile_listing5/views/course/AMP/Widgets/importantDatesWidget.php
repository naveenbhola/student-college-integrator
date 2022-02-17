<?php if(!empty($admissions))
{
  $className = 'border-top margin-20';
}
?>

              <div class="imp-date pad-top <?=$className;?>">
                <?php if(!empty($admissions)) { ?>
                     <h3 class="f14 color-6 font-w6 m-btm">Important dates</h3>
                  <?php } ?>
                     <div class="ss pos-rl">
                         <div class="bar-line padlr0 pos-rl">
                            <?php 
                                $importantDates = $importantDatesData['importantDates'];
                                $source = $importantDatesData['source'];
                                $monthGroups = array();
                                $upcomingShown = false;
                                foreach ($importantDates as $index => $date) { 
                                    $upcomingClass = '';
                                    $upComingCurrent = '';
                                    $disabledBG = '';
                                    if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                                        $upcomingClass = 'current';
                                        $upComingCurrent = 'color-r';
                                    }
                                     if(empty($date['showUpcoming']) && !$upcomingShown){ 
                                          $upcomingClass = 'disable-color';
                                          $disabledBG = 'bg-clr-f1';
                                        }  
                                    ?>
                             <div class="crc-blck">
                                 <div class="crc <?=$upcomingClass;?> <?=$disabledBG;?>"></div>
                                 <div class="l-cnt i-block v-top"><p class="f12 color-3 font-w6 <?=$upcomingClass;?>">
                                     <?php 
                                        $formattedDate = getFormattedDate($date);
                                        $dateComp = explode(',',$formattedDate);
                                        if(count($dateComp) <= 2){
                                            echo $dateComp[0].'<span> ,'.$dateComp[1].'</span>';
                                        }
                                        else{
                                            $middleArr = explode('-',$dateComp[1]);
                                            echo $dateComp[0].'<span> ,'.$middleArr[0].'</span> - ';
                                            echo $middleArr[1];
                                            echo '<span> ,'.$dateComp[2].'</span>';
                                        }
                                        ?>
                                 </p></div>
                                 <div class="r-cnt i-block v-top">
                                     <p class="f13 color-3 font-w4 <?=$upcomingClass;?>"><?=$date['event_name'];?></p>
                                      <?php 
                                        if(!empty($date['showUpcoming']) && !$upcomingShown){ 
                                            $upcomingShown = true; ?>   
                                            <span class="f11 color-6 <?=$upComingCurrent;?>">Upcoming</span>
                                            <?php }
                                            ?>
                                 </div>
                             </div>
                             <?php } ?>
                         </div>
                     </div>
                     <?php if($showImportantViewMore) { ?>
                        <a class="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" on="tap:imp-date" role="button" tabindex="0" data-vars-event-name="IMPDATES_VIEWALL_DATES">View all dates</a>
                        <?php } ?>
                 </div>


        <amp-lightbox id="imp-date" class="" layout="nodisplay" scrollable>
         <div class="lightbox">

             <div class="color-w full-layer">
               <div class="pos-fix f14 color-3 bck1 pad10 font-w6">
                  Important Dates<a class="cls-lightbox color-3 font-w6 t-cntr" on="tap:imp-date.close" role="button" tabindex="0">×</a>
              </div>

                 <div class="col-prime pad10 margin-50">
                   <div class="f12 color-0 pos-rl m-btm">
                   <?php if(count($importantDatesData['examsHavingDates']) > 1 || (count($importantDatesData['examsHavingDates']) > 0 && $importantDatesData['isCourseDates'])) { ?>
                       <div class="dropdown-primary pos-abs color-w border-all tab-cell pos-abs" on="tap:exam-list" role="button" tabindex="0">
                         <span class="option-slctd block color-6 f12 font-w6 ga-analytic" id="optnSlctd" data-vars-event-name="IMPDATES_CHOOSE_EXAM">Choose Exam</span>
                       </div>
                    <?php } ?>
                  </div>
                     <?php 
                          $this->load->view('course/AMP/Widgets/importantDatesLayer',array('examName' => 'All'));
                          foreach ($importantDatesData['examsHavingDates'] as $examKey => $examValue) {
                            $this->load->view('course/AMP/Widgets/importantDatesLayer',array('examName' => $examValue));
                          }
                        ?>
                 </div>
             </div>
         </div>
      </amp-lightbox>                 

     <!-- Layer for exam names--> 
  <?php if(count($importantDatesData['examsHavingDates']) > 1 || (count($importantDatesData['examsHavingDates']) > 0 && $importantDatesData['isCourseDates'])) { ?>
    <amp-lightbox id="exam-list" class="" layout="nodisplay" scrollable>
      <div class="lightbox" on="tap:exam-list.close" role="button" tabindex="0">
          <a class="cls-lightbox color-f font-w6 t-cntr">&times;</a>
          <div class="m-layer">
              <div class="min-div color-w catg-lt">
                  <ul class="color-6">
                        <li><label for="imp_dates_All" class="block">All</label></li>
                    <?php foreach($importantDatesData['examsHavingDates'] as $row) {?>

                        <li><label for="imp_dates_<?php echo $row['exam_id']?>" class="block"><?php echo $row['exam_name']?></label></li>
                    <?php } ?>
                  </ul>
              </div>
          </div>
      </div>
  </amp-lightbox>
  <?php } ?>