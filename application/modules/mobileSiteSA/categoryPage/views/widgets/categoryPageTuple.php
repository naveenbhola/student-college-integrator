<?php
/*
 * Expected Inputs on this page :
 * 1. $courseObj = Array(); containg courses of same university. First course will be displayed as main course,
 *                  remaining courses will be displayed as similar courses.
 *                  In case of Only one course is there no similar courses section will be displayed.
 *
 * 2. $universityObject = University Object to which above courses array belong.
 *
 * 3. $this->abroadCategoryPageLib = if available, then pass this in input array of view file. Otherwise it will get loaded.
 *
 * 4. $this->abroadListingCommonLib = if available, then pass this in input array of view file. Otherwise it will get loaded.
 *
 * 5. The variables $counsellorData, $userShortlistedCourses, $identifier, $pageType should be set appropriately for various flows
 *
 * 6. Send the user's RMC Courses in the variable $userRmcCourses to this view file. If not sent, user will get 404's
 *
 * 7. Send the $rateMyChanceCtlr or you will face 500's
 *
 * 8. $compareTupleTrackingSource must be set, for the compare button to make sense.
 *
 * 9. $userComparedCourses : Optional, if not set they will be fetched from the library.
 *
 */
        $jqmString = 'href="javascript:void(0);"';
        if(empty($userRmcCourses)){
            $userRmcCourses = array();
        }
        if(!($this->abroadCategoryPageLib instanceof AbroadCategoryPageLib)){
            $this->abroadCategoryPageLib = $this->load->library('categoryList/AbroadCategoryPageLib');
        }
        if(!($this->abroadListingCommonLib instanceof AbroadListingCommonLib)){
            $this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
        }
        $tempCourseArr = array();
        foreach($courseObj as $key=>$course){
 	    if(!is_object($course) || $course instanceof Course || $course->getId() == "")
   	    {
        	continue;
            }
            $tempCourseArr[$course->getId()] = $course;
        }
        $courseObj = $tempCourseArr;
        unset($tempCourseArr);
        $imgUrl = $universityObject->getUniversityDefaultImgUrl('172x115');
        if($imgUrl == ''){
            $imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
        }
        
        if($categoryPageRequest){
            $courseObj = $this->abroadCategoryPageLib->sortEligibilityExam($categoryPageRequest,$courseObj);
        }else if($isShortlistPage){
            $courseObj = $this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses($courseObj);
        }
        $firstCourse = reset($courseObj);

    //code for changing the tracking for shortlisting functionlity
    if($isRecommendationPage){
        $changedRecommendedPageType = $pageType;
        $pageType                   = 'RecommendationPage_mob';
    }
