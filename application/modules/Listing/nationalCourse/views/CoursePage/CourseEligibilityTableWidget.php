<?php 
    if(!$eligibility['showEligibilityVal'] && !$eligibility['showCutOff'] && empty($eligibility['showEligibilityAdditionalInfo'])){
        ?>
        <div class="eligwotble">
            <h3>Qualification</h3>
            <?php 
                foreach ($eligibility['table'] as $key => $val) {
                    if(!empty($val['url'])){
                        echo "<a class='exam-name' target='_blank' ga-attr='COURSE_ELIGIBILITY".$val['qualification']."' href={$val['url']}>";
                    }
                    ?>
                    <strong><?php echo $val['qualification']; ?></strong>
                    <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Exam'] ?></p></span>
                    <?php 
                    if(!empty($val['url'])){
                        echo "</a>";
                    }
                }
            ?>
        </div>
        <?php
    }
    else{
        ?>
        <table class="table table-bordered crs-tble" id="eligibilityTable">
            <thead class="thead-default">
                <tr>
                    <th width="16%">
                        <h3 class="para-1">Qualification</h3>
                    </th>
                    <?php 
                        if($eligibility['showEligibilityVal'] == true) {
                            ?>
                            <th width="22%">
                                <h3 class="para-1">Minimum Eligibility to Apply</h3>
                            </th>
                            <?php 
                        } 
                    ?>
                    <?php 
                        if($eligibility['showCutOff'] == true) {
                            ?>
                            <th width="24%">
                                <h3 class="para-1">Cut-Offs  <?php if($eligibility['cutOffYear']) {?><span class="yr-spn">(<?=$eligibility['cutOffYear'];?>)</span><?php } ?></h3>
                            </th>
                            <?php 
                        } 
                    ?>
                    <?php  if(isset($eligibility['showEligibilityAdditionalInfo']) && $eligibility['showEligibilityAdditionalInfo']){?>
                        <th width="36%"><h3 class="para-1">Additional Details</h3></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody class="tbody-default">
                <?php 
                    foreach ($eligibility['table'] as $key => $val) {
                        ?>
                        <tr>
                            <td>
                                <?php if(!empty($val['url'])){echo "<a  class='exam-name' target='_blank' ga-attr='COURSE_ELIGIBILITY_EXAM".($val['qualification'])."' href={$val['url']}>";} ?>
                                <strong><?php echo $val['qualification']; ?></strong>
                                <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Exam'] ?></p></span>

                                <?php if(!empty($val['url'])){echo "</a>";} ?>
                            </td>
                            <?php 
                                if($eligibility['showEligibilityVal'] == true) {
                                    ?>
                                    <td><?php echo $val['eligibility']; ?></td>
                                    <?php 
                                } 
                            ?>
                            <?php 
                                if($eligibility['showCutOff'] == true) {
                                    ?>
                                    <td>
                                        <?php 
                                            if(is_array($val['cutoff'])){
                                                $cutoffstr = array();
                                                foreach ($val['cutoff'] as $cut) {
                                                    if($val['quotaCount'][$cut['quota']] > 1){
                                                        ob_start();
                                                        ?>                            
                                                        <?php
                                                            $tooltipText = ''; 
                                                            krsort($eligibility['examCutoffData'][$val['qualification']]);                                                 
                                                            foreach ($eligibility['examCutoffData'][$val['qualification']] as $round => $roundData) {
                                                                foreach ($roundData as $quota => $value) {  
                                                                    $quotaName = $quota;                                                          
                                                                    if(stripos($quota,'Related_states') !== false){
                                                                        $temp = explode(':',$quota);
                                                                        $quotaName = $temp[0];
                                                                    }
                                                                    if($quotaName == $cut['quota']){                                                                
                                                                        $tooltipText .= "Round ".($round)." : ".$value."<br/>";
                                                                    }
                                                                }                                                        
                                                            }
                                                        ?>
                                                        <div class="tp-block">
                                                            <i class="info-icn" infodata = "<?=$tooltipText?>" infopos="right"></i>
                                                        </div>
                                                        
                                                        <?php
                                                        $temp = ob_get_clean();
                                                    }
                                                    else{
                                                        $temp = '';
                                                    }
                                                    $cutoffstr[] = $cut['cutoffstr'].$temp;
                                                }
                                                echo implode($cutoffstr,'<br>');
                                            }
                                            else{                                        
                                                echo $val['cutoff'];
                                            }
                                        ?>
                                    </td>
                                    <?php 
                                }
                            ?>
                            <?php  if(isset($eligibility['showEligibilityAdditionalInfo']) && $eligibility['showEligibilityAdditionalInfo']){?>
                            <td><?php echo nl2br(makeURLAsHyperlink(htmlentities($val['additionalInfo']))); ?></td>
                            <?php }?>
                        </tr>    
                        <?php 
                    }
                ?>
            </tbody>
        </table>
        <?php
    }
?>