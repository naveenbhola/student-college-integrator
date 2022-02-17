<?php
if($naukriData['total_naukri_employees'] > 30 || $naukriData['salary_total_employee'] >30) {?>
<section>
<div class="data-card m-5btm">
         <h2 class="color-3 f16 heading-gap font-w6">Alumni Employment Stats</h2>
        <div class="card-cmn color-w">
            <p class="f12 color-6 font-w4">Data based on resumes from <a><i class="cmn-sprite n-logo"></i></a> , India's no.1 job site</p>
            <?php if($naukriData['salary_data_count'] >0 && $naukriData['salary_total_employee'] >30){ ?>
            <div class="margin-20 pos-rl border-all">
                <h3 class="pos-abs f14 color-6 color-w font-w6 pad3 avg-sl">Average annual salary details of <?php echo $naukriData['salary_total_employee'] ?> alumni of this course <span class = "f12 color-6 font-w4">(by work experience) (In <b>INR</b>)<span></h3>
              <amp-iframe width="400" height="300" layout="responsive" sandbox="allow-scripts allow-popups" scrolling="no" frameborder="0" src = '<?php echo SHIKSHA_HOME; ?>/mobile_listing5/CourseMobile/getAlumniStatsChartAmp/<?php echo $naukriData['chart']?>'>
              </amp-iframe>

            </div>
            <?php } ?>
            <?php
            if(count($naukriData['naukri_specializations'])>0 && $naukriData['total_naukri_employees'] > 30){ ?>
                     <div class="card-cm color-w margin-20">
                        <div class="comp-bus-bar pos-rl m-top border-all">

                             <div class="dropdown-primary alum-dt" on="tap:spec-list-amp" role="button" tabindex="0">
                            <span class="option-slctd block color-6 f12 font-w6" id="optnSlctd">All Specializations</span>
                             </div>
                            <h3 class="pos-abs f14 color-6 color-w font-w6 pad3 emp-al pos-rl">Employment details of <?php echo $naukriData['total_naukri_employees'] ?>  alumni</h3>
                            <div class="margin-30">
                                <?php $this->load->view('course/AMP/Widgets/ampNaukriFuncAndCompWidget'); ?>
                                <?php $this->load->view('course/AMP/Widgets/ampNaukriFuncAndCompWidgetWRTspec'); ?>

                           </div>
                        </div>
                    </div>
            <?php }?>
            <input type="checkbox" class="read-more-state hide" id="naukriDesclaimer">
            <p class="read-more-wrap" id="naukriDesclaimer"><?php echo cutStringWithShowMoreInAMP("Disclaimer : Shiksha alumni salary and employment data relies entirely on salary information provided by individual users on Naukri. To maintain confidentiality, only aggregated information is made available. Salary and employer data shown here is only indicative and may differ from the actual placement data of college. Shiksha offers no guarantee or warranty as to the correctness or accuracy of the information provided & will not be liable for any financial or other loss directly or indirectly arising or related to use of the same.",95, 'naukriDesclaimer','more');?></p>
        </div>
    </div>
</section>
<?php }?>
<!--view all alumini salary-->
    <amp-lightbox id="spec-list-amp" class="" layout="nodisplay" scrollable>
       <div class="lightbox" on="tap:spec-list-amp.close" role="button" tabindex="0">
         <a class="cls-lightbox  color-f font-w6 t-cntr">&times;</a>
           <div class="m-layer">
             <div class="min-div color-w catg-lt">
               <ul class="color-6">
                    <li><label for="spec_all">All Specializations</label></li>
                        <?php

                        foreach($naukriData['naukri_specializations'] as $splz){
                            $splz = trim($splz);
                            if($splz == "Systems" || $splz == "Other Management" || $splz == "#N/A") { continue; }
                            elseif($splz == "Marketing") {?>
                               <li><label for="spec_<?php echo $specMappingAMP[$splz];?>" class="block" >Sales & Marketing</label></li>
                            <?php }
                                elseif($splz == "HR/Industrial Relations"){?>
                                    <li><label for="spec_<?php echo $specMappingAMP[$splz];?>" class="block" >Human Resources</label></li>
                            <?php }
                                elseif($splz == "Information Technology"){?>
                                   <li><label for="spec_<?php echo $specMappingAMP[$splz];?>" class="block" >IT</label></li>
                            <?php }
                                else{?>
                                    <li><label for="spec_<?php echo $specMappingAMP[$splz];?>" class="block" ><?php echo $splz; ?></label></li>
                            <?php }
                        }
                            if(in_array("Other Management", $naukriData['naukri_specializations'])) { ?>
                                   <li><label for="spec_other" class="block" >Other Management</label></li>

                            <?php } ?>

               </ul>
             </div>
           </div>
       </div>
    </amp-lightbox>
