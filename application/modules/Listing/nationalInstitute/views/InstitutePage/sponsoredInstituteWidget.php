<div class="new-row" id="sponsoredWidget" style="display: none;">
        <div class="group-card no__pad gap listingTuple pdng0">
            <h2 class="head-1 gap">Latest Admission Alert <span class="spnsrd-tag" style="">Sponsored</span></h2>
            <div class="gap adms-alrtList">
                <ul>
                    <?php 
                        foreach ($sponsoredWidgetData as $key => $value) { ?>
                            <li>
                                <strong><?=$value['name'];?></strong>
                                <p><?=$value['description'];?></p>
                                <a rel="nofollow" href="<?=$value['url'];?>" target="_blank"><?=$value['ctaName'];?></a>
                            </li>        
                    <?php } ?>
                </ul>
            </div>

        </div>
</div>