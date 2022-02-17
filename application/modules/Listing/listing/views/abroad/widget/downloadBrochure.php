<?php
    //current widget
    $widget = "download_form_bottom";
    $userData = $validateuser;
    
    $sourcePage      = $brochureDataObj['sourcePage'      ];
    $courseId        = $brochureDataObj['courseId'        ];
    $courseName      = $brochureDataObj['courseName'      ];
    $universityId    = $brochureDataObj['universityId'    ];
    $universityName  = $brochureDataObj['universityName'  ];
    $departmentId    = $brochureDataObj['departmentId'    ];
    $departmentName  = $brochureDataObj['departmentName'  ];
    $userStartTimePrefWithExamsTaken = $brochureDataObj['userStartTimePrefWithExamsTaken'];
    $userContactByConsultant   = $userStartTimePrefWithExamsTaken['contactByConsultant'];
    $userCity		       = $userStartTimePrefWithExamsTaken['userCity'];
    $passport		       = $userStartTimePrefWithExamsTaken['passport'];
    $userPreferredDestinations = $userStartTimePrefWithExamsTaken['userPreferredDestinations'];
    // get decoded course data
    $courseList = json_decode(base64_decode($brochureDataObj['courseData']),TRUE);
    // get user desired course
    $userDesiredCourse = $brochureDataObj['userDesiredCourse'];
    // prepare data to be  sent via module run to form view
    $dataForModuleRun['widget'] = $widget;
    // for action type & tracking
    $dataForModuleRun['responseSourcePage'] 	= 'response_abroad_'.$sourcePage.'_'.$widget;
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
    


    // based on user logged in status
    if($userData != "false" && !empty($userData)){
        // user is logged in 
	$firstname  = $userData[0]['firstname'];
	$lastname   = $userData[0]['lastname'];
	$mobile     = $userData[0]['mobile'];
	$cookiestr  = $userData[0]['cookiestr'];
	$loggedIn   = "true";
        // no need for form validations
        $required   = 'required=false';
        // no need to show the fields if when to start & education were both filled , show otherwise
        if($showWhenPlanToGo == false && $showExams == false)
        {
            $extraFieldsUnavailable = 1;
            $elemDisplay= 'display:none';
            $disabled = ' disabled="disabled" ';
	    $disabledClass = ' disable-field ';
        }
        else{
            $elemDisplay = '';
            $disabled = ' disabled="disabled" ';
	    $disabledClass = ' disable-field ';
        }
    }
    else{
            //user not logged in 
            $loggedIn = "false";
            // validations required
            $required = 'required=true';
            // display  the fields
            $elemDisplay= '';
    }
    
    // when desired course for user is not available, need to ask user for contact by consultant value
    if($userDesiredCourse > 0)
    {
	$dataForModuleRun['contactByConsultant'] = 'yes';
    }
    else{
	$dataForModuleRun['contactByConsultant'] = ($loggedIn!="true")?'yes':$userContactByConsultant;
    }
    $dataForModuleRun['userContactByConsultant']  = $userContactByConsultant ;
    $dataForModuleRun['userCity']  		  = $userCity ;
    $dataForModuleRun['passport'] 		  = $passport;
    $dataForModuleRun['userPreferredDestinations']= $userPreferredDestinations;
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
                              $dataForModuleRun['trackingPageKeyId'] = 39;
                              $dataForModuleRun['consultantTrackingPageKeyId'] = 380;
                            break;
        case 'university'   : //$layerTitle .= 'for courses in '.$universityName;
                              $listingType = $sourcePage;
			      $eventTrackerStringGA = "ABROAD_UNIVERSITY_PAGE";
                              $listingTypeId = $universityId;
                              $pageType   = $sourcePage.'_';
                              $dataForModuleRun['trackingPageKeyId'] = 2;
                              $dataForModuleRun['consultantTrackingPageKeyId'] = 389;
                            break;
        case 'department'   : //$layerTitle .= 'for courses in '.$departmentName;
			      $eventTrackerStringGA = "ABROAD_DEPARTMENT_PAGE";
                              $listingTypeId = $sourcePage;
                              $pageType   = $sourcePage.'_';
                              $dataForModuleRun['trackingPageKeyId'] = 47;
                              $dataForModuleRun['consultantTrackingPageKeyId'] = 384;
                            break;
    }
?>
<div class="brochure-form clearwidth" id = "brochure-form-inline">
	<div class="headings" style="margin-bottom:20px;">
	    <div class="icon-box"><i class="listing-sprite download-icon"></i></div>
	    <h2><?=($layerTitle)?></h2>
	    <i class="listing-sprite pointer"></i>
	</div>
    <?php if (count($courseList)>1){ ?>
	<div class="flLt signup-txtwidth" style="margin-left: 17px; width: 46%;"><?php //($showOnlyCourseDropdown?'margin-bottom: 20px;':'') ?>
	    <div class="custom-dropdown">
		<select name="university_course_list_select" class="universal-select signup-select" id="listing_course_list_select_bottom" caption = "course" onchange="listingCourseSelectChangeBottom();showEducationFieldsBottom(this);">
		    <option value="">Please select a course</option>
		<?php foreach ($courseList as $key => $value) { ?>
		    <option ispaid = "<?=($value['paid']?1:0)?>" clientCourseId = "<?=$key?>" subcategory = "<?=$value['subcategory']?>" value="<?php echo $value['desiredCourse']; ?>"><?php echo $value['name']; ?></option>
		<?php } ?>
		</select>
	    </div>
	    <div>
		<div class="errorMsg" id="listing_course_list_select_bottom_error"></div>
	    </div>
	</div>
    <?php } ?>
    
	<div class="registered-title clearwidth">
	    <strong class="flLt">Tell us about yourself</strong>
	    <?php if($userData == "false"){ ?>
	    <a class="flRt font-11" href="Javascript:void(0);" onclick = "studyAbroadTrackEventByGA('<?=$eventTrackerStringGA?>', 'existingUserLogin'); registrationOverlayComponent.hideOverlay(); loadStudyAbroadForm({'isStudyAbroadPage':1},'/registration/Forms/showLoginLayer','loginFormContainer');engageDownloadBrochureWithLogin = 1; return false;">Already registered?</a>
	    <?php } ?>
	</div>
    <?php
	    echo Modules::run('registration/Forms/LDB',"studyAbroadRevamped",'downloadEbrochureSABottom', $dataForModuleRun);
    ?>

    <?php if($responseCountForCourse > 50  && $sourcePage == 'course'){ // at least show that there were 50 responses ?>
	<div class="student-int-clr" style="color: #515151;margin: 0px 0px 10px 15px;"><?=$responseCountForCourse?> students have shown interest in last 3 months</div>
    <?php } ?>
    <div style="position: fixed;top: 0px; left: 0px; opacity: 0.7; background: url('/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFull"></div>
</div>
<script>
    var engageDownloadBrochureWithLogin = 0;
    var courseCount = <?php echo count($courseList);?>;
</script>