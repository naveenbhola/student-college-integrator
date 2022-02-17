<div class="new-container panel-pad exmCont">
            
            <?php $this->load->view('newExam/titleCard');?>

			       <div class="nav-col nav-bar color-w pos-rl" id="tab-section">
              <?php 
                $this->benchmark->mark('View_SectionNav_start');
                  $this->load->view('mobile_examPages5/newExam/sectionNavTab');
                $this->benchmark->mark('View_SectionNav_end');
              ?>
            </div>
            <!-- <div class="toc-block" id="examTOC">
            <?php //$this->load->view('newExam/examTOC');?>
            </div> -->

            <?php
                $this->benchmark->mark('View_Announcements_start');
                $displayedSectionCount = 0;
                foreach ($examContent['homepage'] as $key => $curObj) { 
                  if(in_array($curObj->getEntityType(), $noSnippetSections)){
                  continue;
                  }
                  $homepageSection['data'] = $curObj->getEntityValue();
                  $homepageSection['sectionName'] = $curObj->getEntityType();
                  $homepageSection['section'] = ($homepageSection['sectionName'] == 'Summary')?'homepage':'';
                  $this->load->view('mobile_examPages5/newExam/homePageSection', $homepageSection);

                  if($homepageSection['sectionName'] ==  'Summary')
                  {
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA"));
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA1"));
                  }  
                  if($homepageSection['sectionName'] ==  'Summary' && !empty($updates['totalUpdates']) && $updates['totalUpdates']>0){
                    $this->load->view('mobile_examPages5/newExam/widgets/announcements');
                  }
                  $displayedSectionCount++;
                  if($displayedSectionCount == 4)
                  {
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON"));
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON1"));   
                  }
                }
                $this->benchmark->mark('View_Announcements_end');

                $i = 0;
                foreach ($examContent['sectionname'] as $section) {
                if($displayedSectionCount == 4)
                {
                  $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON"));
                  $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "AON1"));   
                }
                  $data['section'] = $section;
                  if(array_key_exists($section, $wikiFields) && !empty($examContent[$section])){
                    $wikiData['sectionData'] = $examContent[$section];
                    $wikiData['sectionName'] = $wikiFields[$section];
                    $wikiData['section'] = $section;
                    $this->benchmark->mark('View_wikiFields_start');
                    $this->load->view('mobile_examPages5/newExam/wikiFields', $wikiData); //common view for all wiki fields  
                    $displayedSectionCount++;
                    $this->benchmark->mark('View_wikiFields_end');
                  }else {
                    $this->benchmark->mark('View_Sections_start');
                    switch($section){
                        case 'importantdates':
                          if(!empty($importantDatesData['dates']) || !empty($examContent['importantdates']['wiki']))
                          {
                              $this->load->view('mobile_examPages5/newExam/importantDates',$data);
                              $displayedSectionCount++;
                          }
                        break;
                        case 'results':
                          if(!empty($examContent['results']['wiki']) || !empty($resultData))
                          {
                            $this->load->view('mobile_examPages5/newExam/result',$data);
                            $displayedSectionCount++;
                          }
                      break;
                        case 'applicationform':
                          if(!empty($appFormData['appFormWiki']) || !empty($appFormData['formURL']) || !empty($appFormData['fileUrl'])) {
                            $this->load->view('mobile_examPages5/newExam/applicationForm',$data);
                            $displayedSectionCount++;
                          }
                      break;
                        case 'samplepapers':
                        if((!empty($samplePaperData) && count($samplePaperData) > 0) || (!empty($guidePaperData) && count($guidePaperData) > 0) || !empty($examContent['samplepapers']['wiki'])) {
                            $this->load->view('mobile_examPages5/newExam/samplePaper',$data);
                            $displayedSectionCount++;
                          }
                      break;
                      case 'preptips':
                        if((!empty($preptipsData) && count($preptipsData) > 0) || !empty($examContent['preptips']['wiki'])) {
                            $this->load->view('mobile_examPages5/newExam/prepTips',$data);
                            $displayedSectionCount++;
                          }
                      break;
                    }
                    $this->benchmark->mark('View_Sections_end');
                  }
                  if($section != 'homepage'){
                    $i++;                    
                  }
                  if($i == 1 && $isHomePage){
                      $this->benchmark->mark('View_getFeaturedCollege_start');
                      echo Modules::run('mobile_examPages5/ExamPageMain/getFeaturedCollege',$examId, $groupId);
                      $displayedSectionCount++;
                      $this->benchmark->mark('View_getFeaturedCollege_end');

                      if(!empty($examAccepting['instCourseMapping']) && !empty($examAccepting['totalCount']))
                      {
                        $this->benchmark->mark('View_institutesAcceptingExam_start');
                        $this->load->view('mobile_examPages5/newExam/widgets/institutesAcceptingExam');
                        $displayedSectionCount++;
                        $this->benchmark->mark('View_institutesAcceptingExam_end');
                      }

                      $this->benchmark->mark('View_getContentDeliveryData_start');
                      echo Modules::run('mobile_examPages5/ExamPageMain/getContentDeliveryData',$examId, $groupId, 'mobile');
                      $displayedSectionCount++;
                      $this->benchmark->mark('View_getContentDeliveryData_end');
                  }
                }

  				if(!$isHomePage && !empty($updates['totalUpdates']) && $updates['totalUpdates']>0){
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA"));
                    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "LAA1"));
                    $this->benchmark->mark('View_announcements2_start');
  				          $this->load->view('mobile_examPages5/newExam/widgets/announcements');
                    $this->benchmark->mark('View_announcements2_end');
				        }
                  if(!$isHomePage || (count($examContent['sectionname']) == 1))
                  {
                    $this->benchmark->mark('View_getFeaturedCollege2_start');
                    echo Modules::run('mobile_examPages5/ExamPageMain/getFeaturedCollege',$examId, $groupId);
                    $this->benchmark->mark('View_getFeaturedCollege2_end');

                        if(!empty($examAccepting['instCourseMapping']) && !empty($examAccepting['totalCount']))
                        { 
                          $this->benchmark->mark('View_institutesAcceptingExam2_start');
                          $this->load->view('mobile_examPages5/newExam/widgets/institutesAcceptingExam');
                          $this->benchmark->mark('View_institutesAcceptingExam2_end');
                        }

                        $this->benchmark->mark('View_getContentDeliveryData2_start');
                        echo Modules::run('mobile_examPages5/ExamPageMain/getContentDeliveryData',$examId, $groupId, 'mobile');
                        $this->benchmark->mark('View_getContentDeliveryData2_end');
                  }
                  //Article section
                  $this->benchmark->mark('View_prepareArticleWidget_start');
                  echo Modules::run('mobile_examPages5/ExamPageMain/prepareArticleWidget',$examId, $examName, 'mobile');
                  $this->benchmark->mark('View_prepareArticleWidget_end');

                ?>
            

	<div class="qnaWrapper clr_lw">

	    <?php
                  //AnA section
                  $this->benchmark->mark('View_prepareAnAWidget_start');
                  //echo Modules::run('mobile_examPages5/ExamPageMain/prepareAnAWidget',$examId, $examName, 'mobile');
                  $this->load->view("mobile_listing5/institute/widgets/qna",$anaWidget);
                  $this->benchmark->mark('View_prepareAnAWidget_end');
	    ?>   
         
            <section>
              <h2 class="color-3 f16 heading-gap font-w6"></h2>
            <div class="lcard color-w f14 color-3">
                  <strong class="font-w6">Have any doubt related to <?=$examName?>? Ask our experts</strong>
                  <div class="btn-sec">
                        <a class="btn btn-primary color-o color-f f14 font-w6 m-15top m-5btm" href="#questionPostingLayerOneDiv" data-param="<?=$trackingKeys['ask'];?>" data-inline="true" data-rel="dialog" data-transition="fade" id="ask_on_exam" ga-attr="ASK_QUESTION">Ask Now</a>
                  </div>
              </div>
            </section>

	  </div>

		 <?php 
          if(!empty($similarExams['similarExams']) && count($similarExams) > 0)
          {
            $this->benchmark->mark('View_similarExamWidget_start');
            $this->load->view('mobile_examPages5/newExam/widgets/similarExamWidget');
            $this->benchmark->mark('View_similarExamWidget_end');
          }
          ?>
          <?php if(in_array($streamCheck, array('beBtech','fullTimeMba')))
            { ?>
              <section id="eventCalendarWidgetId">
                        <?php
                                $filterArr = array();
                                if($streamCheck == 'beBtech'){
                                        $filterArr['categoryName'] = 'Engineering';
                                        $filterArr['courseId'] = ENGINEERING_COURSE;
                                }
                                else{
                                        $filterArr['categoryName'] = 'MBA';
                                        $filterArr['courseId'] = MANAGEMENT_COURSE;
                                }
				$filterArr['educationTypeId'] = EDUCATION_TYPE;
				echo Modules::run('event/EventController/getCalendarWidget','examPageMobile',$filterArr);
                        ?>
	      </section>
          <?php } ?>
           
		  <?php $this->benchmark->mark('View_alllayer_start'); 
            $this->load->view("mobile_examPages5/newExam/groupLayer"); ?>
		  <?php $this->load->view("mobile_examPages5/newExam/widgets/announcementsLayer"); ?>
      <?php $this->load->view("mobile_examPages5/newExam/widgets/similarExamLayer");?>
      <?php $this->load->view("mobile_examPages5/newExam/widgets/successLayer")?>
      <?php $this->load->view("mobile_examPages5/newExam/stickyCTA");
      $this->benchmark->mark('View_alllayer_end'); ?>
      <?php $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow'));?>
      </div>
