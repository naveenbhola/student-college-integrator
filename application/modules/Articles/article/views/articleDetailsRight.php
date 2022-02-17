<div class="wdh100">
    <?php 
    if($validateuser == 'false'){
    ?>
    <div class="search-block txt-cntr">
        <h3 class="signup-h3">Taking an Exam? Selecting a College?</h3>
        <p class="inf-txts">Find insights & recommendations on colleges and exams that you <strong>won't</strong> find anywhere else</p>
        <a id="_article-login" class="nw-btn">Sign Up & Get Started</a>
        <p class="background-brdr"><span>On Shiksha, get access to</span></p>
        <ul class="inf-li">
        <li> <strong><?=$entityCount['collegeCount']?></strong> Colleges</li>
        <li> <strong><?=$entityCount['examCount']?></strong> Exams</li>
        <li> <strong><?=$entityCount['reviewCount']?></strong> Reviews</li>
        <li> <strong><?=$entityCount['answerCount']?></strong> Answers</li>
        </ul>
    </div>
    <?php 
    }
    ?>
    
    <?php 
    echo Modules::run("Interlinking/InterlinkingFactory/getEntityRHSWidget", $relatedEntityIds,'articleDetailPage');
    if($streamCheck == 'fullTimeMba') {
            $this->load->view('RecentActivities/ReviewRHSWidget');
    } 
    ?>                                                   
    <div class="clearFix"></div>

    <!-- Calendar Widget-->
<?php 
    //if($streamCheck == 'fullTimeMba' || $streamCheck == 'beBtech')
    //{ 
                //$fromWhereForCalendar = 'articlePageRight';
//        echo Modules::run('event/EventController/getCalendarWidget',$fromWhereForCalendar, $eventCalfilterArr);
        	echo Modules::run("Interlinking/ExamWidget/getUpcomingExamDatesWidget", $relatedEntityIds,'articleDetailPageDesktop');
    //}
    ?>

    <!-- Calendar Widget end-->

    <div class="clearFix">&nbsp;</div>

    <!--Start_Banner-->
    <!--
    <div align="center">
    <?php
            //$bannerProperties = array('pageId'=>'ARTICLES_DETAILS', 'pageZone'=>'SIDE');
            //$this->load->view('common/banner', $bannerProperties);
     ?>
    </div>
    -->
    <!--End_Banner-->

    <?php
        if($blogObj->getType() == 'boards' || $blogObj->getType() == 'coursesAfter12th'){
            echo Modules::run('article/ArticleController/getCustomInterlinkingWidget',$blogObj->getType());
        }
    ?>

    <?php $this->load->view('dfp/dfpCommonRPBanner',array('bannerPlace' => 'RP','bannerType'=>"rightPanel")); ?>

    <div class="clearFix"></div>
</div>
