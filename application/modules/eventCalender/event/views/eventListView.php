<?php
        $this->load->builder('ExamBuilder','examPages');
        $examPageBuilder          = new ExamBuilder($params);
        $this->examPageRepository = $examPageBuilder->getExamRepository();
        $examPageRequest=$this->load->library('examPages/examPageRequest');
        ?>    
                                  <div class="cal-content" id="calListView">
                                  <div class="cal-eventList">
                                      <h2><?php echo $examFilter['examCalendarTitle'];?> Entrance Exams Events List <?php echo $yearRangeString;?></h2>
                                      <div class="cal-grp2R" style="width: 75px;float: right;">
                                            <h2 class="txtTyp1">View :  </h2>
                                            <div class="cal-dropdown3">
                                                <ul class="btngrp-1">
                                                    <li><a class="actveheadbtn">Month</a>
                                                    </li>
                                                    <li><a>Year</a>
                                                    </li>
                                                </ul>
                                                <ul class="btngrp-2">
                                                    <li><a onclick="showListView('calender-vw');trackEventByGA('CALENDAR_VIEW_CLICK','EXAM_CALENDAR_CALENDARVIEW_<?php echo $examFilter['examCalendarTitle'];?>');"><i class="calsprite  ic-calender-view"></i></a>
                                                    </li>
                                                    <li><a class="actveheadbtn"><i class="calsprite ic-list-view"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                      <p class="clr"></p>
                                 <ul class="evntLst">
                                  <?php
                                  $counter = 0;
                                  $currentMonth=date("m");
                                  
                                  foreach($eventListData as $m=>$eventListDataRow) {
                                                                    $eventDates = array_keys($eventListDataRow);
                                                                    $firstEventDate = $eventDates[0];
                                                                    $eventYear = date('Y',strtotime($firstEventDate));
                                  $eventSection = '';                            
                                  if((int)$currentMonth!=(int)$m)
                                     {
                                                                    $eventSection = 'eventSectionsClass';
                                     }
                                    ?>
                                  <li onclick="showHideEventList('monthSection_<?php echo $counter;?>')"><p class="eevnthead" style="cursor: pointer">
                                  <?php echo date('F', strtotime("2000-$m-1"))." ".$eventYear;?></p>
                                  <div class="eevnt-content <?php echo $eventSection;?>" id="monthSection_<?php echo $counter;?>">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                 
                                  <tr>
                                    <td class="eev-head"><p>Date</p></td>
                                    <td class="eev-head"><p>Label</p></td>
                                    <td class="eev-head"><p>Event</p></td>
                                  </tr>
                                  <?php
                                  foreach($eventListDataRow as $date=>$value) {  $custom=0; $general=0;?>
                                  <?php
                                    $monthParse = date_parse_from_format("Y-m-d", $date);
                                    $month= $monthParse["month"];
                                    ?>               
                                  <?php
                                  $colorArr = array('customEvent'=>'eev-custom','examPageEvent'=>'eev-default');
                                                foreach($value as $eventType=>$res){
                                                foreach($res as $array=>$resultSet) {?>
                                                <tr class="<?php echo $colorArr[$eventType];?>">
                                                <?php               if($eventType=="examPageEvent" && $general==0) {
                                                                    $dateFormatted=date("d-m-Y",strtotime($date));
                                                                    ?>
                                                <td class="eev-cont eevDate" rowspan="<?php echo count($res);?>"><p><?php echo $dateFormatted?></p></td> <?php $general=$general+1; } ?>
                                                
                                                <?php if($eventType=="customEvent" && $custom==0) {
                                                                    $dateFormatted=date("d-m-Y",strtotime($date));
                                                                    ?>
                                                <td class="eev-cont eevDate" rowspan="<?php echo count($res);?>"><p><?php echo $dateFormatted?></p></td> <?php $custom=$custom+1; } ?>
                                                
<td class="eev-cont eevLabel"><a <?php if($resultSet['exam_url']!=''){ ?> href= "<?php echo $resultSet['exam_url'];?>"  target="_blank"<?php } ?> ><?php echo stripslashes($resultSet['fullTitle']);?></a></td>
                                                <td class="eev-cont eevEvent"><p><?php echo stripslashes($resultSet['fullDescription']);?><?php if($resultSet['article_id']>0) {?><a href="<?php echo $resultSet['article_url'];?>" target="_blank"><br>Know More</a><?php } ?></p></td><?php } ?>
                                   </tr>
                                   <tr><td colspan="3" class="brkrEvntLst"></td></tr>
                                                 
                                          <?php } ?>
                                          <?php } ?>
                                          </table>                                       
                                              </div>
                                          </li>
                                          <?php $counter++;} ?>
                                  </ul>

                                  </div>  
                             </div>
                                  </div>
                                  <?php
                                  $inputData = array(
                                      'examFilter' =>$examFilter,
                                      'eventDataForExamWidget' =>$examNameList
                                    );
                                  echo Modules::run('event/EventController/getExamWidget',$inputData);?>
                        </div>
                    </div>
<script>
var eventListCounter = '<?php echo $counter;?>';
var eventListMonth= '<?php echo $m;?>';
</script>
<style>
.eventSectionsClass{display: none !important}                                  
</style>
