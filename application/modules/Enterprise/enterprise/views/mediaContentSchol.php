<div class="row" id="mediaSchol" style="display:none;">	
    <div>
        <div class="r1 bld">Upload Documents:&nbsp;</div>
        <div class="r2">

            <?php 
                $k = 0;
                foreach ($docs as $doc) { ?>
                <div id="doc_<?php echo $k;?>">
                    <div style=""><a href="<?php echo $doc['url'];?>"><?php echo $doc['name'];?></a><a href="javascript:removeScholDoc(<?php echo $doc['docid'].','.$k;?>);">&nbsp;&nbsp; Remove</a></div>
            </div>
            <?php
            $k++;
            }?>

            <?php for($i=0;$i<3;$i++){ 
            if($i<$k){
            ?>
            <div id="s_upload_<?php echo $i;?>" style="display:none;"><input type="text" name="s_upload_f_caption[]" style="height:14px"/>&nbsp;<input type="file" name="s_upload_f[]" /></div><br/>
            <?php }else{ ?>
            <div id="s_upload_<?php echo $i;?>"><input type="text" name="s_upload_f_caption[]" style="height:14px"/>&nbsp;<input type="file" name="s_upload_f[]" /></div><br/>
            <?php }
            }
            ?>
        </div>							
    </div>
    <div class="clear_L"></div>
</div>															
<div class="lineSpace_10">&nbsp;</div>
