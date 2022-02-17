<section>
 <div class="data-card m-5btm">
     <h2 class="color-3 f16 heading-gap font-w6">Partner Colleges</h2>
     <div class="card-cmn color-w">
         <ul class="prtnrs">
            <?php foreach ($partners['data'] as $key => $value) {?>  
             <li class="pos-rl">
                 <a class="block" href="<?=$value['url']?>">
                     <div class="block">
                        <p class="f14 color-6 m-btm font-w6 word-break">
                            <?php if (strlen($value['name']) > 84) {
                                        echo trim(substr($value['name'], 0, 81))."...";
                            }else{
                                        echo $value['name'];
                            } ?>
                        </p>
                        <?php if($partners['checkDurationExistForOne']){?>
                                <?php if($value['duration']) {?>
                                  <span class="block f12 color-9 ">Duration <strong class="font-w6"><?=$value['duration']?></strong></span>
                                <?php }else{ ?>
                                    <p><span class="block f12 color-9 ">Duration</span>-</p>
                                <?php } ?>    
                        <?php } ?>                      
                     </div>
                 </a>
             </li>
             <?php } ?>
         </ul>
     </div>
 </div>
</section>