<section class="articleBanner">
    <div class="_cntr">
        <div class="articleContentSec">
            <div class="fltlft articleSlide">
                <div class="heading3">
                    <h2>FEATURED ARTICLES</h2>
                </div>
                <div class="sliderContainer homepageFeaturedArticleSlider">
                    <a class="leftArrow lftArwDsbl"><i></i></a>
                    <div class="slidingArea">
                        <ul class="featuredSlider">
                            <?php 
                            foreach ($featuredArticles as $pos => $val) {
                                $trimmedTitle = (strlen($val['blogTitle'])>75)?substr($val['blogTitle'], 0, 72).'...':$val['blogTitle'];
                                $imageURL = ($val['homepageImgURL'] == '')?'/public/images/articleSliderDefault.jpg':$val['homepageImgURL'];
                            ?>
                                <li>
                                    
                                    <div>
                                    <a href="<?php echo SHIKSHA_HOME.$val['blogUrl']; ?>" lang="en">
                                        <span>
                                            <img class="lazy" data-original="<?php echo MEDIA_SERVER.$imageURL; ?>" alt="<?php echo htmlentities($val['blogTitle']); ?>" title="<?php echo htmlentities($val['blogTitle']); ?>" />
                                        </span>
                                        <p><?php echo $trimmedTitle; ?></p>
                                    </a>
                                    </div>
                                   
                                </li>
                            <?php 
                            }
                            ?>
                        </ul>
                    </div>
                    <a class="rightArrow"><i></i></a>
                </div>
                <div class="right_button">
                    <a class="button button--orange" href="<?php echo SHIKSHA_HOME; ?>/news-articles">View All Articles</a>
                </div>
            </div>
            <?php echo Modules::run('shiksha/getRecentArticles', 4, 'homepage');?>
            <div class="clr"></div>
        </div>
    </div>
</section>