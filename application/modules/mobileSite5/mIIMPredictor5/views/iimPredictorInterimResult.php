

<!-- <p class="iim-helper">Want to find out</p>
      <ul class="predict-ul initial_hide" id="someEligible" >
         <li><a href=#>Which IIMs you are <span class="iim-font-weight-helper">eligible/ineligible</span> for?</a></li>
         <li><a href=#>CAT <span class="iim-font-weight-helper">percentile</span> at which you can expect a call from IIMs?</a></li>
      </ul>
      <p class="elg-msg initial_hide" id="allEligible" >CAT <span class="iim-font-weight-helper">percentile</span> at which you can expect a call from IIMs?</p>
      <p class="elg-msg initial_hide" id="notEligible">Reason of ineligibility for these IIMs?</p> -->

    <section class="predict-tabs">
    	<?php if($count_eligible>0 && $count_ineligible>0):?>
      		<ul class="prdct-tabs">
       			<?php if($count_eligible>0) :?>
           			<li><a href="#tab_0">Eligible</a><i class="v-line"></i></li>
           		<?php endif;?> 
           		<?php if($count_ineligible>0) :?>
           			<li><a href="#tab_1">Ineligible</a></li>
           		<?php endif;?> 
      		</ul>
      	<?php endif;?>

        <div class="prf-tab-cont">
        <?php //if($count_eligible>0 && $count_ineligible>0 ) { ?>
          <?php if($count_eligible>0) :?>
            <div class="prf-tabpane" id="tab_0">
              <div class="accordion">
                <div class="section">
                  <div class="h-1">
                    <a href="" class="accr-a active1"><?php echo str_replace("_"," ",$eligibilityData['IIMName']);?><br/>
                      <span class="inst-eligblty">Eligibility: <em>Yes</em></span><b class="line">|</b><span class="inst-eligblty"><?php if(empty($userData['cat_percentile'])){ ?>Chance of call: <em><?php echo round($eligibilityData['IIMData']['Total_Percentile'], 2); ?></em>  <?php }else{ ?>Chances of Call: <em><?php echo $eligibilityData['IIMData']['chances'];?></em><?php } ?></span>
                    </a>
                    <a class="trans_btn accr_btn">Compare</a>
                  </div> 

        
                  <div id="elegible" class="sec-cont open initial-show">
                    <ul class="ul-input">
                      <?php if($userData['cat_percentile']>0):?><li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li><?php endif;?>
                      <li><a href="#"><i class="orng-rund"></i>Minimum %ile to aim</a></li>
                      <p class="clr"></p> 
                    </ul>

                    <section class="ac-sec">
                      <?php if($count_eligible>0):?>
                        <ul class="ac-prgrs">
                          <?php foreach($eligibilityData['IIMData'] as $elegible_key => $elegible_value):
                            if($elegible_key == 'chances' || $elegible_key == 'score_calculated') {
                              continue;
                            } ?>
                          
                            <li>
                              <div class="aa1">
                                <label><?php echo str_replace("_Percentile","",$elegible_key);?></label>
                              </div>
                              <div class="aa2">
                                <div class="progress-sm">
                                  <?php 
                                    if($userData[$elegible_key]) {
                                      $user_data_value = $userData[$elegible_key];
                                    } else {
                                      $user_data_value = $userData['cat_percentile'];  
                                    }
                                  ?>
                              
                                  <?php if($userData['cat_percentile']>0):?>
                                    <div class="lmt">
                                      <div class="progress-bar progress-bar-primary" style="<?php if($user_data_value >= 4) echo 'width:'.($user_data_value-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                      <span class="percntile1 <?php if(!empty($user_data_value) && $user_data_value < $elegible_value){ echo 'warningMsg'; } ?>"><?php echo round($user_data_value,2); ?><em class="em-lft">%ile</em></span>
                                    </div>
                                  <?php endif;?>
                                </div>
                                <div class="progress-sm">
                                  <div class="lmt">
                                   <div class="progress-bar progress-bar-ornage" style="<?php if($elegible_value >= 4) echo 'width:'.($elegible_value-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                   <span class="percntile"><?php echo round($elegible_value, 2); ?><em class="em-lft">%ile</em></span>
                                  </div>
                                </div>
                              </div>                                                                                    
                              
                              <p class="clr"></p>  
                            </li> 
                          <?php endforeach;?>
                        </ul>
                      <?php endif;?>
                      
                      <p class="clr"></p>
                    </section>
                               
                    <section class="accrdn-f">                
                      <a href=""><?php echo str_replace("_"," ",$eligibilityData['IIMName']);?> shortlisting criteria</a>                                               
                    </section>
                                
                  </div>
                </div> 
              </div>
            </div>
          <?php endif;?>


          <?php if($count_ineligible>0):?>
          
            <div class="prf-tabpane" id="tab_1">
              <div class="accordion">
                <div class="section">
                  <div class="h-1">
                    <a href="" class="accr-a active1"><?php echo str_replace("_"," ",$nonEligibilityData['IIMName']); ?><br/>
                      <span class="inst-eligblty">Eligibility: <em>No</em></span>
                    </a>
                  </div> 
                
                  <div id="0" class="sec-cont open initial-show">
                    <section class="ac-sec">
                      <div class="input-col">
                        <ul class="ul-input">
                             <li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li>
                             <li><a href="#"><i class="orng-rund"></i>Minimum score required</a></li>
                             <p class="clr"></p> 
                        </ul>
                      </div>
                      
                      <?php if($count_ineligible>0):?>
                        <ul class="ac-prgrs">
                          <?php foreach($nonEligibilityData['IIMData'] as $Key => $Val):?>  
                            <li>
                                        
                              <div class="aa1">
                                <label><?php echo $fieldsmapping[$Key]; ?></label>
                              </div>

                             
                              <div class="aa2">
                                <div class="progress-sm">
                                    <div class="lmt">
                                     <div class="progress-bar progress-bar-primary" style="<?php if($userData[$Key] >= 4) echo 'width:'.($userData[$Key]-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                     <span class="percntile1"><?php echo $userData[$Key];?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
                                    </div>
                                </div>
                                <div class="progress-sm">
                                  <div class="lmt">
                                     <div class="progress-bar progress-bar-ornage" style="<?php if($Val >= 4) echo 'width:'.($Val-4).'% !important'; else echo 'width:0% !important'; ?>"></div>
                                     <span class="percntile" ><?php echo $Val;?><em class="em-lft"><?php echo printMarksType($Key); ?></em></span>
                                  </div>
                                </div>
                              </div>
                              <p class="clr"></p>  
                            </li>
                          <?php endforeach;?>
                        </ul>
                      <?php endif;?> 
                      <p class="clr"></p>
                    </section>
                       
                    <section class="accrdn-f">
                     <a href="" class=""><?php echo str_replace("_"," ",$nonEligibilityData['IIMName']); ?> eligibility criteria</a>
                    </section>
                  </div>
                </div>
              </div>
            </div>
          <?php endif;?>
        </div>
        <?php //} ?>
      </section>

      <script>

      $(".prf-tabpane").hide(); //Hide all content
      $("ul.prdct-tabs li:first").addClass("current").show();
      $(".prf-tabpane:first").show();
      $("ul.prdct-tabs li").click(function() {
        $("ul.prdct-tabs li").removeClass("current");
        $(this).addClass("current");
        $(".prf-tabpane").hide();
        var activeTab = $(this).find("a").attr("href");
        $(activeTab).fadeIn();
      });
      </script>
      