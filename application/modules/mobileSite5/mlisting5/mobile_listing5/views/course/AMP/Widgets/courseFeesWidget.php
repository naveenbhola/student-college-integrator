<?php
extract($fees);
$showFeesStructureLink = '';
if(empty($otpAndHostelFees['durationWise'])){
    $showFeesStructureLink = 'display:none;';
}
$showFeeDetails=false;
if( !empty($otpAndHostelFees['otp']) ||  !empty($otpAndHostelFees['hostel']) || !empty($feesDescription) || $feesDisclaimer === true || $totalFeesIncludes != '' || !$isLocationLevelFeesAvailable){
  $showFeeDetails = true;
}
$removePadding = '';
if(empty($otpAndHostelFees['otp']) || empty($otpAndHostelFees['hostel'])){
  $removePadding = "style='padding-left:0px'";
}
$selectedCategoryFee = in_array($selectedCategory, $feesFilterOption) ? $selectedCategory : 'general';
?>
    <!--fees block-->
         <section>
             <div class="data-card m-5btm" id="fee">
                 <h2 class="color-3 f16 heading-gap font-w6 pos-rl">
                     Fees
                     <?php if(!(count($feesFilterOption) == 1 && $feesFilterOption[0] == 'general') && !empty($feesFilterOption)) { ?>
					      <div class="dropdown-primary tab-cell" on="tap:fee-cat-list" role="button" tabindex="0">
					          <span class="option-slctd block color-6 f12 font-w6 ga-analytic" id="optnSlctd" data-vars-event-name="FEE_CHOOSE_CATEGORY">Choose Category</span>
					      </div>
					    <?php } ?>
                 </h2>
                 <div class="card-cmn color-w">
                 <?php foreach($feesInfo as $feeKey => $feeValue) {
                   $checked = '';
                    if($selectedCategoryFee == $feeKey)
                    {
                      $checked = 'checked=true';
                    }
                  ?>
                 	<input type="radio" name="feesTypes" value="<?php echo "feeFilt".$feeKey;?>" id="<?php echo "feeFilt".$feeKey;?>" class="hide disp" <?=$checked;?>>
                 	<div class="table tob1">
                 		<p class="color-3 f13 font-w6"><span class="color-6 font-w4">Showing Info for </span><?php echo '"'.ucfirst($categoriesNameMapping[$feeKey]).' Category'.'"';?></p>
	                   <h3 class="color-6 pos-rl f14 font-w4">Total Fees</h3>
	                   <div class="getFeedtl">
                     <p class="color-3 f22 font-w6 m-3btm"><?=$feeValue['totalFees'][$feeKey];?></p>

                      <section class="wd50 i-block" amp-access="NOT bMailed" amp-access-hide>
                            <a class="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" href="<?=SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingId=<?=$courseId;?>&actionType=brochure&fromwhere=coursepage&pos=sticky&fromFeeDetails=true" data-vars-event-name="Get_Fee_Details" title="<?=$instituteName;?>">Get Fee Details</a>
                      </section>
                    </div>
                <section amp-access="bMailed" amp-access-hide>       
                     <?php if($totalFeesIncludes != '') { ?>
				         	<p class="color-6 f12 l-16 font-w4 word-break">(Fees Components : <?=$totalFeesIncludes;?>)</p>
				         <?php } ?>
			         <?php if(!$isLocationLevelFeesAvailable) {?>
			                <?php $this->load->view('mobile_listing5/course/AMP/Widgets/courseFeeStructureLayer',array('category'=>$feeKey,'totalFeesForCat'=>$feeValue['totalFees'][$feeKey],'feeIncludes'=>$totalFeesIncludes)); ?>
			          <?php } ?>
			           <?php if(trim($otpAndHostelFees[$feeKey]['otp']) != '' || trim($otpAndHostelFees[$feeKey]['hostel']) ) { ?>

                     	<div class="margin-20 table fee-tab">
                     		<?php if(trim($otpAndHostelFees[$feeKey]['otp']) != '') { ?>
		                         <div class="tab-cell">
		                             <p class="f14 color-6 font-w4">One Time Payment </p>
		                             <p class="pos-rl fee-c"><strong class="f16 color-3 font-w6"><?php echo $otpAndHostelFees[$feeKey]['otp']; ?></strong><a class="pos-rl" on="tap:one-time-pay" role="button" tabindex="0"><i class="cmn-sprite clg-info i-block v-mdl"></i></a></p>
		                         </div>
		                        <?php } ?>
		                        <?php if ($otpAndHostelFees[$feeKey]['hostel'] != '') { ?>
			                         <div class="tab-cell">
			                             <p class="f14 color-6 font-w4">Hostel Fees (Yearly)</p>
			                             <p class="pos-rl"><strong class="f16 color-3 font-w6"><?php echo $otpAndHostelFees[$feeKey]['hostel'];?></strong></p>
			                         </div>
		                         <?php } ?>
                    	 </div>
                    	 <?php } ?>
                    </section>
                    </div>
                  <?php } ?>
                <section amp-access="bMailed" amp-access-hide>   
                  <?php  if(!empty($feesDescription)){?>
	                     <div class="m-top">
                        <input type="checkbox" class="read-more-state hide" id="fees_desc">
	                       <p class="read-more-wrap word-break"><?php echo cutStringWithShowMoreInAMP($feesDescription,210,'fees_desc','more');?>
	                    </div>
                    <?php } ?>
                    <?php if($feesDisclaimer === true) { ?>
	                     <div class="margin-20 f12 color-9 font-w4">
	                         Disclaimer : Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary.
	                     </div>
                     <?php } ?>
                 </div>
                </section>   
             </div>

             <?php $this->load->view('/mobile_listing5/course/AMP/Widgets/courseScholarshipWidget'); ?>
         </section>
<?php if(!(count($feesFilterOption) == 1 && $feesFilterOption[0] == 'general') && !empty($feesFilterOption)) { ?>

    <amp-lightbox id="fee-cat-list" class="" layout="nodisplay" scrollable>
      <div class="lightbox" on="tap:fee-cat-list.close" role="button" tabindex="0">
          <a class="cls-lightbox color-f font-w6 t-cntr">&times;</a>
          <div class="m-layer">
              <div class="min-div color-w catg-lt">
                  <ul class="color-6">
                    <?php foreach($feesFilterOption as $oneFeeType) {?>

                        <li><label for="<?php echo "feeFilt".$oneFeeType; ?>" class="block"><?php echo ucfirst($categoriesNameMapping[$oneFeeType]); ?> Category</label></li>
                    <?php } ?>
                  </ul>
              </div>
          </div>
      </div>
  </amp-lightbox>
  <?php } ?>


    <amp-lightbox id="one-time-pay" layout="nodisplay">
       <div class="lightbox">
          <a class="cls-lightbox  color-f font-w6 t-cntr" on="tap:one-time-pay.close" role="button" tabindex="0">&times;</a>
          <div class="m-layer">
            <div class="min-div color-w catg-lt pad10">
              <div class="m-btm padb">
                <strong class="block m-btm color-3 f14 font-w6">One Time Payment</strong>
                  <p class="color-3 l-18 f12">Note - Applicable if you want to pay the complete fees at one go.</p>
              </div>
            </div>
          </div>
       </div>
  </amp-lightbox>
