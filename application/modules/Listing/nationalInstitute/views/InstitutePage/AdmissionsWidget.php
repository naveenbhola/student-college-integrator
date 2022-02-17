<?php 
$GA_Tap_On_View_More_Admission = 'VIEW_MORE_ADMISSION';
$GA_Tap_On_Exam = 'VIEW_EXAM';
$GA_Tap_On_View_Info = 'VIEW_ADMISSION_INFO';
?>
<?php 
if(!empty($admissionInfo) || (!empty($examList) && count($examList) > 0) || !empty($viewAdmissionLink)) {
    ?>
    <div class="new-row listingTuple" id="admissionsection-offer">
        <?php 
        if(!empty($admissionInfo)) {
            ?>
            <?php 
                if((empty($examList) && count($examList) == 0) && empty($viewAdmissionLink)) {
                    $gapAdmission = "gap";
                } 
            ?>
            <div class="group-card uni-examsec <?=$gapAdmission;?>">
                <h2 class="head-1 gap">Admissions & Cut-Offs</h2>
                <div class="admission-div" id="admission-div">
                    <div class="column-list">
                            <p>
                            <?php echo $admissionInfo;?>
                            </p>
                        
                    </div>
                </div>
                <div class="gradient-col" id="viewMoreLink" style="display: block">
                    <a href="<?php echo $admissionPageUrl;?>" class="button button--secondary mL10 arw_link" ga-attr="<?=$GA_Tap_On_View_More_Admission?>" target="_blank">View More</a>
</div>
            </div>
            <?php 
        } 
        ?>
        <?php 
        if((!empty($examList) && count($examList) > 0) || !empty($viewAdmissionLink)) {
            ?>
            <div class="group-card gap">
                <?php 
                if(!empty($examList) && count($examList) > 0) { 
                    if(!empty($viewAdmissionLink)){
                        $marginClass = 'b-margin';
                    }

                    if(empty($admissionInfo)) {
                        $marginClass .= " mt16";
                    }
                    ?>
                    <div class="ExamWidget-div <?=$marginClass;?>" id="ExamWidget-div"> 
                        <h2 class="head-2">Exams offered by <?php echo $listing_type; ?></h2>
                            <!-- -->
                        <div class="partner-col ExamsSlider" >
                            <?php
                                if(is_array($examList) && count($examList)>4){
                                    ?>
                                    <a id="navPhotoPrev" class="icn-prev lftArwDsbl"><i></i></a>
    	                            <?php 
                                } 
                            ?>
                            <div class="r-caraousal crs-list">
                                <ul class="featuredSlider viewed-crsLst">
                                    <?php 
                                    foreach($examList as $examKey => $examValue) {
					$examYear = ($examValue['year']!='')?' '.$examValue['year']:'';
                                        ?>
                                        <li ga-attr="<?=$GA_Tap_On_Exam?>">
                                            <?php 
                                                if(!empty($examValue['url'])) {
                                                    $examURL = addingDomainNameToUrl(array('url' => $examValue['url'] , 'domainName' =>SHIKSHA_HOME));
                                                    ?>
                                                    <div class="r-card"  onclick="window.open('<?php echo $examURL;?>');">
                                                    <?php 
                                                }
                                                else{
                                                    ?>
                                                    <div class="r-card">
                                                    <?php 
                                                } 
						$displayTitle = $examValue['name'].$examYear;
                                            ?>
                                            <div>
                                                <p class="para-2"><strong><a style="color:#111" href="<?php echo $examURL;?>" target="_blank"><?php echo htmlentities(substr($displayTitle,0,24))?><?php if(strlen(htmlentities($displayTitle))>24){echo '...'; };?></a></strong></p>
                                                <p class="exam_name"><?php echo htmlentities(substr($examValue['fullName'],0,56))?><?php if(strlen(htmlentities($examValue['fullName']))>56){echo '...'; } ?></p>

                                                <?php 
                                                    if(!empty($examValue['url'])) {
                                                        $examURL = addingDomainNameToUrl(array('url' => $examValue['url'] , 'domainName' =>SHIKSHA_HOME));
                                                        ?>
                                                        <a class="link viewExamLink" href="<?php echo $examURL;?>" target="_blank">View Exam Details</a>
                                                        <?php 
                                                    } 
                                                ?>
                                            </div>
                                            </div>
                                        </li>
                                        <?php 
                                    }
                                    ?>
                                </ul>
                            </div> 
    	                    <?php 
                                if(is_array($examList) && count($examList)>4){
                                    ?>
                                    <a id="navPhotoNxt" class="icn-next"><i></i></a>
                                    <?php 
                                } 
                            ?>
                        </div>
                    </div>
                    <?php 
                } 
                ?>
                <?php 
                if(!empty($viewAdmissionLink)) {
                    ?>
                    <div class="col-pCont">
                        <div class='fntot-dv'>
                            <span class="para-3 w-txt col-p70">Want to know the eligibility, admission process and important dates of all <?php echo htmlentities($instituteName);?> courses?
                            </span>
                            <span class="para-3 w-txt col-p30" id="viewAdmissionLink">
                                <a href="<?php echo $admissionPageUrl;?>" class="button button--secondary mL10" ga-attr="<?=$GA_Tap_On_View_Info?>" target="_blank">View Admissions & Cut-Offs info</a>
                            </span>
                            <span class="clr"></span>
                        </div>
                    </div>
                    <?php 
                } 
                ?>
            </div>
            <?php 
        } 
        ?>
    </div>
    <?php 
} 
?>
