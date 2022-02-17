<?php if(count($articleWidgetsData) > 0) { ?>
    <section class="top__18">
        <h2 class="f16__semi clr__1 lh__18">Articles about Top <?php echo $rankingPage->getName();?> Colleges and Courses</h2>
        <div class="clear__float white__bg top__12">
            <ul class="article__ul">
                <?php foreach($articleWidgetsData as $key => $val) { ?>
                    <?php if($key > 3) {$class = 'hid';} ?>
                    <li class="articleLinks <?php echo $class; ?>">
                        <a class="f14__semi clr__1 fit__block" href="<?=$val['url'];?>"  ga-attr="ARTICLEINTERLINK" ><?php echo cutString(strip_tags($val['artcileTitle']), 59); ?></a>
                    </li>
                <?php } ?>
            </ul>

            <?php if(count($articleWidgetsData) > 4) {?>
                <a id="viewAllArticles" class="ranking__btns __transbtn">View All Articles</a>
            <?php }?>
        </div>

    </section>
<?php } ?>