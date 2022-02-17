<?php $this->load->view('examPages/newExam/stickyTitleCard'); ?>
<?php $this->load->view('examPages/newExam/groupLayer');?>
<?php $this->load->view('examPages/newExam/allAnnouncementLayer');?>

<div class="layer-common" id="similarlayer" style="display:none;">
   <div class="group-card pop-div">
      <a class="cls-head heplyr" data-layer="similarlayer">&times;</a>
      <div>
          <div class="card__right mt__15 ext__div ps__rl">
            <div class="pd_btm_10 anmtLayerdiv" id="examlayerdiv"><h3 class="f16__clr3 fix__sec fnt__n">Exams Similar to <?=$examName;?></h3></div>
            <div class="examlayer">
              <ul class="similar__exams" id="allExams">
              </ul>
            </div>
          </div>
      </div>
   </div>
</div>

<div class="exams__container">

      <?php echo $examBreadCrumb; ?>
       
       <div class="inner__container clear__space">
           <!--title Card-->
           <?php $this->load->view('examPages/newExam/titleCard'); ?>
           <!--end-->
           <!--nav-bar-->
           <div class="nav__block ovrflw__hidden ps__rl" id="TabSection">              
                <?php $this->load->view('examPages/newExam/sectionNav'); ?>              
          </div>           
      <!--end-nav-bar-->
           <!--page content start-->
             <div class="data__col clear__space">
               <!--left__cards sections-->
                <div class="main__col">

                  <!-- <div class="toc-block mt__15" id="examTOC">
                    <?php //$this->load->view('mobile_examPages5/newExam/examTOC');?>
                  </div> -->
                    <!--summary section-->
                    <?php
                    
		                $hsNumber = 0;
		                $secondDFPBannerShown = false;
                    foreach ($examContent['homepage'] as $key => $curObj) { 
                      if(in_array($curObj->getEntityType(), $noSnippetSections)){
                      continue;
                      }
                      $homepageSection['data'] = $curObj->getEntityValue();
                      $homepageSection['sectionName'] = $curObj->getEntityType();
                      $homepageSection['section'] = ($homepageSection['sectionName'] == 'Summary')?'homepage':'';
                      $hsNumber++;
                      if($hsNumber == 3){
                        $this->load->view('examPages/newExam/widgets/middleCTALayer');
                      }

                      /*if($hsNumber == 5){
			                 $secondDFPBannerShown = true;
                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType'=>"content")); 
                        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2','bannerType'=>"content")); 
                      }*/

                      $this->load->view('examPages/newExam/homePageSection', $homepageSection); 

                    }

          		      if($isHomePage && count($examContent['homepage']) < 3){
                			$this->load->view('examPages/newExam/widgets/middleCTALayer');
          		      }


                      $x =0;
                      foreach ($examContent['sectionname'] as $section) { 
                        if($isHomePage && $x == 4){ // After 4th section - AON and AON1
                          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C1','bannerType'=>"content"));
                          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C2','bannerType'=>"content"));
                        }
                        $data['section'] = $section;
                        if(array_key_exists($section, $wikiFields) && !empty($examContent[$section])){
                          $wikiData['sectionData'] = $examContent[$section];
                          $wikiData['sectionName'] = $wikiFields[$section];
                          $wikiData['section'] = $section;
                          $this->load->view('examPages/newExam/wikiFieldsView', $wikiData); //common view for all wiki fields  
                          $x++;
                        }else {
                          switch($section){
                              case 'importantdates':
                                if(!empty($importantDatesData['dates']) || !empty($examContent['importantdates']['wiki']))
                                {

                                  $this->load->view('examPages/newExam/importantDates',$data);
                                  $x++;
                                }
                                //important Dates
                            break;
                              case 'results':
                                if(!empty($examContent['results']['wiki']) || !empty($resultData))
                                {
                                  $this->load->view('examPages/newExam/result',$data);  
                                  $x++;
                                }
                            break;
                              case 'applicationform':
                                if(!empty($appFormData['appFormWiki']) || !empty($appFormData['formURL']) || !empty($appFormData['fileUrl'])) {
                                  $this->load->view('examPages/newExam/applicationForm',$data);
                                  $x++;
                            }
                            break;
                              case 'samplepapers':
                              if((!empty($samplePaperData) && count($samplePaperData) > 0) || (!empty($guidePaperData) && count($guidePaperData) > 0) || !empty($examContent['samplepapers']['wiki'])) {
                                  $this->load->view('examPages/newExam/samplePaper',$data);
                                  $x++;
                            }
                            break;
                              case 'preptips':
                              if((!empty($preptipsData) && count($preptipsData) > 0) || !empty($examContent['preptips']['wiki'])) {
                                  $this->load->view('examPages/newExam/prepTips',$data);
                                  $x++;
                            }
                            break;

                          }
                        }
                        if($section != 'homepage'){
                          $i++;                    
                        }
                        if($i == 1 && $isHomePage){
                          //Content Delivery Section
                          echo Modules::run('examPages/ExamPageMain/getContentDeliveryData',$examId, $groupId);
                        }
                        
                        if($x == 1 && !$isHomePage){  // Exam child page - After 1st section - LAA and LAA1
                          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C1','bannerType'=>"content"));
                          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
                          $x=5;
                        }
                   }
       
         if(!$isHomePage || (count($examContent['sectionname']) == 1)){
			$this->load->view('examPages/newExam/widgets/middleCTALayer');
                      echo Modules::run('examPages/ExamPageMain/getContentDeliveryData',$examId, $groupId);
            }
        ?>
       <!--Article section-->
       <?php echo Modules::run('examPages/ExamPageMain/prepareArticleWidget',$examId, $examName);?>
       <!--ANA section-->
       <div class="qnaWrapper global-box-shadow">
	       <?php $this->load->view('nationalInstitute/InstitutePage/ANAWidget', $anaWidget);?>
	       <?php $this->load->view('examPages/newExam/AskNowCTA'); ?>
       </div>

                  <?php 
                    //similar exams widget
                    if(!empty($similarExams['similarExams']) && count($similarExams) > 0)
                    {
                        $this->load->view('examPages/newExam/similarExam');
                    ?>
                  <?php }
                  ?>
        
                </div>

                <!--right__sliders section-->
                <div class="right__sliders">
    <?php echo Modules::run('examPages/ExamPageMain/getFeaturedCollege',$examId, $groupId);?>
                  <!--inst-->

                  <?php 
                  if(!empty($updates['totalUpdates']) && $updates['totalUpdates']>0){
                    $this->load->view('examPages/newExam/widgets/announcements');
                  } 

                  ?>         
                  <!---->
                  <?php 
                    if(!empty($examAccepting['instCourseMapping']) && !empty($examAccepting['totalCount']))
                    {
                        $this->load->view('examPages/newExam/widgets/institutesAccepting');   
                    }                  

                    if(in_array($streamCheck, array('beBtech','fullTimeMba')))
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
                                echo Modules::run('event/EventController/getCalendarWidget','examPage',$filterArr);
			?>
		      </section>
                    <?php } ?>

		    <?php $this->load->view('dfp/dfpCommonRPBanner',array('bannerPlace' => 'RP','bannerType'=>"rightPanel")); ?>
                </div>
             </div>
           <!---->
       </div>
</div>
