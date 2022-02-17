                            <div class="profile-col">
                                <div class="profile-col-heading">
                                    <p class="expertise-h1"  style='color:#ccc'>Expertise</p>
                                    <p class="expertise-h2">These preferences shall be used to personalize the <b>Ask &amp; Answer</b> homefeed</p>
                                     <?php 
                                     

                                        $privacyFields = array('0'=>'expertise'); 
                                        $privacyFields = serialize($privacyFields);
                                    
                                        $publicFlag = false;  
                                        if($privacyDetails['expertise'] == 'public'){
                                            $publicFlag = true;
                                          }
                                      ?>
                                    <?php if(!$publicProfile) {?>
                                    <div class="mrMins">  
                                     <?php $this->load->view('userTogglePrivacy',array('privacyFields' => $privacyFields,'publicFlag'=>$publicFlag)); ?>
                                    </div>
                                    <?php } ?>
                                    <!--p class="active-col"><i class="profile-sprite show-actvive"></i></p-->
                                 <div class="clearFix"></div>
                               </div> 
                               <div class="profile-detail-list">

                                    <?php

                                     if(($publicProfile && isset($expertiseInfo['stream']) && !empty($expertiseInfo['stream'])) || (!$publicProfile)){

                                      ?>
                                     <div class="n-card">  

                                         <div class="study-stream">Field/Stream</div>
                                         <div id="expertiseStreamSelect_tags">
                                         <?php
                                            foreach ($expertiseInfo['stream'] as $key => $value) {
                                            ?>                                              
                                                    <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='stream'>
                                                       <span class="name"><?=$value['tagName'];?></span>
                                                       <?php if(!$publicProfile){ ?><span class="cls">&times;</span><?php } ?>
                                                    </div>
                                                    <?php                                             
                                            }
                                         
                                         ?>
                                         </div>
                                        <?php if(!$publicProfile){
                                            ?>
                                            <div class="" data-enhance="false">                      
                                              <a href="javascript:void(0);" class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Stream' customPlaceholder="Search by field of interest" layerAttr='expertiseStreamSelect'><i class="profile-sprite slct-add"></i>Add</a> 
                                          </div>

                                          <div class="select-Class" data-enhance="false">
                                            <select name="expertiseStreamSelect" entity='stream' multiple="multiple" show-search="1" id="expertiseStreamSelect" style="display:none;" append-selected-value="1">
                                            </select>                     
                                          </div>  
                                            <?php
                                        }
                                        ?>
                                        
                                     </div>
                                     <?php }
                                        if(($publicProfile && isset($expertiseInfo['country']) && !empty($expertiseInfo['country'])) || (!$publicProfile)){
                                      ?>
                                     
                                      <div class="n-card">  
                                            <div class="study-stream">country</div>
                                            <div id="countryExpertiseSelect_tags">
                                              <?php                                             
                                                  foreach ($expertiseInfo['country'] as $key => $value) {
                                                  ?>                                              
                                                          <div class="slct-choice" idForTag="<?=$value['tagId']?>" entity='country'>
                                                             <span class="name"><?=$value['tagName'];?></span>
                                                             <?php if(!$publicProfile){ ?><span class="cls">&times;</span><?php } ?>
                                                          </div>
                                                          <?php                                             
                                                  }                                             
                                               ?>
                                            </div>
                                            <?php if(!$publicProfile){
                                            ?>  
                                            <div class="" data-enhance="false">                      
                                                <a href="javascript:void(0);"  customPlaceholder="Search by country name" layerAttr='countryExpertiseSelect' class="slct-add-col search-clg-field shikshaSelect_input" forAttr='Country'><i class="profile-sprite slct-add"></i>Add</a> 
                                            </div>

                                            <div class="select-Class" data-enhance="false">
                                              <select name="countryExpertiseSelect" entity='country'  multiple="multiple" show-search="1" id="countryExpertiseSelect" style="display:none;" append-selected-value="1">
                                              </select>                     
                                            </div>  
                                            <?php } ?>
                                     </div>
                                     <?php 
                                       }
                                       ?>
                                  
                                        
                                </div>
                                
                             </div>
                           <!----> 