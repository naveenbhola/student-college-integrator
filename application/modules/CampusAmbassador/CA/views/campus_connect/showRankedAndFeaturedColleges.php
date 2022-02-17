<div class="bx-wrapper" style="max-width: 840px; margin: 0px auto;">
        <div class="bx-viewport" style="width: 3500px; overflow:hidden ; position: relative; height: 5000px;">
                <div class="slider1">
                <ul style="width: 3400px; position: relative; left: 0px;" id="collegeWidgetSlider1">     
                        <?php
                        $i =0;
                        $divId = 1;
                        foreach($result as $instId => $src){
                        if($i%3==0){ ?>
                            <li style="float: left; width: 849px" id="slider_<?=$divId?>">
                        <?php $divId++; } ?>

                        <div class="slide slide-box bx-clone">
                            <img src="<?php if(isset($src['imgUrl']) && $src['imgUrl'] != ''){ echo $src['imgUrl']; }else{ echo SHIKSHA_HOME.'/public/images/college_default_img.jpg'; } ?>" alt="<?php echo $src['imgUrl']; ?>" title="<?php echo $src['name']; ?>"/>
        
                                <div class="slide-box-text">
                                <p class="sldr_s1"><span><?php if(strlen($src['name'])>50){ 
                                    ?><a href="<?php echo $src['instUrl']; ?>"><?php echo substr($src['name'],0,47).'...';?></a><?php } 
                                    else{?><a href="<?php echo $src['instUrl']; ?>"><?php echo $src['name'];?></a><?php } ?></span></p><p class="sldr_s2"><?php if(isset($src['locality']) && $src['locality'] != ''){ echo $src['locality'].''.',' ;} ?> <?=$src['cityName']; ?></p>

                                <p class="rank sldr_s3">Rank : 
                               <?php if(count($src['rank']) > 0) { 
                                    $addingSeparator = false;
                                    $isNARequired = false;
                                    foreach ($src['rank'] as $source => $rank) { 
                                        if($rank){
                                            $isNARequired = true;
                                            if($addingSeparator == false){
                                                $addingSeparator = true;
                                            }else{
                                                echo ',';
                                            }
                                        ?>
                                        <strong><?php echo $rank?></strong> <span>(<?php echo $source;?>)</span>
                                <?php   }else{
                                        $isNARequired = false;
                                    }
                                }
                                if($isNARequired == false && !$addingSeparator){
                                    echo "<span>NA</span>";
                                }
                                }
                                $Id = $instId;
                                $type = 'all_content_pages';
                                $optionalArgs['typeOfListing'] = $src['listingType']; 
                                $optionalArgs['typeOfPage'] = 'questions';
                                $url = getSeoUrl($Id,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
                                ?>
                                </p>
                                <p class="c-std sldr_s4"><a href="<?=$url;?>" onclick="setCookie('intermediateCookie','askQuestion',-1,'/',COOKIEDOMAIN);"><?php echo $src['CA_Count']; ?> Current Students</a> to answer your questions</p>
                                <p class="c-std sldr_s5"><a href="<?=$url;?>" onclick="setCookie('intermediateCookie','askQuestion',-1,'/',COOKIEDOMAIN);">View Existing Questions </a></p>
                        <p class="">
                        <a href="<?=$url;?>" class="rv_gryBtn1 btn" onclick = "setCookieForIntermediatePage('askQuestion',<?=$trackingPageKeyId?>);">Ask Your Question</a>
                         </p>
            </div>
</div>
<?php
if($i%3==2){
    echo '</li>';
}
$i++;
} ?>
        
</ul>
</div>
</div>
        <div class="bx-controls bx-has-controls-direction">
                <div class="bx-controls-direction"><a style="display: none;" href="javascript:void(0)" class="bx-prev" id="prevButton1" onclick="colgWidgetSlideLeft(); trackEventByGA('SCROLL_ON_COLLEGE_CARD_CCHOME', 'CCHome_Scroll_On_<?=$quesWidgetType?>_Card');">Prev</a><?php if(count($result)>3) { ?><a href="javascript:void(0)" class="bx-next" id="nextButton1" onclick="colgwidgetSlideRight('<?php echo $divId; ?>'); trackEventByGA('SCROLL_ON_COLLEGE_CARD_CCHOME', 'CCHome_Scroll_On_<?=$quesWidgetType?>_Card');">Next</a><?php } ?></div>
        </div>
</div>
