<?php $data = $pickupData['dateDetails']; ?>
<h3 class="f-bold f14 color-3">Pick up date & timing</h3>
<ul class="form-ul">
    <li>
        <label class="color-6 i-block f14">Pick up date</label>
        <div class="lft-sec">
            <p class="f12 color-3" id="pickupDate">
                <?php echo $pickupData['dateDetails']['pickupDate']?$pickupData['dateDetails']['pickupDate']:'-'; ?>
            </p>
        </div>
    </li>
    <li>
        <label class="color-6 i-block f14">Pick up timings</label>
        <div class="lft-sec">
            <?php
                if(count($data['times']) == 0){         // We don't have any times, show a -
            ?>
                    <p class="f12 color-3">-</p>      
            <?php
                }else if(count($data['times']) == 1){
            ?>
                    <p class="f12 color-3"><?php echo reset($data['times']);?></p>
                    <input type="radio" name="pickupTime" value="<?=reset(array_keys($data['times']))?>" style="display:none;" checked="checked">
            <?php        
                }else{
            ?>
                    <p class="f12 color-3">
                        <?php foreach($data['times'] as $option => $time){ ?>
                            <span class="time-slots">
                                <input type="radio" name="pickupTime" checked="checked" value="<?=$option?>"/><?=$time?>
                            </span>
                        <?php } ?>
                    </p>
            <?php        
                }
            ?>
        </div>
    </li>
</ul>

