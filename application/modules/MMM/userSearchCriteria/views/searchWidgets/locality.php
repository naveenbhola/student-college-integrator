<?php
if((!empty($citiesLocalities)) && (!empty($cities))) {

    foreach($cities as $city) { 

        if(!empty($citiesLocalities[$city['city_id']])) { ?>


            <?php
            $citiesLocalitiesArr = $citiesLocalities[$city['city_id']];
            ksort($citiesLocalitiesArr);
            ?>

            <div class="cityBlocks_<?php echo $criteriaNo;?> clone" id="cityDetailBlock<?php echo $city['city_id'].'_'.$criteriaNo;?>" cityId="<?php echo $city['city_id'];?>">

                <div class="locality-bx"><?php echo $city['city_name'];?></div>

                <div class="ul-block">

                    <ul class="">
                        <li>

                            <div class="Customcheckbox2">
                                <input type="checkbox" value="<?php echo $city['city_id'];?>" id="locality<?php echo $city['city_id'].'_'.$criteriaNo;?>" class="parentEntity clone">
                                <label for="locality<?php echo $city['city_id'].'_'.$criteriaNo;?>" class="clone">All</label>
                             </div> 
                        
                             <div class="l-col">
                                <ul class="">       
                                    <?php 
                                    foreach($citiesLocalitiesArr as $locality) { ?>
                                    
                                    <li>
                                        <div class="Customcheckbox2">
                                            <input type="checkbox" class="childEntity locality<?php echo $city['city_id'].'_'.$criteriaNo;?> clone" parentId="locality<?php echo $city['city_id'].'_'.$criteriaNo;?>" value="<?php echo $locality->localityId;?>" id="<?php echo $locality->localityId;?>" >
                                            <label for="<?php echo $locality->localityId;?>" class="clone"><?php echo $locality->localityName;?></label>
                                        </div>                          
                                    </li>

                                    <?php 
                                    } ?>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        <?php
        }
    } 
} ?>
