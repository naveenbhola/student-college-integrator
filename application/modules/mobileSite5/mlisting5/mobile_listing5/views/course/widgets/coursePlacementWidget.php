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
if(!empty($placementData['min'])){
?>
<script>var minSalary = <?php echo $placementData['min'];?></script>
<?php
}
?>
<?php
if(!empty($placementData['median'])){
?>
<script>var medianSalary = <?php echo $placementData['median'];?></script>
<?php
}
?>
<?php
if(!empty($placementData['avg'])){
?>
<script>var avgSalary = <?php echo $placementData['avg'];?></script>
<?php
}
?>
<?php
if(!empty($placementData['max'])){
?>
<script>var maxSalary = <?php echo $placementData['max'];?></script>
<?php
}
?>
<div class="crs-widget listingTuple" id="placements">
         <h2 class="head-L2">Placements <span>(as provided by college)</span></h2>
        <div class="lcard placemnt">
            <p class="para-4"><?php echo htmlentities($placementsHeading);?></p>
            <?php if($placementData['percentage_batch_placed']){?>
            <p class="got-slc"><strong><?=$placementData['percentage_batch_placed'] ?>% </strong>of Total Batch placed</p>
            <?php } ?>
            <?php if($isDomesticPlacementAvailable){?>
            <div class="graph-div" id="placementGraphDiv">
                
                    <h3 class="admisn">Domestic Placements  <span>  (Annual) (In <b><?php echo $placementData['unit_name'];?></b>)</span></h3>
                    <canvas id="placementsGraph"></canvas>
                
            </div>
            <?php }?>
            <?php if($isInternationalPlacementAvailable){?>
            <div class="graph-div">
             <h3 class="admisn">International Placements <span>(Annual)</span></h3>
             <?php if(!empty($placementData['total_international_offers'])){?>
                <div class="t-off">
                    <p>Total offers</p>
                    <span><?=$placementData['total_international_offers'];?></span>
                </div>
             <?php } ?>
             <?php if(!empty($placementData['max_international_salary'])){ ?>
                <div class="t-off">
                    <p>Maximum Salary</p>
                    <span><?=$placementData['max_international_salary_unit_name']." ".getRupeesDisplableAmount($placementData['max_international_salary']) ?></span>
                </div>
             <?php } ?>
            </div>
            <?php } ?>
            <?php if($placements && $placements->getReportUrl() && $internships && $internships->getReportUrl()) { ?>
            <a href="javascript:void(0);" class="placementInternshipReport_btn btn-mob" ga-attr="PLACEMENT_CTA_COURSEDETAIL_MOBILE">Download Placement Report</a>
            <div style="display:none;">
                <input id="placementInternshipReportSelect_input"/>
            </div>
            <div class="select-class">
                <select id="placementInternshipReportSelect" style="display:none;">
                    <option value="959">Internship Report</option>
                    <option value="958">Placement Report</option>
                </select>
            </div>
            <?php } elseif($internships && $internships->getReportUrl()) { ?>
                <a href="javascript:void(0);" class="internshipReport btn-mob" ga-attr="INTERNSHIP_CTA_COURSEDETAIL_MOBILE">Download Internship Report</a>
            <?php } elseif($placements && $placements->getReportUrl()) { ?>
                <a href="javascript:void(0);" class="placementReport btn-mob" ga-attr="PLACEMENT_CTA_COURSEDETAIL_MOBILE">Download Placement Report</a>
            <?php } ?>
            <?php if($placementsCompanies){?>
            <h3 class="admisn">Companies / Recruiters who visited the campus</h3>
            
            <div class="cmpny-list">
                <?php 
                    $companiesCounter = 0;
                    $companiesCount = count($placementsCompanies);
                    foreach($placementsCompanies as $key=>$companyData){
                        if($companiesCounter >= 10){
                            if($companiesCounter == ($companiesCount-1)){
                                echo '<span class="hid">'.$companyData['company_name'].'</span>';    
                            }else{
                                echo '<span class="hid">'.$companyData['company_name'].'</span><b class="hid"> | </b>';
                            }                                                                               
                            $companiesCounter++;
                        }else{
                            if($companiesCounter == ($companiesCount-1)){
                                echo '<span>'.$companyData['company_name'].'</span>';    
                            }else{
                                echo '<span>'.$companyData['company_name'].'</span><b> | </b>';
                            }                            
                            $companiesCounter++;
                        }
                    }
                    if($companiesCount > 10 )
                        echo '<a class="link placementCompVM" href="javascript:void(0)" ga-attr="PLACEMENT_COMP_VIEWMORE_COURSEDETAIL_MOBILE">View More</a>';
                ?>
                <!--<span>Accenture</span><b>|</b><span>Amazon</span><b>|</b><span>Airtel</span><b>|</b><span>Infosys</span><b>|</b><span>Infoedge</span><b>|</b><span>IBM</span><b>|</b><span>Dell</span><b>|</b><span>Google</span><b>|</b><span>Microsoft</span><b>|</b><a class="link">View More</a>-->
            </div>
            <?php }?>
        </div>
    </div>
