<div class="overview-details course-detail-tab class-profile-tab" style="display:none;">
    <div class="course-detail-mid flLt">
        <h2>Class profile for this course</h2>
        <ul class="column-data">
    <?php if($classProfileData["average_work_experience"] != "") { ?>
            <li class="flLt">
                <p>Average Work Experience</p>
                <p><strong><?=htmlentities($classProfileData["average_work_experience"])?></strong></p>
            </li>
    <?php } if($classProfileData["average_gpa"]) { ?>         
            <li class="flLt">
                <p>Average Bachelors GPA / Percentage</p>
                <p><strong><?=htmlentities($classProfileData["average_gpa"])?></strong></p>
            </li>
    <?php } if($classProfileData["average_xii_percentage"]) { ?>        
            <li class="flLt">
                <p>Average Class XII Percentage</p>
                <p><strong><?=htmlentities($classProfileData["average_xii_percentage"])?></strong></p>
            </li>
    <?php } if($classProfileData["average_gmat_score"]) { ?>
            <li class="flLt">
                <p>Average GMAT Score</p>
                <p><strong><?=htmlentities($classProfileData["average_gmat_score"])?></strong></p>
            </li>
    <?php } if($classProfileData["average_age"]) { ?>
            <li class="flLt">
                <p>Average Student Age</p>
                <p><strong><?=htmlentities($classProfileData["average_age"])?></strong></p>
            </li>
    <?php } if($classProfileData["percentage_international_students"]) { ?>
            <li class="flLt">
                <p>Percentage of International Students</p>
                <p><strong><?=htmlentities($classProfileData["percentage_international_students"])?></strong></p>
            </li>
    <?php } ?>
        </ul>
    </div>
</div>