<?php
    if(isset($instituteObjects) && $instituteObjects!=''){
?>
    <div id="recommendationDiv<?php echo $keyVal?>" class="similar-layer comRecoCont" style="display: none;z-index:10;">
                <div class="layer-head">
                    <a href="javascript:void(0)" class="comRecoClose flRt close-sec" title="Close" onClick="comRecoClose('<?php echo $keyVal?>');">
                        <i class="cmpre-sprite ic-cls"></i>
                    </a>
                    <div class="layer-title"><strong>Similar Colleges</strong></div>
                </div>
                <div class="customInputs" id="similarCourses<?php echo $keyVal?>">
                    <ul>
                        <?php
                            $i = -1;
                            foreach ($instituteObjects as $institute){
				$cityLocation = $courseInfo[$institute->getId()]['cityName'];
				$courseId     = $courseInfo[$institute->getId()]['courseId'];
				if(strlen($institute->getName()) > 60){
					$instStr  = preg_replace('/\s+?(\S+)?$/', '',substr(htmlspecialchars($institute->getName()),0,56));
					$instStr .= "...";
				}else{
					$instStr = htmlspecialchars($institute->getName());
				}
                                $i++;                                
                                ?>
                                <li>
                                     <input type="checkbox" id="ins-comp-<?php echo $keyVal?>-<?php echo $i?>" autocomplete="off" value="<?php echo $courseId;?>" onclick="checkForNumber('ins-comp-<?php echo $keyVal?>-<?php echo $i?>','<?php echo $keyVal?>'); trackEventByGA('LinkClick','COMPARE_PAGE_SUGGESTION_CHOOSE');">
                                     <label for="ins-comp-<?php echo $keyVal?>-<?php echo $i?>">
                                        <span class="common-sprite"></span><p style="font-size:12px;"><?php echo $instStr?>, <?php echo $cityLocation;?></p>
                                     </label>
                                </li>
                            <?php    
                            }
                        ?>
                    </ul>

                    <input type="button" class="comp-orange-btn" value="Add to Compare" onClick="trackEventByGA('LinkClick','COMPARE_PAGE_SUGGESTION_BUTTON_CLICK'); recalculateComparePage('<?php echo $keyVal?>');"/>
                </div>
            </div>
    
<?php
    }
?>
