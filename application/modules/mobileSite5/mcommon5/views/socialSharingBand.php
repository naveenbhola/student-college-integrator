<?php
$ignoreBucket = array('whatsapp');
$socialBucket = array('facebook'=>'https://www.facebook.com/sharer/sharer.php?u=', 'whatsapp'=>'https://api.whatsapp.com/send?text=', 'twitter'=>'http://twitter.com/intent/tweet?url=', 'linkedin'=>'https://www.linkedin.com/shareArticle?mini=true&url=', 'email' => '');
?>
<div class="social-sharing-widget">
    <div class="sharing-box">
        <ul class="sharing-band-list">
            <li class="shareBandCount"><span class="sharethis">Share this : </span></li>
            <?php foreach($socialBucket as $key=>$value){

                    if($fromWhere == 'desktop' && in_array($key, $ignoreBucket)){
                         continue; 
                    }
                    if($key == 'email'){
                        $href = "mailto:?subject=I found an interesting link on Shiksha.com&body=Check out this link @shareUrl@. For more details visit www.shiksha.com";
                    }else if($key =='whatsapp'){
                        $href = $value."Check out this link @shareUrl@. For more details visit www.shiksha.com";
                    }else{
                        $href=$value;
                    }
            ?>
            <li class="ssblist" data-position="<?php echo $widgetPosition;?>" data-type="<?php echo $key?>"><a href="<?php echo $href?>" target="_blank"><i class="social-icons <?php echo $key?>"></i></a></li>
        <?php }?>
        </ul>
    </div>
</div>