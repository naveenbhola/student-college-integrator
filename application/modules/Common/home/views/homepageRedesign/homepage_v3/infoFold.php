<section class="Msgbanner">
    <div class="_cntr">
        <h1>Empowering millions of students in making the right career and college decision</h1>
    </div>
</section>
<section class="studyBaner">
    <div class="_cntr">
        <div class="tabSection" id="fld1H">
            <ul class="studyTab">
                <li class="active" data-index="1">
                    <h2>STUDY IN INDIA</h2>
                </li>
                <li data-index="2">
                    <h2>STUDY ABROAD</h2>
                </li>
            </ul>
        </div>
        <div class="tabContent" id="fld2C">
            <div data-index="1" class="active">
                <ul>
                    <li>
                        <h3><span class="numb animNum0"><?php echo $hpCounterResult['national']['instCount']; ?></span>+</h3>
                        <span class="subHd">COLLEGES & UNIVERSITIES</span>
                    </li>
                    <li>
                        <h3><span class="numb animNum0"><?php echo $hpCounterResult['national']['reviewsCount']; ?></span>+</h3>
                        <span class="subHd">COLLEGE REVIEWS</span>

                    </li>
                    <li>
                        <h3><span class="numb animNum0"><?php echo $hpCounterResult['national']['questionsAnsweredCount']; ?></span>+</h3>
                        <span class="subHd">QUESTIONS ANSWERED</span>

                    </li>
                    <li>
                        <h3><span class="numb animNum0"><?php echo $hpCounterResult['national']['careerCount']; ?></span>+</h3>
                        <span class="subHd">CAREERS</span>
                    </li>
                </ul>
            </div>
            <div data-index="2">
                <ul>
                    <li >
                        <h3><span class="numb animNum1"><?php echo $hpCounterResult['abroad']['countryCount']; ?></span>+</h3>
                        <span class="subHd">COUNTRIES</span>
                    </li>
                    <li class="saLU">
                        <h3><span class="numb animNum1"><?php echo $hpCounterResult['abroad']['universityCount']; ?></span>+</h3>
                        <span class="subHd">UNIVERSITIES</span>

                    </li>
                    <li class="saLC">
                        <h3><span class="numb animNum1"><?php echo $hpCounterResult['abroad']['courseCount']; ?></span>+</h3>
                        <span class="subHd">COURSES</span>

                    </li>
                    <li class="end">
                        <h3>END TO END</h3>
                        <span class="subHd">APPLICATION GUIDANCE</span>
                    </li>
                </ul>
                <div class="bottomStrip">
                    <a target="_blank" href="<?php echo SHIKSHA_STUDYABROAD_HOME ?>" class="go-to">GO TO STUDY ABROAD WEBSITE</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    var dynamicCounterValue = [['<?php echo $hpCounterResult['national']['instCount']; ?>','<?php echo $hpCounterResult['national']['reviewsCount']; ?>','<?php echo $hpCounterResult['national']['questionsAnsweredCount']; ?>','<?php echo $hpCounterResult['national']['careerCount']; ?>'],['<?php echo $hpCounterResult['abroad']['countryCount']; ?>','<?php echo $hpCounterResult['abroad']['universityCount']; ?>','<?php echo $hpCounterResult['abroad']['courseCount']; ?>']];
</script>