<?php
    $examPageRequest=$this->load->library('examPages/examPageRequest');     
?>
<p class="clr"></p>
                                  <!--calendar to exam page widget-->
                                  <div class="cal-xamPageWidget">
                                    <?php if($randomExams && count($randomExams) > 0){ ?>
                                      <div class="xamPgWidgt xamPgWidgt1">
                                          <h3>Know about popular <?php echo $examFilter['examCalendarTitle'];?> Entrance Exams</h3>
                                          <div class="xamPgCnt">
                                            <ul>
                                              <?php
                                               foreach($randomExams as $randomExam){
                                                  $examPageRequest->setExamName($examNameListWidget[$randomExam]);
                                                  $examPageImpDateInfo = $examPageRequest->getUrl('imp_dates');
                                                  $examPageWidgetURL = $examPageImpDateInfo['url'];
                                              ?>
                                                <li onclick="trackEventByGA('CALENDAR_EXAMWIDGET_CLICK','EXAM_CALENDAR_EXAMWIDGET_<?php echo $examFilter['examCalendarTitle'];?>');"><h2><a href="<?php echo $examPageWidgetURL;?>" target="_blank"><?php echo strtoupper($examNameListWidget[$randomExam]); ?></a></h2><?php if($urlData[$examNameListWidget[$randomExam]]['topRankPageUrl'] != '') {?><p><a href= "<?php echo SHIKSHA_HOME.'/'.$urlData[$examNameListWidget[$randomExam]]['topRankPageUrl'];?>" target= "_blank">Top colleges accepting <?php echo strtoupper($examNameListWidget[$randomExam]); ?> score <i class="calsprite ic-linkicn"></i></a></p><?php } ?></li>
                                              <?php } ?>
                                            </ul>
                                            <p class="clr"></p>
                                          </div>
                                      </div>

                                  <?php } echo Modules::run('event/EventController/getPredictorWidget');?>
