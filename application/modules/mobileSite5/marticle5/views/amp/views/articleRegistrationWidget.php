 <?php 
 if($validateuser == 'false' || empty($validateuser)){
        $widget = '';
        if($blogObj->getType() == 'boards'){
                $widget = 'BOARDS_STATIC_WIDGET';
        }
        else if($blogObj->getType() == 'coursesAfter12th'){
                $widget = 'CA12_STATIC_WIDGET';
        }
 ?> 
<section>
    <div class="art-crd art-intDv color-w">
        <div class="search-block txt-cntr">
            <h3 class="signup-h3">Taking an Exam? Selecting a College?</h3>
            <p class="inf-txts">Find insights & recommendations on colleges and exams that you <strong>won't</strong> find anywhere else</p>
            <a class="nw-btn ga-analytic" data-vars-event-name="RHS_REGISTRATION"  href="<?php echo SHIKSHA_HOME;?>/muser5/UserActivityAMP/getRegistrationAmpPage?actionType=applynow&fromwhere=<?php echo $pageType; ?>&entityId=<?php echo $blogId; ?>&entityType=blog&widget=<?=$widget?>">Sign Up & Get Started </a>
            <p class="background z-ind"><span>On Shiksha, get access to</span></p>
            <ul class="inf-li">
                <li><strong><?=$entityCount['collegeCount']?></strong> Colleges</li>
                <li><strong><?=$entityCount['examCount']?></strong> Exams</li>
                <li><strong><?=$entityCount['reviewCount']?></strong> Reviews</li>
                <li><strong><?=$entityCount['answerCount']?></strong> Answers</li>
            </ul>
        </div>
    </div>
</section>
<?php } ?>    
