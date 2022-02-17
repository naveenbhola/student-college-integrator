<?php
//count is used so that css and js don't get loaded on the ranking page every time the widget is called. 
if($count == 10) {
?>
<link rel="stylesheet" href="/public/mobile5/css/<?php echo getCSSWithVersion("mentorship-mobile",'nationalMobile'); ?>" >
<?php
 $isMentorshipInlineCssPresent = 1;
?>
<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>"></script>
<?php } ?>

<?php if($isMentee != 1){ ?>


<section class="clearfix content-section">
    <div class="mentorsip-inline-slider mentorshipslider<?=$count;?>">
        <ul class="slides">
        <li style="height:292px;">
            <div style="text-align:center;">
            <div class="mentor-widget-head">
                <p><strong>Planning for Engineering..<br> And confused? </strong></p>
            </div>
            <div class="mentor-widget-box get-mentor-sec clearfix">
                <strong>Get a mentor</strong>
                <p class="font-12">Who will guide you through the entire <br> engineering preparation and college selection process.</p>
                <a href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $trackingPageKeyId;?>&for_focus=menteeform" class="get-mentor-btn" onclick="trackEventByGAMobile('MOBILE_MENTOR_INLINE_WIDGET_GET_A_MENTOR_FROM_<?php echo strtoupper($frompage); ?>_FIRST_SLIDE');">GET A MENTOR</a> 
                <p class="free-prgm-title">This is a free program</p>
                <div class="clearfix" style="margin-top:8px;">
                    <a href="#" class="middle-links">See who are these mentors? >></a>
                </div>
            </div>
            <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
         </div>
            <div class="next-box"><i class="mentor-mobile-sprite next-icon-a"></i></div>
        </li>
        <li style="height:292px;">
            <div style="text-align:center;">
            <div class="mentor-widget-head">
                    <p><strong>Who are these mentors? </strong></p>
                </div>
            <div class="mentor-widget-box get-mentor-sec clearfix">
                <p><span class="count-color"><?php if($totalMentorCount>100) echo $totalMentorCount;?></span> CURRENT ENGINEERING STUDENTS</p>
                <ul class="mentor-widget-list">
                    <li><span>Studying in various branches</span></li>
                    <li><span>From top colleges like IITs, NITs</span></li>
                    <li><span>Spread across 22 states</span></li>
                </ul>
                <div class="clearfix"></div>
                <a href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $trackingPageKeyId;?>&for_focus=menteeform" class="get-mentor-btn" onclick="trackEventByGAMobile('MOBILE_MENTOR_INLINE_WIDGET_GET_A_MENTOR_FROM_<?php echo strtoupper($frompage); ?>_SECOND_SLIDE');">GET A MENTOR</a> 
                <p class="free-prgm-title">This is a free program</p>
                 <div class="clearfix" style="margin-top:8px;">
                    <a href="#" class="middle-links">See How it works >></a>
                </div>
            </div>
            <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
         </div>
            <div class="prev-box"><i class="mentor-mobile-sprite prev-icon-a"></i></div>
            <div class="next-box"><i class="mentor-mobile-sprite next-icon-a"></i></div>
        </li>
        <li style="height:292px;">
            <div style="text-align:center;">
            <div class="mentor-widget-head">
                    <p><strong>How does it work?</strong></p>
                </div>
            <div class="mentor-widget-box get-mentor-sec clearfix">
                <p class="work-detail-list"><span>Enroll</span> <span class="seperator"> | </span> <span style="width:36%;">Mentor Match</span> <span class="seperator"> | </span> <span>Connect</span> </p>
                <ul class="mentor-widget-list">
                    <li><span>Submit your details & preferences</span></li>
                    <li><span>We wil assign you a mentor</span></li>
                    <li><span>Ask question & schedule chat</span></li>
                </ul>
                <div class="clearfix"></div>
                <a href="<?=SHIKSHA_HOME?>/mentors-engineering-exams-colleges-courses?tracking_keyid=<?php echo $trackingPageKeyId;?>&for_focus=menteeform" class="get-mentor-btn" onclick="trackEventByGAMobile('MOBILE_MENTOR_INLINE_WIDGET_GET_A_MENTOR_FROM_<?php echo strtoupper($frompage); ?>_THIRD_SLIDE');">GET A MENTOR</a> 
                <p class="free-prgm-title">This is a free program</p>
                 <div class="clearfix" style="margin-top:8px;">
                </div>
            </div>
            <div class="mentorship-badge"><strong>Mentorship Program</strong></div>
         </div>
            <div class="prev-box"><i class="mentor-mobile-sprite prev-icon-a"></i></div>
        </li>
     </ul>
   </div>
</section>
<?php } ?>

<script>
$(document).ready(function() {
var selector = ".mentorshipslider<?=$count?>";
initializeMentorshipSlider(selector);
});
</script>

<?php if($count == 10) { ?>
<script>
function initializeMentorshipSlider(selector){

  $(selector).flexslider({
              animation: "slide",
              slideshow : false,
              directionNav: false,
              controlNav : true,
              animationSpeed : 75,
              animationLoop: false,
              smoothHeight : true,
              useCSS : false,
              pauseOnAction : false,
              touch : true,
              easing : "linear",
              pauseOnHover: false,
              slideshowSpeed: 6000,
              start: function(selector) {
                $(selector).find('.next-box').click(function(event){
                    event.preventDefault();
                    selector.flexAnimate(selector.getTarget("next"));
                });
                $(selector).find('.middle-links').click(function(event){
                    event.preventDefault();
                    selector.flexAnimate(selector.getTarget("next"));
                });
                $(selector).find('.prev-box').click(function(event){
                    event.preventDefault();
                    selector.flexAnimate(selector.getTarget("prev"));
                });
            },
            });
}
</script>
<?php } ?>