<?php
    //current widget
    $widget = "download_form_bottom";
    $userData = $validateuser;
    
    $sourcePage      = $brochureDataObj['sourcePage'      ];
    $courseId        = $brochureDataObj['courseId'        ];
    $courseName      = $brochureDataObj['courseName'      ];
    $universityId    = $brochureDataObj['universityId'    ];
    $universityName  = $brochureDataObj['universityName'  ];
    $userStartTimePrefWithExamsTaken = $brochureDataObj['userStartTimePrefWithExamsTaken'];
    // get decoded course data
    $courseList = json_decode(base64_decode($brochureDataObj['courseData']),TRUE);
    // get user desired course
    $userDesiredCourse = $brochureDataObj['userDesiredCourse'];
    // prepare data to be  sent via module run to form view
    $dataForModuleRun['widget'] = $widget;
    // for action type & tracking
    $dataForModuleRun['responseSource'] 	= 'response_abroad_'.$sourcePage.'_'.$widget;
    $dataForModuleRun['destinationCountry'] 	= $brochureDataObj['destinationCountryId'];
    $dataForModuleRun['destinationCountryName'] = $brochureDataObj['destinationCountryName'];
    $dataForModuleRun['pageReferer'] 		= $brochureDataObj['sourcePage'];
    $dataForModuleRun['listingTypeForBrochure'] = 'course';
    if(count($courseList) == 1){ // in case of course listing page we send these values straightaway
		$dataForModuleRun['clientCourseId'] 	= reset(array_keys($courseList));
		$desiredCourse 				= reset($courseList);
		$dataForModuleRun['desiredCourse'] 	= $desiredCourse['desiredCourse'];
		$dataForModuleRun['isPaid'] 		= $desiredCourse['paid'];
		$dataForModuleRun['specialization'] 	= $desiredCourse['subcategory'];
    }
    
    // prepare title for the layer
    $layerTitle = "Download brochure ";
    // based on source page the title will be different
    switch($sourcePage)
    {
        case 'course'       : $layerTitle .= 'for this course';//.$courseName;
			    $eventTrackerStringGA = "ABROAD_COURSE_PAGE";
                $listingType = $sourcePage;
                $listingTypeId = $courseId;
                $pageType   = $sourcePage.'_';
                //$dataForModuleRun['consultantTrackingPageKeyId'] = 380;
				$dataForModuleRun['courseId'] = $courseId;
				$dataForModuleRun['courses'] = array($courseObj);
                break;
        case 'university'   : //$layerTitle .= 'for courses in '.$universityName;
                $listingType = $sourcePage;
			    $eventTrackerStringGA = "ABROAD_UNIVERSITY_PAGE";
                $listingTypeId = $universityId;
                $pageType   = $sourcePage.'_';
                //$dataForModuleRun['consultantTrackingPageKeyId'] = 389;
				$dataForModuleRun['universityId'] = $universityId;
				$dataForModuleRun['universityObj'] = $universityObj;
				$dataForModuleRun['courses'] = $courses;
                break;
    }
    $dataForModuleRun['trackingPageKeyId'] = $MISTrackingDetails['trackingPageKeyId'];
	$dataForModuleRun['MISTrackingDetails'] = $MISTrackingDetails;
	$dataForModuleRun['singleSignUpFormType'] = 'response';
	$dataForModuleRun['isInlineForm'] = true;
	$checkIsValidResponseUser = Modules::run("registration/Forms/isValidAbroadUser",$loggedInUserData['userId'],null,'studyAbroadRevamped',array('returnDataFlag'=>1));
	$dataForModuleRun = array_merge($dataForModuleRun, $checkIsValidResponseUser);
	$dataForModuleRun['userWorkExperience'] = $checkIsValidResponseUser['userInfoArray']['workExperience'];
	if(!($loggedInUserData['userId']>0))
	{
		$cookieDataForLogin = array();
		if($sourcePage == 'course')
		{
			$cookieDataForLogin['courseId'] = $dataForModuleRun['courseId'];
		}else{
			$cookieDataForLogin['universityId'] = $dataForModuleRun['universityId'];
		}
		$cookieDataForLogin['trackingPageKeyId'] = $MISTrackingDetails['trackingPageKeyId'];
		$cookieDataForLogin['sourcePage'] = $sourcePage;
		$cookieDataForLogin['widget'] = $widget;
	}else{
		$abroadSignupLib->prepareDataForLoggedInUser($dataForModuleRun);
	}
	// extra user info
    $dataForModuleRun['userCity']  		  = $userStartTimePrefWithExamsTaken['userCity']; ;
    $dataForModuleRun['passport'] 		  = $userStartTimePrefWithExamsTaken['passport'];
    $dataForModuleRun['userPreferredDestinations']=  $userStartTimePrefWithExamsTaken['userPreferredDestinations'];
?>
<div class="brochure-form clearwidth" id = "brochure-form-inline">
	<div class="headings" style="margin-bottom:20px;">
	    <div class="icon-box"><i class="listing-sprite download-icon"></i></div>
	    <h2><?=($layerTitle)?></h2>
	    <i class="listing-sprite pointer"></i>
	</div>
	<div class="registered-title clearwidth">
	    <strong class="flLt">Tell us about yourself</strong>
	    <?php if($userData == "false"){ ?>
	    <a class="lg-lnk flRt font-11" href="Javascript:void(0);" onclick = "studyAbroadTrackEventByGA('<?=$eventTrackerStringGA?>', 'existingUserLogin');"data-login="<?php echo (base64_encode(json_encode($cookieDataForLogin))); ?>" data-src="/login?ar=MQ==">Already registered?</a>
	    <?php } ?>
		<input type="hidden" listingtype = "<?php echo $sourcePage; ?>"  listingtypeid = "<?php echo $listingTypeId; ?>" name="inlineformlisting"> 
	</div>
    
    <?php
	    echo Modules::run('registration/Forms/LDB',"SASingleRegistration",'SASingleRegistrationForm', $dataForModuleRun);
    ?>

    <?php if($responseCountForCourse > 50  && $sourcePage == 'course'){ // at least show that there were 50 responses ?>
	<div class="student-int-clr" style="color: #515151;margin: 0px 0px 10px 15px;"><?=$responseCountForCourse?> students have shown interest in last 3 months</div>
    <?php } ?>
    <div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('<?php echo IMGURL_SECURE; ?>/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
</div>
<script>
    var engageDownloadBrochureWithLogin = 0;
    var courseCount = <?php echo count($courseList);?>;
</script>
