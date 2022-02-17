<style type="text/css">
  <?php $this->load->view('/css/allExamPage'); ?>
</style>    
      <div class="allexams_wrap">
      <div class="all_exams">
          <h2 class="page_title"><?php echo $examType." $courseName Entrance Exams"; ?></h2>
          <div class="exams_column auto_clear">
              <?php foreach ($exams as $oneDataIndex => $oneData) {
                    $yearVal = (!empty($oneData['examYear']))?' '.$oneData['examYear']:'';
                    $displayTitle = $oneData['examName'].$yearVal;
                ?> 
              <div class="exam_card">
                <div class="exam_alias">
                                
                   <div class="exam_date auto_clear">
                     <div class="name_width">
                       <a href="<?php echo $oneData['urlKey']; ?>" class="exam_title" title="<?php echo $oneData['examName']; ?>"><?php echo strlen($displayTitle) >= 30 ? substr($displayTitle, 0, 27).'...': $displayTitle; ?></a>
                       <p class="exam_dtl hide_txt" title="<?php echo $oneData['examFullName']; ?>"><?php echo htmlentities(substr($oneData['examFullName'], 0, 52));if(strlen(htmlentities($oneData['examFullName']))>52){echo '...'; } ?></p>
                       <a class="view_examdtl" href="<?php echo $oneData['urlKey']; ?>">View Exam  Details</a>
                     </div>
                     <?php if($guideDownloaded[$oneData['groupId']] == 1){
                      $downloadClass = 'ecta-disable-btn';
                      $downloadText = 'Guide Mailed';
                     }else{
                      $downloadClass = 'button--orange';
                      $downloadText = 'Download Guide';
                     }?>
                     <a class="exam_btn dwn-eguide dgub<?=$oneData['groupId']?> <?=$downloadClass?> button" data-trackingkey='<?=$trackingKeyForGuide?>' data-groupid="<?=$oneData['groupId']?>">Download Guide</a>
                   </div>
                   <!--important dates-->
                   <?php if(!empty($oneData['dates']) && count($oneData['dates']) > 0){?>
                   <div class="imp_dates auto_clear">
                      <h2 class="topic_name">Important Dates</h2>
                      <div class="imp_tabs">
                        <?php foreach ($oneData['dates'] as $key => $value) {
                            $disableClass = '';
                            $examRequestObj->setExamName($oneData['examName']);
                            $isRootUrl = 'No';
                            if($examRequestObj->isRootUrl() && $examRequestObj->isRootUrl() == 'Yes'){
                              $isRootUrl = 'Yes';
                            }
                            if($oneData['isPrimary']){
                              $groupIdForUrl = 0;
                            }else{
                            $groupIdForUrl = $oneData['groupId'];
                            }
                            $importantDatesURL = $examRequestObj->getUrl('importantdates',true, false, $groupIdForUrl, $isRootUrl);
                            if($value['endDate'] < date("Y-m-d")){
                                $disableClass = 'disable';
                            }
                        ?>
                        <div class="table-div <?=$disableClass?>">
                            <div><?=$value['stringToShow']?></div>
                            <div><?=$value['description']?></div>
                        </div>
                        <?php } ?>
                      </div>
                      <p class="exam_padd auto_clear"><a class="view_examdtl flt_right" href=<?=$importantDatesURL?>>View All Dates</a></p>
                   </div>
                   <?php } ?>
                 </div>
                 <!--fixed div-->
                 <?php if(count($finalSectionMappingArr[$oneData['examId']]) > 0){ ?>
                     <div class="fix_at_btm">
                        <h3 class="topic_name">Other Useful Links</h3>
                        <div>
                        <?php 
                          $count = 1;
                          $examRequestObj->setExamName($oneData['examName']);
                          
                          $isRootUrl = 'No';
                          if($examRequestObj->isRootUrl() && $examRequestObj->isRootUrl() == 'Yes'){
                            $isRootUrl = 'Yes';
                          }

                          if($oneData['isPrimary']){
                            $groupIdForUrl = 0;
                          }else{
                            $groupIdForUrl = $oneData['groupId'];
                          }
                          foreach ($finalSectionMappingArr[$oneData['examId']] as $key => $sec) {
                            $secUrl = $examRequestObj->getUrl($sec,true, false, $groupIdForUrl, $isRootUrl);
                          ?>
                          <a class="view_examdtl" href=<?=$secUrl?>><?=$sectionNames[$sec]?></a>
                          <?php 
                          if(count($finalSectionMappingArr[$oneData['examId']]) != $count){ ?>
                              <span>|</span>
                          <?php } $count++; }  ?>
                        </div>
                      </div>
                 <?php } ?>

                 <!--mobile view column-->
                 <div class="mobile_visble auto_clear">
                     <a  class="view_examdtl" href="<?php echo $oneData['urlKey']; ?>">View Exam Details</a>
                     <a class="button exam_btn dgub<?=$oneData['groupId']?> <?=$downloadClass?>" id="download_guide" data-trackingkey='<?=$trackingKeyForGuide?>' data-groupid="<?=$oneData['groupId']?>"><?=$downloadText?></a>
                 </div>
               </div>
                   <?php    } ?>

               
          </div>
       </div>
  </div>
