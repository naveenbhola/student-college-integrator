<?php 
$singleSeatClass = '';
$ITEM_TO_SHOW = 7;
if(count($seats) == 1){
    $singleSeatClass = 'no-box';
}
?>
<div class="new-row">
    <div class="courses-offered listingTuple" id="seats">
        <div class="group-card no__pad gap">
        <h2 class="head-1 gap" style="margin:0;">Seats Info</h2>
            <!-- <div class="title-card no__pad">
                
            </div> -->
            <div  class="pad__16">
                <?php if(!empty($seats['totalSeats'])){?>
                <div class="seats-info <?=$singleSeatClass?>">
                    <p class="head-1"><?=$seats['totalSeats']?><span>Total seats</span></p>
                </div>
                <?php } ?>
                <?php if(!empty($seats['categoryWiseSeats']) || !empty($seats['examWiseSeats']) || !empty($seats['domicileWiseSeats']) ) {?>
                <div class="seats-show">
                    <div class="tabSection">
                        <ul class="h-tabs">
                            <?php if(!empty($seats['categoryWiseSeats'])){?><li data-index="1"  id="seatsCategory"> <h2>Category</h2></li><?php } ?>
                            <?php if(!empty($seats['examWiseSeats'])){?><li data-index="2" id="seatsExam"><h2>Entrance Exam</h2></li><?php } ?>
                            <?php if(!empty($seats['domicileWiseSeats'])){?>
                                <li data-index="3" id="seatsDomicile">
                                    <h2>Domicile
                                        <?php if(!empty($seats['relatedStates'])) {?>
                                            <div class="tp-block">
                                                <i class="info-icn" infodata = "<?=$seats['relatedStates'];?>" infopos="top"></i>
                                            </div>
                                        <?php } ?>
                                    </h2>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="tabContent">
                        <?php if(!empty($seats['categoryWiseSeats'])){?>
                        <div class="seats-column seatsCategory" data-index="1">
                            <?php  if(count($seats['categoryWiseSeats']) > $ITEM_TO_SHOW) { ?>
                            <a  class="prev-link lftArwDsbl"><i class=""></i></a>
                            <?php } ?>
                            <div class="seats-list-col">
                                <ul>
                                    <?php foreach ($seats['categoryWiseSeats'] as $key => $val) {?>
                                        <li>
                                        <p class="">
                                            <?=$val['seats']?><span><?=$val['category']?></span>
                                        </p>
                                    </li>
                                    <?php }?>
                                    
                                </ul>
                            </div>
                            <?php  if(count($seats['categoryWiseSeats']) > $ITEM_TO_SHOW) { ?>
                            <a class="next-link"><i></i></a>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <?php if(!empty($seats['examWiseSeats'])){?>
                        <div class="seats-column seatsExam" style="display:none;" data-index="2">
                                <?php  if(count($seats['examWiseSeats']) > $ITEM_TO_SHOW) { ?>
                                    <a href="" class="prev-link"><i class=""></i></a>
                                <?php } ?>
                                <div class="seats-list-col">
                                    <ul class="">
                                    <?php foreach ($seats['examWiseSeats'] as $key => $val) {?>
                                        <li>
                                        <p class="">
                                            <?=$val['seats']?><span><?=$val['exam']?></span>
                                        </p>
                                    </li>
                                    <?php }?>
                                    </ul>
                                </div>
                                <?php if(count($seats['examWiseSeats']) > $ITEM_TO_SHOW) { ?>
                                    <a class="next-link"><i></i></a>
                                <?php } ?>
                        </div>
                        <?php } ?>
                        <?php if(!empty($seats['domicileWiseSeats'])){?>
                        <div class="seats-column seatsDomicile" style="display:none;" data-index="2">
                                <?php if(count($seats['domicileWiseSeats']) > $ITEM_TO_SHOW) { ?>
                                    <a href="" class="prev-link"><i class=""></i></a>
                                <?php } ?>
                                <div class="seats-list-col">
                                    <ul class="">
                                    <?php foreach ($seats['domicileWiseSeats'] as $key => $val) {?>
                                        <li>
                                        <p class="">
                                            <?=$val['seats']?><span><?=$val['category']?></span>
                                        </p>
                                    </li>
                                    <?php }?>
                                    </ul>
                                </div>
                                <?php if(count($seats['domicileWiseSeats']) > $ITEM_TO_SHOW) { ?>
                                    <a class="next-link"><i></i></a>
                                <?php } ?>
                        </div> 
                        <?php } ?>
                    </div>

                </div>
                <?php } ?>
            </div>
        </div>

    </div>

</div>