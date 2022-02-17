<?php
$layerStructure = '';
$layerRoundsStructure = '';
?>
<table class="table crs-tble">
    <thead class="thead-default">
        <tr>
            <th>
               <h3 class="para-1">Qualification</h3>
            </th>
            <?php if($eligibility['showEligibilityVal'] == true) {?>
            <th>
                <h3 class="para-1">Minimum Eligibility</h3>
            </th>   
            <?php } ?>
            <?php if($eligibility['showCutOff'] == true) {?>
            <th> 
            <h3 class="para-1">Cut-Offs  <?php if($eligibility['cutOffYear']) {?><span class="yr-spn">(<?=$eligibility['cutOffYear'];?>)</span><?php } ?></h3>
            </th>
            <?php }?>
        </tr>

    </thead>
    <tbody class="tbody-default">
    <?php foreach ($eligibility['table'] as $key => $val) {
        if(!empty($val['additionalInfo']) && $val['additionalInfo'] != '--') {
            $layerStructure .='<li> <p class="head-L3">'.$val['qualification'].'</p><p class="para-L3">'.nl2br(makeURLAsHyperlink(htmlentities($val['additionalInfo']))).'</p></li>';
        }
        ?>
        <tr>
            <td>
                <?php if(!empty($val['url'])){echo "<a class='eligi-exm' href={$val['url']}>";} ?>
                <strong><?php echo $val['qualification']; ?></strong>
                <?php if(!empty($val['url'])){echo "</a>";} ?>

                <?php if(!empty($val['additionalInfo']) && $val['additionalInfo'] != '--') {?>
                <?php 
                if($pageName == 'Admission')
                {
                    $gaAttr = "ga-attr = 'VIEW_ADDITIONAL_DETAILS'";
                }
                ?>
                <a  href="javascript:void(0)" class="link additionalEligibility" <?php echo $gaAttr;?>>Additional Details</a>
                <?php } ?>
            </td>            
            <?php if($eligibility['showEligibilityVal'] == true) {?>
                <td><?php echo $val['eligibility']; ?></td>
            <?php } ?>
            <?php if($eligibility['showCutOff'] == true) {?>
                <td>
                <?php
                if(is_array($val['cutoff'])){
                    $cutoffstr = array();
                    $tooltip = '';
                    foreach ($val['cutoff'] as $cut) {
                        if($val['quotaCount'][$cut['quota']] > 1){
                            ob_start();
                            $quotaName = ucwords(implode(explode('_',$cut['quota']),' '));                            
                            krsort($eligibility['examCutoffData'][$val['qualification']]);                                                 
                            $tooltipText = '';
                            foreach ($eligibility['examCutoffData'][$val['qualification']] as $round => $roundData) {
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
                            <a href="javascript:void(0)" class="eligiRounds"><i class="clg-sprite clg-info"></i></a>
                        <?php $temp = ob_get_clean();
                        }else{
                            $temp = '';
                        }
                        
                        $cutoffstr[] = '<div class="cutoff-col-div">'.$cut['cutoffstr'].$temp.'</div>';
                    }
                    if(!empty($tooltip)){
                        $layerRoundsStructure.='<li><p class="head-L3">'.$val['qualification'].'</p><p class="para-L3">'.$tooltip.'</p></li>';
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
    <?php } ?>        
    </tbody>
</table>

<div class="hid" id="eligiAddLayer">
    <div class="crs-widget listingTuple">
        <div class="lcard">
            <ul class="schlrshp-list schlrshp-lyr">
                <?=$layerStructure?>
            </ul>       
        </div>
    </div> 
</div>

<div class="hid" id="eligiRoundsLayer">
    <div class="crs-widget listingTuple">
        <div class="lcard">
            <ul class="schlrshp-list schlrshp-lyr">
                <?=$layerRoundsStructure?>
            </ul>       
        </div>
    </div> 
</div>
