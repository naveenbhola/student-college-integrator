<div class="popup_layer lyr1" id="courselayer" style="display:none;">
    <a href="javascript:void(0);" class="hlp-rmv heplyr" data-layer="courselayer">&times;</a>
    <div class="hlp-popup_ch nopadng">        
        <div class="amen-box">
            <div class="hlp-info">                                
                <div class="loc-layer">
                    <div class="hed"> Choose the course you're interested in: </div>
                    <div class="loc-list-col">
                    <ul class="prm-lst">      
                    <?php 
                        foreach ($groupList as $key=>$details) {                            
                     ?>              
                        <li><a href="<?php echo $currentUrl.'?course='.$details['id'];?>" ga-attr="COURSE_SELECTION"><p><?php echo $details['name'];?></p></a></li>                        
                        </li>
                     <?php } ?>                   
                    </ul>
                    </div>
                </div>
        </div>
    </div>
    </div>
</div>