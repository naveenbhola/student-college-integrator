<?php
class elasticCommonLib {

    public function getPageIdentifierMapping($pageIdentifier = "", $pageURL="", $childPageIdentifier) {
        $data = array();

        switch ($pageIdentifier) {
            case "mobileVerificationSMS":
            case "mobileVerificationSMSExam":
            case "smsResponse":
            case "hamburgerRightPanel":
            case "nationalSite":
            case "commonHeader":
            case "any":
            case "enterpriseCMS":
            case "hamburgerLeftPanelAMP":
            case "shiksha":
            case "facebook":
            case "rightHandLayerMobile":
            case "hamburgerLeftPanel":
            case "registrationLayer":
            case "Domestic":
            case "ContactUs":
            case "Student_Helpline":
            case "loginPage":
            case "eventCalendar":
            case "tagDetailPage":
            case "compareMobile":
            case "compareDesktop":
            case "comparePage":
            case "discussionTagDetailPage":
            case "unansweredTagDetailPage":
            case "allTagFollow":
            case "discussionTab":
            case "unansweredTab":
            case "getMentorPage":
            case "advisoryBoard":
            case "answerViewMorePage":
            case "commentViewMorePage":
            case "commentReplyPage":
            case "answerCommentPage":
            case "userProfileEditPage":
            case "mentorshipPage":
            case "campusRepresentativeIntermediatePage":
            case "careerCompasPage":
            case "campusRepresentative":
            case "campusAmbassadorForm":
            case "mentorshipForm":
            case "courseFaqHomePage":
            case "forgotPasswordPage":
            case "privacyPolicyPage":
            case "careersListPage":
            case "termsConditionsPage":
            case "userPublicProfilePage":
            case "contactUsPage":
            case "coursePageQuestionPosting":
            case "studentHelpLinePage":
            case "courseFaqPage":
            case "aboutUsPage":
            case "eventDetailsPage":
            case "viewAllTagsPage":
            case "articleAuthorProfilePage":
            case "shikshaAuthorsProfilePage":
            case "ShikshaEnterprise":
            case "shikshaComplaintPage":
            case "managementTeamPage":
            case "404Page":
            case "cafeModerationPanelLoginPage":
            case "counselorPage":
            case "expertProfileEditPage":
            case "counselorHomePage":
            case "responseExamPage":
            case "responsePage":
            case "registrationPage":
            case "userPointSystemInfoPage":
            case "applyHomePage":
            case "unsubscribePage":
            case "studentCommunicationHistoryPage":
            case "SecurityCheck":
            case "scholarshipDetailPage":
            case "compareCoursesPage":
            case "profileSettingPage":
            case "jeeResultPage":
            case "cookiePolicyPage":
            case "userPointSystemPage":
                $data = array("pageIdentifier" => "others", "childPageIdentifier" => $pageIdentifier);
                break;
            
            case "mobileVerificationMailer":
            case "mobileVerificationMailerExam":
            case "clientMailers":
            case "mailers":
                $data = array("pageIdentifier" => "mailers", "childPageIdentifier" => $pageIdentifier);
                break;

            case "univeristyEntityListingPage":
            case "instituteEntityListingPage":
            case "examEntityTrendPage":
                $data = array("pageIdentifier" => "shikshaTrends", "childPageIdentifier" => $pageIdentifier);
                break;

            case "collegeReviewPage":
            case "collegeReviewIntermediatePage":
            case "collegeReviewRatingForm":
                $data = array("pageIdentifier" => "collegeReviews", "childPageIdentifier" => $pageIdentifier);
                break;


            case "MMP":
                $data = array("pageIdentifier" => "MMP", "childPageIdentifier" => $pageIdentifier);
                break;

            case "careerDetailPage":
            case "careerCounselling":
            case "careerOpportunities":
            case "careerHomePage":
                $data = array("pageIdentifier" => "careerCentral", "childPageIdentifier" => $pageIdentifier);
                break;

            case "shortlistPage":
            case "myShortlist":
            case "shortlistedColleges":
                $data = array("pageIdentifier" => "shortlistPage", "childPageIdentifier" => $pageIdentifier);
                break;

            case "qnaPage":
            case "discussionPage":
            case "unansweredPage":
            case "allDiscussionsPage":
            case "questionDetailPage":
            case "discussionDetailPage":
            case "userDetailPage":
            case "announcementPage":
            case "cafeBuzzPage":
            case "announcementDetailPage":
            case "advisoryBoardPage":
            case "questionIntermediatePage":
            case "unAnsweredPage":
                $data = array("pageIdentifier" => "AnA", "childPageIdentifier" => $pageIdentifier);
                break;

            case "allQuestionsPage":
                if(!(strpos($pageURL, "ask.shiksha.com") === false)){
                    $data = array("pageIdentifier" => "AnA", "childPageIdentifier" => $pageIdentifier);
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => $pageIdentifier);
                }
                break;

            case "onlineFormDashboard":
            case "onlineFormPage":
            case "studentFormsDashBoardPage":
            case "onlineApplicationForm":
            case "onlineDisplayFormPage":
                $data = array("pageIdentifier" => "onlineForms", "childPageIdentifier" => $pageIdentifier);
                break;

            case "allCollegePredictorPage":
            case "clatpredictor":
            case "collegePredictor":
            case "iimPredictorInput":
            case "mahcetpredictor":
            case "nchmctpredictor":
            case "niftpredictor":
            case "iimPredictorOutput":
                $data = array("pageIdentifier" => "collegePredictor", "childPageIdentifier" => $pageIdentifier);
                break;

            case "rankPredictor":
            case "CatPercentilePredictor":
                $data = array("pageIdentifier" => "rankPredictor", "childPageIdentifier" => $pageIdentifier);
                break;

            case "homePage":
                $data = array("pageIdentifier" => "homePage", "childPageIdentifier" => $pageIdentifier);
                break;

            case "androidApp":
                $data = array("pageIdentifier" => "androidApp", "childPageIdentifier" => $pageIdentifier);
                break;

            case "examPageMain":
                $childPageIentifier = "examPage";
                $urlParts = parse_url($pageURL."/");
                $requestPath = $urlParts['path'];
                $requestPath = rtrim($requestPath,"/");
                $requestPath = explode("/", $requestPath);
                $parts = count($requestPath);
                //_p($requestPath);
                $requestPath = $requestPath[$parts-1];

                if(!(strpos($requestPath, "-pc-") === false)){
                    $childPageIentifier = "allExamPage";
                }else if(!(strpos($requestPath, "-st-") === false)){
                    $childPageIentifier = "allExamPage";
                }else if(!(strpos($requestPath, "-sb-") === false)){
                    $childPageIentifier = "allExamPage";
                }else if(!(strpos($requestPath, "admit-card") === false)){
                    $childPageIentifier = "examAdmitCardPage";
                }else if(!(strpos($requestPath, "answer-key")=== false)){
                    $childPageIentifier = "examAnswerKeyPage";
                }else if(!(strpos($requestPath, "dates") === false )){
                    $childPageIentifier = "examImportantDatesPage";
                }else if(!(strpos($requestPath, "application-form") === false )){
                    $childPageIentifier = "examApplicationFormPage";
                }else if(!(strpos($requestPath, "counselling") === false )){
                    $childPageIentifier = "examCounsellingPage";
                }else if(!(strpos($requestPath, "cutoff") === false )){
                    $childPageIentifier = "examCutOffPage";
                }else if(!(strpos($requestPath, "pattern") === false )){
                    $childPageIentifier = "examPatternPage";
                }else if(!(strpos($requestPath, "results") === false )){
                    $childPageIentifier = "examResultsPage";
                }else if(!(strpos($requestPath, "question-papers") === false )){
                    $childPageIentifier = "examQuestionPapersPage";
                }else if(!(strpos($requestPath, "slot-booking") === false )){
                    $childPageIentifier = "examSlotBookingPage";
                }else if(!(strpos($requestPath, "syllabus") === false )){
                    $childPageIentifier = "examSyllabusPage";
                }else if(!(strpos($requestPath, "vacancies") === false )){
                    $childPageIentifier = "examVacanciesPage";
                }else if(!(strpos($requestPath, "call-letter") === false )){
                    $childPageIentifier = "examCallLettersPage";
                }else if(!(strpos($requestPath, "news") === false )){
                    $childPageIentifier = "examNewsPage";
                }else if(!(strpos($requestPath, "preptips") === false )){
                    $childPageIentifier = "examPrepTipsPage";
                }else if(!(strpos($requestPath, "-exam") === false )){
                    $childPageIentifier = "examPageMain";
                }else{
                    $childPageIentifier = "examPageMain";
                }
                if($childPageIdentifier == "examPageMain"){
                    $data = array("pageIdentifier" => "examPageMain", "childPageIdentifier" => $childPageIentifier);
                }else{
                    $data = array("pageIdentifier" => "examPageMain", "childPageIdentifier" => $pageIdentifier);
                }
                    
                break;

            case "allExamPage":
            case "examLandingPage":
                $data = array("pageIdentifier" => "examPageMain", "childPageIdentifier" => "allExamPage");
                break;

            case "articlePage":
            case "articleDetailPage":
            case "viteeeResults":
            case "srmResults":
            case "allArticlePage":
            case "boards":
            case "coursesAfter12th":
                $data = array("pageIdentifier" => "articlePage", "childPageIdentifier" => $pageIdentifier);
                break;

            case "courseHomePage":
                $data = array("pageIdentifier" => "courseHomePage", "childPageIdentifier" => $pageIdentifier);
                break;

            case "courseListingPage":
            case "courseDetailsPage":
            case "Desktop_CourseListingPage_National":
                $data = array("pageIdentifier" => "CLP", "childPageIdentifier" => "courseListingPage");
                break;

            case "rankingPage":
                $data = array("pageIdentifier" => "rankingPage", "childPageIdentifier" => $pageIdentifier);
                break;

            case "categoryPage":
            case "searchPage":
            case "searchV2Page":
            case "SRP":
            case "Global_Search":
            case "qnaSRPPage":
            case "collegeSRPPage":
            case "examSRPPage":
            case "searchWidgetPage":
                $data = array("pageIdentifier" => "search", "childPageIdentifier" => $pageIdentifier);
                break;

            case "instituteListingPage":
            case "allCoursePage":
            case "allReviewsPage":
            case "allArticlesPage":
            case "universityListingPage":
            case "cutOffPage":
            case "placementPage":
                $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => $pageIdentifier);
                break;

