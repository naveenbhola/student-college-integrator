
<?php
if(!empty($placements)){
$placementData                     = $placements->getSalary();    
}
$isInternationalPlacementAvailable = false;
$isDomesticPlacementAvailable      = false;
if($placementData['total_international_offers'] || $placementData['max_international_salary']){
    
    $isInternationalPlacementAvailable = true;
}
if(!empty($placementData['min']) || !empty($placementData['median']) || !empty($placementData['avg']) || !empty($placementData['max']) ){
    $isDomesticPlacementAvailable  = true;
}

?>
<section>            
<div class="data-card m-5btm" id="placements">
         <h2 class="color-3 f16 heading-gap font-w6">Placements <span class="f13">(as provided by college)</span></h2>
        <div class="card-cmn color-w">
            <?php if(!empty($placementData['percentage_batch_placed']) || !empty($isDomesticPlacementAvailable)){ ?>
            <div class="m-btm">
                <?php if($placementData['percentage_batch_placed']){?>
                <p class="padlr0"><strong class="f22 color-6 font-w6"><?=$placementData['percentage_batch_placed'] ?>% </strong><span> of Total Batch placed</span></p>
                <?php } ?>
                </div>
                <?php } ?>
                <?php if($isDomesticPlacementAvailable){ ?>
                <div class="m-btm graph-div m20 pos-rl">
                    <h3 class="f14 color-6 pos-abs">Domestic Placements  <span class="font-w4 color-6 f13">  (Annual) (In <b><?php echo $placementData['unit_name'];?></b>)</span></h3>
                    <amp-iframe width="600" height="300"  class="frame-graph" layout="responsive" sandbox="allow-scripts allow-popups" scrolling="no" frameborder="0" src = '<?php echo SHIKSHA_HOME; ?>/mobile_listing5/CourseMobile/getNaukriPlacementChartsAMP/<?php echo json_encode($placementData); ?>'>

                    </amp-iframe>   
                </div>
                 
                <?php }?>
                <?php if($isInternationalPlacementAvailable){?>
                <div class="m-btm graph-div m20 pos-rl">
                    <h3 class="f14 color-6 pos-abs">International Placements <span class="font-w4 color-3 f13">(Annual)</span></h3>
                 <?php if(!empty($placementData['total_international_offers'])){?>
                        <div class="table m-top">
                            <div class="tab-cell pad10">
                                <p class="f14 color-6">Total offers</p>
                                <p class="f16 color-6 font-w6"><?=$placementData['total_international_offers'];?></p>
                            </div>
                        </div>
                 <?php } ?>
                 <?php if(!empty($placementData['max_international_salary'])){ ?>
                            <div class="tab-cell pad10">
                             <p class="f14 color-6">Maximum Salary</p>
                             <p class="f16 color-6 font-w6"><?=$placementData['max_international_salary_unit_name']." ".getRupeesDisplableAmount($placementData['max_international_salary']) ?></p>
                            </div>
                 <?php } ?>
                	</div>
                <?php } ?>
                <?php if($placements && $placements->getReportUrl() && $internships && $internships->getReportUrl()) { ?>
	                <a class="btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" on="tap:placementReportBrochu" role="button" tabindex="0" data-vars-event-name="PLACEMENT_REPORT">Download Placement Report</a>                        
               <!---
                <div>
                    <input id="placementInternshipReportSelect_input"/>
                </div>
                -->
                <!---
                <div class="select-class">
                    <select id="placementInternshipReportSelect">
                        <option value="959">Internship Report</option>
                        <option value="958">Placement Report</option>
                    </select>
                </div>
                -->
                <?php } elseif($internships && $internships->getReportUrl()) { ?>
                        <section class="" amp-access="NOT validuser" amp-access-hide>
                            <a class="internshipReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=intern&fromwhere=coursepage" data-vars-event-name="INTERN_REPORT">Download Internship Report</a>
                        </section>
                        
                        <section class="" amp-access="validuser" amp-access-hide tabindex="0">
                              <a class="internshipReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href="<?=$courseObj->getURL();?>?actionType=intern" data-vars-event-name="INTERN_REPORT">Download Internship Report
                              </a>
                        </section>
                <?php } elseif($placements && $placements->getReportUrl()) { ?>
                        <section class="" amp-access="NOT validuser" amp-access-hide>
                            <a class="placementReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=placement&fromwhere=coursepage" data-vars-event-name="PLACEMENT_REPORT">Placement Report</a>
                        </section>              
                        
                        <section class=" " amp-access="validuser" amp-access-hide tabindex="0">
                            <a class="placementReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href="<?=$courseObj->getURL();?>?actionType=placement" data-vars-event-name="PLACEMENT_REPORT">
                                  Download Placement Report
                            </a>
                        </section>
                <?php } ?>
                <?php if($placementsCompanies){?>
                <h3 class="f14 color-6 font-w6 m-top">Companies / Recruiters who visited the campus</h3>
                <input type="checkbox" class="read-more-state hide" id="placement" />
                <div class="m-5top read-more-wrap">
                    <?php 
                        $companiesCounter = 0;
                        $companiesCount = count($placementsCompanies);

                        foreach($placementsCompanies as $key=>$companyData){
                            $hideClass = '';
                            if($companiesCounter < 10){
                                if($companiesCounter == ($companiesCount-1)){
                                    echo '<span class="hid l-18 f12 color-3">'.$companyData['company_name'].'</span>';    
                                }else{
                                    echo '<span class="hid l-18 f12 color-3">'.$companyData['company_name'].'</span><b class="font-w4 color-c pad3 f12"> | </b>';
                                }                                                                               
                                $companiesCounter++;
                            }else{
                                $hideClass = 'read-more-target';
                                if($companiesCounter == ($companiesCount-1)){
                                    echo '<span class="l-18 f12 color-3 listinline '.$hideClass.'">'.$companyData['company_name'].'</span>';    
                                }else{
                                    echo '<span class="l-18 f12 color-3 listinline '.$hideClass.'">'.$companyData['company_name'].'</span><b class= "font-w4 color-c pad3 f12 listinline '.$hideClass.'"> | </b>';
                                }                            
                                $companiesCounter++;
                            }
                        }
                        if($companiesCount > 10 )
                            echo '<label for="placement" class="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr">View More</label>';
                    ?>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    </section>
 <?php if($placements && $placements->getReportUrl() && $internships && $internships->getReportUrl()) { ?>
    <amp-lightbox id="placementReportBrochu" layout="nodisplay">
        <div class="lightbox">
            <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:placementReportBrochu.close" role="button" tabindex="0">×</a>
            <div class="m-layer">
                         <div class="min-div color-w new-lt">
                            <ul class="color-6">
                                <section class="" amp-access="NOT validuser" amp-access-hide>
                                    <li>
                                      <a class="ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=intern&fromwhere=coursepage" data-vars-event-name="INTERN_REPORT">Internship Report</a>
                                      </li>
                                </section>
                            
                            <section class="" amp-access="validuser" amp-access-hide tabindex="0">
                                <li>
                                  <a class="ga-analytic" href="<?=$courseObj->getURL();?>?actionType=intern" data-vars-event-name="INTERN_REPORT">Internship Report
                                  </a>
                                
                                </li>
                            </section>
                            <section class="" amp-access="NOT validuser" amp-access-hide>
                                <li>
                                      <a class="ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=placement&fromwhere=coursepage" data-vars-event-name="PLACEMENT_REPORT">Placement Report</a>
                                </li>
                            </section>              
                            
                            <section class=" " amp-access="validuser" amp-access-hide tabindex="0">
                                <li>
                                  <a class="ga-analytic" href="<?=$courseObj->getURL();?>?actionType=placement" data-vars-event-name="PLACEMENT_REPORT">
                                      Placement Report
                                  </a>
                                </li>
                            </section>
                             </ul>
                        </div>
            </div>
        </div>
    </amp-lightbox>
<?php } ?>