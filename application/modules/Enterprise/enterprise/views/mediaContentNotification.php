<div class="row" id="mediaAdmission" style="display:none;">	
    <div>
        <div class="r1 bld">Upload Documents:&nbsp;</div>
        <div class="r2">

            <?php
                $k = 0;
                foreach ($details['docs'] as $doc) { ?>
                <div id="doc_<?php echo $k;?>">
                    <div style=""><a href="<?php echo $doc['url'];?>"><?php echo $doc['name'];?></a><a href="javascript:removeNotificationDoc(<?php echo $doc['docid'].','.$k;?>);">&nbsp;&nbsp; Remove</a></div>
            </div>
            <?php
            $k++;
            }?>

            <?php for($i=0;$i<3;$i++){ 
            if($i<$k){
            ?>
            <div id="a_upload_<?php echo $i;?>" style="display:none;"><input type="text" name="a_app_forms_caption[]" style="height:14px"/>&nbsp;<input type="file" name="a_app_forms[]" /></div><br/>
            <?php } else { ?>
            <div id="a_upload_<?php echo $i;?>"><input type="text" name=a_app_forms_caption[]" style="height:14px"/>&nbsp;<input type="file" name="a_app_forms[]" /></div><br/>
            <?php }
            }
            ?>
        </div>							
    </div>
    <div class="clear_L"></div>
</div>															
<div class="lineSpace_10">&nbsp;</div>
