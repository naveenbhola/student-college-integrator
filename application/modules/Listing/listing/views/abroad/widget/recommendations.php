<?php
if($numberOfRecommendations) {
?>
<div class="widget-wrap clearwidth" style="margin-top:10px;">
    <div class="institute-head clearwidth">
        <h3 class="font-14 flLt"><?php if($recommendationType == 'alsoViewed') { echo 'Students who viewed this institute also viewed'; } else if($recommendationType == 'similarInstitutes') { echo 'Other colleges offering '.$LDBCourseName.' in '.$countryName; } ?></h3>
        <?php if($numberOfSlides > 1) { ?>
        <div class="next-prev flRt">
            <span id="recoSlide<?php echo $uniqId; ?>" class="flLt slider-caption">1 of <?php echo $numberOfSlides; ?></span>
            <a id="prev<?php echo $uniqId; ?>" href="javascript:void(0)" class="prev-box" onclick="slideRecoLeft('<?php echo $uniqId; ?>', true);"><i id="prevIcon<?php echo $uniqId; ?>" class="common-sprite prev-icon"></i></a>
            <a id="next<?php echo $uniqId; ?>" href="javascript:void(0)" class="next-box active" onclick="slideRecoRight('<?php echo $uniqId; ?>', true);"><i id="nextIcon<?php echo $uniqId; ?>" class="common-sprite next-icon-active"></i></a>
        </div>
        <?php } ?>
    </div>
    <div class="institute-list clearwidth" style="width:629px; overflow: hidden;">
        <ul id="slideContainer<?php echo $uniqId; ?>" style="width: 2000px; position: relative; left: 0px;">
            <?php
            $count = 0;

            foreach($courseData as $courseId => $data) {
                $count++;
                $brochureDataObj = array(
                                            'sourcePage'       => $sourcePage,
                                            'courseId'         => $courseId,
                                            'courseName'       => $data['courseName'],
                                            'universityId'     => $data['universityId'],
                                            'universityName'   => $data['universityName'],
                                            'widget'           => $recommendationType,
                                            'userStartTimePrefWithExamsTaken' => $userShortRegistrationData,
                                            'trackingPageKeyId' => 40,
                                            'consultantTrackingPageKeyId' => 373
                                        );
            ?>
            <li <?php if($count % 3 == 0) { echo 'class="last-item"'; } ?>>
                <div style="border-bottom: 1px solid rgb(204, 204, 204); padding: 10px 15px 7px 10px; min-height: 68px;">
                    <p style="color: #666;" title="<?php echo $data['universityName']; ?>"><a href="<?php echo $data['universityURL'];?>"><?php echo $data['universityName']; ?></a></p>
                    <p class="font-12" style="color:#666"><?php echo $data['universityLocation']; ?></p>
                </div>
                <div class="inst-image">
                    <a href="<?php echo $data['courseURL']; ?>" title="<?php echo $data['courseName']; ?>"><img width="172" height="114" src="<?php echo $data['universityImageURL']; ?>" alt="institute image" /></a>
                </div>
                <div class="inst-details clearwidth">
                    <div style="min-height:40px;">
                    <strong><a href="<?php echo $data['courseURL']; ?>" title="<?php echo $data['courseName']; ?>"><?php echo strlen($data['courseName']) > 40 ? substr($data['courseName'], 0 , 40).'...' : $data['courseName']; ?></a></strong>
                    <!--<p style="color: #666;" title="<?php echo $data['universityName']; ?>"><?php echo strlen($data['universityName']) > 40 ? substr($data['universityName'], 0 , 40).'...' : $data['universityName']; ?></p>
                    <p class="font-11" style="color:#999"><?php echo $data['universityLocation']; ?></p>-->
                    </div>
                    <div class="inst-info clearfix">
                        <p>
                            <label class="flLt">Course Duration</label>
                            <span class="flLt"><?=$data['courseDuration']?></span>
                        </p>
                        <p>
                            <label class="flLt">1st Year Total Fees</label>
                            <!--<span class="flLt"><?php //echo $data['courseFees']; ?></span>-->
                            <span class="flLt"><?php echo str_replace("Lac","Lakh",str_replace("Thousand","K",$data['courseFees'])); ?></span>
                        </p>
                        <p>
                            <label class="flLt">Eligibility</label>
                            <span class="flLt" <?php if(strpos($data['courseExam'],'Accepted')!=FALSE){echo "onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)' style='position:relative'";} ?>>
                                <?php if(strpos($data['courseExam'],'Accepted')!=FALSE){$this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>strtok($data['courseExam'],':'),'coursepageWidget'=>true));}?>
                                <?php echo ((strpos($data['courseExam'],': Accepted')!=FALSE)?reset(explode(':',$data['courseExam'])):$data['courseExam']);?>
                            </span>
                        </p>

                     </div>
                        <a style="display: block !important;" href="javascript:void(0)" class="button-style bold" uniqueattr="ABROAD_COURSE_PAGE/<?php echo $recommendationType; ?>_downloadBrochure" onclick="loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj)); ?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><i class="common-sprite download-icon2-a"></i>Download Brochure</a>
                        <div class="customRmcButton">
                            <?php if($data['showRmcButton']){ ?>
                                <?php
                                    $brochureDataObj['pageTitle'] = $pageTitle;
                                    $brochureDataObj['userRmcCourses'] = $userRmcCourses;
                                    $brochureDataObj['trackingPageKeyId'] = 461;
                                    echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
                                ?>
                            <?php }else{ ?>
                            <div style="height:41px;"></div>
                            <?php } ?>
                        </div>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <div class="slide-bullets clearwidth">
        <ul class="slider-control">
            <?php
        if($numberOfSlides > 1) {
            $slideButtonHTML = '<li id="recoSliderButton1'.$uniqId.'" class="active" onclick="changeRecoSlide(1, \''.$uniqId.'\', true);"></li>';
        }

                if($numberOfSlides >= 2) {
            $slideButtonHTML .= '<li id="recoSliderButton2'.$uniqId.'" onclick="changeRecoSlide(2, \''.$uniqId.'\', true);"></li>';
        }

        if($numberOfSlides == 3) {
            $slideButtonHTML .= '<li id="recoSliderButton3'.$uniqId.'" onclick="changeRecoSlide(3, \''.$uniqId.'\', true);"></li>';
        }

                echo $slideButtonHTML;
        ?>
        </ul>
    </div>
</div>

<script>
var slideWidth = 629;
var uniqueId = '<?php echo $uniqId; ?>';

if (typeof(numSlides) == 'undefined') {
    numSlides = {};
    currentSlide = {};
    firstSlide = {};
    lastSlide = {};
}

numSlides[uniqueId] = <?php echo $numberOfSlides; ?>;
currentSlide[uniqueId] = 0;
firstSlide[uniqueId] = 0;
lastSlide[uniqueId] = (numSlides[uniqueId] - 1) * (-1);
</script>

<?php
}
?>
<style>
    .customRmcButton .btn-rate-change .rate-change-button {width : 185px !important;}
</style>
