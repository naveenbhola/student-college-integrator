<?php
$GA_Tap_On_Link = 'RECO_VIEW_LISTING_LINK';
 if(count($dataArray) > 0){ ?>
<div class="group-card gap rght-crd" style="width:396px;height:auto;">
    <h2 class="rgt-title">You may be interested in <?=$pageType?> on</h2>
    <ul style="margin-top:10px;">
        <?php
            $i = 0;
            foreach ($dataArray as $recommendation){ ?>
        <li><a href="<?=$recommendation['URL']?>" ga-attr="<?=$GA_Tap_On_Link;?>"><?=$recommendation['listingName']?> (<?=$recommendation['contentCount']?>)</a></li>
        <?php
            $i++;
            if($i == 5)
                break;
            } ?>
    </ul>
    <?php if($pageType == 'reviews'){ ?>
    <div class="btm-fixed-div no-m-top" style="padding-top:10px;">
        <h2 class="rgt-title">Search similar college reviews</h2>
            <input type="text" placeholder="College Name" name="txtSelectOption" value="" id="lReviewSearch" spellcheck="false">
            <i class="search-icon" id="rSearch"></i>
            <ul id="reviewSearch_container" style="list-style: none; display: none;" class="suggestion-box tag-suggestions reviewSearch_container"></ul>
    </div>
    <p class="review_suggestion_error" id="review_suggestion_error" style="display: none;">Please select a college from suggestions</p>
    <?php } ?>
</div>
<?php } ?>

