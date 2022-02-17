<!--admission block -->
<?php //_p($importantDatesData);die;?>
         <div class="data-card m-5btm">
             <h2 class="color-3 f16 heading-gap font-w6">
                <?php 
                    if(!empty($admissions) && !empty($importantDatesData['importantDates'])){
                                echo "Admissions";
                    }
                    else if(!empty($admissions)){
                        echo "Admission Process";
                    }
                    else if(!empty($importantDatesData['importantDates'])){
                        echo "Important Dates";
                    }
                ?>
             </h2>
             <div class="card-cmn color-w">
                <?php if(!empty($admissions)){ 
                        if(!empty($importantDatesData['importantDates'])){
                    ?>
                            <h3 class="f14 color-3 font-w6 m-btm">Admission Process</h3>
                        <?php } ?>
                        
                        <ul class="ad-ul">
                            <?php  for($i=1;$i<=min(3,count($admissions));$i++){
                                          if(!empty($admissions[$i])){
                                ?>  
                                        <li>
                                            <div class="ad-stage">
                                                <?php if(count($admissions)>1){?>
                                                <p class="f13 color-3 font-w6 m-3btm"><?php echo nl2br($admissions[$i]['admission_name']);?></p>
                                                <?php } ?>
                                                <input type="checkbox" class="read-more-state hide" id="admissionStage_<?=$i;?>">
                                                <p class="read-more-wrap word-break" id="<?php echo "admissionStage".$i; ?>"><?php echo cutStringWithShowMoreInAMP($admissions[$i]['admission_description'],160,'admissionStage_'.$i,'more');?> 
                                                </p>
                                            </div>
                                        </li>
                                <?php     }
                                        }
                                ?>
                                <?php if(count($admissions)>3){ ?>
                                    <a on="tap:admission-process" class="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" role="button" tabindex="0" id="admissionProcess" data-vars-event-name="Admissions_VIEW_COMPLETE_PROCESS">View complete process</a>
                                <?php } ?>
                             <!-- <li>
                                 <div class="ad-stage">
                                     <p class="f13 color-3 font-w6 m-3btm">Register and Apply</p>
                                     <input type="checkbox" class="v-more-st" id="post-6">
                                     <p class="f13 color-3 l-16 v-wrap">Interested candidates can apply online with the required Information. <span class="v-more pad3">faculty and students have been distributed through Harvard Business Publishing (HBP)</span><label class="f13 color-b v-more-triger" for="post-6">View more</label></p>
                                 </div>
                             </li> -->
                        </ul>
                        <?php } ?>
                        <?php if(count($importantDatesData['importantDates']) > 0){
                            $this->load->view('course/AMP/Widgets/importantDatesWidget');
                         } ?>

                 

             </div>
         </div>
<?php if(count($admissions)>3){ ?>
    <amp-lightbox class="" id="admission-process" layout="nodisplay" scrollable>
           <div class="lightbox">
             <a class="cls-lightbox f25 color-f font-w6" on="tap:admission-process.close" role="button" tabindex="0">&times;</a>
              <div class="color-w pad10 m-layer">
                 <p class="m-btm f14 color-3 font-w6">Admission Process</p>
                 <?php foreach($admissions as $key => $admissionStage){?>
                                  <div class="m-btm padb">
                                      <strong class="block m-5btm color-3 f14 font-w6"><?php echo nl2br($admissionStage['admission_name']);?></strong>
                                      <p class="color-3 l-18 f12"><?php echo nl2br(makeURLAsHyperlink(htmlentities($admissionStage['admission_description']),true));?></p>
                                  </div>
                        <?php } ?>
              </div>
            </div>
    </amp-lightbox>         
<?php } ?>