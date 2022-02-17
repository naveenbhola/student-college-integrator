<div class="lcard">
<?php echo $examBreadCrumb; ?>
    <div class="ex-blk">
    <?php

      if(empty($activeSectionName)){
            $activeSectionName = 'homepage';
      }

      $headingTag = 'h1';
    ?>
	<h1 class="color-3 f18 font-w6 l-14"><?=$h1?></h1>
      <?php /* }*/ ?>

      <?php /*if(count($groupList)>1){?>
      <span class="f12 color-3">Conducted for <strong><?php echo count($groupList)?> courses</strong>. Showing details for</span>
      <?php }?>
      <?php if(count($groupList)>1){?>
      <p class="f14 color-3 font-w6"><?php echo $groupName;?>
        <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course</a>
      </p>
      <?php }?>

      <?php if($conductedBy['name'] || $conductedBy){?>
        <p class="f12 m-top color-6">Conducted by <strong class="f14 color-3 font-w6">
        <?php if(is_array($conductedBy)){?><a class="block f14 ga-analytic" data-vars-event-name="CONDUCTED_BY" href="<?php echo $conductedBy['url'];?>"><?php echo $conductedBy['name'];?></a><?php }else{ echo htmlentities($conductedBy);}?></strong></p>
      <?php }*/?>
      <?php
      $section = $activeSectionName;
      if(empty($activeSectionName)){
        $section = 'homepage';
      }

      if($anaWidget['totalNumber'] == 1){
                $displayString = "1 Answered Question";
        }
        else if($anaWidget['totalNumber'] >= 2){
                $displayNumber = formatNumber($anaWidget['totalNumber']);
                $displayString = $displayNumber." Answered Questions";
        }
?>
      <?php if(!empty($updatedOn[$section])){ ?>
      <div class="updatedOn"><?php echo $updatedOn[$section];?></div>
	<?php } ?>
	<?php if($anaWidget['totalNumber'] > 0){ ?>
       <div class="discuss-block">
           <a id="discusn" on="tap:ana-section.scrollTo(duration=200)"><i class="discusn-ico"></i><?php echo $displayString;?></a>
       </div>
       <?php } ?>
        <?php   if (!empty($upcomingDateInformation['displayLabel'])){?>
                    <div class="admit-sctn">
                        <p>
                            <i class="admit-ico"></i><strong><?=$upcomingDateInformation['displayLabel']?>:</strong> <?=$upcomingDateInformation['displayDate']?>
                        </p>
                    </div>
        <?php   }?>
       <div class="flex-mob">
      <div class="btn-sec ouline-n shrt-btn">
        <section amp-access="GuideMailed" amp-access-hide tabindex="0" class="ouline-n">
          <a class="btn btn-secondary color-o color-f f14 font-w7 m-15top ga-analytic btn-mob-dis ">
              Guide Sent
          </a>
        </section>
        <section amp-access="NOT GuideMailed AND NOT validuser" amp-access-hide>
          <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getUpdatesTop" data-vars-event-name="DOWNLOAD_GUIDE">Get Updates</a>
        </section>
        <section amp-access="NOT GuideMailed AND validuser" amp-access-hide>
          <a class="btn btn-primary color-o color-f f14 font-w7 m-15top ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_guide&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getUpdatesTop" data-vars-event-name="DOWNLOAD_GUIDE">Get Updates</a>
        </section>
      </div>

      <?php if(array_key_exists('samplepapers', $snippetUrl)) { ?>
      <div class="btn-sec long-btn">
        <section amp-access="NOT validuser" amp-access-hide>
            <a class="btn btn-secondary color-o color-f f14 font-w7 m-15top ga-analytic" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getQuestionPaperTop" data-vars-event-name="DOWNLOAD_SAMPLE_PAPERS_BUTTON">Get Question Papers</a>
        </section>
        <section amp-access="validuser" amp-access-hide>
            <a class="btn btn-secondary color-o color-f f14 font-w7 m-15top ga-analytic" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_download_sample_paper&sectionName=<?=$activeSectionName?>&fromwhere=exampage&clickId=getQuestionPaperTop" data-vars-event-name="DOWNLOAD_SAMPLE_PAPERS_BUTTON">Get Question Papers</a>
        </section>
      </div>
      <?php } ?>
      </div>
      <?php if(!empty($applyNowDetails['courseId']) && $applyNowDetails['courseId']>0){ ?>
      <div class="dot-div m-15top">
         <h2 class="f12 color-6 font-w4 pad8">Last Date to Apply <span class="f12 color-3 font-w6 pad3"><?=$applyNowDetails['of_creationDate']?></span>
            <section class="i-block" amp-access="NOT validuser" amp-access-hide>
              <a class="color-b f12 font-w6 ga-analytic" data-vars-event-name="APPLY_NOW" href="<?=SHIKSHA_HOME?>/muser5/UserActivityAMP/getResponseExamAmpPage?examGroupId=<?=$groupId?>&actionType=exam_apply_online&sectionName=<?=$activeSectionName?>&fromwhere=exampage&instituteName=<?=$applyNowDetails['instituteName']?>&courseId=<?=$applyNowDetails['courseId']?>&isInternal=<?=$applyNowDetails['isInternal']?>&clickId=applyOnline">Apply Now</a>
            </section>
            <section class="i-block" amp-access="validuser" amp-access-hide>
              <a class="color-b f12 font-w6 ga-analytic" data-vars-event-name="APPLY_NOW" href="<?=$examPageUrl;?>?course=<?=$groupId?>&actionType=exam_apply_online&sectionName=<?=$activeSectionName?>&fromwhere=exampage&instituteName=<?=$applyNowDetails['instituteName']?>&courseId=<?=$applyNowDetails['courseId']?>&isInternal=<?=$applyNowDetails['isInternal']?>&clickId=applyOnline">Apply Now</a>
            </section>
          </h2>
      </div>
      <?php } ?>

     <!--  <p class="f11 m-top color-6">Get detailed information about exam announcements, dates, sample paper, exam pattern, application form and more...</p> -->
  </div>
</div>
<?php if(count($groupList)>1){
  $this->load->view('newExam/AMP/groupLayer');
} ?>
