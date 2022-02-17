    <?php if($view == 'permalinkBottom'){ ?>
        <ul class="flLt" style="width:100%;"> 
            <?php if(in_array('facebook', $share)){ ?>
                <li><a href="javascript:void(0)" class="permalink-sprite permalink-fb-share-icon" onclick="ReviewsSocialShare('facebook','<?php echo $permURL; ?>','Share With Facebook')"></a></li>
            <?php  } ?>

            <?php if(in_array('twitter', $share)){ ?>
                <li><a href="javascript:void(0)" class="permalink-sprite permalink-twt-share-icon" onclick="ReviewsSocialShare('twitter','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
            <?php  } ?>

            <?php if(in_array('linkedin',$share)){ ?>
                <li><a href="javascript:void(0)" class="permalink-sprite permalink-linkdin-share-icon" onclick="ReviewsSocialShare('linkedin','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
            <?php } ?>

            <?php if(in_array('google',$share)){ ?>
                <li><a href="javascript:void(0)" class="permalink-sprite permalink-gplus-share-icon" onclick="ReviewsSocialShare('google','<?php echo $permURL; ?>','Google+')"></a></li>
            <?php  } ?>
             </ul>
    <?php }else{ ?>
    <ul>
        <li><label>Share</label></li>

       	<?php if(in_array('facebook', $share)){ ?>
            <li><a href="javascript:void(0)" class="sprite facebook-share-icon" onclick="ReviewsSocialShare('facebook','<?php echo $permURL; ?>','Share with facebook')"></a></li>
    	<?php } ?>

    	<?php if(in_array('twitter', $share)){ ?>
    	    <li><a href="javascript:void(0)" class="sprite twitter-share-icon" onclick="ReviewsSocialShare('twitter','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php } ?>

        <?php if(in_array('linkedin',$share)){ ?>
            <li><a href="javascript:void(0)" class="sprite linkedin-share-icon" onclick="ReviewsSocialShare('linkedin','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php } ?>
    
        <?php if(in_array('google',$share)){ ?>
            <li><a href="javascript:void(0)" class="sprite google-plus-icon" onclick="ReviewsSocialShare('google','<?php echo $permURL; ?>','Google+')"></a></li>
        <?php } ?>

        <?php if(in_array('whatsapp',$share)){ ?>   
            <li><a href="javascript:void(0)" class="sprite whatsapp-share-icon" onclick="ReviewsSocialShare('whatsapp','<?php echo $permURL?>','<?php echo $subTitle; ?>')"></a></li>
        <?php } ?>

        </ul>
    <?php }?>