<div class="exm-sbNav">
    <div class="clg-nav">
        <div class="clg-navList">
            <ul>
                <?php
               if(empty($activeSectionName))
               {
                   $activeSectionName = 'homepage';
               }
           foreach($snippetUrl as $sectionKey=>$url){
               $className = '';
               if($activeSectionName == $sectionKey)
               {
                   $className = 'active';
                   $sectionUrl = $url;
               }else
               {
                   $sectionUrl = $url;
               }
               ?>
                <li><a data-vars-event-name="<?php echo str_replace(' ', '_', strtoupper($sectionNameMapping[$sectionKey]));?>_NAVIGATION" href="<?php echo $sectionUrl;?>" class="<?=$className;?>"><?php echo str_replace('Summary','Overview', $sectionNameMapping[$sectionKey]);?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

