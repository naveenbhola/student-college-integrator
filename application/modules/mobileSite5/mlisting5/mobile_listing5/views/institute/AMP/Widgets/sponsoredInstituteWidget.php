<section>
    <div class="data-card m-5btm pos-rl">
        <h2 class="color-3 f16 heading-gap font-w6">Latest Admission Alert <span class="sponsered-tag">Sponsored</h2>
        <div class="card-cmn color-w">
            <div class="adms-alrtList">
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
    </div>
</section>
