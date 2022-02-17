<div class="carausel-section study-plan-widget clearfix">
    <div class="clearfix">
        <i class="common-sprite plan-icon flLt"></i><h2 class="flLt">Plan your study abroad journey</h2>
    </div>
    <div class="planning-tabs">
        <ul>
            <li id="chooseRight" style="width:42%" class="active"><a href="javascript:void(0)" onclick="swapContentOrgTab(1)" style="outline:0 none;"><i class="common-sprite choose-icon"></i>How to choose the right</a><i class="common-sprite plan-pointer"></i></li>
            <li id="knowAbout"><a href="javascript:void(0)" onclick="swapContentOrgTab(2)" style="outline:0 none;"><i class="common-sprite abt-plan-icon"></i>Know all about</a><i class="common-sprite plan-pointer"></i></li>
        </ul>
    </div>
    <div id="chooseRightSection" style="display:block;" class="planning-info clearwidth">
        <ul>
            <li>
                <a href="<?=$contentOrgData['COUNTRY']?>">
                    <i class="home-sprite country-icon"></i>
                    <strong>Country</strong>
                    <p>Get help in deciding your study destination</p>
                </a>
            </li>
            <li>
                <a href="<?=$contentOrgData['COURSE']?>">
                    <i class="home-sprite course-icon"></i>
                    <strong>Course</strong>
                    <p>Select from suitable courses on offer </p>
                </a>
            </li>
            <li>
                <a href="<?=$contentOrgData['EXAM']?>">
                    <i class="home-sprite exam-plan-icon"></i>
                    <strong>Exam</strong>
                    <p>Know which exam you should take</p>
                </a>
            </li>
            <li class="last">
                <a href="<?=$contentOrgData['COLLEGE']?>">
                    <i class="home-sprite college-plan-icon"></i>
                    <strong>College</strong>
                    <p>Shortlist colleges based on your preference </p>
                </a>
            </li>
        </ul>
    </div>
    <div id="knowAboutSection" style="display:none;" class="planning-info clearwidth">
        <ul>
            <li>
                <a href="<?=$contentOrgData['APPLICATION_PROCESS']?>">
                    <i class="home-sprite process-icon"></i>
                    <strong>Application Process</strong>
                    <p>Understand how to apply to a college </p>
                </a>
            </li>
            <li>
                <a href="<?=$contentOrgData['SCHOLARSHIP_FUNDS']?>">
                    <i class="home-sprite loan-icon"></i>
                    <strong>Scholarship &amp; Loan</strong>
                    <p>Find out financing options available to you </p>
                </a>
            </li>
            <li>
                <a href="<?=$contentOrgData['VISA_DEPARTURE']?>">
                    <i class="home-sprite visa-icon"></i>
                    <strong>Visa &amp; Departure</strong>
                    <p>Know about Student Visa &amp; pre-departure checklist </p>
                </a>
            </li>
            <li class="last">
                <a href="<?=$contentOrgData['STUDENT_LIFE']?>">
                        <i class="home-sprite stu-life-icon"></i>
                        <strong>Student Life</strong>
                        <p>Get insights &amp; tips to adapt and excel in a foreign land  </p>
                </a>
            </li>
        </ul>
    </div>
</div>