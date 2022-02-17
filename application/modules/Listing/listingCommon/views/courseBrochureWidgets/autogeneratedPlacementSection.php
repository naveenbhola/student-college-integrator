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
    ?>
    <div class="cmn-card mb2">
        <h2 class="f20 clor3 mb2 f-weight1">Placements<span class="f13 clor9"> (<?=$unitText?>as provided by college)</span></h2>
        <?php if($placementsHeading) {?>
                  <p class="f16 clor9 mb2"><?=$placementsHeading?></p>
        <?php } ?>
        <div class="salry-div">
        <?php if($placementData['percentage_batch_placed']){?>
              <div class="seats mb2">
                  <div>
                    <p class="max-seats"><?=$placementData['percentage_batch_placed']?>%<span> of Total Batch Placed</span></p>
                  </div>
              </div>
          <?php } ?>
       <div class="salary-col">
           <ul>
            <?php if($isDomesticPlacementAvailable) {?>
                    <li>
                        <?php 
                        if($isInternationalPlacementAvailable){?> 
                            <strong class="int-plct f14 f-semi">Domestic Placements(<?="in ".$placementData['unit_name']?>)</strong>     
                        <?php }else{?>
                            <strong class="int-plct f14 f-semi"></strong>     
                        <?php }  


                        $domesticPlacementSection = array('min','median','avg','max');
                        $domesticPlacemntSectionCounter = 0;

                        foreach ($domesticPlacementSection as $key => $value) {
                            if($domesticPlacemntSectionCounter == 2 && $placementData[$value]){
                                echo "</li>";
                                echo "<li>";
                                echo "<strong class='int-plct f14 f-semi'></strong>";                                
                            }
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
                            echo "<li>";
                            echo "<strong class='int-plct f14 f-semi'></strong>";
                            echo "</li>";
                        }
                        ?>                        
                    </li>
                    <?php } ?>
                    <?php if($isInternationalPlacementAvailable) {?>
                    <li>
                        <strong class="int-plct f14 f-semi">International Placements</strong>
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
       </div>
   </div>
    <?php if(!empty($placementsCompanies)) { ?>
             <h3 class="int-plct mb2">Companies / Recruiters who visited the campus <i class="info-icn"></i></h3>
             <div>
                <ul class="cmpny-ul">
                  <?php foreach ($placementsCompanies as $key => $data) {?>
                     <li><?=$data['company_name']?></li>
                  <?php } ?>
                </ul>
             </div>
    <?php } ?>
</div>