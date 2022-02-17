<?php
    if(isset($institutesRecommended) && count($institutesRecommended)>0 ){
?>
    <div id="recommendationDiv<?=$keyVal?>" class="similar-layer comRecoCont" style="display: none;z-index:10;">
                <div class="layer-head">
                    <a href="javascript:void(0)" class="comRecoClose flRt close-sec" title="Close" onClick="comRecoClose('<?=$keyVal?>');">
                        <i class="cmpre-sprite ic-cls"></i>
                    </a>
                    <div class="layer-title"><strong>Similar Colleges</strong></div>
                </div>
                <div class="customInputs" id="similarCourses<?=$keyVal?>">
                    <ul>
                        <?php
                            $i = -1;
                            foreach ($institutesRecommended as $institute){

				$course = $institute->getFlagshipCourse();
				$course->setCurrentLocations($request);
				if(strlen($institute->getName()) > 60){
					$instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName()),0,56));
					$instStr .= "...";
				}else{
					$instStr = htmlspecialchars($institute->getName());
				}
                                $i++;                                
                                ?>
                                <li>
                                     <input type="checkbox" id="ins-comp-<?=$keyVal?>-<?=$i?>" autocomplete="off" value="<?=$course->getId();?>" onclick="checkForNumber('ins-comp-<?=$keyVal?>-<?=$i?>','<?=$keyVal?>'); trackEventByGA('LinkClick','COMPARE_PAGE_SUGGESTION_CHOOSE');">
                                     <label for="ins-comp-<?=$keyVal?>-<?=$i?>">
                                        <span class="common-sprite"></span><p style="font-size:12px;"><?=$instStr?>, <?=$course->getCurrentMainLocation()->getCity()->getName()?></p>
                                     </label>
                                </li>
                            <?php    
                            }
                        ?>
                    </ul>

                    <input type="button" class="comp-orange-btn" value="Add to Compare" onClick="trackEventByGA('LinkClick','COMPARE_PAGE_SUGGESTION_BUTTON_CLICK'); recalculateComparePage('<?=$keyVal?>');"/>
                </div>
            </div>
    
<?php
    }
?>
