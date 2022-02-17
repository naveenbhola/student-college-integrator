<?php if($view == 'default'){ ?>
    <ul>
        <li><label>Share on</label></li>
     
        <?php if(in_array('facebook', $share)){ ?>
            <li><a href="javascript:void(0)" class="sprite-bg facebook-share-icon" onclick="ReviewsSocialShare('facebook','<?php echo $permURL; ?>','Share With Facebook')"></a></li>
        <?php  } ?>
        
        <?php if(in_array('twitter', $share)){ ?>
            <li><a href="javascript:void(0)" class="sprite-bg twitter-share-icon" onclick="ReviewsSocialShare('twitter','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php  } ?>
        
        <?php if(in_array('linkedin',$share)){ ?>
            <li><a href="javascript:void(0)" class="sprite-bg linkedin-share-icon" onclick="ReviewsSocialShare('linkedin','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php  } ?>
    
        <?php if(in_array('google',$share)){ ?>
            <li><a href="javascript:void(0)" class="sprite-bg google-plus-icon" onclick="ReviewsSocialShare('google','<?php echo $permURL; ?>','Google+')"></a></li>
        <?php  } ?>
    </ul>
<?php }else if($view == 'permalinkTop'){ ?>
    <ul>
        <li style="padding-top:3px;">Share On</li>

        <?php if(in_array('facebook', $share)){ ?>
            <li><a href="javascript:void(0)" class="permalink-sprite permalink-fb-icon" onclick="ReviewsSocialShare('facebook','<?php echo $permURL; ?>','Share With Facebook')"></a></li>
        <?php  } ?>

        <?php if(in_array('twitter', $share)){ ?>
            <li><a href="javascript:void(0)" class="permalink-sprite permalink-twt-icon" onclick="ReviewsSocialShare('twitter','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php  } ?>

        <?php if(in_array('linkedin',$share)){ ?>
            <li><a href="javascript:void(0)" class="permalink-sprite permalink-linkdin-icon" onclick="ReviewsSocialShare('linkedin','<?php echo $permURL; ?>','<?php echo $subTitle; ?>')"></a></li>
        <?php } ?>

        <?php if(in_array('google',$share)){ ?>
            <li><a href="javascript:void(0)" class="permalink-sprite permalink-gplus-icon" onclick="ReviewsSocialShare('google','<?php echo $permURL; ?>','Google+')"></a></li>
        <?php  } ?>
    </ul>    
<?php }else if($view == 'permalinkBottom'){ ?>
        <ul class="flLt" style="width:70%;">
            <li style="padding-top:3px;">Share On</li>
            
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
    <?php } ?>