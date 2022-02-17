<div class="nav-tabs nav-lt">
   <div class="chp-nav" id="chpNavSticky">
      <div class="chp-navList">
         
         <?php if(count($snippetUrl)>3){?><span class="expnd-circle expended" id="expended" onclick="manageNav(this)"><span class="rippleefect ib-circle"><i class="expnd-switch"></i></span></span><?php }?>

         <ul class="l2Menu-list" id="navContainer">
            <?php if(empty($activeSectionName)){
                $activeSectionName = 'homepage';
            }
            $tmpArr = array_keys($snippetUrl);
            for ($i=0; $i < count($snippetUrl) ; $i+=3){
                $liClass = '';
                $voidUrl = 'javascript:void(0)';
                if($tmpArr[i+2]){
                        if($activeSectionName == $tmpArr[$i]){
                            $liClass = 'active';
                        }else if($activeSectionName == $tmpArr[$i+1]){
                            $liClass = 'active';
                        }else if($activeSectionName == $tmpArr[$i+2]){
                            $liClass = 'active';
                        }  
                ?>
                <li class="<?php echo $liClass;?>">
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i]) ? $voidUrl : $snippetUrl[$tmpArr[$i]];?>"  elementtofocus="<?php echo $tmpArr[$i];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i]]);?></a>
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i+1]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i+1]) ? $voidUrl : $snippetUrl[$tmpArr[$i+1]];?>"  elementtofocus="<?php echo $tmpArr[$i+1];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i+1]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i+1]]);?></a>
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i+2]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i+2]) ? $voidUrl : $snippetUrl[$tmpArr[$i+2]];?>"  elementtofocus="<?php echo $tmpArr[$i+1];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i+1]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i+2]]);?></a>
                </li>
            <?php }else if($tmpArr[i+1]){ 
                        if($activeSectionName == $tmpArr[$i]){
                            $liClass = 'active';
                        }else if($activeSectionName == $tmpArr[$i+1]){
                            $liClass = 'active';
                        }  
            ?>
                <li class="<?php echo $liClass;?>">
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i]) ? $voidUrl : $snippetUrl[$tmpArr[$i]];?>"  elementtofocus="<?php echo $tmpArr[$i];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i]]);?></a>
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i+1]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i+1]) ? $voidUrl : $snippetUrl[$tmpArr[$i+1]];?>"  elementtofocus="<?php echo $tmpArr[$i+1];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i+1]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i+1]]);?></a>
                </li>
            <?php }else{
                        if($activeSectionName == $tmpArr[$i]){
                            $liClass = 'active';
                        }  
            ?>
                <li class="<?php echo $liClass;?>">
                    <a class="sec-a <?php if($activeSectionName == $tmpArr[$i]){?> active <?php }?>" href="<?php echo ($activeSectionName == $tmpArr[$i]) ? $voidUrl : $snippetUrl[$tmpArr[$i]];?>"  elementtofocus="<?php echo $tmpArr[$i];?>" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$tmpArr[$i]]));?>_NAVIGATION"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$tmpArr[$i]]);?></a>
                </li>
            <?php }
        }
        unset($tmpArr);
    ?>
         </ul>
      </div>
   </div>
</div>