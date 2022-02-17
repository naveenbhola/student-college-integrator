<div class="search-widget">
    <div class="search__block institute-slider" >
        <h3 class="col-heading s-hide ">
            Learn more about...
            <p class="slider-cntrl"> <i class=" i-left  i-left-disable "></i> <i class="i-right"></i> </p>
        </h3>
        <div class="find-que top-m ">
            <div class="slider-col-tab">
                <ul class="slide-col-ul">
                    <?php 
                    foreach ($widgetInstituteData as $instituteId => $widgetData) {
                        ?>
                        <li>
                            <div class="data-cols">
                                <div class="main-divs">
                                    <div class="slide-img">
                                        <a href="<?php echo $widgetData['instituteUrl']; ?>">
                                            <img class="lazy" data-original="<?php echo $widgetData['imageUrl']; ?>" style="width: 60px;height: 48px;">
                                        </a>
                                    </div>
                                    <div class="slide-text">
                                        <p class="inf-txts"><?php echo $widgetData['instituteName']; ?></p>
                                        <span class="sc-loc"><?php echo $widgetData['mainLocation']['city']; ?></span>
                                        <a href="<?php echo $widgetData['instituteUrl']; ?>" ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_ViewCollegeDetails" class="link-col">View College Details</a>
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
                                                    <li><a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_Reviews"  href="<?php echo $widgetData['reviewUrl']; ?>"><?php echo '<strong>'.$widgetData['reviewCount'].'</strong> Student Review'; echo ($widgetData['reviewCount'] > 1) ? 's' : ''; ?></a></li>
                                                    <?php
                                                }
                                                if(!empty($widgetData['anaCount'])){
                                                    ?>
                                                    <li><a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_QnA"  href="<?php echo $widgetData['anaUrl']; ?>"><?php echo '<strong>'.formatNumber($widgetData['anaCount']).'</strong> Answered Question'; echo ($widgetData['anaCount'] > 1) ? 's' : ''; ?></a></li>
                                                    <?php
                                                }
                                                if(!empty($widgetData['articleCount'])){
                                                    ?>
                                                    <li><a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_NewsnArticles"  href="<?php echo $widgetData['articleUrl']; ?>"><?php echo '<strong>'.$widgetData['articleCount'].'</strong> News & Articles'; ?></a></li>
                                                    <?php
                                                }
                                                if(!empty($widgetData['showAdmissionLink'])){
                                                    ?>
                                                    <li><a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_Admission_Process"  href="<?php echo $widgetData['admissionUrl']; ?>">Admission Process</a></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }

                                    ?>
                                </div>

                                <div class="most-viewd">
                                    <?php 
                                    if(!empty($widgetData['topCourses'])){
                                        ?>
                                        <h3 class="most-txt">Most viewed courses</h3>
                                        <ul class="mvie-courses">
                                            <?php 
                                            foreach ($widgetData['topCourses'] as $value) {
                                                ?>
                                                <li><p><a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_MostViewedCourses" title="<?php echo $value['name']; ?>"  href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></p></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                    <div class="dwn-btns-col">
                                        <a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_ViewAllCourses"  href="<?php echo $widgetData['allCoursesUrl']; ?>" class="ana-btns f-btn">View All Courses <span>(<?php echo formatNumber($widgetData['allCoursesCount']); ?>)</span></a>
                                        <a ga-attr="Institute_Cart" ga-optlabel="Desktop_Institute_Cart_DownloadBrochure"  href="javascript:void(0);" onclick="ajaxDownloadEBrochure(this,<?php echo $widgetData['instituteId'];?>,'<?php echo $widgetData['listingType']; ?>','<?php echo addslashes(htmlentities($widgetData['instituteName']));?>','entityWidget_<?php echo $pageType; ?>','<?php echo $widgetTrackingKeyId;?>','','','','')" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" class="ana-btns a-btn">Download Brochure</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                    // exam cards
                    foreach ($examCardData as $key => $examData) {
                        ?>
                        <li>
                            <div class="data-cols">
                                <div class="main-divs">
                                    <div class="slide-text">
                                        <p class="inf-txts"><?php echo $examData['examName']; ?> Exam</p>
                                        <a href="<?php echo $examData['url']; ?>" ga-attr="Exam_Card" ga-optlabel="Desktop_EXAM_CARD_VIEWEXAMDETAILS" class="link-col">View Exam Details</a>
                                    </div>
                                </div>

                                <div <?php if(empty($examData['anaData'])){?> style="margin-top: 15px;" <?php }?>>
                                    <?php 
                                    if(!empty($examData['anaData'])){
                                        ?>
                                        <div class="border-class">
                                            <ul class="widget-li">
                                                
                                                    <li style="width: 100%"><a ga-attr="Exam_Card_Ana" ga-optlabel="Desktop_EXAM_CARD_ANA"  href="<?php echo $examData['anaData']['allQuestionURL']; ?>"> <strong><?php echo $examData['anaData']['totalNumber'];?></strong> Answered Questions</a></li>
                                            </ul>
                                        </div>
                                        <?php
                                    }?>
                                </div>

                                <div class="most-viewd">
                                    <?php 
                                    if(!empty($examData['sections'])){
                                        ?>
                                        <h3 class="most-txt">Most viewed information</h3>
                                        <ul class="mvie-courses">
                                            <?php 
                                            foreach ($examData['sections'] as $value) {
                                                if(!empty($value['url'])){
                                                ?>
                                                <li><p><a ga-attr="Exam_Card_Section" ga-optlabel="Desktop_EXAM_CARD_VIEWED_INFORMATION" title="<?php echo $value['name']; ?>"  href="<?php echo $value['url']; ?>"><?php echo $value['name']; ?></a></p></li>
                                                <?php } 
                                            }?>
                                        </ul>
                                        <?php
                                    }
                                    ?>
                                    <div class="dwn-btns-col">
                                        <?php if($examData['isGetSamplePaper']){?>                                   
                                            <a ga-attr="Exam_Card" ga-optlabel="Desktop_EXAM_CARD_GET_SAMPLEPAPERS"  href="javascript:void(0);" class="ana-btns f-btn dwn-esmpr" data-trackingkey="1389" data-groupId="<?php echo $examData['groupId'];?>" data-url="<?php echo $examData['url']; ?>">Get Question Papers</a>
                                        <?php }?>

                                        <a ga-attr="EXAM_Card" ga-optlabel="Desktop_EXAM_CARD_DOWLOAD_GUIDE"  href="javascript:void(0);" class="ana-btns a-btn dwn-eguide <?php if($examData['isGuideDownloaded']){?> ecta-disable-btn <?php }?>dgub<?php echo $examData['groupId'];?>" data-trackingkey="1393" data-groupId="<?php echo $examData['groupId'];?>">Download Guide</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
    
                </ul>
            </div>
        </div>
    </div>
</div>
