<section>
    <div class="color-1">
        <h2 class="color-1 f16 heading-gap font-w6">Learn more about</h2>
        <div class="paddng5 slider-col-tab">
            <amp-carousel height="395" layout="fixed-height" type="carousel">
            <?php 
                foreach ($widgetInstituteData as $instituteId => $widgetData) {
                    ?>
                <figure class="color-w wh-sp n-mg pos-rl art-dv">
                    <div class="main-divs">
                        <div class="slide-img">
                            <a href="<?php echo $widgetData['instituteUrl']; ?>" class="block">
                                <amp-img src="<?php echo empty($widgetData['imageUrl']) ? MEDIAHOSTURL."/public/mobile5/images/recommender_dummy.png" : $widgetData['imageUrl']; ?>" width="60px" height="48px">
                                </amp-img>
                            </a>
                        </div>
                        <div class="slide-text">
                            <p class="inf-txts"><?php echo $widgetData['instituteName']; ?></p>
                            <span class="sc-loc"><?php echo $widgetData['mainLocation']['city']; ?></span>
                            <a href="<?php echo getAmpPageURL($widgetData['listingType'],$widgetData['instituteUrl']); ?>" data-vars-event-name="ViewCollegeDetails" data-vars-event-cat-name ="Amp_Institute_Cart" class="link-col ga-analytic">View College Details</a>
                        </div>
                    </div>
                    <div class="max__height">
                        <?php 
                        if(!empty($widgetData['anaCount']) || !empty($widgetData['articleCount']) || !empty($widgetData['reviewCount']) || !empty($widgetData['showAdmissionLink'])){
                            ?>
                        <div class="border-class">
                            <ul class="widget-li">
                                <?php 
                                if(!empty($widgetData['reviewCount'])){
                                    ?>
                                    <li><a class='ga-analytic' data-vars-event-name="Reviews" data-vars-event-cat-name ="Amp_Institute_Cart" href="<?php echo $widgetData['reviewUrl']; ?>"><?php echo '<strong>'.$widgetData['reviewCount'].'</strong> Student Review'; echo ($widgetData['reviewCount'] > 1) ? 's' : ''; ?></a></li>
                                    <?php
                                }
                                if(!empty($widgetData['anaCount'])){
                                    ?>
                                    <li><a class='ga-analytic' data-vars-event-name="QnA" data-vars-event-cat-name ="Amp_Institute_Cart"   href="<?php echo $widgetData['anaUrl']; ?>"><?php echo '<strong>'.formatNumber($widgetData['anaCount']).'</strong> Answered Question'; echo ($widgetData['anaCount'] > 1) ? 's' : ''; ?></a></li>
                                    <?php
                                }
                                if(!empty($widgetData['articleCount'])){
                                    ?>
                                    <li><a class='ga-analytic' data-vars-event-name="NewsnArticles" data-vars-event-cat-name ="Amp_Institute_Cart" href="<?php echo $widgetData['articleUrl']; ?>"><?php echo '<strong>'.$widgetData['articleCount'].'</strong> News &amp; Articles'; ?></a></li>
                                    <?php
                                }
                                if(!empty($widgetData['showAdmissionLink'])){
                                    ?>
                                    <li><a class='ga-analytic' data-vars-event-name="Admission_Process" data-vars-event-cat-name ="Amp_Institute_Cart"  href="<?php echo $widgetData['admissionUrl']; ?>">Admission Process</a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    <?php } ?>    
                    </div>
                    <div class="most-viewd">
                        <?php 
                        if(!empty($widgetData['topCourses'])){
                            ?>
                            <h3 class="most-txt f14 color-1 font-w7">Most viewed courses</h3>
                            <ul class="mvie-courses">
                                <?php 
                                foreach ($widgetData['topCourses'] as $value) {
                                    ?>
                                    <li><p><a class='ga-analytic' data-vars-event-name="MostViewedCourses" data-vars-event-cat-name ="Amp_Institute_Cart" href="<?php echo getAmpPageURL('course',$value['url']); ?>"><?php echo $value['name']; ?></a></p></li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                        <div class="dwn-btns-col">
                            <a data-vars-event-name="ViewAllCourses" data-vars-event-cat-name ="Amp_Institute_Cart" href="<?php echo $widgetData['allCoursesUrl']; ?>" class="ana-btns f-btn ga-analytic">View All Courses <span>(<?php echo formatNumber($widgetData['allCoursesCount']); ?>)</span></a>
                            <a data-vars-event-name="DownloadBrochure" data-vars-event-cat-name ="Amp_Institute_Cart" href="<?php echo SHIKSHA_HOME;?>/muser5/UserActivityAMP/getResponseAmpPage?listingType=<?php echo $widgetData['listingType']; ?>&listingId=<?php echo $widgetData['instituteId']; ?>&actionType=brochure&fromwhere=<?php echo $pageType; ?>&pos=rhsInstituteCard&entityId=<?php echo $entityId; ?>&entityType=<?php echo $entityType; ?>" class="ana-btns a-btn ga-analytic">Download Brochure</a>
                        </div>
                    </div>
                </figure>
            <?php } ?>   
            </amp-carousel>
        </div>
    </div>
</section>
