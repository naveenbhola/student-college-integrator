<div class="crs-widget listingTuple intrlnkng-wdgt" class="hid">
        <h2 class="head-L2 intrstd-head">You may be interested in the following:</h2>
        <div class="intrstd-clgWdgt">
            <ul>
            <?php
                foreach ($categoryPageInterlinkgUrls as $interlinkingUrl) { 
            ?>
            <li><a ctpg-key="<?=$categoryPagekey?>" class="link-blue-small" href="<?=$interlinkingUrl['url'];?>"><?=$interlinkingUrl['heading'];?></a></li>
            <?php
                }
            ?>
            </ul>
        </div>
</div>