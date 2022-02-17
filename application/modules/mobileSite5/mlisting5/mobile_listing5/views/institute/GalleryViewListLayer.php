    <div class="gal-layer">
        <div class="gal-hd">
            <p class="para-L2">Gallery <a href="#" onclick="closeGalleryViewListLayer()">&times;</a></p>
            
        </div>
        <div class="glry-div" id="gallery-div">
            <?php 
            if($listing_type == 'university')
            {
                $GA_currentPage = 'UNIVERSITY DETAIL PAGE';
                $GA_Tap_On_Gallery = 'TAP_ON_GALLERY_UNIVERSITYDETAIL_MOBLDP';
            }
            else
            {
                $GA_currentPage = 'INSTITUTE DETAIL PAGE';
                $GA_Tap_On_Gallery = 'TAP_ON_GALLERY_INSTITUTEDETAIL_MOBLDP';
            }
            $displayedTags = 0;
            foreach($media['photos']['order'] as $oKey => $oValue) {?>
                <div class="galry-block">
                    <strong id="galleryText_<?php echo $displayedTags;?>"><?php echo $oValue;?></strong>
                    <ul>
                        <li>
                        <?php foreach($media['photos']['list'][$oValue] as $photoKey => $photoValue) {?>
                            <div class="img-blck"><a href="#galleryDetailList" data-param="714" data-inline="true" data-rel="dialog" data-transition="fade" ><img alt="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$photoValue['media_title']);?>" src="<?php echo $photoValue['thumbnail_url'];?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Gallery;?>','<?php echo $GA_userLevel;?>');openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $photoValue['media_id'];?>,'<?php echo $oValue;?>','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')" width="94" height="76"></a></div>
                        <?php } ?>
                        </li>
                    </ul>
                </div>
            <?php $displayedTags++;} ?>
            <?php if(count($media['videos']['Videos']) > 0) {?>
                    <div class="galry-block">
                        <strong id="galleryText_<?php echo $displayedTags;?>">Videos</strong>
                        <ul>
                            <li>
                                <?php foreach($media['videos']['Videos'] as $videoKey => $videoValue) {?>
                                        <div class="img-blck"><a href="#galleryDetailList" data-param="714" data-inline="true" data-rel="dialog" data-transition="fade" ><img alt="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" title="<?php echo htmlentities($listingGallerytext.' - '.$videoValue['media_title']);?>" src="<?php echo $videoValue['thumb_url'];?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Gallery;?>','<?php echo $GA_userLevel;?>');openGalleryDetailLayer(<?php echo $listing_id;?>,'<?php echo $listing_type?>',<?php echo $videoValue['media_id'];?>,'Videos','<?php echo $currentCityId;?>','<?php echo $currentLocalityId;?>')" width="94" height="76"></a></div>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                <?php $displayedTags++;} ?>
        </div>
    </div>
<script>
    var displayedTags = <?php echo $displayedTags;?>;
    if(displayedTags == 1 && ($('#galleryText_0').text() == 'Others'))
    {
        $('#galleryText_0').attr('style','visibility:hidden');
    }
</script>