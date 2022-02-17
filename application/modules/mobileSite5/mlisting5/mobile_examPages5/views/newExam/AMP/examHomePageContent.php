<section class="s-container">
  <?php $this->load->view('mcommon5/AMP/dfpBannerView',array('bannerPosition' => 'leaderboard'));?>
          <div class="card-cmn color-w">
              <?php $this->load->view('newExam/AMP/titleCard');?>
          </div>
	  <?php $this->load->view('newExam/AMP/sectionNavTab');?>
    <?php //$this->load->view('newExam/examTOC');?>
          <?php
              $displayedSectionCount = 0;
                foreach ($examContent['homepage'] as $key => $curObj) { 
                  if(in_array($curObj->getEntityType(), $noSnippetSections)){
                  continue;
                  }
                  $homepageSection['data'] = $curObj->getEntityValue();
                  $homepageSection['sectionName'] = $curObj->getEntityType();
                  $homepageSection['keyId'] = $key;
                  $homepageSection['section'] = ($homepageSection['sectionName'] == 'Summary')?'homepage':'';
                  $this->load->view('mobile_examPages5/newExam/AMP/homePageSection', $homepageSection);
                  if($homepageSection['sectionName'] ==  'Summary')
                  {
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA1"));
                  }
                  if($homepageSection['sectionName'] ==  'Summary' && !empty($updates['totalUpdates']) && $updates['totalUpdates']>0){
                    $this->load->view('newExam/AMP/widgets/announcements');
                  } 
                  $displayedSectionCount++;
                  if($displayedSectionCount == 4)
                  {
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON"));
                    $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON1"));   
                  }
            }?>
                 
         <?php
                $i = 0;
                foreach ($examContent['sectionname'] as $section) {

                if($displayedSectionCount == 4)
                {
                  $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON"));
                  $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON1"));   
                }

                
                $data['section'] = $section;
                if(array_key_exists($section, $wikiFields) && !empty($examContent[$section])){
                  $wikiData['sectionData'] = $examContent[$section];
                  $wikiData['sectionName'] = $wikiFields[$section];
                  $wikiData['section'] = $section;
                  $this->load->view('mobile_examPages5/newExam/AMP/wikiFields', $wikiData); //common view for all wiki fields  
                  $displayedSectionCount++;
                }else {
                  switch($section){
                      case 'importantdates':
                        if(!empty($importantDatesData['dates']) || !empty($examContent['importantdates']['wiki']))
                        {
                          $this->load->view('mobile_examPages5/newExam/AMP/importantDates',$data);
                          $displayedSectionCount++;
                        }
                        //important Dates
                    break;
                      case 'results':
                        if(!empty($examContent['results']['wiki']) || !empty($resultData))
                        {
                          $this->load->view('mobile_examPages5/newExam/AMP/results',$data);  
                          $displayedSectionCount++;
                        }
                    break;
                      case 'applicationform':
                        if(!empty($appFormData['appFormWiki']) || !empty($appFormData['formURL']) || !empty($appFormData['fileUrl'])) {
                          $this->load->view('mobile_examPages5/newExam/AMP/applicationForm',$data);
                          $displayedSectionCount++;
                }
                    break;
                      case 'samplepapers':
                      if((!empty($samplePaperData) && count($samplePaperData) > 0) || (!empty($guidePaperData) && count($guidePaperData) > 0) || !empty($examContent['samplepapers']['wiki'])) {
                          $this->load->view('mobile_examPages5/newExam/AMP/samplePaper',$data);
                          $displayedSectionCount++;
                }
                    break;
                    case 'preptips':
                      if((!empty($preptipsData) && count($preptipsData) > 0) || !empty($examContent['preptips']['wiki'])) {
                          $this->load->view('mobile_examPages5/newExam/AMP/prepTips',$data);
                          $displayedSectionCount++;
                }
                    break;
                  }
                }
                if($section != 'homepage'){
                  $i++;                    
                }
                if($i == 1 && $isHomePage){
                //Featured College Section
                  echo Modules::run('mobile_examPages5/ExamPageMain/getFeaturedCollege',$examId, $groupId,'amp');
                  $displayedSectionCount++;
                  if(!empty($examAccepting['instCourseMapping']) && !empty($examAccepting['totalCount']))
                  {
                    $this->load->view('mobile_examPages5/newExam/AMP/widgets/institutesAcceptingExam');
                    $displayedSectionCount++;
                  }    
                  //Content Delivery Section
                  echo Modules::run('mobile_examPages5/ExamPageMain/getContentDeliveryData',$examId, $groupId, 'amp');  
                  $displayedSectionCount++;
                }
              }
        ?>
        <!---
          <section>
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6">Learn more about...</h2>
                <div class="card-cmn color-w f14 color-3">
                    <div>
                        <div class="in-im"><amp-img src="images/ins-img.jpg" width="63" height="45"></amp-img></div>
                        <div class="im-inln l-14">
                              <p class="color-3 f13 font-w7">Indian Institute of Management</p>
                              <span class="color-6 f11 block m-5btm">Ahmedabad</span>
                            <a href="#" class="block f12">View College Details</a>
                          </div>
                    </div>
                    <div class="border-class">
                            <ul class="widget-li f12 color-3 v-top">
                              <li>12 Student Reviews</li>
                              <li>21 Answered Questions</li>
                              <li>5 News & Articles</li>
                              <li>Admission Process</li>
                            </ul>
                         </div>
                    <p class="f14 color-3 font-w6">Most viewed courses</p>
                    <p class="f13">Post Graduate Program in Management</p>
                    <p class="f13">Faculty Development Program</p>
                    <p class="f13">Executive MBA</p>
                    <div class="btn-sec clr">
                        <a class="btn wd48 btn-secondary color-w color-b f12 font-w6 m-15top f-lt">View All Courses (30)</a>
                        <a class="btn wd48 btn-primary color-o color-f f12 font-w7 m-15top f-rt">Download Brochure</a>
                      </div>
                </div>
              </div>
          </section>
          -->
          
          
          <!--Article section-->
          <?php 
            if(!$isHomePage)
            {
                $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));
                $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA1"));
                if(!empty($updates['totalUpdates']) && $updates['totalUpdates']>0){
                    $this->load->view('newExam/AMP/widgets/announcements');
                  }
            }
            if(!$isHomePage || (count($examContent['sectionname']) == 1))
            {
              //Featured College Section
                  echo Modules::run('mobile_examPages5/ExamPageMain/getFeaturedCollege',$examId, $groupId,'amp');
                  if(!empty($examAccepting['instCourseMapping']) && !empty($examAccepting['totalCount']))
                  {
                    $this->load->view('mobile_examPages5/newExam/AMP/widgets/institutesAcceptingExam');
                  }    
                  //Content Delivery Section
                  echo Modules::run('mobile_examPages5/ExamPageMain/getContentDeliveryData',$examId, $groupId, 'amp'); 
            }
          ?>

          <?php echo Modules::run('mobile_examPages5/ExamPageMain/prepareArticleWidget',$examId, $examName, 'amp');?>

	  <div class="qnaWrapper">
		 <?php echo Modules::run('mobile_examPages5/ExamPageMain/prepareAnAWidget',$examId, $examName, 'amp');?>
	         <?php $this->load->view('mobile_examPages5/newExam/AMP/widgets/askWidget'); ?>
	  </div>
          
          
          <?php 
            if(!empty($similarExams['similarExams']) && count($similarExams) > 0)
            {
              $this->load->view('mobile_examPages5/newExam/AMP/widgets/similarExamWidget');
            }
          ?>
          <?php if($streamCheck == 'fullTimeMba' || $streamCheck == 'beBtech')
          {
            $fromWhereForCalendar = 'examPageAMP';
            echo Modules::run('event/EventController/getCalendarWidget',$fromWhereForCalendar, $eventCalfilterArr); 
          }
        $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));?>
      </section>
