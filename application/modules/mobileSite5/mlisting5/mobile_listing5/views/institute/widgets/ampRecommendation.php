<?php if(!empty($RecommendedListingData))
{ ?>
<section>
    <div class="tool-crd color-1">
        <h2 class="color-1 f16 heading-gap font-w6"><?php echo $widgetHeading; ?></h2>
        <div class="pad-lft">
            <amp-carousel height="256" layout="fixed-height" type="carousel">
                <?php 
                    foreach($RecommendedListingData as $key=>$data){
                        ?>
                        <figure class="color-w paddng5 dv-wdt n-mg">
                            <div>
                                <amp-img src="<?php echo empty($data['image_url']) ? MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png" : $data['image_url']; ?>" height="105"></amp-img>
                            </div>
                            <figcaption class="f12 color-6 wh-sp m-5top">
                                <a class="f14 ins-hgt ga-analytic" data-vars-event-name="similar_institute" href="<?php echo getAmpPageURL('institute',$data['institute_url']); ?>">
                                    <?php echo substr(htmlentities($data['institute_name']),0,50); ?>
                                    <?php if(strlen(htmlentities($data['institute_name'])) > 50){echo '...'; } ?>
                                </a>
                                <p class="cty-hgt">
                                    <?php 
                                    if(!empty($data['establish_year']) || !empty($data['main_location'])){
                                        if(!empty($data['main_location'])){ echo $data['main_location'];}           
                                        if(!empty($data['establish_year']) && !empty($data['main_location'])){ 
                                            echo ' | '.'Estd. '.$data['establish_year'];
                                        }else{
                                            echo $data['establish_year'];
                                        }
                                    }
                                    ?>
                                </p>
                                <a href="<?php echo SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?php echo $listing_type; ?>&listingId=<?php echo $data['institute_id']; ?>&actionType=brochure&fromwhere=<?php echo $pageType; ?>&pos=alsoViewed&entityId=<?php echo $entityId; ?>&entityType=<?php echo $entityType; ?>"  class="req-btn ga-analytic" data-vars-event-name="DBROCHURE_STICKY">Request Brochure</a>
                            </figcaption>
                        </figure>
                        <?php
                    }
                ?>
            </amp-carousel>
        </div>
    </div>        
</section>
<?php } ?>
