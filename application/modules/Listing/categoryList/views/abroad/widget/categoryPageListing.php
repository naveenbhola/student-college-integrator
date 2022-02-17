<!-- START : INSTITUTE TUPLE LISTING-->
<div id ="instTupleSec" class="univ-tab-details clearwidth">
    <ul class="tuple-cont">
        <?php
        $compareChkBox = 0;
        $count = 0;
        $univCount = count($resultantUniversityObjects);
        $imageCount = 0;
        $scholarshipWidgetShown = false;
        foreach($resultantUniversityObjects as $universityObject)
        {
            $count++;
            $courseObj = array();
            $courseObjList = array();
            $courseList = $universityObject->getCourses();
            foreach($courseList as $deptCourse)
            {
                $courseObjList[$deptCourse->getId()] = $deptCourse;
            }
            $courseObj = $courseObjList;

            $sortOrderOfCourses = $universityObject->getSortOrderForSimilarCourses();
            if(!empty($sortOrderOfCourses))
            {
                $updatedCourseObjList = array();
                foreach($sortOrderOfCourses as $courseIdWithOrder){
                    $updatedCourseObjList[] = $courseObjList[$courseIdWithOrder];
                }
                $courseObj = $updatedCourseObjList;
            }

            if($sortingCriteria['sortBy'] == 'none' || empty($sortingCriteria['sortBy'])) {
                $paidCourse = array();
                $freeCourse = array();
                foreach($courseObj as $course)
                {
                    if($course->isPaid())
                    {
                        $paidCourse[] = $course;
                    } else
                    {
                        $freeCourse[] = $course;
                    }
                }

                if(!empty($paidCourse) && !empty($freeCourse))
                {
                    $courseObj = array_merge($paidCourse, $freeCourse);
                } elseif(!empty($paidCourse))
                {
                    $courseObj = $paidCourse;
                } else
                {
                    $courseObj = $freeCourse;
                }
            }

            $firstCourse 					= reset($courseObj);
            $isFirstCourseShotlistedByUser 	= in_array($firstCourse->getId(), $userShortlistedCourseIds['courseIds']) ? TRUE : FALSE;
            
            $imgUrl = $universityObject->getUniversityDefaultImgUrl('172x115');
            
            if($imgUrl == '')
            {
                $imgUrl = SHIKSHA_HOME."/public/images/univDefault_172x115.jpg";
            }
            $imageCount++;
            ?>
        <li class="clearwidth <?php echo  $universityObject->isSticky() ? 'sponsered' :'' ?>">
            <div id ="categoryPageListing_tupleId_<?php echo $firstCourse->getId();?>" class="tuple-box">
                <div class="flLt">
                    <div class="tuple-image">
                        <?php if($imageCount <=$loadImagesWithoutLazyCount){ ?>
                            <a target="_blank" href="<?php echo $universityObject->getURL();?>"><img src="<?php echo $imgUrl?>" alt="<?php echo htmlentities($firstCourse->getName()).", ".htmlentities($universityObject->getName()).", ".$universityObject->getLocation()->getCountry()->getName();?>" title="<?php echo htmlentities($firstCourse->getName()).", ".htmlentities($universityObject->getName())?>" align="center" width="172" height="115"/></a>
                        <?php }else{ ?>
                            <a target="_blank" href="<?php echo $universityObject->getURL();?>"><img class="lazy" data-original="<?php echo $imgUrl?>" alt="<?php echo htmlentities($firstCourse->getName()).", ".htmlentities($universityObject->getName()).", ".$universityObject->getLocation()->getCountry()->getName();?>" title="<?php echo htmlentities($firstCourse->getName()).", ".htmlentities($universityObject->getName())?>" align="center" width="172" height="115"/></a>
                        <?php } ?>
                        <div class ="tuple-shrtlist-image" >
                            <?php if($isFirstCourseShotlistedByUser) {?>
                                <i class="cate-sprite add-shrlst-icon"></i>
                                <p>Saved</p>
                            <?php }?>
                        </div>
                    </div>
                    <?php
                    // prepare data now required for new single registration form
                    /*$courseData = array(
                        $firstCourse->getId() =>
                            array(
                                'desiredCourse' => ($firstCourse->getDesiredCourseId()?$firstCourse->getDesiredCourseId():$firstCourse->getLDBCourseId()),
                                'paid'			=> $firstCourse->isPaid(),
                                'name'			=> $firstCourse->getName(),
                                'subcategory'	=> $firstCourse->getCourseSubCategoryObj()->getId()
                            )
                    );
                    $brochureDataObj = array(
                        'sourcePage'       		=> 'category',
                        'courseId'         		=> $firstCourse->getId(),
                        'courseName'       		=> $firstCourse->getName(),
                        'universityId'     		=> $universityObject->getId(),
                        'universityName'   		=> $universityObject->getName(),
                        'widget'				=> 'request_callback',
                        'destinationCountryId'		=> $universityObject->getLocation()->getCountry()->getId(),
                        'destinationCountryName'	=> $universityObject->getLocation()->getCountry()->getName(),
                        'courseData'	      		=> base64_encode(json_encode($courseData))
                    );

                    if($counsellorData[$universityObject->getId()] > 0){
                        $brochureDataObj['trackingPageKeyId'] = 16;	?>
                        <!-- <div class="req-callbck">
                            <a href="Javascript:void(0)" onclick = "loadStudyAbroadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer');" ><i class="cate-sprite req-callbck-icn"></i>Request a call back</a>
                        </div> -->
                    <?php } */?>
                </div>
                <div class="tuple-detail" >
                    <div class="tuple-title">
                      <p class="sponsered-text">Sponsored</p>
                        <p>
                            <a target="_blank" href="<?php echo ($universityObject->getURL())?>"><?php echo  $universityObject->isMain() ? "<i class='cate-sprite paid-icon'></i>" :'' ?><?php echo htmlentities($universityObject->getName())?></a><span class="font-11">, <?php echo $universityObject->getLocation()->getCity()->getName()?>, <?php echo $universityObject->getLocation()->getCountry()->getName()?></span>
                        </p>
                    </div>
                    <div class="course-touple clearwidth">
                        <p><a target="_blank" href="<?php echo $firstCourse->getURL();?>" class="tuple-sub-title"><?php echo htmlentities($firstCourse->getName());?></a></p>
                        <div class="clearwidth">
                            <div class="uni-course-details flLt">
                                <div class="detail-col flLt" style="width:170px;">
                                    <?php $fees = $firstCourse->getTotalFees()->getValue();

                                    if($fees){
                                        $feesCurrency = $firstCourse->getTotalFees()->getCurrency();
                                        $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                                        $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
                                        $courseFees = str_replace("Lac","Lakh",$courseFees);
                                        ?>

                                        <strong> 1st Year Total Fees</strong>
                                        <p><?php echo $courseFees?></p>
                                    <?php }?>
                                </div>
                                <div class="detail-col flLt" style="width:130px;">
                                    <strong>Eligibility</strong>
                                    <?php	$examCount = 0;
                                    foreach($firstCourse->getEligibilityExams() as $examObj){
                                        if($examObj->getId() == -1){continue;}
                                        if(++$examCount >= 3){continue;}
                                        if($examObj->getCutoff() == "N/A"){
                                            $printSpan = true;
                                            $cutOffText = "Accepted";
                                        }
                                        else{
                                            $printSpan = false;
                                            $cutOffText = $examObj->getCutoff();
                                        }
                                        ?>
                                        <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;width:117px !important;">
                                            <?php
                                            if($printSpan){
                                                $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                                            }
                                            ?>
                                            <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>
                                        </p>
                                    <?php }?>
                                    <?php	if($examCount>=3){?>
                                        <a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
                                        <div class="extra-exam-div hide">
                                            <?php	$examCount = 0;
                                            foreach($firstCourse->getEligibilityExams() as $examObj){
                                                if($examObj->getId() == -1){continue;}
                                                if(++$examCount <= 2){continue;}
                                                if($examObj->getCutoff() == "N/A"){
                                                    $printSpan = true;
                                                    $cutOffText = "Accepted";
                                                }
                                                else{
                                                    $printSpan = false;
                                                    $cutOffText = $examObj->getCutoff();
                                                }
                                                ?>
                                                <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;width:117px !important;">
                                                    <?php
                                                    if($printSpan){
                                                        $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                                                    }
                                                    ?>
                                                    <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>
                                                </p>
                                            <?php }?>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="detail-col flLt" style="width:132px">
                                    <?php if($universityObject->getTypeOfInstitute() == 'public'){?>
                                        <p><span class="tick-mark">&#10004;</span>Public university</p>
                                    <?php }else{?>
                                        <p class="non-available"><span class="cross-mark">&times;</span>Public university</p>
                                    <?php }?>
                                    <?php if($firstCourse->isOfferingScholarship()){?>
                                        <p><span class="tick-mark">&#10004;</span>Scholarship</p>
                                    <?php }else{?>
                                        <p class="non-available"><span class="cross-mark">&times;</span>Scholarship</p>
                                    <?php }?>
                                    <?php if($universityObject->hasCampusAccommodation()){?>
                                        <p><span class="tick-mark">&#10004;</span>Accommodation</p>
                                    <?php }else{?>
                                        <p class="non-available"><span class="cross-mark">&times;</span>Accommodation</p>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="btn-col btn-brochure" style="<?php echo ($firstCourse->getCourseApplicationDetail() > 0?'margin-top: 8px !important':'margin-top: 27px !important')?>">
                                <?php

                                // prepare data now required for new single registration form */
                                $courseData = array( $firstCourse->getId() => array(
                                    'desiredCourse' => ($firstCourse->getDesiredCourseId()?$firstCourse->getDesiredCourseId():$firstCourse->getLDBCourseId()),
                                    'paid'		=> $firstCourse->isPaid(),
                                    'name'		=> $firstCourse->getName(),
                                    'subcategory'	=> $firstCourse->getCourseSubCategoryObj()->getId()
                                )
                                );
                                $brochureDataObj = array(
                                    'sourcePage'       => 'category',
                                    'courseId'         => $firstCourse->getId(),
                                    'courseName'       => $firstCourse->getName(),
                                    'universityId'     => $universityObject->getId(),
                                    'universityName'   => $universityObject->getName(),
                                    'widget'		=> 'category_page',
                                    'destinationCountryId'	=> $universityObject->getLocation()->getCountry()->getId(),
                                    'destinationCountryName'=> $universityObject->getLocation()->getCountry()->getName(),
                                    'courseData'	      => base64_encode(json_encode($courseData))
                                );
                                $brochureDataObj['widget'] = 'category_page';
                                $brochureDataObj['trackingPageKeyId'] = 14;
                                $brochureDataObj['consultantTrackingPageKeyId'] = 371;
                                ?>
                                <a href="Javascript:void(0);" class="button-style" onclick = "studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'DownloadBrochure'); loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><strong>Download Brochure</strong></a>
                            </div>

                            <?php
                            $brochureDataObj['pageTitle'] = str_replace("in All Countries","Abroad",$catPageTitle);
                            $brochureDataObj['userRmcCourses'] = $userRmcCourses;
                            // load rate my chance button if the applicationDetails are there and also for each course
                            if($firstCourse->showRmcButton())
                            {
                                $brochureDataObj['trackingPageKeyId'] = 366;
                                $brochureDataObj['courseObj'] = $firstCourse;
                                echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
                                unset($brochureDataObj['courseObj']);
                            }
                            ?>

                        </div>

                        <?php
                        $announcementObj = $universityObject->getAnnouncement();
                        if($announcementObj) {
                            $announcementText = $announcementObj->getAnnouncementText();
                            $announcementActionText = $announcementObj->getAnnouncementActionText();
                            $announcementStartDate = $announcementObj->getAnnouncementStartDate();
                            $announcementEndDate = $announcementObj->getAnnouncementEndDate();
                            $today = date("Y-m-d");
                            if($announcementText && $today >= $announcementStartDate && $today <= $announcementEndDate) {
                                ?>
                                <div class="category-update-sec clearwidth">
                                    <strong>Announcement</strong>: <?php echo $announcementText?> <br/> <?php echo $announcementActionText?>
                                </div>
                            <?php   }
                        } ?>

                        <div class="flLt" style="width:83%">
                            <?php if(count($courseObj) > 1){
                                $similarCourseMsg = "";
                                if(count($courseObj) == 2){$similarCourseMsg = "1 similar course";}
                                else{$similarCourseMsg = (count($courseObj)-1)." similar courses";}
                                ?>
                                <a href="javascript:void(0)" onclick="showHideSimilarCourse(this)" class="smlr-course-btn"><i class="cate-sprite plus-icon"></i><?php echo $similarCourseMsg?></a>
                            <?php }?>
                        </div>

                        <div class="compare-box flRt customInputs">
                            <?php
                            $checkedStatus = '';
                            if(in_array($firstCourse->getId(),$userComparedCourses)){
                                $checkedStatus = 'checked="checked"';
                            }
                            ?>
                            <input type="checkbox" name="compare<?=$firstCourse->getId()?>" id="compare<?=$firstCourse->getId()?>" class="compareCheckbox<?=$firstCourse->getId()?>" <?=$checkedStatus?>  onclick="toggleCompare('<?=$firstCourse->getId()?>')">
                            <label class="compareCheckboxLabel<?=$firstCourse->getId()?>" onclick="toggleCompare('<?=$firstCourse->getId()?>',546);">
                                <span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
                            </label>

                        </div>
                    </div>
                    <i title="<?php echo  $isFirstCourseShotlistedByUser ?"Click to remove":"Click to save" ?>" class="cate-sprite <?php echo  $isFirstCourseShotlistedByUser ? "added-shortlist" : "add-shortlist"?>" uniqueattr="ABROAD_CAT_PAGE/shortlistCourse"  onclick ="addRemoveFromShortlistedTab('<?php echo $firstCourse->getId()?>','categoryPageListing',this,'',18);"></i>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="similarCourseTuple">
                <?php $flagTuple = 0;
                foreach($courseObj as $courseTuple){
                    $isUserShortlistedSimilarCourse = in_array($courseTuple->getId(),$userShortlistedCourseIds['courseIds']);
                    if(++$flagTuple <= 1){continue;}
                    ?>
                    <div id ="categoryPageListing_tupleId_<?php echo $courseTuple->getId()?>" class="tuple-box tuple-separater hide">
                        <div class="tuple-detail">
                            <div class="tuple-title" style="margin-bottom: 0">
                                <p><a target="_blank" href="<?php echo $courseTuple->getURL();?>" class="tuple-sub-title" style="font-size:15px;color:#008489 !important"><?php echo htmlentities($courseTuple->getName());?></a></p>
                            </div>
                            <div class="course-touple clearwidth">

                                <div class="clearwidth">
                                    <div class="uni-course-details clearwidth">
                                        <div class="detail-col flLt" style="width:170px;">
                                            <?php $fees = $courseTuple->getTotalFees()->getValue();
                                            if($fees){
                                                $feesCurrency = $courseTuple->getTotalFees()->getCurrency();
                                                $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                                                $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
                                                $courseFees = str_replace("Lac","Lakh",$courseFees);
                                                ?>
                                                <strong>1st Year Total Fees</strong>
                                                <p><?php echo $courseFees?></p>
                                            <?php }?>
                                        </div>
                                        <div class="detail-col flLt" style="width:130px;">
                                            <strong>Eligibility</strong>
                                            <?php	$examCount = 0;
                                            foreach($courseTuple->getEligibilityExams() as $examObj){
                                                if($examObj->getId() == -1){continue;}
                                                if(++$examCount >= 3){continue;}
                                                if($examObj->getCutoff() == "N/A"){
                                                    $printSpan = true;
                                                    $cutOffText = "Accepted";
                                                }
                                                else{
                                                    $printSpan = false;
                                                    $cutOffText = $examObj->getCutoff();
                                                }
                                                ?>
                                                <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;">
                                                    <?php
                                                    if($printSpan){
                                                        $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                                                    }
                                                    ?>
                                                    <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>
                                                </p>
                                            <?php }?>
                                            <?php	if($examCount>=3){?>
                                                <a class="extra-exam-anchor" href="javascript:void(0)" onclick="showExamDiv(this)"><?php echo "+".($examCount-2)." more";?></a>
                                                <div class="extra-exam-div hide">
                                                    <?php	$examCount = 0;
                                                    foreach($courseTuple->getEligibilityExams() as $examObj){
                                                        if($examObj->getId() == -1){continue;}
                                                        if(++$examCount <= 2){continue;}
                                                        if($examObj->getCutoff() == "N/A"){
                                                            $printSpan = true;
                                                            $cutOffText = "Accepted";
                                                        }
                                                        else{
                                                            $printSpan = false;
                                                            $cutOffText = $examObj->getCutoff();
                                                        }
                                                        ?>
                                                        <p <?php echo ($printSpan)?" onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)'":""?> style="position: relative;">
                                                            <?php
                                                            if($printSpan){
                                                                $this->load->view("listing/abroad/widget/examAcceptedTooltip",array('examName'=>$examObj->getName()));
                                                            }
                                                            ?>
                                                            <?php echo htmlentities($examObj->getName())?>: <?php echo $cutOffText?>
                                                        </p>
                                                    <?php }?>
                                                </div>
                                            <?php }?>
                                        </div>
                                        <div class="detail-col flLt" style="width:132px">
                                            <?php if($universityObject->getTypeOfInstitute() == 'public'){?>
                                                <p><span class="tick-mark">&#10004;</span>Public university</p>
                                            <?php }else{?>
                                                <p class="non-available"><span class="cross-mark">&times;</span>Public university</p>
                                            <?php }?>
                                            <?php if($courseTuple->isOfferingScholarship()){?>
                                                <p><span class="tick-mark">&#10004;</span>Scholarship</p>
                                            <?php }else{?>
                                                <p class="non-available"><span class="cross-mark">&times;</span>Scholarship</p>
                                            <?php }?>
                                            <?php if($universityObject->hasCampusAccommodation()){?>
                                                <p><span class="tick-mark">&#10004;</span>Accommodation</p>
                                            <?php }else{?>
                                                <p class="non-available"><span class="cross-mark">&times;</span>Accommodation</p>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="btn-col btn-brochure" style="<?php echo ($courseTuple->getCourseApplicationDetail() > 0?'margin-top: 8px !important':'margin-top: 27px !important')?>">
                                        <?php
                                        /* prepare data now required for new single registration form */
                                        $courseData = array( $courseTuple->getId() => array(
                                            'desiredCourse' => ($courseTuple->getDesiredCourseId()?$courseTuple->getDesiredCourseId():$courseTuple->getLDBCourseId()),
                                            'paid'		=> $courseTuple->isPaid(),
                                            'name'		=> $courseTuple->getName(),
                                            'subcategory'	=> $courseTuple->getCourseSubCategoryObj()->getId()
                                        )
                                        );
                                        $brochureDataObj = array(
                                            'sourcePage'       => 'category',
                                            'courseId'         => $courseTuple->getId(),
                                            'courseName'       => $courseTuple->getName(),
                                            'universityId'     => $universityObject->getId(),
                                            'universityName'   => $universityObject->getName(),
                                            'widget'	      => 'category_page',
                                            'destinationCountryId'	=> $universityObject->getLocation()->getCountry()->getId(),
                                            'destinationCountryName'=> $universityObject->getLocation()->getCountry()->getName(),
                                            'trackingPageKeyId' => 14,
                                            'consultantTrackingPageKeyId' => 371,
                                            'courseData'	      => base64_encode(json_encode($courseData))
                                        );
                                        ?>
                                        <a href="Javascript:void(0);" class="button-style" onclick = "studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'DownloadBrochure'); loadBrochureDownloadForm('<?php echo base64_encode(json_encode($brochureDataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><strong>Download Brochure</strong></a>
                                    </div>

                                    <?php
                                    $brochureDataObj['pageTitle'] = str_replace("in All Countries","Abroad",$catPageTitle);
                                    $brochureDataObj['userRmcCourses'] = $userRmcCourses;
                                    // load rate my chance button
                                    if($courseTuple->showRmcButton())
                                    {
                                        $brochureDataObj['trackingPageKeyId'] = 366;
                                        $brochureDataObj['courseObj'] = $courseTuple;
                                        echo $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
                                        unset($brochureDataObj['courseObj']);
                                    }
                                    ?>
                                </div>

                                <div class="compare-box flRt customInputs">
                                    <?php
                                    $checkedStatus = '';
                                    if(in_array($courseTuple->getId(),$userComparedCourses)){
                                        $checkedStatus = 'checked="checked"';
                                    }
                                    ?>
                                    <input type="checkbox" name="compare<?=$courseTuple->getId()?>" id="compare<?=$courseTuple->getId()?>" class="compareCheckBox" <?=$checkedStatus?>  onclick="toggleCompare('<?=$firstCourse->getId()?>')">
                                    <label onclick="toggleCompare(<?=$courseTuple->getId()?>,546)">
                                        <span class="common-sprite"></span><p>Add<?=empty($checkedStatus)?'':'ed'?> to compare</p>
                                    </label>
                                </div>
                            </div>
                            <i class="cate-sprite <?php echo  $isUserShortlistedSimilarCourse ? "added-shortlist" : "add-shortlist"?>" onclick = "addRemoveFromShortlistedTab('<?php echo  $courseTuple->getId()?>','categoryPageListing',this,'',18);" ></i>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="clearwidth">
                <div class = "replaceWithConsultant" courseId="<?php echo $firstCourse->getId()?>" id="consultantTuple<?php echo $firstCourse->getId()?>"></div>
            </div>

            </li><?php
            // Showing BMS Banner..
            if($count == 2) {	?>
                <li class="clearwidth" style="border: 0 none; background: #fff"><div class="banner-cont2">
                        <!--<p>Advertisements</p>-->
                        <?php
                        $bannerProperties = array('pageId'=>'SA_CATEGORY', 'pageZone'=>'MIDDLE','shikshaCriteria' => $criteriaArray);
                        $this->load->view('common/banner',$bannerProperties);
                        ?>
                    </div>
                </li>
                <?php
            }
            if($count == 4)
            {	//BSB desktop
                //echo '<li id="bsbCP" class="clearwidth" style="border: 0 none; background: #fff"></li>';
                $this->load->view("categoryList/abroad/widget/scholarshipInterlinkingCards");
                $scholarshipWidgetShown = true;
            }
            //Do not show if it is exam accepting page
            if (!($categoryPageRequest->isExamCategoryPage())) {
                if ($count == 7) {
                    $this->load->view("categoryList/abroad/widget/categoryPageCountryRecommendations");
                } else if ($univCount == $count && $univCount < 7) {
                    $this->load->view("categoryList/abroad/widget/categoryPageCountryRecommendations");
                }
                if ($count == 10 || ($univCount < 10 && $univCount == $count)) {
                    //$this->load->view("categoryList/abroad/widget/scholarshipInterlinkingCards");
                    echo '<li id="bsbCP" class="clearwidth" style="border: 0 none; background: #fff"></li>';
                }
            }
        }
        if($scholarshipWidgetShown !== true && count($resultantUniversityObjects)>0){
            //BSB desktop
            //echo '<li id="bsbCP" class="clearwidth" style="border: 0 none; background: #fff"></li>';
            $this->load->view("categoryList/abroad/widget/scholarshipInterlinkingCards");
        }
        echo '</ul>';

        if(count($resultantUniversityObjects) + count($snapshotUniversities) == 0){
            $resetFilterHtml = "<a href='javascript:void(0);' onclick='resetAllFilters();' >reset filters</a> or ";
            ?>
            <?php if($categoryPageRequest->isExamCategoryPage()){ ?>
                <div class="zero-result clearwidth" >
                    <p>No colleges were found in the <?php echo $examsForFilter[reset($appliedFilters["exam"])]?> range <?php echo $categoryPageRequest->examScore[0];?>-<?php echo $categoryPageRequest->examScore[1];?>.</p>
                    <p><a href='javascript:void(0);' onclick="resetAllFilters();">Click here</a> to reset the filters.</p>
                </div>
            <?php }else{ ?>
                <div class="zero-result clearwidth" >
                    <p>Your search refinement resulted in zero results. </p>
                    <p>Please <?php echo $resetFilterHtml?><a href="javascript:void(0)" onclick="showAbroadOverlay('changeCourseCountryContDiv', 'Change Course/Country Preference');">change your course/country</a> combination and try again.</p>
                </div>
            <?php } ?>
        <?php }
        ?>

</div>
<script>
    function showExamDiv(obj){
        $j(obj).closest('.detail-col').find('.extra-exam-div').slideDown();
        $j(obj).hide();
    }
    function showinfotext(){
        var topoff = $j("#infotooltip").offset().top;
        var leftoff = $j("#infotooltip").offset().left;
        $j("#tooltip-hover-text").css({
            'position':'absolute',
            'top':topoff-44,
            'left': leftoff-17
        });
        $j('#tooltip-hover-text').show();
    }
    function hideinfotext(){
        $j('#tooltip-hover-text').hide();
    }

    function toggleCompare(courseId,source){
        addRemoveFromCompare(courseId,source);
    }
</script>
<!-- END : INSTITUTE TUPLE LISTING-->
