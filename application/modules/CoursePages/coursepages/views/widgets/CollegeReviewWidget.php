<div <?= $cssClass ?> id="<?= $widgetObj->getWidgetKey() . 'Container' ?>">
    <?php
    
    $widget_collegeReviewData = $widgets_data['CollegeReviewWidget']['widget_collegeReviewData'];
    $widgetForPage = $widgets_data['CollegeReviewWidget']['widgetForPage'];
    $loadCareerWidget = $widgets_data['CollegeReviewWidget']['loadCareerWidget'];
    $suggestorPageName = $widgets_data['CollegeReviewWidget']['suggestorPageName'];
    $jobsInfo=$widgets_data['CollegeReviewWidget']['jobsInfo'];
    ?>
    <h2><?= $widgetObj->getWidgetHeading(); ?></h2>
    <?php $this->load->view('home/homepageRedesign/autoSuggestorCollegeReviewWidget'); ?>
    <?php $this->load->view('home/homepageRedesign/autoSuggestorCampusConnectWidget'); ?>


    <div id="searchOpacityLayer"></div>
    <div id="searchReviewLayer" style="display: none;position:fixed;left: 30%;z-index: 10000;width:auto;"></div>

    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("homepageWidget"); ?>" type="text/css" rel="stylesheet" />
    <div style=" clear: both;"></div>
    <div style=" position: relative;">

        <div class="sugest-bx" id="tileSuggested" style="border: none;">
            <ul style="text-align: left; display: none;" id="suggestions_container"></ul>
            <ul class="cc-sugest" id="suggestedList"></ul>
        </div>
        <div class="sugest-bx" id="tileSuggestedCampusConnect" style="border: none;">
            <ul style="text-align: left; display: none;padding: 0;font: 500 12px/18px tahoma" id="suggestions_containerCampusConnect"></ul>
            <ul class="cc-sugest" id="suggestedListCampusConnect"></ul>
        </div>
        <div id="slider" style='width:100%;overflow:hidden; margin-bottom: 20px;'>
            <div id="controls" style='width:300%'>
                <div style='width:300%;overflow:hidden'>
                    <div class="slidescontainer" style='width:300%;position:relative;overflow:hidden;background-color:#fff;'>
                        <div class="cump-con">
                            <div class="cump-wid-sl-bt">
                                <a href="javascript:void(0)"  onclick='animateCustom(false)'>
                                    <i class="cr-home-sprite"  ></i>
                                </a>
                            </div>  
                            <ul class="cump-con-wid" id='main_slider_ul'>
                                <?php if(isset($widgets_data['CollegeReviewWidget']['reviewRatingInfo'])){?>
                                <li class='main_slider' onclick="trackEventByGA('TileClick', '<?= $widgetForPage ?>_COLLEGE_REVIEW_WIDGET')">
                                    <h4><?php  echo $widgets_data['CollegeReviewWidget']['reviewRatingInfo']['heading'] ?> </h4>
                                    <p class="stud"><?php echo $widgets_data['CollegeReviewWidget']['reviewRatingInfo']['subHeading'] ?></p>
                                    <div class="cump-wid-srch" style="position:relative" id="searchBox">
                                        <input type="text" placeholder="Search Reviews by College Name" name="keyword"  id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('', 'focus');" autocomplete="off" style='height: 25px;'/>
                                        <i class="cr-home-sprite" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME ?>', 'keywordSuggest')"></i>
                                    </div>
                                    <p class="cump-wid-ask">Popular Review Collection</p>
                                    <div>
                                        <div class="cump-wid-rank links">
                                            <?php
                                            $count = 0;
                                            foreach ($widget_collegeReviewData as $data) {
                                                ?>
                                                <a class='link<?php echo $count ?>' href="<?php echo $data['seoUrl']; ?>" class="cump-wid-tp" style='display: none;'><?php echo $data['title']; ?></a>
                                                <span class='break<?php echo $count ?>' style='display: none'>&nbsp;<br></span>

                                                <?php $count++; ?>
                                                <?php
                                            }
                                            ?>


                                        </div> 
                                        <div class="cump-wid-viw" style="margin-top: 19px;">
                                            <a href="<?php echo base_url().$widgets_data['CollegeReviewWidget']['reviewRatingInfo']['viewAllUrl'];?>">View All</a>
                                        </div> 
                                    </div>
                                </li>
                                <?php }
                                if(isset($widgets_data['CollegeReviewWidget']['campusConnectInfo'])){?>
                                <li  class='main_slider' onclick="trackEventByGA('TileClick', '<?= $widgetForPage ?>_CAMPUS_CONNECT_WIDGET')">
                                    <h4><?php  echo $widgets_data['CollegeReviewWidget']['campusConnectInfo']['heading'] ?></h4>
                                    <p class="stud"><?php  echo $widgets_data['CollegeReviewWidget']['campusConnectInfo']['subHeading']; ?></p>
                                    <div class="cump-wid-srch" id='searchBoxCampusConnect'>
                                        <input type="text" placeholder="Enter College Name to get Connected" name="keyword" id="keywordSuggestCampusConnect" minlength="1" autocomplete="off" style='height: 25px;'/>
                                        <i class="cr-home-sprite" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME ?>', 'keywordSuggestCampusConnect')"></i>
                                    </div>
                                    <p class="cump-wid-ask">Explore what others are asking</p>
                                    <a href="<?php echo base_url().$widgets_data['CollegeReviewWidget']['campusConnectInfo']['explore']['1']['url']; ?>" class="cump-wid-tp"><?php  echo $widgets_data['CollegeReviewWidget']['campusConnectInfo']['explore']['1']['caption'];?></a>
                                    <div>
                                        <div class="cump-wid-rank">
                                            <a href="<?php echo base_url().$widgets_data['CollegeReviewWidget']['campusConnectInfo']['explore']['2']['url'];?>"><?php  echo $widgets_data['CollegeReviewWidget']['campusConnectInfo']['explore']['2']['caption'];?></a>
                                        </div> 
                                        <div class="cump-wid-viw">
                                            <a href="<?php echo base_url().$widgets_data['CollegeReviewWidget']['campusConnectInfo']['explore']['viewAllUrl'];?>">View All</a>
                                        </div> 
                                    </div>
                                </li>
                                <?php }
                                if(isset($widgets_data['CollegeReviewWidget']['jobsInfo'])){?>
                                <li  class='main_slider' onclick="trackEventByGA('TileClick', '<?= $widgetForPage ?>_CAREER_COMPASS_WIDGET')">
                                    <h4><?php  echo $widgets_data['CollegeReviewWidget']['jobsInfo']['heading']; ?></h4>
                                    <div class="stud"><?php  echo $widgets_data['CollegeReviewWidget']['jobsInfo']['subHeading']; ?></div>
                                    <p class="cump-wid-bst">Find Best colleges to get a job in:</p>
                                    <ul class="cump-wid-info">
                                        <?php 
                                                                                    foreach ($jobsInfo['column1'] as $columnDetails){
                                        ?>
                                        <li><a href="<?php echo $columnDetails['url']; ?>"><?php echo $columnDetails['name'];?></a></li>
                                        <?php }?>
                                        
                                    </ul>
                                    <ul class="cump-wid-info">
                                           <?php 
                                                                                    foreach ($jobsInfo['column2'] as $columnDetails){
                                                                                        if(isset($columnDetails['cookieDetails'])){
                                        ?>
                                        <li><a href="javascript:void(0)" onClick="setCookie('<?php echo $columnDetails['cookieDetails']['name'];?>', '<?php echo $columnDetails['cookieDetails']['value'];?>', 3000, '/', COOKIEDOMAIN); window.location = '<?= $columnDetails['url'] ?>'"><?php echo $columnDetails['name']; ?></a></li>
                                                                                        <?php }
                                                                                        else{
                                                                                     ?>
                                          <li><a href="<?php echo $columnDetails['url']; ?>"><?php echo $columnDetails['name'];?></a></li>
                                                                                         <?php       
                                                                                        }
                                                                                        }?>
                                    </ul>
                                </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php foreach ($widget_collegeReviewData as $data) { ?>
        <h2 class="tileText" style='display: none;'>
            <a class="tileFinder" href="<?php echo $data['seoUrl'] ?>"><?php echo $data['title'] ?></a>
        </h2>
    <?php } ?>

</div>

