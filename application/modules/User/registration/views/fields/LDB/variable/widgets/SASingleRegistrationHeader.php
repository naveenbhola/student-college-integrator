<?php  $conversionType = $MISTrackingDetails['conversionType'];
       $keyName = $MISTrackingDetails['keyName'];
?>
<?php
$headingForLoggedInUser = (!empty($formData['userId'])) ?"Update your profile":"";

if($conversionType=='response'){
    if($keyName == 'downloadBrochure') { ?>
        <div class="sp-img"><img src="/public/images/SASingleSignup/Brochure.jpg" ></div>
        <div class="sp-cont">
            <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Download Brochure':$headingForLoggedInUser;?></p>
            <p>1,42,770 brochures were downloaded on Shiksha study abroad last year.<br> Get details about tuition fees, eligibility, living expense etc.</p>
        </div>
    <?php }else if($keyName == 'scholarshipDownloadBrochure'){
        ?>
        <div class="sp-img"><img src="/public/images/SASingleSignup/Brochure.jpg" ></div>
        <div class="sp-cont">
            <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Download Scholarship Brochure':$headingForLoggedInUser;?></p>
            <p>1,42,770 brochures were downloaded on Shiksha study abroad last year.<br> Get details about scholarship eligibility & application details.</p>
        </div>
        <?php
        }else if($keyName == 'scholarshipApply'){
        ?>
        <div class="sp-img"><img src="/public/images/SASingleSignup/Apply.png" ></div>
        <div class="sp-cont">
            <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Signup to apply for Scholarship':$headingForLoggedInUser;?></p>
            <p>1,45,000+ students and parents signed up last year on Shiksha to study abroad.<br> Make an informed decision about your abroad education.</p>
        </div>
        <?php
        } else{ ?>
        <div class="sp-img"><img src="/public/images/SASingleSignup/RMC.jpg" ></div>
        <div class="sp-cont">
            <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Rate your chances':$headingForLoggedInUser;?></p>
            <p>An expert Shiksha counselor will review your profile and give an assessment of your admission chances</p>
        </div>
<?php }} else if($conversionType=='downloadGuide'){ ?>

<div class="sp-img"><img src="/public/images/SASingleSignup/Guide.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Download Guide':$headingForLoggedInUser;?></p>
    <p>35,447 guides were downloaded on Shiksha study abroad last year.<br>Get in-depth details on this topic.</p>
</div>
<?php } else if($conversionType=='Course shortlist'){ ?>

<div class="sp-img"><img src="/public/images/SASingleSignup/Save-Course.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Save Courses':$headingForLoggedInUser;?></p>
    <p>52,423 courses were saved on Shiksha study abroad last year.<br>Save to create a personalized list to access later from any device.
</p>
</div>
<?php } else if($conversionType=='compare'){ ?>
<div class="sp-img"><img src="/public/images/SASingleSignup/Compare_image_03.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Signup to compare colleges':$headingForLoggedInUser;?></p>
    <p>1,45,000+ students and parents signed up last year on Shiksha to study abroad.<br> Make an informed decision about your abroad education.
</p>
</div>
<?php } else if($conversionType=='commentPost' || $conversionType=='replyPost'){ ?>
<div class="sp-img"><img src="/public/images/SASingleSignup/Desktop-Addcomment_03.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Signup to add a comment':$headingForLoggedInUser;?></p>
    <p>Join the largest community of study abroad aspirants in India.<br> More than 1,45,000 students signed up last year on Shiksha to study abroad.
</p>
</div>



<?php } else if($conversionType=='questionPost'){ ?>
<div class="sp-img"><img src="/public/images/SASingleSignup/Desktop-Addcomment_03.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Signup to submit your question':$headingForLoggedInUser;?></p>
    <p>Join the largest community of study abroad aspirants in India.<br> More than 75,000 students signed up last year on Shiksha to study abroad.
</p>
</div>


<?php } else{ ?>

<div class="sp-img"><img src="/public/images/SASingleSignup/Signup.jpg" ></div>
<div class="sp-cont">
    <p class="sp-title"><?php echo empty($headingForLoggedInUser)?'Signup to get started':$headingForLoggedInUser;?></p>
    <p>1,45,000+ students and parents signed up last year on Shiksha to study abroad.<br> Make an informed decision about your abroad education.</p>
</div>
<?php } ?>
