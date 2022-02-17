 <div class="act-row">
                              <?php for ($i=$cardCounter; $i < $statCountSlider; $i++) { 
                                    $cardCounter++;
                                    
                                ?>
                              <?php if($stats[$i]['isCTA']) { ?>
                                 <div id="<?php echo str_replace(' ', '',$stats[$i]['lable'] );?>" label='<?php echo $stats[$i]['lable']?>' class="act-10" data-enhance="false" <?php if($stats[$i]['lable'] == 'Ask Question Now') { ?> onclick="$('.q-ask').click();" <?php } else { ?> onclick="window.location='<?php echo $stats[$i]['redirectionUrl']?>'" <?php } ?>>
                                    <p><?php echo $stats[$i]['lable']; ?></p>
                                   <?php if($stats[$i]['value'] >0){?> <span class="num"><?php echo $stats[$i]['value'];?></span> <?php }?>
                                    <?php if(!empty($stats[$i]['lable'])){?> <span class="border-line">&nbsp;</span><?php }?>
                                 </div>
                              <?php } else { ?>
                                <div id="<?php echo str_replace(' ', '',$stats[$i]['lable'] );?>" label='<?php echo $stats[$i]['lable']?>' class="act-1">
                                    <p><?php echo $stats[$i]['lable']; ?></p>
                                   <?php if($stats[$i]['value'] >0){?> <span class="num"><?php echo $stats[$i]['value'];?></span> <?php }?>
                                    <?php if(!empty($stats[$i]['lable'])){?> <span class="border-line">&nbsp;</span><?php }?>
                                 </div>
                              <?php } ?>

                             <?php 
                                
                                if( ($cardCounter>=$cardPerGrid) && ($cardCounter % $cardPerGrid == 0) ) {
                                  break;                                
                                }

                             }?>

                           </div> 
                         
                          <!--2nd row-->
                          <div class="act-row">
                              <?php for ($i=$cardCounter; $i < $statCountSlider ; $i++) { 
                                    $cardCounter++;

                                ?>
                                <?php if($stats[$i]['isCTA']) { ?>
                                 <div id="<?php echo str_replace(' ', '',$stats[$i]['lable'] );?>" label='<?php echo $stats[$i]['lable']?>' class="act-10" data-enhance="false" <?php if($stats[$i]['lable'] == 'Ask Question Now') { ?> onclick="$('.q-ask').click();" <?php } else { ?> onclick="window.location='<?php echo $stats[$i]['redirectionUrl']?>'" <?php } ?>>
                                   <p><?php echo $stats[$i]['lable']; ?></p>
                                   <?php if($stats[$i]['value'] >0){?> <span class="num"><?php echo $stats[$i]['value'];?></span><?php }?>
                                   <?php if(!empty($stats[$i]['lable'])){?> <span class="border-line">&nbsp;</span><?php }?>
                                  <!--  -->
                                 </div>
                              <?php } else { ?>
                                 <div id="<?php echo str_replace(' ', '',$stats[$i]['lable'] );?>" label='<?php echo $stats[$i]['lable']?>' class="act-1">
                                    <p><?php echo $stats[$i]['lable']; ?></p>
                                   <?php if($stats[$i]['value'] >0){?> <span class="num"><?php echo $stats[$i]['value'];?></span><?php }?>
                                   <?php if(!empty($stats[$i]['lable'])){?> <span class="border-line">&nbsp;</span><?php }?>
                                 </div>
                                 <?php } ?>
                             <?php 
                                if($cardCounter % (2*$cardPerGrid) == 0){
                                  break;
                                }
                             }?>

                           </div>