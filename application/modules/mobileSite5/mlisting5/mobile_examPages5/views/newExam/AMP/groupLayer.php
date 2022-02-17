
<amp-lightbox id="change-group" class="" layout="nodisplay" scrollable>
 <div class="lightbox">
    <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:change-group.close" role="button" tabindex="0">×</a>
    <div class="m-layer">
        <div class="min-div color-w catg-lt">
                <div class="f14 color-3 bck1 pad10 font-w6">
                Choose the course you're interested in:
              </div>
                    <ul class="prm-lst">      
                    <?php 
                        foreach ($groupList as $key=>$details) {                            
                     ?>              
                        <li><a href="<?php echo $currentUrl.'?course='.$details['id'];?>" class="block"><?php echo $details['name'];?></a></li>                        
                        </li>
                     <?php } ?>                   
                    </ul>
        </div>
    </div>
</div>
</amp-lightbox>     