<section class="predict-tabs">
  <?php if($count_eligible>0 && $count_ineligible>0):?>
  	<ul class="prdct-tabs">
  		<?php if($count_eligible>0) :?>
   			<li><a default="#tab_0" href="javascript:void(0);">Eligible</a><i class="v-line"></i></li>
   		<?php endif;?> 
   		<?php if($count_ineligible>0) :?>
   			<li><a default="#tab_1" href="javascript:void(0);">Ineligible</a></li>
   		<?php endif;?> 
    </ul>
  <?php endif;?>

  <div class="prf-tab-cont">
    <?php if($count_eligible>0) :?>
      <div class="prf-tabpane" id="tab_0">
        <div class="accordion">
          <div class="section">
            <div class="h-1">
              <a class="accr-a active1"><?php echo str_replace("_"," ",$eligibilityData['IIMName']);?><br/>
                <span class="inst-eligblty">Eligibility: <em>Yes</em></span><span class="inst-eligblty"><?php if(empty($userData['cat_percentile'])){ ?>Cut-off: <em><?php echo round($eligibilityData['IIMData']['Total_Percentile'], 2); ?></em>  <?php }else{ ?>Chances of Call: <em><?php echo $eligibilityData['IIMData']['chances'];?></em><?php } ?></span>
              </a>
              <a class="trans_btn accr_btn">Add to Compare</a>
            </div> 

  
            <div id="elegible" class="sec-cont open initial-show">
              <div class="input-col">
                <ul class="ul-input">
                  <?php if($userData['cat_percentile']>0):?><li><a href="#"><i class="blue-rund"></i><i class="z-l"></i>Your input</a></li><?php endif;?>
                  <li><a href="#"><i class="orng-rund"></i>Cut-off</a></li>
                  <p class="clr"></p> 
                </ul>
              </div>

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
              <a class="accr-a active1"><?php echo str_replace("_"," ",$nonEligibilityData['IIMName']); ?><br/>
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
</section>

<script>

$j(".prf-tabpane").hide(); //Hide all content
$j("ul.prdct-tabs li:first").addClass("current").show();
$j(".prf-tabpane:first").show();
$j("ul.prdct-tabs li").click(function() {
  $j("ul.prdct-tabs li").removeClass("current");
  $j(this).addClass("current");
  $j(".prf-tabpane").hide();
  var activeTab = $j(this).find("a").attr("default");
  $j(activeTab).fadeIn();
});
</script>
