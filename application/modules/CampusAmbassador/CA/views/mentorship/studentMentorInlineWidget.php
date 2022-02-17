<?php
if($frompage == 'rankingpage'){
    $gaTracking1 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_FIRST_SLIDE_ENGINEERING_RANKING_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_FIRST_SLIDE_RANKING_PAGES');";
    $gaTracking2 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_SECOND_SLIDE_ENGINEERING_RANKING_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_SECOND_SLIDE_RANKING_PAGES');"; 
    $gaTracking3 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_THIRD_SLIDE_ENGINEERING_RANKING_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_THIRD_SLIDE_RANKING_PAGES');"; 
}
if($frompage == 'coursepages')
{
    $gaTracking1 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_FIRST_SLIDE_ENGINEERING_COURSE_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_FIRST_SLIDE_COURSE_PAGES');";
    $gaTracking2 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_SECOND_SLIDE_ENGINEERING_COURSE_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_SECOND_SLIDE_COURSE_PAGES');";
    $gaTracking3 = "trackEventByGA('GET_A_MENTOR_INLINE_WIDGET_THIRD_SLIDE_ENGINEERING_COURSE_PAGES_WIDGET_CLICK','GET_A_MENTOR_INLINE_WIDGET_THIRD_SLIDE_COURSE_PAGES');";
}

