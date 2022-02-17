<div class="crs-widget listingTuple" id="sponsoredWidget" style="display: none;">
    <h2 class="head-L2">Latest Admission Alert <span class="sponsered-tag">Sponsored</span></h2>
    <div class="lcard adms-alrtList">
    <ul>
        <?php 
            foreach ($sponsoredWidgetData as $key => $value) { ?>
                <li>
                    <strong><?=$value['name'];?></strong>
                    <p><?=$value['description'];?></p>
                    <a rel="nofollow" href="<?=$value['url'];?>"><?=$value['ctaName'];?></a>
                </li>        
        <?php } ?>
    </ul>
    </div>
</div>