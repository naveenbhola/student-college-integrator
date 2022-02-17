<?php //print_r($listingGallerytext);die('1234');?>
      <!--Gallery block-->

         <!--section gallery-2 version-->
         <section class="">
         <?php
            $totalMedia = $media['photos']['totalPhotos'] + count($media['videos']['Videos']);
            $mediaText = '';
            if($media['photos']['totalPhotos'] > 0)
                $mediaText .= 'Photos ';
            if(count($media['videos']['Videos']) > 0 && !empty($mediaText))
                $mediaText .= ' & Videos';
            if(count($media['videos']['Videos']) > 0 && empty($mediaText))
                $mediaText .= 'Videos';
            $countText = $totalMedia;//$totalMedia > 3 ? 3 :
         ?>
             <h2 class="color-3 f16 heading-gap font-w6">Gallery <span class="pos-rl color-9 f12">(Showing 1 of <?php echo $totalMedia;?>  <?php echo $mediaText;?>)</span></h2>
             <div class="data-card m-5btm">
                 <div class="card-cmn color-w">
                     <div>
                         <div>
                             <amp-carousel id="carousel-with-carousel-preview" class="m-n ga-analytic" data-vars-event-name="GALLERY" width="400" height="300" layout="responsive" type="slides">
                            <?php
                                foreach($media['photos']['order'] as $orderKey => $orderValue) {
                                        foreach ($media['photos']['list'][$orderValue] as $photoKey => $photoValue) {
                                    ?>
                                        <div class="slide">
                                            <amp-img class="no-out" src="<?php echo $photoValue['media_url'];?>" on="tap:lightbox11" role="button" tabindex="0" width="400" height="300" layout="fill" alt="a sample image"></amp-img>
                                                <div class="caption gal-cap">
                                                    <?php
                                                    $title = $orderValue;
                                                    if(!empty($photoValue['media_title']))
                                                    {
                                                        if($title == 'Photos'){
                                                            $title = $photoValue['media_title'];
                                                        }else{
                                                            $title .= ' - '.$photoValue['media_title'];
                                                        }

                                                    }
                                                    echo htmlentities($title);?>
                                                </div>
                                        </div>
                            <?php       }
                                    }
                                    foreach($media['videos']['Videos'] as $videoKey => $videoValue) {
                                        //$videoUrl = str_replace("http://www.youtube.com/v/","https://www.youtube.com/embed/",$videoValue['media_url']);
                                        $videoUrl =substr($videoValue['media_url'], strpos($videoValue['media_url'], "/v/") + 3);
                                        ?>
                                        <div class="slide">
                                            <amp-youtube class="no-out"
                                                data-videoid="<?=$videoUrl;?>"
                                                layout="responsive"
                                                width="400" height="300"></amp-youtube>

                                        </div>


                                             <!--<amp-iframe width="400"
                                                height="300"
                                                layout="responsive"
                                                sandbox="allow-scripts allow-same-origin allow-popups"
                                                frameborder="0"
                                                allowfullscreen
                                                src="<?=$videoUrl;?>">
                                              </amp-iframe> -->
                            <?php    }
                            ?>
                             </amp-carousel>
                             <amp-carousel class="carousel-preview ga-analytic" data-vars-event-name="GALLERY" width="auto" height="48" layout="fixed-height" type="carousel">
                                <?php
                                $imageCount = 0;
                                foreach($media['photos']['order'] as $orderKey => $orderValue) {
                                        foreach ($media['photos']['list'][$orderValue] as $photoKey => $photoValue) {

                                    ?>
                                             <button on="tap:carousel-with-carousel-preview.goToSlide(index=<?=$imageCount;?>)">
                                                 <amp-img src="<?php echo $photoValue['thumbnail_url'];?>" width="60" height="40" layout="responsive" alt="a sample image"></amp-img>
                                             </button>
                            <?php
                                    $imageCount++;
                                        }
                                    }
                                    foreach($media['videos']['Videos'] as $videoKey => $videoValue) {
                            ?>
                                             <button on="tap:carousel-with-carousel-preview.goToSlide(index=<?=$imageCount;?>)">

                                             <?php
                                             if(empty($videoValue['thumb_url'])) {
                                                $videoId = substr($videoValue['media_url'], strpos($videoValue['media_url'], "www.youtube.com/v/") + 18);
                                                $videoValue['thumb_url'] = 'https://i1.ytimg.com/vi/'.$videoId.'/0.jpg';
                                              } ?>
                                                 <amp-img src="<?php echo $videoValue['thumb_url'];?>" width="60" height="40" layout="responsive" alt="a sample image"></amp-img>
                                             </button>
                                            <?php
                                                $imageCount++;
                                            }
                                        ?>
                             </amp-carousel>
                         </div>
                     </div>
                 </div>
         </section>

<?php if($photosExist) { ?>
<amp-image-lightbox id="lightbox11" layout="nodisplay"></amp-image-lightbox>
<?php } ?>