if($isMentee != 1){ ?>
<div class="mentorship-inline-slider" style="overflow:hidden; <?php if($frompage == 'rankingpage'){ ?> margin-left:145px; <?php } ?>">
	<ul id="slideContainerMentor" style="<?php if($frompage == 'engineeringArticles' || $frompage == 'questionDetailCafe' || $frompage == 'questionDetailInstitute'){ ?>width:1920px; <?php }else {?>width:2021px;<?php }?> position: relative; left:0px;">
    	<li id="slideMentor1">
   	    	<img src="/public/images/mentorship-widget-img.jpg" width="358" height="293" alt="mentorship-img" class="flLt" <?php if($frompage == 'coursepages' || $frompage == 'rankingpage'){?> style="position:relative; top:15px"<?php } ?> />
            <div class="flRt mentor-details">
            	<p class="planning-title" <?php if($frompage == 'coursepages' || $frompage == 'rankingpage'){?>style="margin-top:25px;"<?php } ?>>Planning for Engineering..<br/><span style="font-size:18px;">And confused? </span></p>
                <p class="get-mentor-title">Get a mentor ( a current engineering student)</p>
                <p>Who will guide you through the entire engineering preparation and college selection process.</p>
                <a class="get-mentor-btn-2" href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $tracking_keyid; ?>#menteeform"; target="_blank"; onclick="<?=$gaTracking1?>;">GET A MENTOR</a>
                <?php if($frompage == 'engineeringArticles' || $frompage == 'questionDetailCafe' || $frompage == 'questionDetailInstitute')
                   {    
                        $marginpFirstSlide = 15;
                   }
                   else
                         $marginpFirstSlide = 25; 
                    ?>
                <p class="font-11" style="margin-left:<?php echo $marginpFirstSlide; ?>px;">This is a free program</p>
                <a href="javascript:void(0)" class="flRt font-14" onclick="mentorSlideRight();">See who are these mentors?  >></a>
            </div>
            <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
            <div class="clearFix"></div>
        </li>

        <li id="slideMentor2" class="slider-padding">
   	    	<p class="mentor-slider-title">Who are these mentors? </p>
            <div class="clear-width tac"><i class="mentorship-slider-sprite mentor-student-icon"></i></div>
            <h3 class="current-eng-title"><span class="current-count"><?php if($totalMentorCount>100) echo $totalMentorCount;?></span> Current Engineering Students</h3>
            <ul class="mentor-count-list">
                <li>Studying in<br> various branches</li>
                <li>From top <br>colleges like IITs, NITs..</li>
                <li class="last">Spread <br>across 22 states</li>
            </ul>
            <div class="clearFix tac">
                <a class="get-mentor-btn-2" style="margin:0 0 5px 0; line-height:27px" href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $tracking_keyid; ?>#menteeform" target="_blank"; onclick="<?=$gaTracking2?>;">GET A MENTOR</a>
            </div>
            <div class="clearFix font-14">
            	<a href="javascript:void(0)" class="flLt font-14" onclick="mentorSlideLeft();"><< Back</a>

                <?php
                   if($frompage == 'engineeringArticles' || $frompage == 'questionDetailCafe' || $frompage == 'questionDetailInstitute')
                   {
                        $marginSpanSecondSlide = 204;
                   }
                   else
                         $marginSpanSecondSlide = 200; ?>

                <span class="font-11 tac" style="color:#a6a6a6; margin-left:<?php echo $marginSpanSecondSlide?>px">This is a free program</span>
                <a href="javascript:void(0)" class="flRt" onclick="mentorSlideRight();">See How it works >></a>
            </div>
            <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
        </li>
        
        <li id="slideMentor3" class="slider-padding">
   	    	<p class="mentor-slider-title">How does it work?</p>
            <div class="dedicated-mentor-widget">
    			<ul>
                   <li>
                        <div class="mentorship-head">
                            <strong class="flLt">Enroll</strong>
                            <i class="mentorship-slider-sprite mentor-enroll-icon flRt"></i>
                            <div class="clearfix"></div>
                         </div>
                        <div class="mentorship-info">
                        	Submit your details along <br>with preferences
                        </div>
                    </li>
                    <li>
                        <div class="mentorship-head">
                            <strong class="flLt">Mentor Match</strong>
                            <i class="mentorship-slider-sprite mentor-match-icon flRt"></i>
                            <div class="clearfix"></div>
                         </div>
                        <div class="mentorship-info">
                        	We will assign a mentor for <br>your preferred branch &amp; location
                        </div>
                    </li>
                    <li class="last">
                        <div class="mentorship-head">
                            <strong class="flLt">Connect</strong>
                            <i class="mentorship-slider-sprite mentor-connect-icon flRt"></i>
                            <div class="clearfix"></div>
                         </div>
                        <div class="mentorship-info">
                        	 Ask questions and schedule<br> chats with your mentor
                        </div>
                    </li>
                </ul>
    			<div class="step-section clear-width">
                    <div style="left:0" class="step-count">1
                    </div>
                    <div style="left:50%" class="step-count">2
                    </div>
                    <div style="right:0" class="step-count">3
                    </div>
                </div>
    			<div class="clearFix tac" style="margin:5px 0">
                    <a class="get-mentor-btn-2" style="margin:5px 0; line-height:27px" href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $tracking_keyid; ?>#menteeform" target="_blank"; onclick="<?=$gaTracking3?>"; >GET A MENTOR</a>
                    
                </div>
                <div class="">
            	   <a href="javascript:void(0)" class="flLt font-14" onclick="mentorSlideLeft();"> << Back </a>
                   <?php if($frompage == 'engineeringArticles' || $frompage == 'questionDetailCafe' || $frompage == 'questionDetailInstitute')
                   {    
                        $marginSpanThirdSlide = 204;
                   }
                   else
                         $marginSpanThirdSlide = 200; 
                    ?>
                   <span class="font-11 tac" style="color:#a6a6a6; margin-left:<?php echo $marginSpanThirdSlide?>px;">This is a free program</span>
                </div>
                <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
            </div>
        </li>
    </ul>
</div>
<?php } ?>
<script>
var frompage = '<?php echo $frompage;?>';
//inline widget functions
if(frompage == 'engineeringArticles' || frompage == 'questionDetailInstitute' || frompage == 'questionDetailCafe')
{
   var slideWidthMentor = 640;
}
else
var slideWidthMentor = 670;
var numSlidesMentor = 3;
var currentSlideMentor = 0;

function mentorSlideRight()
{
    currentSlideMentor--;
    if (currentSlideMentor == -3) {
        currentSlideMentor = 0;
    }
    var shiftMentor = slideWidthMentor * currentSlideMentor;
    $j('#slideContainerMentor').animate({left:shiftMentor+'px'});
}
function mentorSlideLeft()
{
    if (currentSlideMentor < 0) {
        currentSlideMentor++;
        var shiftMentor = slideWidthMentor * currentSlideMentor;
        $j('#slideContainerMentor').animate({left:shiftMentor+'px'});
    }
}

</script>