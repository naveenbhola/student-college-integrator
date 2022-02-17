<label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr">
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_COURSE_L0">  Find Colleges by Course
               <div class="submenu menu-layer secondary">
                  <div class="return-button"></div>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Find Colleges By Course</label>
                  <div class="items">
                    <?php 
                    unset($data);
                    foreach($streamBaseCourse as $streamId=>$baseCourse){
                      $data['location'] = 'city_1';
                      $data['stream_id']    =  $streamId;
                      if($tabsContentByCategory[$streamId]['name'] == 'Sarkari Exams' || $streamId == 21){?>
                        <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" href="<?php echo SHIKSHA_HOME;?>/government-exams/exams-st-21"><?php echo $tabsContentByCategory[$streamId]['name'];?></a>
                        </label>
                      <?php }else{?>
                        <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="<?php echo 'fnd-cr'.$streamId;?>"><?php echo $tabsContentByCategory[$streamId]['name'];?>
                        </label>
                    <?php }}?>
                  </div>
               </div>
            </label>

            <?php 
            unset($data);
              foreach($streamBaseCourse as $streamId=>$baseCourse){
                unset($data);
                $data['location'] = 'city_1';
                $data['stream_id']    =  $streamId;
            ?>
            <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_COURSE_L1" id="<?php echo 'fnd-cr'.$streamId;?>">  
              <div class="submenu menu-layer tertiary-1">
                 <label class="return-button" for="<?php echo 'fnd-cr'.$streamId;?>"></label>
                   <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b"> 
                    <?php 
                        $streamUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                        $queryParam = '?fromamp=hamburger&ampstream_id='.$data['stream_id'];
                    ?>
                   Find Colleges by Course in <?php echo $tabsContentByCategory[$streamId]['name'];?></label>
                 <div class="items">
                     <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_COURSE_L2" href="<?php echo $streamUrl.$queryParam;?>">All <?php echo $tabsContentByCategory[$streamId]['name'];?> Colleges</a>
                     <?php foreach ($baseCourse as $baseCourseId => $name) {
                        $data['base_course_id'] = $baseCourseId;
                        $baseCourseUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                        $queryParam = '&fromamp=hamburger&ampstream_id='.$data['stream_id'];
                      ?>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_COURSE_L2" href="<?php echo $baseCourseUrl.$queryParam?>"><?php echo $name;?></a>
                     <?php }?>
                 </div>
              </div>
            <?php }?>

             <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="clg-ranking">College Rankings
            </label>

            <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="read-review"> Read Student Reviews
            </label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_COMPARE" href="<?php echo SHIKSHA_HOME;?>/resources/college-comparison">Compare Colleges</a></label>
          </div>
          <div class="div-d pad-top border-btmf1">
            <label class="block padtb0 color-6 f12">EXAMS</label>
             <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr">
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_VIEW_EXAM_DETAIL_L0">  View Exam Details
               <div class="submenu menu-layer secondary">
                  <div class="return-button"></div>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">View Exam Details</label>
                  <div class="items">
                  <?php 

                  foreach ($streamWithExamNames as $streamName => $exams) {
                    if($streamName == 'Sarkari Exams'){
                    ?>
                    <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" href="<?php echo SHIKSHA_HOME;?>/government-exams/exams-st-21"><?php echo $streamName;?></a></label>
                  <?php }else{?>
                    <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="<?php echo 'vexam'.base64_encode(substr($streamName, 0,5));?>"><?php echo $streamName;?>
                    </label>

                    <?php }}?>
                  </div>
               </div>
            </label>
            <?php foreach ($streamWithExamNames as $streamName => $exams) {?>
            <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_VIEW_EXAM_DETAIL_L1" id="<?php echo 'vexam'.base64_encode(substr($streamName, 0,5));?>">  
                      <div class="submenu menu-layer tertiary-1">
                         <label class="return-button" for="<?php echo 'vexam'.base64_encode(substr($streamName, 0,5));?>"></label>
                           <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b"> View Exam Details in <?php echo $streamName;?></label>
                         <div class="items">
                            <?php foreach ($exams as $examName => $value) {?>
                              <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_VIEW_EXAM_DETAIL_L2" href="<?php echo $value['url'];?>"><?php echo $examName;?></a>
                            <?php }?>
                         </div>
                      </div>
            <?php }?>
            <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="check-exam">
              Check Exam Dates
            </label>

          </div>
          <div class="div-d pad-top border-btmf1">
            <label class="block padtb0 color-6 f12">TOOLS</label>
            
            <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="predict-exam">
                Predict Your Exam Rank
               
            </label>

            <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="predict-clg">
               Predict college basis rank/score
            </label>


            
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_IIMCALL_PREDICTOR" href="<?php echo $this->config->item('iimPredictorUrl');?>">IIM & Non IIM Call Predictor</a></label>

            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_ DU CUTOFF" href="<?php echo SHIKSHA_HOME."/university/university-of-delhi-24642/cutoff" ?>">DU Cut-Offs</a></label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_ALUMNI" href="<?php echo SHIKSHA_HOME."/mba/resources/mba-alumni-data";?>">Check Alumni Salary Data</a></label>
          </div>
          
          <div class="div-d pad-top border-btmf1">
            <label class="block padtb0 color-6 f12">EXPERT GUIDANCE</label> 
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block f14 ga-analytic" data-vars-event-name="HAMBURGER_ASK_EXPERTS" href="<?php echo SHIKSHA_HOME.'/mAnA5/AnAMobile/getQuestionPostingAmpPage';?>">Ask Shiksha Experts</a></label>
             <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="ask-stud">
              Ask Current Students
               
            </label>
          </div>

          <div class="div-d pad-top border-btmf1">
            <label class="block padtb0 color-6 f12">RESOURCES</label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block f14 ga-analytic" data-vars-event-name="HAMBURGER_NEWSARTICLE" href="<?php echo $this->config->item('articleUrl');?>">News & Articles</a></label>

            <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="apply-colleges">
              Apply to Colleges
            </label>

            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block f14 ga-analytic" data-vars-event-name="HAMBURGER_DISCUSSION" href="<?php echo $discussionsHome;?>">Student Discussions</a></label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block f14 ga-analytic" data-vars-event-name="HAMBURGER_VIEWQUESTION" href="<?php echo SHIKSHA_ASK_HOME_URL;?>">View Student Questions</a></label>
          </div>
          <div class="div-d pad-top">
            <label class="block padtb0 color-6 f12">ABOUT SHIKSHA</label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_ABOUT" href="<?php echo SHIKSHA_HOME;?>/mcommon5/MobileSiteStatic/aboutus">Learn About Us</a></label>
            <label class="menu-item item-layer-1 pad10 txt-ovr"><a class="block ga-analytic" data-vars-event-name="HAMBURGER_HELPLINE" href="<?php echo SHIKSHA_HOME;?>/mcommon5/MobileSiteStatic/studentHelpLine">Student Helpline</a></label>
          </div>
          <div class="block pad10 t-cntr b-top m-btm">
             <a class="color-6 f14 font-w4 l-12 m-3btm ga-analytic" data-vars-event-name="HAMBURGER_ABROAD" href="<?php echo SHIKSHA_STUDYABROAD_HOME;?>">
               Interested in overseas  <br> universities, institutes and course visit
                <p class=" block stu pos-rl">
                 <span class="color-b f16 font-w6">studyabroad.shiksha.com<i></i></span>
               </p>
             </a>
          </div>
        </div>

        <!--child layers of hamburger-->
          
          <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L0" id="find-by-course">
           <div class="submenu menu-layer secondary" id="in-ss">
               <label class="return-button" for="find-by-course"></label>
               <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Find Colleges by Specialization</label>
                 <div class="items">
                 <?php 

                 foreach($tabsContentByCategory as $streamArr){
                  if($streamArr['name'] == 'Sarkari Exams' || $streamArr['id'] == 21){?>

                    <label class="menu-item item-layer-2 pad10 txt-ovr"><a class="block ga-analytic" href="<?php echo SHIKSHA_HOME;?>/government-exams/exams-st-21"><?php echo $streamArr['name'];?></a></label>

                <?php }else{?>

                   <label class="menu-item item-layer-2 has-sub-level pad10 txt-ovr" for="<?php echo 'fnc-spec'.$streamArr['id'];?>"><?php echo $streamArr['name'];?>
                   </label>
                  <?php unset($data);}}?>
               </div>
             </div>

             <!-- child layer of specialization -->
             <?php 
                foreach($tabsContentByCategory as $streamArr){
                  unset($data);
                  $data['location'] = 'city_1';
                  $data['stream_id']    = $streamArr['id'];
             ?>
             <input type="checkbox"  class="ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L1" id="<?php echo 'fnc-spec'.$streamArr['id'];?>">
                     <div class="submenu menu-layer tertiary">
                       <label class="return-button" for="<?php echo 'fnc-spec'.$streamArr['id'];?>"></label>

                       <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Find Colleges By Specialization in <?php echo $streamArr['name'];?></label>
                       <div class="items">
                       <?php 
                          $streamUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                          $queryParam = '?fromamp=hamburger&ampstream_id='.$data['stream_id'];
                       ?>
                       <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L2" href="<?php echo $streamUrl.$queryParam;?>">All <?php echo $streamArr['name'];?> Colleges</a>
                        
                        <?php foreach($streamArr['substreams'] as $subStreamArr){
                              $data['substream_id'] = $subStreamArr['id'];
                          ?>
                           
                          <?php if(count($subStreamArr['specializations'])>0){?>
                                <label class="menu-item item-layer-3 has-sub-level pad10 txt-ovr" for="<?php echo 'spec-sub'.$subStreamArr['id'];?>"><?php echo $subStreamArr['name'];?>
                                </label>
                          <?php }else{
                                  $streamUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                                  $queryParam = '?fromamp=hamburger&ampstream_id='.$data['stream_id'].'&ampsb_stream_id='.$subStreamArr['id'];
                          ?>

                                <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L2" href="<?php echo $streamUrl.$queryParam;?>"><?php echo $subStreamArr['name'];?></a>
                          <?php }}?>


                        <?php foreach($streamArr['specializations'] as $spec){
                          unset($data['substream_id'],$data['specialization_id']);
                          $data['specialization_id'] = $spec['id'];
                          $specUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                          $queryParam = '&fromamp=hamburger&ampstream_id='.$data['stream_id'];
                        ?>
                         <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L2" href="<?php echo $specUrl.$queryParam;?>"><?php echo $spec['name'];?></a>
                        <?php }?>
                       </div>
                     </div>

                     <?php foreach($streamArr['substreams'] as $subStreamArr){
                          unset($data['specialization_id']);
                          $data['substream_id'] = $subStreamArr['id'];
                      if(count($subStreamArr['specializations'])>0){?>
                      <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L2" id="<?php echo 'spec-sub'.$subStreamArr['id'];?>">
                                   <div class="submenu menu-layer tertiary-1">
                                     <label class="return-button" for="<?php echo 'spec-sub'.$subStreamArr['id'];?>"></label>
                                     <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Find Colleges By Specialization in <?php echo $subStreamArr['name'];?></label>
                                      <div class="items">
                                      <?php 
                                        $subStreamUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                                        $queryParam = '?fromamp=hamburger&ampstream_id='.$data['stream_id'].'&ampsb_stream_id='.$data['substream_id'];
                                       ?>
                                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L3" href="<?php echo $subStreamUrl.$queryParam;?>">All <?php echo $subStreamArr['name'];?> Colleges</a>
                                       <?php foreach($subStreamArr['specializations'] as $subSpecArr){
                                              $data['specialization_id'] = $subSpecArr['id'];
                                              $specUrl = Modules::run('mcommon5/MobileSiteHome/getCategoryPageUrlForLocation',$data,true,'hamburger');
                                              $queryParam = '&fromamp=hamburger&ampstream_id='.$data['stream_id'].'&ampsb_stream_id='.$data['substream_id'];
                                        ?>
                                        <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_FIND_COLLEGE_BY_SPEC_L3" href="<?php echo $specUrl.$queryParam;?>"><?php echo $subSpecArr['name'];?></a>
                                        <?php }?>
                                      </div>
                                   </div>
                          <?php }
                        }
                    }?>         

             <!-- college ranking -->
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_COLLEGE_RANKING_L0" id="clg-ranking">  
               <div class="submenu menu-layer secondary">
                  <label class="return-button" for="clg-ranking"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">College Rankings</label>
                  <div class="items">
                    <?php 
                    foreach ($rankingMenuData as $streamId => $rankings) {
                            $menuText = $streamToTagsMapping[$streamId]['name'];
                    ?>
                    <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="<?php echo 'clg-rnk'.$streamId;?>">
                      <?php echo $menuText;?>
                    </label>
                  <?php }?>
                  </div>
               </div>

               <?php 
                foreach ($rankingMenuData as $streamId => $rankings) {
                        $menuText = $streamToTagsMapping[$streamId]['name'];
                ?>
                <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_COLLEGE_RANKING_L1" 
                id="<?php echo 'clg-rnk'.$streamId;?>"> 
                  <div class="submenu menu-layer tertiary-1">
                         <label class="return-button" for="<?php echo 'clg-rnk'.$streamId;?>"></label>
                           <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">College Rankings in <?php echo $menuText;?></label>
                         <div class="items">
                              <?php foreach ($rankings as $key => $value) {?>
                                <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_COLLEGE_RANKING_L2" href="<?php echo $value['url'];?>">
                                <?php echo $value['title'];?></a>
                              <?php }?>
                         </div>
                      </div>
              <?php }?>

              <!-- read student review -->
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_READ_STUDENT_REVIEW_L0" id="read-review">
               <div class="submenu menu-layer secondary">
                  <label class="return-button" for="read-review"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Read Student Reviews</label>
                  <div class="items">
                  <?php foreach ($collegeReviewsUrl as $key => $value) {?>
                        <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_READ_STUDENT_REVIEW_L1" href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a>
                  <?php }?>
                  </div>
               </div>

             <!--check exams date-->
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_CHECK_EXAM_DATES_L0" id="check-exam"> 
               <div class="submenu menu-layer secondary">
                  <label class="return-button" for="check-exam"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Check Exam Dates</label>
                  <div class="items">
                  <?php foreach($examImportantDatesUrl as $key=>$value){?>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_CHECK_EXAM_DATES_L1" href="<?php echo $value['url'];?>"><?php echo $value['name'];?></a>
                    <?php }?>
                  </div>
               </div>
             <!--exam predictor-->
             <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_PREDICT_YOUREXAM_RANK_L0" id="predict-exam">
               <div class="submenu menu-layer secondary">
                  <label class="return-button" for="predict-exam"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Predict Your Exam Rank</label>
                  <div class="items">
                  <?php foreach($rpExams as $key=>$value){?>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_PREDICT_YOUREXAM_RANK_L1" href="<?php echo SHIKSHA_HOME.'/'.$value['url'];?>"><?php echo $value['name'];?></a>
                    <?php }?>
                  </div>
               </div>
             <!--college predictor-->
             <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_PREDICT_COLLEGE_L0" id="predict-clg"> 
                <div class="submenu menu-layer secondary">
                  <label class="return-button" for="predict-clg"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Predict college basis rank/score</label>
                  <div class="items">
                  <?php foreach($cpExams as $exam=>$value){?>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_PREDICT_COLLEGE_L1" href="<?php echo SHIKSHA_HOME.$value['directoryName']."/".$value['collegeUrl'];?>"><?php echo $exam;?></a>
                    <?php }?>
                  </div>
               </div>
             <!--ask current students-->
              <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_ASK_CURRENT_STUDENT_L0" id="ask-stud">
              <div class="submenu menu-layer secondary" id="ask-stud">
                  <label class="return-button" for="ask-stud"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Ask Current Students</label>
                  <div class="items">
                  <?php foreach($campusPrograms as $key=>$value){?>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_ASK_CURRENT_STUDENT_L1" href="<?php echo $value['ccUrl'];?>"><?php echo $value['programName'];?></a>
                    <?php }?>
                  </div>
               </div>
               <!--apply to colleges-->
               <input type="checkbox" class="ga-analytic" data-vars-event-name="HAMBURGER_ASK_CURRENT_STUDENT_L0" id="apply-colleges">
              <div class="submenu menu-layer secondary" id="apply-colleges">
                  <label class="return-button" for="apply-colleges"></label>
                  <label class="block txt-ovr color-f font-w6 f15 pad-l bg-clr-b">Apply to Colleges</label>
                  <div class="items">
                  
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_ASK_CURRENT_STUDENT_L1" href="<?php echo SHIKSHA_HOME."/mba/resources/application-forms";?>">MBA</a>
                      <a class="menu-item item-layer-3 pad10 txt-ovr ga-analytic" data-vars-event-name="HAMBURGER_ASK_CURRENT_STUDENT_L1" href="<?php echo SHIKSHA_HOME."/engineering/resources/application-forms";?>">Engineering</a>
                  
                  </div>
               </div>
        <!--end of layers-->


        