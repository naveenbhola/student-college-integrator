<?php 
if(!empty($placements)){
$placementData                     = $placements->getSalary();    
}
$isInternationalPlacementAvailable = false;
$isDomesticPlacementAvailable = false;

if($placementData['min'] || $placementData['median'] || $placementData['avg'] || $placementData['max']){
    $isDomesticPlacementAvailable = true;
}
if($placementData['total_international_offers'] || $placementData['max_international_salary']){
    $isInternationalPlacementAvailable = true;
}
$removePadding = '';
$unitText = '';
if(!$isInternationalPlacementAvailable && $placementData['unit_name']){
    $unitText = "in ".$placementData['unit_name']."; ";
}
$placementDonutPadding = '';
?>
<div class="new-row">
    <div class="group-card no__pad gap listingTuple" id="placements">
        <h2 class="head-1 gap">Placements <span class="head-spn">(<?=$unitText?>as provided by college)</span></h2>
        <?php if($placementsHeading) {?>
            <p class="plcmnt-txt"><?=$placementsHeading;?></p>
        <?php }else{
            $placementDonutPadding = 'placementsDonutPadding';
            } ?>
        <div class="pad__16">
            <div class="salry-div">
                <?php if($placementData['percentage_batch_placed']){?>
                <div class="bar-div">
                    <input type="hidden" value="<?=$placementData['percentage_batch_placed']?>" name="percentagePlaced">
                    <canvas height="210" width="210"></canvas>
                    <p class="prg-barMsg" class="<?=$placementDonutPadding?>"><span><?=$placementData['percentage_batch_placed']?>%</span><br> of Total Batch<br> Placed</p>
                </div>
                <?php }else{
                    $removePadding = 'padding-left:0px;width:100%;';
                    } ?>
                <div class="salary-col" style="<?=$removePadding?>">
                    <ul>
                        <?php if($isDomesticPlacementAvailable) {?>
                        <li>
                            <?php 
                            if($isInternationalPlacementAvailable){?> 
                                <strong class="int-plct">Domestic Placements (<?="in ".$placementData['unit_name']?>)</strong>     
                            <?php }else{?>
                                <strong class="int-plct"></strong>     
                            <?php }  


                            $domesticPlacementSection = array('min','median','avg','max');
                            $domesticPlacemntSectionCounter = 0;

                            foreach ($domesticPlacementSection as $key => $value) {
                                // if($domesticPlacemntSectionCounter == 2 && $placementData[$value]){
                                //     echo "</li>";
                                //     echo "<li>";
                                //     echo "<strong class='int-plct'></strong>";                                
                                // }
                                if($placementData[$value]){?>                             
                                <div class="sal-box">
                                    <label><?=getRupeesDisplableAmount($placementData[$value])?></label>
                                    <span><?=placementSectionHeading($value)?> Salary <span class="sal-tag">(Annual)</span></span>
                                </div>
                                <?php 
                                $domesticPlacemntSectionCounter++;
                                }
                                
                            }
                            if($domesticPlacemntSectionCounter <= 2){
                                // echo "<li>";
                                // echo "<strong class='int-plct'></strong>";
                                // echo "</li>";
                            }


                            ?>                        

                        </li>
                        <?php } ?>

                        <?php if($isInternationalPlacementAvailable) {?>
                        <li>
                            <strong class="int-plct">International Placements</strong>
                            <?php if($placementData['total_international_offers']){?>
                            <div class="sal-box">
                                <label><?=$placementData['total_international_offers'];?></label>
                                <span>Total Offers</span>
                            </div>
                            <?php } ?>
                            <?php if($placementData['max_international_salary']){?>
                            <div class="sal-box">
                                <label><?=$placementData['max_international_salary_unit_name']." ".getRupeesDisplableAmount($placementData['max_international_salary'])?></label>
                                <span>Maximum Salary <span class="sal-tag">(Annual)</span></span>
                            </div>
                            <?php } ?>
                        </li>                                
                        <?php } ?>
                    </ul>
                    <?php if(!$isInternationalPlacementAvailable) { ?>
                        <?php if(!empty($placementsCompanies)){?>
                        <h3 class="int-plct">Companies / Recruiters who visited the campus</h3>
                        <div class="compny-sec">
                            <p>
                                <?php foreach ($placementsCompanies as $key => $data) {?>
                                    <span class="comp-nm"><?=$data['company_name']?></span>    
                                <?php } ?>                             
                            </p>
                            <a href="javascript:void(0)" id="comp-vw" class="hid" ga-track="PLACEMENT_COMP_VIEWMORE_COURSEDETAIL_DESKTOP">View More</a>
                        </div>
                        <?php } ?>
                    <?php } ?>
                    <?php      
                    if($internships && $internships->getReportUrl() || $placements && $placements->getReportUrl()){?>
                     <div class="report-btn">
                        <?php if($internships && $internships->getReportUrl()) {?>

                        <a href="javascript:void(0);" class="btn-secondary btn-medium internshipReport" ga-track="INTERNSHIP_CTA_COURSEDETAIL_DESKTOP">Download Internship Report</a>

                        <?php } ?>
                        <?php if($placements && $placements->getReportUrl()) {?>
                        <a href="javascript:void(0);" class="button button--orange btn-medium placementReport" ga-track="PLACEMENT_CTA_COURSEDETAIL_DESKTOP">Download Placement Report</a>
                        <?php } ?>                    
                    </div>
                    <?php } ?>
                </div>
               
            </div>
            <?php if(!empty($placementsCompanies) && $isInternationalPlacementAvailable){?>
            <h3 class="int-plct">Companies / Recruiters who visited the campus</h3>
            <div class="compny-sec">
                <p>
                    <?php foreach ($placementsCompanies as $key => $data) {?>
                        <span class="comp-nm"><?=$data['company_name']?></span>    
                    <?php } ?>                             
                </p>
                <a href="javascript:void(0)" id="comp-vw" class="hid" ga-track="PLACEMENT_COMP_VIEWMORE_COURSEDETAIL_DESKTOP">View More</a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>