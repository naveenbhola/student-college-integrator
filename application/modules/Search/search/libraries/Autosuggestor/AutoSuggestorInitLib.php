<?php

class AutoSuggestorInitLib {

	private function _getAutoSuggestorOptions($type,$isMobile) {
		if(!empty($type)) {
			//checking for different config in different
			switch ($type) {
				case 'courseInstituteSearch': 
					if($isMobile){
						$suggestionContainerClass = 'college-course-list';
						$suggestionCount          = 6;
						$bucketCount              = 3;
						$showGrouping			  = 0;
						$getTrendingSuggestions	  = 0;
					}else{
						$suggestionContainerClass = 'search-college-layer';
						$suggestionCount          = 8;
						$bucketCount              = 4;
						$showGrouping			  = 1;
						$getTrendingSuggestions	  = 1;
					}
					$options =	array('suggestionContainerClass'=> $suggestionContainerClass,
				   					  'bucketCount' 			=> $bucketCount,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleAutoSuggestorTabPressed',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedSearch',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickedSearch',
				   					  'navigationKeysHandler'	=> 'handleNavigationKeysHandler',
				   					  'objectName' 				=> 'autoSuggestorInstanceSearch',
				   					  'searchBoxId' 			=> 'searchby-college', 
								   	  'suggestionContainerId' 	=> 'search-college-layer',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'course_and_institute',
								      'suggestionCount' 		=> $suggestionCount,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'isMobileSearch'          => $isMobile,
									  'showGrouping' 			=> $showGrouping,
									  'getTrendingSuggestions'	=> $getTrendingSuggestions
									   );
					break;
				case 'exams':
				//for exam search in shiksha
					if($isMobile){
						$suggestionContainerClass = 'college-course-list';
						$suggestionCount          = 6;
						$bucketCount              = 3;
						$showGrouping             = 0;
						$getTrendingSuggestions	  = 0;
					}
					else{
						$suggestionContainerClass = 'search-college-layer';
						$suggestionCount          = 8;
						$bucketCount              = 4;
						$showGrouping             = 1;
						$getTrendingSuggestions	  = 1;
					}
					$options = array(
							'suggestionContainerClass' => $suggestionContainerClass,
							'bucketCount'              => $bucketCount,
							'searchVersion'            => '2',
							'tabPressedHandler'        => 'handleAutoSuggestorTabPressedExams',
							'enterPressedHandler'      => 'handleAutoSuggestorEnterPressedExams',
							'mouseClickedHandler'      => 'handleAutoSuggestorEnterPressedExams',
							'backKeyHandler'    	   => 'handleAutoSuggestorBackKeyPressedExams',
							'objectName'               => 'autoSuggestorInstanceExam',
							'searchBoxId'              => 'searchby-exam', 
							'suggestionContainerId'    => 'search-exam-layer',
							'suggestionType'           => 'exam',
							'suggestionCount'          => $suggestionCount,
							'autoSuggestorUrl'         => '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
							'showGrouping'             => $showGrouping,
							'getTrendingSuggestions'	=> $getTrendingSuggestions
						);
					break;

				case 'question':
				//for question search in shiksha
					if($isMobile){
						$suggestionContainerClass = 'college-course-list';
						$suggestionCount          = 6;
						$bucketCount              = 3;
						$showGrouping             = 0;
						$getTrendingSuggestions	  = 0;
						$askNewQuestion			  = "/mAnA5/AnAMobile/getQuestionPostingAmpPage";
					}
					else{
						$suggestionContainerClass = 'search-college-layer';
						$suggestionCount          = 8;
						$bucketCount              = 4;
						$showGrouping             = 1;
						$getTrendingSuggestions	  = 1;
						$askNewQuestion			  = SHIKSHA_ASK_HOME;
					}
					$options = array(
							'suggestionContainerClass' 		 => $suggestionContainerClass,
							'bucketCount'              		 => $bucketCount,
							'searchVersion'            		 => '2',
							'tabPressedHandler'        		 => 'handleAutoSuggestorTabPressedQuestion',
							'enterPressedHandler'      		 => 'handleAutoSuggestorEnterPressedQuestion',
							'mouseClickedHandler'      		 => 'handleAutoSuggestorEnterPressedQuestion',
							'mouseClickedHandlerLastElement' => 'handleAutoSuggestorEnterPressedAskQuestion',
							'askNewQuestionURL'				 => $askNewQuestion,
							'mouseClickedHandlerCTA' 		 => 'handleAutoSuggestorEnterPressedCTA',
							'backKeyHandler'    	   		 => 'handleAutoSuggestorBackKeyPressedQuestion',
							'objectName'               		 => 'autoSuggestorInstanceQuestion',
							'searchBoxId'              		 => 'searchby-question', 
							'suggestionContainerId'    		 => 'search-question-layer',
							'removeExactMatch'				 => 1,
							'suggestionType'           		 => 'question',
							'suggestionCount'          		 => $suggestionCount,
							'isMobileSearch'           		 => $isMobile,
							'autoSuggestorUrl'         		 => '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
							'showGrouping'             		 => $showGrouping,
							'getTrendingSuggestions'   		 => $getTrendingSuggestions
						);
					break;

				case 'careers': 
					$options = array( 'suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleAutoSuggestorTabPressedCareers',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedCareers',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedCareers',
				   					  'objectName' 				=> 'autoSuggestorInstanceCareer',
				   					  'searchBoxId' 			=> 'searchby-career', 
								   	  'suggestionContainerId' 	=> 'search-career-layer',
								   	  'getTrendingSuggestions'	=> 1,
								   	  'typeAhead' 				=> 0,
								   	  'disableCache' 			=> 1,
								      'suggestionType' 			=> 'career',
								      'suggestionCount' 		=> 8
								   );
					break;

				case 'instituteReviewsHomepage':
					$options = array( 'suggestionContainerClass'=> 'suggestions_container',
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedCompare_CR',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickCompare_CR',
				   					  'objectName' 				=> 'autoSuggestorInstance_CR',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								   	  'disableCache' 			=> 0,
								      'suggestionType' 			=> 'institute',
								      'rightKeyHandler'			=> 'handleAutoSuggestorRightKeyPressedCompare_CR',
								      'callBackFunctionAfterBuildingSuggestionContainer'	=> 'handleAutoSuggestorAfterBuildingSuggestionContainer_CR',
								      'instituteSuggestionsIncludes'	=> 'reviews',
								      'suggestionCount' 		=> 6,
								      'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
								      'conditions'				=>  array('min_course_review' => 3),
								      'orderBy'					=> 'institute_view_count'
								   );
					break;

				case 'instituteCampusConnectHomepage':
					$options = array( 'suggestionContainerClass'=> 'suggestions_containerCampusConnect',
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedCompare_CC',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickCompare_CC',
				   					  'objectName' 				=> 'autoSuggestorInstance_CC',
				   					  'searchBoxId' 			=> 'keywordSuggestCampusConnect', 
								   	  'suggestionContainerId' 	=> 'suggestions_containerCampusConnect',
								   	  'typeAhead' 				=> 0,
								   	  'disableCache' 			=> 0,
								      'suggestionType' 			=> 'institute',
								      'rightKeyHandler'			=> 'handleAutoSuggestorRightKeyPressedCompare_CC',
								      'callBackFunctionAfterBuildingSuggestionContainer'	=> 'handleAutoSuggestorAfterBuildingSuggestionContainer_CC',
								      'suggestionCount' 		=> 8,
								      'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
								      'conditions'				=> array('cr_exists' => 1),
								      'orderBy'					=> 'institute_view_count',
								   );
					break;
					case 'campusConnectHomepage': // for mobile and desktop
					$options = array( 'suggestionContainerClass'=> 'suggestion-box',
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClick',
				   					  'objectName' 				=> 'autoSuggestorInstance',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								   	  'disableCache' 			=> 0,
								      'suggestionType' 			=> 'institute',
								      'rightKeyHandler'			=> '',
								      'callBackFunctionAfterBuildingSuggestionContainer'	=> 'handleAutoSuggestorAfterBuildingSuggestionContainer',
								      'suggestionCount' 		=> 8,
								      'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
								      'conditions'				=> array('cr_exists' => 1),
								      'orderBy'					=> 'institute_view_count',
								   );
					break;
					case 'instituteSearch': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleCampusAutoSuggestorTabPressed',
				   					  'enterPressedHandler' 	=> 'handleCampusAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleCampusAutoSuggestorMouseClick',
				   					  'navigationKeysHandler'	=> 'handleNavigationKeysHandler',
				   					  'objectName' 				=> 'autoSuggestorInstanceInstitute',
				   					  'searchBoxId' 			=> 'dummy_input', 
								   	  'suggestionContainerId' 	=> 'search-college-layer1',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									   );
					break;
					case 'instituteSearchReviewsCMS': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedCompare',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickCompare',
				   					  'objectName' 				=> 'autoSuggestorInstituteSearchReviewsCMS',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 0
									   );
					break;
					case 'universitySearchCMS': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleUniversityAutoSuggestorTabPressed',
				   					  'enterPressedHandler' 	=> 'handleUniversityAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleUniversityAutoSuggestorMouseClick',
				   					  'rightKeyHandler'			=> 'handleUniversityAutoSuggestorRightKeyPressed',
				   					  'objectName' 				=> 'autoSuggestorUniversitySearchCMS',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 1
									   );
					break;
					case 'instituteSearchCompare': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClick',
				   					  'objectName' 				=> 'autoSuggestorInstanceInstituteForCompare',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 5,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 0
									   );
					break;
					case 'instituteSearchCompare1': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClick',
				   					  'objectName' 				=> 'autoSuggestorInstanceInstituteForCompare1',
				   					  'searchBoxId' 			=> 'keywordSuggest1', 
								   	  'suggestionContainerId' 	=> 'suggestions_container1',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 5,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 0
									   );
					break;
					case 'instituteSearchCompareMobile': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedCompare',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickCompare',
				   					  'objectName' 				=> 'autoSuggestorInstanceInstituteForCompareMobile',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 0
									   );
					break;
					case 'instituteSearchReviewsMobile': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClick',
				   					  'objectName' 				=> 'autoSuggestorInstanceInstitute',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 0
									   );
					break;
					case 'viewAllTagsCMS': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'objectName' 				=> 'autoSuggestorInstanceForTags',
				   					  'searchBoxId' 			=> 'tagSearch', 
								   	  'suggestionContainerId' 	=> 'tag-list-container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'tag',
								      'suggestionCount' 		=> 10,
									  'autoSuggestorUrl' 		=> '/Tagging/TaggingDesktop/getAutoSuggestor',
									  'showGrouping' 			=> 1,
									  'disableCache' 			=> 1,
									  'removePartialMatch'		=> 0
									   );
					break;
					case 'vcmsAllTagsCMS': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'vcmsHandleAutoSuggestorEnterPressedTags',
				   					  'mouseClickedHandler' 	=> 'vcmsHandleAutoSuggestorEnterPressedTags',
				   					  'objectName' 				=> 'autoSuggestorInstanceForTags',
				   					  'searchBoxId' 			=> 'vcmsTagSearch', 
								   	  'suggestionContainerId' 	=> 'vcms-tag-list-container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'tag',
								      'suggestionCount' 		=> 10,
									  'autoSuggestorUrl' 		=> '/Tagging/TaggingDesktop/getAutoSuggestor',
									  'showGrouping' 			=> 1,
									  'disableCache' 			=> 1,
									  'removePartialMatch'		=> 0
									   );
					break;
					case 'instituteSearchCMS': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 20,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedInstiCMS',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedInstiCMS',
				   					  'objectName' 				=> 'autoSuggestorInstanceForInstitutesCMS',
				   					  'searchBoxId' 			=> 'instiSearch', 
								   	  'suggestionContainerId' 	=> 'institute-list-container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 40,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 1
									   );
					break;
					case 'instituteSearchExamCMS': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 20,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedExamInstiCMS',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedExamInstiCMS	',
				   					  'objectName' 				=> 'autoSuggestorInstanceForInstitutesCMS',
				   					  'searchBoxId' 			=> 'conductedBy', 
								   	  'suggestionContainerId' 	=> 'institute-list-container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 40,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 1
									   );
					break;
					case 'instituteSearchExam': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 20,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleInstituteAutoSuggestorTabPressed',
				   					  'enterPressedHandler' 	=> 'handleInstituteAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleInstituteAutoSuggestorMouseClick',
				   					  'rightKeyHandler'			=> 'handleInstituteAutoSuggestorRightKeyPressed',
				   					  'objectName' 				=> 'autoSuggestorInstanceForInstitutesCMS',
				   					  'searchBoxId' 			=> 'instField', 
								   	  'suggestionContainerId' 	=> 'institute-list-container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 40,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'showGrouping' 			=> 1
									   );
					break;
					case 'viewAllTags': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'objectName' 				=> 'autoSuggestorInstanceForTags',
				   					  'searchBoxId' 			=> 'tagSearch', 
								   	  'suggestionContainerId' 	=> 'tagSearch_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'tag',
								      'suggestionCount' 		=> 10,
									  'autoSuggestorUrl' 		=> '/Tagging/TaggingDesktop/getAutoSuggestor',
									  'showGrouping' 			=> 1,
									  'disableCache' 			=> 1,
									  'removePartialMatch'		=> 0
									   );
					break;
					case 'viewAllTagsQDP': 
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedTags',
				   					  'objectName' 				=> 'autoSuggestorInstanceForTagsQDP',
				   					  'searchBoxId' 			=> 'tagSearchQDP', 
								   	  'suggestionContainerId' 	=> 'tagSearchQDP_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'tag',
								      'suggestionCount' 		=> 6,
									  'autoSuggestorUrl' 		=> '/Tagging/TaggingDesktop/getAutoSuggestor',
									  'showGrouping' 			=> 1,
									  'disableCache' 			=> 1,
									  'callBackFunctionAfterBuildingSuggestionContainer' => 'showSuggestedTags',
									  'callBackFunctionOnInputKeysPressed' => 'showSuggestedTags',
									   );
					break;
					case 'allReviewsPage':
					$options = array( 'suggestionContainerClass'=> 'suggestions_container',
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedReviews',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorEnterPressedReviews',
				   					  'objectName' 				=> 'autoSuggestorInstance_CR',
				   					  'searchBoxId' 			=> 'lReviewSearch', 
								   	  'suggestionContainerId' 	=> 'reviewSearch_container',
								   	  'typeAhead' 				=> 0,
								   	  'disableCache' 			=> 1,
								      'suggestionType' 			=> 'institute',
								      'rightKeyHandler'			=> '',
								      'callBackFunctionAfterBuildingSuggestionContainer'	=> '',
								      'instituteSuggestionsIncludes'	=> 'reviews',
								      'suggestionCount' 		=> 3,
								      'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
								      'conditions'				=>  array('min_course_review' => 3),
								      'orderBy'					=> 'institute_view_count'
								   );
					break;
					case 'campusConnectModeration': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressed',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClick',
				   					  'objectName' 				=> 'autoSuggestorInstance',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									   );
					break;
					case 'myShortlistInstituteSearch': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedForMyShortlist',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickForMyShortlist',
				   					  'objectName' 				=> 'autoSuggestorInstance',
				   					  'searchBoxId' 			=> 'keywordSuggest', 
								   	  'suggestionContainerId' 	=> 'suggestions_container_shortlist',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 8,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									   );
					break;
					case 'myShortlistInstituteSearchOnMobile': 
					$options =	array('suggestionContainerClass'=> 'suggestion-box',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'enterPressedHandler' 	=> 'handleAutoSuggestorEnterPressedForMyShortlist',
				   					  'mouseClickedHandler' 	=> 'handleAutoSuggestorMouseClickForMyShortlist',
				   					  'objectName' 				=> 'autoSuggestorInstance',
				   					  'searchBoxId' 			=> 'keywordSuggestMyShortlist', 
								   	  'suggestionContainerId' 	=> 'suggestions_container_shortlist',
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'institute',
								      'suggestionCount' 		=> 5,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									   );
				break;
				case 'analyticsSearch': 
					
					$options =	array('suggestionContainerClass'=> 'search-college-layer',
				   					  'bucketCount' 			=> 4,
				   					  'searchVersion' 			=> '2',
				   					  'tabPressedHandler' 		=> 'handleAutoSuggestorTabPressed',
				   					  'enterPressedHandler' 	=> 'handleMouseClickTrendsSuggestor',
				   					  'mouseClickedHandler' 	=> 'handleMouseClickTrendsSuggestor',
				   					  'navigationKeysHandler'	=> 'handleMouseClickTrendsSuggestor',
				   					  'objectName' 				=> 'autoSuggestorInstanceSearchAnalytics',
								   	  'suggestionContainerId' 	=> 'search-analytics-layer',
				   					  'searchBoxId' 			=> 'srch-field-search', 
								   	  'typeAhead' 				=> 0,
								      'suggestionType' 			=> 'analytics',
								      'suggestionCount' 		=> 20,
									  'autoSuggestorUrl' 		=> '/search/AutoSuggestorV2/getSuggestionsFromSolr/',
									  'isMobileSearch'          => 1,
									  'showGrouping' 			=> 1,
									  'getTrendingSuggestions'	=> 0
									   );
					break;
				default:
					break;
			}
		}
		return $options;
	}
	/**
    * Purpose       : To get autosuggestor configuration array which will help in initializing its JS counterparts
    * Params        : $options: containing values about which type of auto suggestor you want
    * Author        : Ankit Garg
    * date          : 2015-07-21
    * return 	 	: returns corresponding config array
    */
	function createAutoSuggestorConfigArray($options,$isMobile = false) {
		$autosuggestorConfigArray = array();
		if(is_array($options)) {
			foreach($options as $val) {
				$autosuggestorConfigArray[$val]['options'] 				= $this->_getAutoSuggestorOptions($val,$isMobile);
			}
		}
		return $autosuggestorConfigArray;
	}
}

