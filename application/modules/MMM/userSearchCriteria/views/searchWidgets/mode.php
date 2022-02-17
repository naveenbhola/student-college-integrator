<tr>
    <td class="nxt-td">
        <div class="nxt">
            <label class="label">Mode:</label>
        </div>
    </td>

    <td class="">
        <div class="ul-block">
            <ul class="">

                <?php
                if(!empty($mode)) { 

                    foreach($mode as $modeId=>$modeDetail) { ?>

                        <li>
                            <div class="Customcheckbox2">
                                <input type="checkbox" id="<?php echo 'mode'.$modeId.'_'.$criteriaNo;?>" value="<?php echo $modeId;?>" class="<?php if($modeId == 20){?>FT<?php }?> parentEntity clone mode_<?php echo $criteriaNo;?>"/>
                                <label for="<?php echo 'mode'.$modeId.'_'.$criteriaNo;?>" class="clone"><?php echo $modeDetail['name'];?></label>
                            </div> 
                            
                            <?php
                            if(!empty($modeDetail['children'])) { ?>
                            <div class="l-col">
                                <ul class="">

                                    <?php
                                    foreach($modeDetail['children'] as $subModeId=>$subModeName) { ?>
                                    <li>
                                        <div class="Customcheckbox2">
                                            <input type="checkbox" id="<?php echo 'subMode'.$subModeId.'_'.$criteriaNo;?>" value="<?php echo $subModeId;?>" parentId="<?php echo 'mode'.$modeId.'_'.$criteriaNo;?>" class="<?php if($modeId == 20){?>FT<?php };?>  childEntity <?php echo 'mode'.$modeId.'_'.$criteriaNo;?> clone mode_<?php echo $criteriaNo;?>"/>
                                            <label for="<?php echo 'subMode'.$subModeId.'_'.$criteriaNo;?>" class="clone"><?php echo $subModeName;?></label>
                                        </div> 
                                    </li>
                                    <?php } ?>

                                </ul>
                            </div> 
                            <?php } ?>

                        </li>
              
                <?php 
                    } 

                } ?>
            </ul>
        </div>
    </td>
</tr>