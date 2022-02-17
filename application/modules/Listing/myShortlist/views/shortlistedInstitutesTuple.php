<?php
$count = 1;
$totalShortlistedCourses = count($shortlistedCourses);
$tracking_keyid = DESKTOP_NL_SHORTLIST_HOME_TUPLE_DEB;

foreach($shortlistedCourses as $courseId => $course) {
    //processing exam name and its score
    $exams = $course->getEligibility(array('general'));
    $exams = $exams['general'];
    $marksFormat = array(
                  'percentile' => "%ile",
                  'percentage' => "%",
                  'score/marks' =>  "marks",
                  'rank'        =>"rank"
              );
    $examAcronyms = array();
    $allExams = $course->allExams;
    if(!empty($allExams)){
        if(!empty($exams)){
            foreach($exams as $exam) {
                if ($exam->getValue() > 0) {
                    $examAcronyms[$exam->getExamName()] = ': '.$exam->getValue().' '.$marksFormat[$exam->getUnit()];
                    unset($allExams[$exam->getExamName()]);
                } else {
                    $examAcronyms[$exam->getExamName()] = '';
                }
            }
        }
        if(count($allExams) > 0){
            foreach ($allExams as $exam) {
                $examAcronyms[$exam] = '';
            }
        }
    }else {
        $examAcronyms = array('N/A' => '');
    }


    //processing exam ends here
?>
<tr id="row_<?php echo $courseId; ?>" class="shortlist-tuple">
    <td class="first"><div class="tuple_<?php echo $courseId; ?>"><i class='common-sprite tuple-star'></i></div></td>
    <td class="inst-name-col">
        <div class="tuple_<?php echo $courseId; ?>">
<strong class="inst-name" style="font-weight:600;color:#333">
            <?php echo html_escape($course->getInstituteName()).',</strong><span>'.html_escape($course->currentLocation->getCityName()).'</span>';?><br/>
            <p style="margin:15px 0px 10px 0px;font-size:12px;"><?php echo html_escape($course->getName());?></p>
            <a href="javascript:void(0);" style="display:none" class="action_point" onclick="getSimilarCourses(<?php echo $course->getId();?>, 'shortlistedTuples','<?php echo base64_encode($course->getInstituteName()) ?>')" class="font-11">View Similar Colleges</a>
        </div>
    </td>
    <td class="eligible-col">
        <div class="tuple_<?php echo $courseId; ?>">
            <?php foreach($examAcronyms as $examName => $score) {
            echo "<p>$examName$score</p>";
            } ?>
        </div>
    </td>
    <td style="text-align:center">
        <div class="tuple_<?php echo $courseId; ?>"><?php
            $index = 0;
            if(isset($courseRank[$courseId])){
            foreach ($courseRank[$courseId] as $rankSource => $rank) {
            if($rank != ''){
            if($index != 0){
            echo '<br>';
            }
            $index++;
            echo trim($rankSource).' : '.trim($rank);
            }
            }
            if($index == 0){
            echo 'N/A';
            }
            }else{
            echo 'N/A';
            }?>
        </div>
    </td>
    <?php
    if($course->getPlacements()!=''){
    $avgSalary = $course->getPlacements()->getSalary();
    $avgSalary = $avgSalary['avg'];
    }

    ?>
    <td> <div class="tuple_<?php echo $courseId; ?>"><?php echo (isset($avgSalary) == 1) ? 'Rs. '.$listingCommonLibObj->formatMoneyAmount($avgSalary, 1, 0) : 'N/A';?></div></td>
    <td><div class="tuple_<?php echo $courseId; ?>"><?php
    if($course->getFees()){
        echo ($course->getFees()->getFeesValue()) ? 'Rs. '.$listingCommonLibObj->formatMoneyAmount($course->getFees()->getFeesValue(), 1, 0) : 'N/A';
    }else{
        echo 'N/A';
    }
    ?></div></td>
    <td class="last">
        <div class="clearfix tuple_<?php echo $courseId; ?>" style="margin-bottom:30px">
            <span class="flLt"><?php echo (!empty($course->formSubmissionDate)) ? $course->formSubmissionDate: 'N/A';?></span>
            <a href="javascript:void(0);" style="display:none;position:relative" onmouseout="if(typeof($j) == 'undefined') {return false;} $j('#delete_tooltip_<?php echo $courseId; ?>').hide();" onmouseover="if(typeof($j) == 'undefined') {return false;} $j('#delete_tooltip_<?php echo $courseId; ?>').show();" onclick="removeShortlistFromMyShortlistTupple(this, '<?php echo $courseId; ?>');" class="flRt close-link action_point">×
                <div class="help-tooltip" id="delete_tooltip_<?php echo $courseId; ?>" style="display:none;"><i class="shortlist-sprite new-tooltip-pointer"></i>Delete</div>
            </a>
        </div>
    </td>
</tr>
<tr class="tuple-cta">
    <td colspan="7" class="btn-contnr-sec">
        <div class="strip-bar">

        <div class="clearfix action_point tuple_<?php echo $courseId; ?>" style="display:none">

                <span class="exclamation" style="margin:5px 0px 0px 10px;display:inline-block;">
                    <a href="javascript:void(0);" onmouseout="if(typeof($j) == 'undefined') {return false;} $j('#report_tooltip_<?php echo $courseId; ?>').hide();" onmouseover="if(typeof($j) == 'undefined') {return false;} $j('#report_tooltip_<?php echo $courseId; ?>').show();" onclick="reportIncorrectLayerShowHide(<?php echo $courseId?>)" style="position:relative; margin-left: 15px;" class="acclimation-mark">!</a>
                    Report incorrect data
                </span>


                 <div style="color:#666; margin:8px 0 8px 200px;position:absolute;" id="msg_div_<?php echo $courseId; ?>"></div>
                <div class="fltryt" style="padding:8px 0 8px 10px;">
            <!-- Download e-brochure starts -->
            <?php if(in_array($course->getId(),$downloadEBrochureApplied)){  ?>
                            <div style="color:#666; margin:0px 5px 0px 10px;display:inline-block;" id="msg_div_<?php echo $courseId;?>">E-Brochure successfully mailed.</div>
                            <?php } ?>
                 <?php 
                 if($checkForDownloadBrochure[$courseId]) { 
                     if(in_array($course->getId(),$downloadEBrochureApplied)){ ?>
                         <a href="javascript:void(0)"   class="brochure-button disabled">Get Brochure</a>
                         <?php } else { ?>
                            <a href="javascript:void(0)"  onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'download_brochure', '<?php echo $course->getId()?>'); ajaxDownloadEBrochure(this,'<?php echo $course->getId();?>', 'course','<?php echo htmlspecialchars(addslashes($course->getName()),ENT_QUOTES);?>','shortlistDeskPage','458');" class="brochure-button" customCallBack ="downloadEbrochureShortlistCallBack">Get Brochure</a>
                     <?php }
               } ?>
        <!-- Download e-brochure ends -->

            <?php if($coursesWithOnlineForm[$courseId] && $onlineApplicationCoursesUrl[$course->getId()]['seo_url'] != '') { 

                $seoURL = str_replace('<courseName>', strtolower(seo_url($course->getName(),'-',30)), $onlineApplicationCoursesUrl[$course->getId()]['seo_url']);
                $seoURL = str_replace('<courseId>', $course->getId(), $seoURL);
                $of_seo_url = ($seoURL!='') ? $seoURL : SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$course->getId();

                ?>
                <a href="javascript:void(0);" class="brochure-button apply-btn" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Apply_Online', '', this, '<?php echo $of_seo_url.'?tracking_keyid=1110';?>');">Apply Online</a>
            <?php } ?>
                        
                  
                </div>
                <div class="help-tooltip" id="report_tooltip_<?php echo $courseId; ?>" style="display:none;top:31px;"><i class="shortlist-sprite new-tooltip-pointer"></i>Report to Shiksha</div>
            <div id="report_layer_<?php echo $courseId; ?>" class="report-layer" style="display:none;">
                <i class="shortlist-sprite report-pointer"></i>
                <div class="report-layer-detail">
                    <p style="text-align: left">Report Incorrect Data</p>
                    <textarea id="report_area_<?php echo $courseId; ?>" onclick="setTimeout(function(){$j('#report_area_<?php echo $courseId; ?>').focus();}, 0);" class="report-area" value="Write Here"></textarea>
                    <div style="text-align: left" class="errorMsg" id="error_div_<?php echo $courseId; ?>"></div>
                                </div>
                                <div class="report-btn-area">
                                    <a href="javascript:void(0);" onclick="if(reportingIncorrectInProgress) { return false; } else { reportingIncorrectInProgress = 1; submitIncorrectData(<?php echo $courseId?>); return false; }" class="report-sbmit-btn">Submit</a>
                                </div>
                            </div>
                          
                <div class="clr"></div>
            </div>
        </div>
    </td>
