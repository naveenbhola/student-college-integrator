<?php
$layerStructure = '';
$layerRoundsStructure = '';
$firstCall = false;
$selectedCategoryEligi = array_key_exists($selectedCategory, $eligibility['categoryData']) ? $selectedCategory : 'general';
if(array_key_exists('noneAvailable' ,$eligibility['categoryData'])) {
    $selectedCategoryEligi = 'noneAvailable';
}
?>
    <?php 
    // _p($eligibility); die;
    foreach($eligibility['categoryData'] as $catKey => $catValue) { 
      $layerRoundsStructure = '';
      $checked = '';
      if($selectedCategoryEligi == $catKey)
      {
        $checked = 'checked=true';
      }
      ?>
                  <input type="radio" name="eligible" value="eligi_<?=$catKey;?>" id="eligi_<?=$catKey;?>" class="hide st" <?=$checked;?>>
                  <div class="table tob1">
                  <?php 
                  if(!empty($categoriesNameMapping[$catKey])) { ?>
                     <p class="color-3f14 f12 font-w6 n-border-color"><span class="i-block color-6 font-w4">Showing info for</span> "<?php echo ucfirst($categoriesNameMapping[$catKey]); ?> Category"</p>
                  <?php }
                  ?>
                    <table class="table tob1" id="#tops">
                        <tbody class="default-body">
                         <tr>
                            <th><h3 class="f14 color-6 font-w6">Qualification</h3></th>
                            <?php if($catValue['showEligibilityVal']  == true) {?>
                              <th><h3 class="f14 color-6 font-w6">Eligibility</h3></th>
                              <?php } if($catValue['showCutOff'] == true) { ?>
                                <th><h3 class="f14 color-6 font-w6">Cut-Offs <?php if($catValue['cutOffYear']) {?><span class="block f12 color-6 font-w4">(<?=$catValue['cutOffYear'];?>)</span><?php } ?></h3></th>
                                <?php } ?>
                          </tr>
                      <?php foreach ($catValue['table'] as $key => $val) { 
                           if(!empty($val['additionalInfo']) && $val['additionalInfo'] != '--' && $val['additionalInfo'] != 'N/A' && !$firstCall) {
                              $layerStructure .='<div class="m-5btm"><strong class="block  color-3 f14 font-w6">'.$val['qualification'].'</strong><p class="color-3 l-18 f12 word-break">'.nl2br(makeURLAsHyperlink(htmlentities($val['additionalInfo']),true)).'</p></div>';
                          }
                        ?>
                          <tr>
                              <td>
                              <?php if(!empty($val['url'])){echo "<a class='block f14 color-b font-w6' href=\"{$val['url']}\"><strong>{$val['qualification']}</strong></a>";}
                                else {?>
                                  <strong class="block color-3 f14 font-w6">  <?php echo $val['qualification']; ?></strong>
                                  <?php
                                  } if(!empty($val['additionalInfo']) && $val['additionalInfo'] != '--' && $val['additionalInfo'] != 'N/A') {?>
                                    <a class="block f12 color-b ga-analytic" data-vars-event-name="ELIGIBILITY_ADDITIONAL_DETAILS" on="tap:additional-dtls" role="button" tabindex="0">Additional details</a>
                                  <?php } ?>
                              </td>
                              <?php if($catValue['showEligibilityVal'] == true) {?>
                                    <td class="color-3 f14 font-w4"><?php echo $val['eligibility']; ?></td>
                              <?php } ?>
                              <?php if($catValue['showCutOff'] == true) {?>
                                  <td class="color-3 f13 font-w4">
                                      <?php
                                        if(is_array($val['cutoff'])){
                                            $cutoffstr = array();
                                            $tooltip = '';
                                            foreach ($val['cutoff'] as $cut) {
                                                if($val['quotaCount'][$cut['quota']] > 1){
                                                    ob_start();
                                                    $quotaName = ucwords(implode(explode('_',$cut['quota']),' '));                            
                                                    krsort($catValue['examCutoffData'][$val['qualification']]);                                                 
                                                    $tooltipText = '';
                                                    foreach ($catValue['examCutoffData'][$val['qualification']] as $round => $roundData) {
                                                        foreach ($roundData as $quota => $value) {
                                                            if(stripos($quota,'Related_states') !== false){
                                                                $temp = explode(':',$quota);
                                                                $quota = $temp[0];
                                                            }
                                                            if($quota == $cut['quota']){
                                                                $tooltipText .= $quotaName." (Round ".($round+1).") : ".$value."<br/>";
                                                            }
                                                        }                                                        
                                                    }
                                                    $tooltip.= $tooltipText;
                                                    ?>
                                                    <a class="pos-rl" on="tap:<?=$catKey;?>-eligible-rounds-dtls" role="button" tabindex="0"><i class="cmn-sprite clg-info i-block v-mdl"></i></a>
                                                <?php $temp = ob_get_clean();
                                                }else{
                                                    $temp = '';
                                                }
                                                
                                                $cutoffstr[] = '<div class="cutoff-col-div">'.$cut['cutoffstr'].$temp.'</div>';
                                            }
                                            if(!empty($tooltip)){
                                                $layerRoundsStructure.='<div class="m-5btm"><strong class="block m-3btm color-3 f14 font-w6">'.$val['qualification'].'</strong><p class="color-3 l-18 f12 word-break">'.$tooltip.'</p></div>';
                                              }
                                            // $layerRoundsStructure.="</p></li>";
                                            echo implode($cutoffstr,'');
                                        }else{
                                            echo $val['cutoff'];
                                        }
                                        ?>
                                  </td>
                              <?php } ?>
                          </tr>
                          <?php }
                          $firstCall = true;
                           ?>

                                          <amp-lightbox id="<?=$catKey;?>-eligible-rounds-dtls" layout="nodisplay" scrollable>
                                                   <div class="lightbox"  on="tap:<?=$catKey;?>-eligible-rounds-dtls.close" role="button" tabindex="0">
                                                      <a class="cls-lightbox  color-f font-w6 t-cntr">&times;</a>
                                                      <div class="m-layer">
                                                        <div class="min-div color-w catg-lt pad10">
                                                              <?=$layerRoundsStructure;?>
                                                        </div>
                                                      </div>
                                                   </div>
                                              </amp-lightbox>  
                      </tbody>
                  </table>
                </div>
                <?php } ?>

<!--Additional Details popup layer  -->
  <amp-lightbox id="additional-dtls" layout="nodisplay" scrollable>
       <div class="lightbox">
          <a class="cls-lightbox  color-f font-w6 t-cntr"   on="tap:additional-dtls.close" role="button" tabindex="0">&times;</a>
          <div class="m-layer">
            <div class="min-div color-w catg-lt pad10">
                  <?=$layerStructure;?>
            </div>
          </div>
       </div>
  </amp-lightbox>   