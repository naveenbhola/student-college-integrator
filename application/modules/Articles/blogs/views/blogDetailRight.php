                        <div class="wdh100">
                                <!--Start_Banner-->
                                <div align="center">
                                <?php
                                        $bannerProperties = array('pageId'=>'ARTICLES_DETAILS', 'pageZone'=>'SIDE');
                                        $this->load->view('common/banner', $bannerProperties);
                                 ?>
                                </div>
                                <!--End_Banner-->
                                <div class="spacer20 clearFix">&nbsp;</div>

                                <!--Start_Registration Widget-->
                                <div class="" style="background:url(/public/images/article-reg-pic.gif) 15px 0 no-repeat;height:120px;width:100%;position:relative;margin-bottom:-65px;">
                                </div>
                                <div class="box-shadow" style="margin-bottom:20px;padding-left:25px;padding-right:23px;padding-bottom:5px;padding-top:50px;">
                                <?php
                                    //Code added to add Floating registration widget on Article page
                                    echo "<script>var floatingRegistrationSource = 'ARTICLE_FLOATINGWIDGETREGISTRATION';</script>";
                                    if($country_id > 2)
                                           echo "<script>var studyAbroad = 1;</script>";
                                    else
                                           echo "<script>var studyAbroad = 0;</script>";
                                    if(intval($country_id) > 2){
                                           $displayData['form'] = 'studyAbroad';
                                    }
                                    $displayData['trackingPageKeyId']=$regTrackingPageKeyId;
                                    $this->load->view('blogs/rightRegistrationWidget',$displayData); 
       
                                ?>
                                <?php //$this->load->view('listing_forms/widgetConnectInstituteLarge'); ?>
                                
                                </div>
                                <!--End_Registration Widget-->
                               <?php 
                                if($CategoryId == 23) {
                                        $this->load->view('RecentActivities/ReviewRHSWidget');
                                } 
                                ?>                                                   
                                <div class="clearFix"></div>

                                <!-- Calendar Widget-->
                                <?php if($CategoryId == 23 || $CategoryId == 56)
                                {
                                                if($CategoryId == 23)
                                                {
                                                    $categoryName='mba';
                                                }
                                                else
                                                {
                                                    $categoryName='engineering';
                                                }
												$fromWhereForCalendar = 'articlePageRight';
                                                echo Modules::run('event/EventController/getCalendarWidget',$categoryName, $fromWhereForCalendar);
                                }
                                ?>
                                <!-- Calendar Widget end-->
                                <div class="clearFix"></div>
                                <!-- Start Cafe Buzz + Ask a Question widget -->
                                <!--<div class="box-shadow" style="margin-top:10px;margin-bottom:30px;padding:10px;">
                                        <div id="askCafeWidget"  uniqueattr="article_detail_page_cafeWidget">
                                        <script>
                                                var jsForWidget = new Array();
                                                jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_common"); ?>');
                                                jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("homePage"); ?>');
                                                addWidgetToAjaxList('/CafeBuzz/CafeBuzz/index/3','askCafeWidget',jsForWidget);
                                        </script>
                                        </div>

                                        <div id="AskAQuestionWidget" uniqueattr="article_detail_page_askAQues">
                                        <script>
                                                addWidgetToAjaxList('/AskAQuestion/AskAQuestion/index/categoryPageRight/0/<?php echo $cat_id_course_page; ?>/<?php echo $subcat_id_course_page; ?>','AskAQuestionWidget',Array());
                                        </script>
                                        </div>
                                        <div class="clearFix"></div>
                                </div>-->
                                <!-- End Cafe Buzz + Ask a Question widget -->

                                <!-- Related Discussion Widget -->
                                <?php //echo Modules::run('RelatedDiscussion/RelatedDiscussion/index', $unifiedCategoryId, $CategoryId, 1, 'Articles'); ?>
                                <?php //echo Modules::run('RelatedDiscussion/RelatedDiscussion/index', 3, $CategoryId, 1,'Articles'); ?>

                                    <!-- Floating Registration Widget --> 
                                  <div id="floatingRegister"> 
                                          <script> 
                                                  var jsForWidget = new Array(); 
                                                  jsForWidget.push('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("processForm"); ?>'); 
                                                  addWidgetToAjaxList('/FloatingRegistration/FloatingRegistration/index/false/1100/article-right-col/\'\'/true/0/institute/\'\'/<?php echo $regbottomTrackingPageKeyId;?>','floatingRegister',jsForWidget); 
                                          </script> 
                                 </div> 

                        </div>