?>
<section class="cate-tupple clearfix" data-enhance="false">
    <article class="cate-inner clearfix">
        <div class="content-inner" <?php echo ($courseToBeFocussed>0?'focusTarget="'.$firstCourse->getId().'"':'')?>>
          <?php if($universityObject->isSticky() || isset($mil) && $mil === true){?>
              <p class="img-sponsored-text">Sponsored</p>
          <?php }?>          
            <header class="clearfix">
                <a class="cate-shortlist flRt" href="javascript:void(0)" onclick="addRemoveFromShortlist(<?php echo $firstCourse->getId()?>,'<?php echo $identifier?>','<?php echo $pageType?>',this);">
                    <i class="sprite shortlist-icn<?php echo in_array($firstCourse->getId(),$userShortlistedCourses)?'-filled':''?>"></i>
                    <br />
                    <span>Save<?php echo in_array($firstCourse->getId(),$userShortlistedCourses)?'d':''?></span>
                </a>
                <div class="cate-title">
                    <?php
                        if($universityObject->isMain()){
                            $spriteClass = 'paid-icon';
                        }elseif (!$universityObject->isMain() && !$universityObject->isSticky() && $firstCourse->isPaid()) {
                            $spriteClass = 'gray-paid-icon';
                        }
                    ?>
                    <strong><a target="_blank" href="<?php echo $universityObject->getURL(); ?>"><?php echo  $spriteClass ? '<i class="sprite '.$spriteClass.'"></i>' :'' ?><?php echo  htmlentities($universityObject->getName())?></a></strong>
                    <span><?php echo  htmlentities($universityObject->getLocation()->getCity()->getName()).', '.htmlentities($universityObject->getLocation()->getCountry()->getName())?></span>
                </div>
            </header>
            <div class="tupple-details clearfix">
                <figure class="flLt" style="position: relative">
                    <a target="_blank" href="<?php echo $universityObject->getURL();?>">
                        <?php if($dontLoadImageWithoutLazy == true){?>
                                  <img class="lazy" style="width: 142px;height: 95px" data-src="<?php echo  $imgUrl;?>" src="" alt="<?php echo htmlentities($firstCourse->getName());?>"/>
                              <?php }else{?>
                            <img class="lazy" style="width: 142px;height: 95px" src="<?php echo  $imgUrl;?>" src="" alt="<?php echo htmlentities($firstCourse->getName());?>"/>
                        <?php }?>
                    </a>

                </figure>
                <aside>
                    <a class="admn-crsname" target="_blank" href="<?php echo $firstCourse->getURL()?>"><?php echo htmlentities(formatArticleTitle($firstCourse->getName(),74));?></a>
                </aside>

                <div class="tbl_div">
                    <div class="admn-details">
                        <?php $fees = $firstCourse->getTotalFees()->getValue();
                            if($fees){
                                    $feesCurrency = $firstCourse->getTotalFees()->getCurrency();
                                    $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                                    $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
                                    ?>
                                    <p>
                                        <label>1st Year Fees</label>
                                        <strong><a target="_blank" style="color:#333" href="<?php echo $firstCourse->getURL()?>" ><?php echo $courseFees?></a></strong>
                                    </p>
                        <?php } ?>
					</div>
					<div class="admn-details">
						<?php
                            foreach ($firstCourse->getEligibilityExams() as $examObj){
                                if($examObj->getId() != -1){
                                    if($examObj->getCutoff() == "N/A"){
                                        $cutOffText = 'Accepted';
                                    }else{
                                        $cutOffText = $examObj->getCutoff();
                                    }
                        ?>
                                    <p>
                                        <label>Eligibility</label>
                                        <strong><a target="_blank" style="color:#333" href="<?php echo $firstCourse->getURL()?>" ><?php echo   htmlentities($examObj->getName().' : '.$cutOffText)?></a></strong>
                                    </p>
                        <?php       break;
                                    }
                            }
                        ?>
                    </div>
                    <?php
                        $brochureDataObj =  array();
                        if($firstCourse->getCourseSubCategoryObj()){
                            $subcategoryData = $firstCourse->getCourseSubCategoryObj()->getId();
                        }else{
                            $subcategoryData = $firstCourse->getCourseSubCategory();
                        }
                        $courseData = array( $firstCourse->getId() => array(
                                                            'desiredCourse' => ($firstCourse->getDesiredCourseId()?$firstCourse->getDesiredCourseId():$firstCourse->getLDBCourseId()),
                                                            'paid'		=> $firstCourse->isPaid(),
                                                            'name'		=> $firstCourse->getName(),
                                                            'subcategory'	=> $subcategoryData
                                                            )
                                    );
                        $brochureDataObj = array(
                                               'sourcePage'             => 'category',
                                               'courseId'               => $firstCourse->getId(),
                                               'courseName'             => $firstCourse->getName(),
                                               'universityId'           => $universityObject->getId(),
                                               'universityName'         => $universityObject->getName(),
                                               'destinationCountryId'   => $universityObject->getLocation()->getCountry()->getId(),
                                               'destinationCountryName' => $universityObject->getLocation()->getCountry()->getName(),
                                               'courseData'	            => base64_encode(json_encode($courseData)),
                                               'mobile'                 => true
                                           );
                        if($isThankYouPage === true)
                        {
                            $brochureDataObj['customReferer'] = $customReferer;
                            $brochureDataObj['refererTitle'] = $refererTitle;
                        }
                        $brochureDataObj['widget'] = 'category_page';
                        $brochureDataObj['pageTitle'] = $catPageTitle;
                        if($isShortlistPage){
                            $brochureDataObj['widget'] = 'shortlistPage';
                            $brochureDataObj['sourcePage'] = 'shortlist';
                        }elseif($isRecommendationPage){
                            $brochureDataObj['widget'] = $identifier;
                            $brochureDataObj['sourcePage'] = $changedRecommendedPageType;
                        }elseif($isSearchPage){
                            $brochureDataObj['widget'] = 'searchPage';
                            $brochureDataObj['sourcePage'] = 'searchPage_mob';
                        }elseif($identifier == 'rmcSuccessPageRecommendation')
                        {
                            $brochureDataObj['widget'] = 'rmcSuccessPageRecommendation';
                            $brochureDataObj['sourcePage'] = 'rmcSuccessPageRecommendation';
                        }

                        /*if(!is_null($trackingPageKeyId))
                        {
                            $brochureDataObj['trackingPageKeyId'] = $trackingPageKeyId;
                        }
                        else*/
                        if($pageType == 'shortlistPage')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 52;
                        }
                        elseif($pageType == 'categoryPageListing_mob')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 51;
                        }
                        elseif($pageType == 'searchPage_mob')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 64;
                        }elseif($identifier == 'alsoViewed')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 65;
                        }elseif($identifier == 'rmcSuccessPageRecommendation')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 525;
                        }elseif ($identifier == 'rateMyChanceTuple')
                        {
                            $brochureDataObj['trackingPageKeyId'] = 610;
                        }

                    ?>
                </div>
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
            <div class="Annoncement-Box">
              <strong>Annoncement</strong>
              <div class="Announcement-section">
                <p> <?php echo $announcementText?> </p>
                  <p><?php echo $announcementActionText?></p>
              </div>
          </div>
           <?php   }
        } ?>
			<div class="compare-bro-sec clearfix">
				<a <?php echo $jqmString; ?> data-rel="dialog" data-transition="slide" onclick = "gaTrackEventCustom('ABROAD_CAT_PAGE','DownloadBrochure');loadBrochureForm('<?php echo base64_encode(json_encode($brochureDataObj))?>',this);" class="btn btn-primary btn-full flLt category-btn" style="margin-top:7px"><i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
				<a class="btn btn-primary btn-full flRt cat-compare-btn tupleCompareLink<?=$firstCourse->getId()?>" href="javascript:void(0);" onclick="addRemoveFromCompare('<?=$firstCourse->getId()?>','<?=$compareTupleTrackingSource?>');"><span class="vam"><?=in_array($firstCourse->getId(),$userComparedCourses)?'Added to ':''?>Compare</span></a>
			</div>
            <?php
                $pageTitle = $catPageTitle;
                if($isShortlistPage){
                    $pageTitle = "Saved Courses Page";
                }elseif($isRecommendationPage){
                    if($changedRecommendedPageType == 'course'){
                        if($catPageTitle != '')
                        {
                            $pageTitle = $catPageTitle;
                        }
                        else{
                            $pageTitle = $courseName;
                        }
                    }
                    else if($changedRecommendedPageType == 'searchPage_mob'){
                        $pageTitle = 'Search Results';
                    }
                    else if($changedRecommendedPageType == 'shortlist_page')
                    {
                        $pageTitle = 'Saved Courses Page';
                    }
                }elseif($isSearchPage){
                    $pageTitle = 'Search Results'; // this is for coming back from rmc page & success page
                }



            ?>
            <?php
                $brochureDataObj['pageTitle'] = $pageTitle;
				$brochureDataObj['userRmcCourses'] = $userRmcCourses;
                if($firstCourse->showRmcButton()){
                    if(!is_null($rmcRecoTrackingPageKeyId)){
                        $brochureDataObj['trackingPageKeyId'] = $rmcRecoTrackingPageKeyId;
                    }
                    else if($pageType == 'shortlistPage')
                    {
                        $brochureDataObj['trackingPageKeyId'] = 431;
                    }
                    elseif($pageType == 'categoryPageListing_mob')
                    {
                        $brochureDataObj['trackingPageKeyId'] = 430;
                    }
                    elseif($pageType == 'searchPage_mob')
                    {
                        $brochureDataObj['trackingPageKeyId'] = 432;
                    }elseif($identifier == 'alsoViewed')
                    {
                        $brochureDataObj['trackingPageKeyId'] = 433;
                    }elseif($identifier == 'rmcSuccessPageRecommendation')
                    {
                        $brochureDataObj['trackingPageKeyId'] = 523;
                    }
                    $brochureDataObj['courseObj'] = $firstCourse;
                    $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
                    $brochureDataObj['trackingPageKeyId'] = '';
                    unset($trackingPageKeyId);
                }
            ?>
            <?php if($userRmcComments[$firstCourse->getId()]!=""){ ?>
                <div class="counselor-feedback-box">
                    <strong>Counselor feedback</strong>
                    <p><?php echo $userRmcComments[$firstCourse->getId()]; ?></p>
                </div>
            <?php } ?>
            <?php
            if((($counsellorData[$universityObject->getId()] > 0) || ($rmcCounsellorData[$universityObject->getId()] > 0)) && !($firstCourse->getCourseApplicationDetail() > 0)){
                if($pageType == 'shortlistPage')
                {
                    $brochureDataObj['trackingPageKeyId'] = 53;
                }
                elseif($pageType == 'categoryPageListing_mob')
                {
                    $brochureDataObj['trackingPageKeyId'] = 54;
                }
                $brochureDataObj['widget'] = 'request_callback';
            ?>
                    <div class="req-btn-row">
                        <a href="#responseForm" data-rel="dialog" data-transition="slide" onclick = "loadBrochureForm('<?php echo base64_encode(json_encode($brochureDataObj))?>',this);" class="btn btn-gray btn-req btn-full"><i class="sprite call-icn"></i><span class="vam">Request A Callback</span></a>
                    </div>
            <?php
            }
            ?>
        </div>
        <?php
            $coursesCountForUniversity = count($courseObj);
            $similarCourseText = '';
            if($coursesCountForUniversity > 1){?>
        <div class="similar-courses-layer">
        <?php $courseCounter = 0;
            foreach($courseObj as $key=>$similarCourse){
                if(++$courseCounter == 1){ continue;}
                ?>
               <div class="similar-courses" style="display: none">
                    <header class="clearfix">
                        <a class="cate-shortlist flRt ui-link" href="javascript:void(0)" onclick="addRemoveFromShortlist(<?php echo $similarCourse->getId()?>,'<?php echo $identifier?>','<?php echo $pageType?>',this);">
                            <i class="sprite shortlist-icn<?php echo in_array($similarCourse->getId(),$userShortlistedCourses)?'-filled':''?>"></i>
                            <br />
                            <span>Save<?php echo in_array($similarCourse->getId(),$userShortlistedCourses)?'d':''?></span>
                        </a>
                        <a target="_blank" class=" cate-title course-title" href="<?php echo $similarCourse->getURL()?>"><?php echo htmlentities(formatArticleTitle($similarCourse->getName(),74));?></a>
                    </header>
                    <div class="tupple-details clearfix">
                        <div class="flLt similar-detail" style="width:100%;">
                            <div class="admn-details flLt" style="height:38px;">
                                <?php $fees = $similarCourse->getTotalFees()->getValue();
                                    if($fees){
                                        $feesCurrency = $similarCourse->getTotalFees()->getCurrency();
                                        $courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                                        $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
//                                        $courseFees = str_replace(array('Thousands','Thousand','Lakhs','Lakh','Crores','Crore'), array('K','K','L','L','Cr','Cr'), $courseFees);
                                ?>
                                            <p>
                                                <label>1st Year Fees</label>
                                                <strong><a target="_blank" style="color:#333" href="<?php echo $similarCourse->getURL()?>" ><?php echo $courseFees?></a></strong>
                                            </p>
                                <?php } ?>
                            </div>
							<div class="admn-details flRt" style="height:38px;">
								<?php
									foreach ($similarCourse->getEligibilityExams() as $examObj){
                                        if($examObj->getId() != -1){
                                            if($examObj->getCutoff() == "N/A"){
                                                $cutOffText = 'Accepted';
                                            }else{
                                                $cutOffText = $examObj->getCutoff();
                                            }
                                ?>
                                            <p>
                                                <label>Eligibility</label>
                                                <strong><a target="_blank" style="color:#333" href="<?php echo $similarCourse->getURL()?>"><?php echo   htmlentities($examObj->getName().' : '.$cutOffText)?></a></strong>
                                            </p>
                                <?php       break;
                                            }
                                    }
                                ?>
							</div>
                        </div>
                    </div>
					<div class="compare-bro-sec clearfix">
						<?php
							$brochureDataObj =  array();
							if($similarCourse->getCourseSubCategoryObj()){
								$subcategory	= $similarCourse->getCourseSubCategoryObj()->getId();
							}else{
								$subcategory  = $similarCourse->getCourseSubCategory();
							}
							$courseData = array( $similarCourse->getId() => array(
																'desiredCourse' => ($similarCourse->getDesiredCourseId()?$similarCourse->getDesiredCourseId():$similarCourse->getLDBCourseId()),
																'paid'		=> $similarCourse->isPaid(),
																'name'		=> $similarCourse->getName(),
																'subcategory'   => $subcategory
																)
										);
							$brochureDataObj = array(
												   'sourcePage'             => 'category',
												   'courseId'               => $similarCourse->getId(),
												   'courseName'             => $similarCourse->getName(),
												   'universityId'           => $universityObject->getId(),
												   'universityName'         => $universityObject->getName(),
												   'destinationCountryId'   => $universityObject->getLocation()->getCountry()->getId(),
												   'destinationCountryName' => $universityObject->getLocation()->getCountry()->getName(),
												   'courseData'	            => base64_encode(json_encode($courseData)),
												   'mobile'                 => true
											   );

							$brochureDataObj['widget'] = 'category_page';
							$brochureDataObj['pageTitle'] = $catPageTitle;
							if($isShortlistPage){
								$brochureDataObj['widget'] = 'shortlistPage';
								$brochureDataObj['sourcePage'] = 'shortlist';
							}elseif($isRecommendationPage){
								$brochureDataObj['widget'] = $identifier;
								$brochureDataObj['sourcePage'] = $changedRecommendedPageType;
							}elseif($isSearchPage){
								$brochureDataObj['widget'] = 'searchPage';
								$brochureDataObj['sourcePage'] = 'searchPage_mob';
							}
							$brochureDataObj['trackingPageKeyId'] = 51;
						?>
						<a style="margin:7px 0; width:49%;padding:7px" class="btn btn-primary btn-full flLt" <?php echo $jqmString; ?> data-rel="dialog" data-transition="slide"  onclick = "gaTrackEventCustom('ABROAD_CAT_PAGE','DownloadBrochure');loadBrochureForm('<?php echo base64_encode(json_encode($brochureDataObj))?>',this);" ><i class="sprite bro-icn"></i> <span class="vam">Email Brochure</span></a>
						<a class="btn btn-primary btn-full flRt cat-compare-btn tupleCompareLink<?=$similarCourse->getId()?>" href="javascript:void(0);" onclick="addRemoveFromCompare('<?=$similarCourse->getId()?>','<?=$compareTupleTrackingSource?>');"><span class="vam"><?=in_array($similarCourse->getId(),$userComparedCourses)?'Added to ':''?>Compare</span></a>
					</div>
                    <?php
                        $pageTitle = $catPageTitle;
                        if($isShortlistPage){
                            $pageTitle = "Saved Courses Page";
                        }elseif($isRecommendationPage){
                            if($changedRecommendedPageType == 'course'){
                                if($catPageTitle != '')
                                {
                                    $pageTitle = $catPageTitle;
                                }
                                else{
                                    $pageTitle = $courseName;
                                }
                            }
                            else if($changedRecommendedPageType == 'searchPage_mob'){
                                $pageTitle = 'Search Results';
                            }
                            else if($changedRecommendedPageType == 'shortlist_page')
                            {
                                $pageTitle = 'Saved Courses Page';
                            }
                        }elseif($isSearchPage){
                            $pageTitle = 'Search Results'; // this is for coming back from rmc page & success page
                        }
                    ?>
                    <?php
                        $brochureDataObj['pageTitle'] = $pageTitle;
                        $brochureDataObj['userRmcCourses'] = $userRmcCourses;
                        if($similarCourse->showRmcButton()){
                            if(!is_null($rmcRecoTrackingPageKeyId)){
                                $brochureDataObj['trackingPageKeyId'] = $rmcRecoTrackingPageKeyId;
                            }
                            else if($pageType == 'shortlistPage')
                            {
                                $brochureDataObj['trackingPageKeyId'] = 431;
                            }
                            elseif($pageType == 'categoryPageListing_mob')
                            {
                                $brochureDataObj['trackingPageKeyId'] = 430;
                            }
                            elseif($pageType == 'searchPage_mob')
                            {
                                $brochureDataObj['trackingPageKeyId'] = 432;
                            }elseif($identifier == 'alsoViewed')
                            {
                                $brochureDataObj['trackingPageKeyId'] = 433;
                            }elseif($identifier == 'rmcSuccessPageRecommendation')
                            {
                                $brochureDataObj['trackingPageKeyId'] = 523;
                            }
                            $brochureDataObj['courseObj'] = $similarCourse;
                            $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj);
                            unset($trackingPageKeyId);
                        }
                         if($userRmcComments[$firstCourse->getId()]!=""){ ?>
                        <div class="counselor-feedback-box">
                            <strong>Counselor feedback</strong>
                            <p><?php echo $userRmcComments[$firstCourse->getId()]; ?></p>
                        </div>
                    <?php } ?>
                </div>
            <?php }
                if($coursesCountForUniversity -1 == 1){
                    $similarCourseText = ($coursesCountForUniversity -1).' Similar course';
                }else{
                    $similarCourseText = ($coursesCountForUniversity -1).' Similar courses';
                } ?>
            </div>
        <?php }       if($similarCourseText != ''){ ?>
        <a style="border-bottom:1px solid #eee;" href="javascript:void(0)" class="similar-btn" onclick="toggleSimilarCourses(this,'<?php echo $similarCourseText?>')"><i class="sprite add-icn"></i><span class="vam"><?php echo $similarCourseText?></span></a>
        <?php }  ?>
        <?php
/*<div class="replaceWithConsultants" courseId="<?php echo $firstCourse->getId()?>" id="consultantTuple<?php echo $firstCourse->getId()?>"></div>    */
?>
</article>
</section>