</tr>
<tr class="tuple-details-tab">
    <td colspan="8" class="layer-row">
        <div class="detail-main-layer">
            <div id="shortlistTupleLoader_<?php echo $courseId; ?>" style="text-align: center; margin-top: 10px;display:none;">
                <img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="loadingImageNew" alt="" class="small-loader">
            </div>
            <ul class="review-tabs clearfix shortlistTupleTab_<?php echo $courseId;?>">
                <li class="active" id="plcTb_<?php echo $courseId; ?>" style="display: none;"><a href="javascript:void(0);" sectioname="placement" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_tuple_tabs', 'placement');showShortlistTupleTabDetails(this, <?php echo $courseId.', '.$course->getInstituteId()?>);"><i class="shortlist-sprite data-icn"></i>Placement Data</a><span class="caret"></span></li>
                <li id="rvwTb_<?php echo $courseId; ?>" style="display: none;"><a href="javascript:void(0);" sectioname="reviews" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_tuple_tabs', 'reviews');showShortlistTupleTabDetails(this, <?php echo $courseId.', '.$course->getInstituteId()?>);"><i class="shortlist-sprite review-icn"></i>College Reviews</a><span class="caret"></span></li>
                <li id="askTb_<?php echo $courseId; ?>" style="display: none;"><a href="javascript:void(0);" sectioname="ask" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_tuple_tabs', 'ask');showShortlistTupleTabDetails(this, <?php echo $courseId.', '.$course->getInstituteId()?>,'<?php echo $questionTrackingPageKeyId;?>');"><i class="shortlist-sprite ask-icn"></i>Ask a Question</a><span class="caret"></span></li>
                <li id="notesTb_<?php echo $courseId; ?>" style="display: none;"><a href="javascript:void(0);" sectioname="notes" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE', 'Shortlist_tuple_tabs', 'notes');showShortlistTupleTabDetails(this, <?php echo $courseId.', '.$course->getInstituteId()?>);"><i class="shortlist-sprite notes-icn"></i>Add Notes</a><span class="caret"></span></li>
            </ul>
            <div id="content-section-<?php echo $courseId;?>"></div>
        </div>
    </td>
</tr>
<?php
$count++;
} ?>
