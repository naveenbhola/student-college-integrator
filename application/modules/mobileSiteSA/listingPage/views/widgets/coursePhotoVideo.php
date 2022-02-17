<?php 
    $mediaData['photo'] = $universityObj->getPhotos();
    $mediaData['video'] = $universityObj->getVideos();
    if(count($mediaData['photo']) + count($mediaData['video'])){
?>
    <section id="coursePhotoVideoSection" class="content-wrap clearfix navSection" style="margin:15px 15px -1 15px;height:280px;">

    <nav class="tabs">
	<ul>
        <?php 
            if(count($mediaData['photo'])){
        ?>
            <li class="active" id="photoTab"><a href="javascript:void(0)" onclick="switchPhotoOrVideo('photo')">Photos (<?=count($mediaData['photo'])?>)</a></li>
        <?php   }
            if(count($mediaData['video'])){
        ?>
            <li id="videoTab" <?=(count($mediaData['photo']))?'':'class="active"'?>><a href="javascript:void(0)" onclick="switchPhotoOrVideo('video')">Videos(<?=count($mediaData['video'])?>)</a></li>
        <?php    }
        ?>
        
	</ul>
    </nav>
    <?php
        if(count($mediaData['photo'])){
    ?>
    <div class="slider-box" id="courseListingPhotoDiv" style="height:210px; margin-bottom:-10px;">
        <ul id="courseListingPhotoUl" style="width: 16000px;" class="sliderUl">
            <?php
                $mediaData['photo'] = array_slice($mediaData['photo'], 0 , 50);
                foreach($mediaData['photo'] as $photoObj){
                    
            ?>
                <li class="trendtuple" style="width:300px;">
                    <div class="figure" style="width:300px;">
                        <img class="lazy" data-src="<?=$photoObj->getUrl()?>" src="" width="300" height="200" alt="univ-img">
                    </div>
                </li>
            <?php    }
            ?>
        </ul>
    </div>
    <div id="courseListingPhotoSliderPagination" class="slider-pagination" style="margin-top:15px;">	
        <strong><span class="currentPositon">1</span>/<span class="totalCount"><?=count($mediaData['photo'])?></span></strong>
    </div>
    <?php    }
    ?>

    <?php
        if(count($mediaData['video'])){
    ?>
        <div class="slider-box" id="courseListingVideoDiv" style="height:210px; margin-bottom:-10px;<?=(count($mediaData['photo']))?'display:none':''?>">
            <ul id="courseListingVideoUl" style="width: 10000px;" class="sliderUl">
                <?php
                    $counter = 0;
                    foreach($mediaData['video'] as $videoObj){
                        ++$counter;
                        $videoUrl = $videoObj->getUrl();
                        preg_match("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $videoUrl, $matches);
                        $id = $matches[0];
                ?>
                    <li class="trendtuple" style="width:300px;">
                        <div class="figure" style="width:300px;">
                            <div class="fig-innerBx" onclick="playYoutubeUrl(this,'<?=$id?>','<?=$counter?>')">
                                <span></span>
                                <i class="sprite icon-video"></i>
                                <img class="lazy" width="300" height="200" src="" data-src="https://img.youtube.com/vi/<?=$id?>/default.jpg">
                            </div>
                            <div id="youtube-<?=$counter?>" class="youtubeContainer" style="display:none"></div>
                        </div>
                    </li>
                <?php    }
                ?>
            </ul>
            <?php
                if($counter > 1){
            ?>
            <div class="slidde-nav">
                <a class="LSlde" onclick="$j('#courseListingVideoDiv').trigger('swiperight');"><i class="sprite slide-arw-left"></i></a>
                <a class="RSlde" onclick="$j('#courseListingVideoDiv').trigger('swipeleft');"><i class="sprite slide-arw-right"></i></a>
            </div>
            <?php }
            ?>
        </div>
        <div id="courseListingVideoSliderPagination" class="slider-pagination" style="margin-top:15px;<?=(count($mediaData['photo']))?'display:none':''?>">	
            <strong><sliderCount class="currentPositon">1</sliderCount>/<sliderCount class="totalCount"><?=count($mediaData['video'])?></sliderCount></strong>
        </div>
    <?php    }?>
    </section>
    <?php }
    ?>