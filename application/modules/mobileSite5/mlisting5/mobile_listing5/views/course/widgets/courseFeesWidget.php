<?php 
extract($fees);
$showFeesStructureLink = '';
 if(empty($otpAndHostelFees['durationWise'])){
    $showFeesStructureLink = 'display:none;';
}
$removePadding = '';
if(empty($otpAndHostelFees['otp']) || empty($otpAndHostelFees['hostel'])){
  $removePadding = "style='padding-left:0px'";
}
?>

<div class="crs-widget gap listingTuple" id="fees">
    <h2 class="head-L2 more-mg">Fees</h2>
    <?php if(!(count($feesFilterOption) == 1 && $feesFilterOption[0] == 'general') && !empty($feesFilterOption)) { ?> 
      <div class="dropdown-primary">
          <input class="option-slctd" id="feesCategoriesSelect_input" ga-attr = 'FEES_DROPDOWN_COURSEDETAIL_MOBILE' readonly value="<?php if($feesSelectedCategory  != '') { echo ucfirst($categoriesNameMapping[$feesSelectedCategory]), " Category"; } else { ?>General Category<?php } ?>">
      </div>
      <div class="select-Class">
          <select id="feesCategoriesSelect" style="display:none;">
              <?php 
              foreach ($feesFilterOption as $oneFeeType) { ?>
                  <option value="<?php echo $oneFeeType; ?>" ga-attr="<?php echo 'FEES_CATEGORY_'.strtoupper($oneFeeType).'_COURSEDETAIL_MOBILE'; ?>"><?php echo ucfirst($categoriesNameMapping[$oneFeeType]); ?> Category</option>
              <?php
              }
              ?>
          </select>
      </div>
    <?php } ?>
    <div class="lcard fee-data">
        <h3 class="head-2">Total Fees </h3>
        <p class="fee-total"><?=$totalFees;?></p>
         <?php if($totalFeesIncludes != '') { ?>
         <span class="para-4">(Fees Components : <?=$totalFeesIncludes?>)</span>
         <?php } ?>
         <?php if(!$isLocationLevelFeesAvailable) {?>
            <a class="link" href="javascript:void(0)" id="feesStructureLayer" style="<?=$showFeesStructureLink?>" ga-attr="FEES_STRUCTURE_COURSEDETAIL_MOBILE">View fee structure</a>
            <div id="feesStructureHtml" style="display:none;">
                <?php $this->load->view('mobile_listing5/course/widgets/courseFeesStructureLayer'); ?>
            </div>
          <?php } ?>
        <?php if(trim($otpAndHostelFees['otp']) != '' || trim($otpAndHostelFees['hostel']) ) { ?>
        <div class="fee-str"> 
            <?php if(trim($otpAndHostelFees['otp']) != '') { ?>
              <div class="main-fee otp" <?=$removePadding?>>
                  <p class="head-2">One Time Payment</p>
                  <p>
                      <strong><?php echo $otpAndHostelFees['otp']; ?></strong>
                      <a href="javascript:void(0)" class="otpTooltip"><i class="clg-sprite clg-inf"></i></a>
                  </p>
              </div>
            <?php } ?>
            <?php if ($otpAndHostelFees['hostel'] != '') { ?>
              <div class="main-fee hostel" <?=$removePadding?>>
                  <p class="head-2">Hostel Fees (Yearly)</p>
                  <p><strong><?php echo $otpAndHostelFees['hostel']; ?></strong><i class="info-icn"></i></p>
              </div>  
            <?php } ?>
        </div>
        <?php } ?>
        <?php  if(!empty($feesDescription)){?><p class="para"><?php echo cutStringWithShowMore($feesDescription,210,'fees_desc','more','');?></p><?php } ?>                  
        <?php if($feesDisclaimer === true) { ?><p class="disc-txt">Disclaimer : Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary. </p><?php } ?>      
    </div>
</div>
<div class="hid" id="otpTooltiplayer">
    <div class="crs-widget listingTuple">
        <div class="lcard">
            <ul class="schlrshp-list schlrshp-lyr">
                <li><p class="head-L3">One Time Payment</p><p class="para-L3">Note - Applicable if you want to pay the complete fees at one go</p></li>
            </ul>       
        </div>
    </div> 
</div>
<?php if($isLocationLevelFeesAvailable){?>
<script>
    var categoryFeesMapping = eval('(' + '<?=$categoryFeesMapping;?>' + ')');
</script>
<?php } ?>