            case "CollegeCutOffPage":
                $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "cutOffPage");
                break;

            case "admissionPage":
                if(!(strpos($pageURL, "/admission") === false)){
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "admissionPage");
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "scholarshipPage");
                }                
                break;

            case "scholarshipPage":
                if(!(strpos($pageURL, "/scholarships") === false)){
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "scholarshipPage");
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "admissionPage");
                }                
                break;

            case "allCoursesPage":
                $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "allCoursePage");
                break;

            case "CLP":
            case "UILP":
            case "careerCentral":
			case "shortlistPage":
			case "AnA":
			case "onlineForms":
			case "collegePredictor":
			case "rankPredictor":
			case "homePage":
			case "androidApp":
			case "articlePage":
			case "courseHomePage":
			case "search":
                $data = array("pageIdentifier" => $pageIdentifier, "childPageIdentifier" => $childPageIdentifier);
                break;

            case "CHP":
                $data = array("pageIdentifier" => "courseHomePage", "childPageIdentifier" => "courseHomePage");
                break;

            case "iimPercentileInput":
            case "iimPercentileOutput":
                $data = array("pageIdentifier" => "collegePredictor", "childPageIdentifier" => $pageIdentifier);
                break;                

            default:
                $data = array("pageIdentifier" => $pageIdentifier, "childPageIdentifier" => "unknown");
                break;
        }
          
        //echo $requestPath."  :::::::::::::::::::::::::::::::::::::::::".$childPageIentifier."<br>";
        #_p($childPageIentifier);
        return $data;
    }
}
?>
