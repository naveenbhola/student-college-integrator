<div class="crs-widget listingTuple" id="partner">
   <h2 class="head-L2">Partner Colleges</h2>
   <div class="lcard">
       <ul class="max-ul">
            <?php foreach ($partners['data'] as $key => $value) {?>                    
            <li>
                <a href="<?=$value['url']?>" target="_blank">
                    <div class="">
                        <p class="inst-title">
                        <?php if (strlen($value['name']) > 90) {
                                    echo trim(substr($value['name'], 0, 90))."...";
                        }else{
                                    echo $value['name'];
                        }
                        ?>
                        </p>  
                        <?php if($partners['checkDurationExistForOne']){?>
                                <?php if($value['duration']) {?>
                                  <span class="period">Duration <strong><?=$value['duration']?></strong></span>
                                <?php }else{ ?>
                                    <p class="duration"><span>Duration</span>-</p>
                                <?php } ?>    
                        <?php } ?>                      
                        
                    </div>
                </a>
            </li>
             <?php } ?>            
       </ul>
   </div>
</div>