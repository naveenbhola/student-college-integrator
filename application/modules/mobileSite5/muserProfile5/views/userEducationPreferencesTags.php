 <div class="profile-col">
                                <div class="profile-col-heading">
   <p class="expertise-h1"  style='color:#ccc'>Ask & Answer PREFERENCES</p>
                                    <p class="expertise-h2">These preferences shall be used to personalize the <b>Ask &amp; Answer</b> homefeed</p>
                                 <div class="clearFix"></div>
                               </div> 
                               <div class="profile-detail-list">
                                     <div class="n-card">  
                                         <div class="study-stream">course Level</div>
                                         <div id="courseLevelEduPref_tags">
                                          <?php
                                           if(isset($educationalPref['course_level'])){
                                              foreach ($educationalPref['course_level'] as $key => $value) {
                                              ?>                                              
                                                      <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='course_level'>
                                                         <span class="name"><?=$value['tagName'];?></span>
                                                         <span class="cls">&times;</span>
                                                      </div>
                                                      <?php                                             
                                              }
                                           }
                                           ?>
                                         </div>
                                          <div class="" data-enhance="false">                      
                                            <a href="javascript:void(0);" customPlaceholder="Search by course level" layerAttr='courseLevelEduPref' class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Course Level'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select entity='course_level' name="courseLevelEduPref" multiple="multiple" show-search="1" id="courseLevelEduPref" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>  

                                     </div>
                                     
                                      <div class="n-card">  
                                            <div class="study-stream">Field/Stream of interest</div>
                                            <div id="streamEduPref_tags">
                                              <?php
                                             if(isset($educationalPref['stream_interest'])){
                                                foreach ($educationalPref['stream_interest'] as $key => $value) {
                                                ?>                                              
                                                        <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='stream_interest'>
                                                           <span class="name"><?=$value['tagName'];?></span>
                                                           <span class="cls">&times;</span>
                                                        </div>
                                                        <?php                                             
                                                }
                                             }
                                             ?>
                                           </div>
                                          <div class="" data-enhance="false">                      
                                            <a customPlaceholder="Search by field of interest" layerAttr='streamEduPref'href="javascript:void(0);" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Stream'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select entity='stream_interest' name="streamEduPref" multiple="multiple" show-search="1" id="streamEduPref" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>
                                     </div>
                                     
                                      <div class="n-card">  
                                            <div class="study-stream">Specialization</div>
                                            <div id="specializationEduPref_tags">
                                               <?php
                                                 if(isset($educationalPref['specialization'])){
                                                    foreach ($educationalPref['specialization'] as $key => $value) {
                                                    ?>                                              
                                                            <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='specialization'>
                                                               <span class="name"><?=$value['tagName'];?></span>
                                                               <span class="cls">&times;</span>
                                                            </div>
                                                            <?php                                             
                                                    }
                                                 }
                                                 ?>
                                             </div>
                                              <div class="" data-enhance="false">                      
                                                <a customPlaceholder="Search by specialization" layerAttr="specializationEduPref" href="javascript:void(0);" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Specialization'><i class="profile-sprite slct-add"></i>Add</a> 
                                              </div>

                                              <div class="select-Class" data-enhance="false">
                                                <select entity='specialization' name="specializationEduPref" multiple="multiple" show-search="1" id="specializationEduPref" style="display:none;" append-selected-value="1">
                                                </select>                     
                                              </div>
                                     </div>
                                     
                                     <div class="n-card">  
                                            <div class="study-stream">Degree/Diploma</div>
                                            <div id="degreeEduPref_tags">
                                                 <?php
                                                   if(isset($educationalPref['degree'])){
                                                      foreach ($educationalPref['degree'] as $key => $value) {
                                                      ?>                                              
                                                              <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='degree'>
                                                                 <span class="name"><?=$value['tagName'];?></span>
                                                                 <span class="cls">&times;</span>
                                                              </div>
                                                              <?php                                             
                                                      }
                                                   }
                                                   ?>
                                           </div>
                                          <div class="" data-enhance="false">                      
                                            <a customPlaceholder="Search by degree/diploma name" layerAttr="degreeEduPref" href="javascript:void(0);" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Degree/Diploma'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select entity='degree' name="degreeEduPref" multiple="multiple" show-search="1" id="degreeEduPref" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>
                                     </div>
                                     
                                     <div class="n-card">  
                                         <div class="study-stream">countries of interest</div>
                                         <div id="countryInterestEduPref_tags">
                                              <?php
                                             if(isset($educationalPref['countries_interest'])){
                                                foreach ($educationalPref['countries_interest'] as $key => $value) {
                                                ?>                                              
                                                        <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='countries_interest'>
                                                           <span class="name"><?=$value['tagName'];?></span>
                                                           <span class="cls">&times;</span>
                                                        </div>
                                                        <?php                                             
                                                }
                                             }
                                             ?>
                                          </div>
                                          <div class="" data-enhance="false">                      
                                            <a href="javascript:void(0);" customPlaceholder="Search by country name" layerAttr="countryInterestEduPref" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Country'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select entity='countries_interest' name="countryInterestEduPref" multiple="multiple" show-search="1" id="countryInterestEduPref" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>
                                     </div>
                                     
                                      <div class="n-card">  
                                            <div class="study-stream">cities of interest in india</div>
                                            <div id="citiesInterestEduPref_tags">
                                              <?php
                                             if(isset($educationalPref['cities_interest'])){
                                                foreach ($educationalPref['cities_interest'] as $key => $value) {
                                                ?>                                              
                                                        <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='cities_interest'>
                                                           <span class="name"><?=$value['tagName'];?></span>
                                                           <span class="cls">&times;</span>
                                                        </div>
                                                        <?php                                             
                                                }
                                             }
                                             ?>
                                           </div>
                                          <div class="" data-enhance="false">                      
                                            <a href="javascript:void(0);" customPlaceholder="Search by city name" layerAttr="citiesInterestEduPref" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Cities'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select entity='cities_interest' name="citiesInterestEduPref" multiple="multiple" show-search="1" id="citiesInterestEduPref" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>
                                     </div>
                                        
                                </div>
                                
                             </div>