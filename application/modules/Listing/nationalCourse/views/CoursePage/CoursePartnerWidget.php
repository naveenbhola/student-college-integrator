<div class="new-row">
    <div class="group-card gap no__pad listingTuple" id="partner">
        <h2 class="head-1 gap">Partner Colleges</h2>
        <div class="partner-clg pad__16">
        <div class="partner-col partnerSlider">
            <?php if(count($partners['data']) > 3) {?>
            <a id="navPhotoPrev" class="icn-prev lftArwDsbl"><i></i></a>
            <?php } ?>
            <div class="r-caraousal">
                <ul class="featuredSlider"> 
                    <?php foreach ($partners['data'] as $key => $value) {?>                    
                    <li class="partner-row">
                        <a data-href="<?=$value['url']?>" alt="<?=$value['name']?>" target="_blank" class="r-card">
                            <p class="disc">
                             <?php if (strlen($value['name']) > 90) {
                                    echo trim(substr($value['name'], 0, 90))."...";
                                }else{
                                    echo $value['name'];
                                }
                                ?>
                            </p>
                            <?php if($partners['checkDurationExistForOne']){?>
                                <?php if($value['duration']) {?>
                                    <p class="duration"><span>Duration</span><?=$value['duration']?></p>
                                <?php }else{ ?>
                                    <p class="duration"><span>Duration</span>-</p>
                                <?php } ?>    
                            <?php } ?>
                        </a>    
                    </li>
                    <?php } ?>                    
                </ul>
            </div>
            <?php if(count($partners['data']) > 3) {?>
            <a id="navPhotoNxt" class="icn-next"><i></i></a>
            <?php } ?>
            </div>
        </div>
    </div>
</div>