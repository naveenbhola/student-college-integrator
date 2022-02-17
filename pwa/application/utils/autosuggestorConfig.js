import config from './../../config/config';

export const tagAutosuggestorConfig = {
  'options' : {
    'suggestionContainerClass' : 'search-college-layer',
    'bucketCount' : 4,
    'searchVersion' : '2',
    'enterPressedHandler' : 'handleAutoSuggestorMouseClickedTags',
    'mouseClickedHandler' : 'handleAutoSuggestorMouseClickedTags',
    'objectName' : 'autoSuggestorInstanceForTags',
    'searchBoxId' : 'tagSearch',
    'suggestionContainerId' : 'tagSearch_container',
    'typeAhead' 				: 0,
    'suggestionType' 			: 'tag',
    'suggestionCount' 		: 7,
    'autoSuggestorUrl' 		: config().SHIKSHA_HOME+'/Tagging/TaggingDesktop/getAutoSuggestor',
    'showGrouping' 			: 1,
    'disableCache' 			: 1,
    'removePartialMatch' : 0
  }
};

export const courseInstituteAutosuggestorConfig = {
  'options' : {
    'suggestionContainerClass': 'search-college-layer',
    'bucketCount' 			: 4,
    'searchVersion' 			: '2',
    'tabPressedHandler' 		: 'handleAutoSuggestorTabPressed',
    'enterPressedHandler' 	: 'handleAutoSuggestorEnterPressedSearch',
    'mouseClickedHandler' 	: 'handleAutoSuggestorMouseClickedSearch',
    'navigationKeysHandler'	: 'handleNavigationKeysHandler',
    'objectName' 				: 'autoSuggestorInstanceSearch',
    'searchBoxId' 			: 'searchby-college',
    'suggestionContainerId' 	: 'search-college-layer',
    'typeAhead' 				: 0,
    'suggestionType' 			: 'course_and_institute',
    'suggestionCount' 		: 8,
    'autoSuggestorUrl' 		: config().SHIKSHA_HOME+'/search/AutoSuggestorV2/getSuggestionsFromSolr/',
    'isMobileSearch'          : 0,
    'showGrouping' 			: 1,
    'getTrendingSuggestions'	: 1
  }
};

export const careersAutosuggestorConfig = {
  'options' : {
    'suggestionContainerClass': 'search-college-layer',
    'bucketCount' 			: 4,
    'searchVersion' 			: '2',
    'tabPressedHandler' 		: 'handleAutoSuggestorTabPressedCareers',
    'enterPressedHandler' 	: 'handleAutoSuggestorEnterPressedCareers',
    'mouseClickedHandler' 	: 'handleAutoSuggestorEnterPressedCareers',
    'objectName' 				: 'autoSuggestorInstanceCareer',
    'searchBoxId' 			: 'searchby-career',
    'suggestionContainerId' 	: 'search-career-layer',
    'getTrendingSuggestions'	: 1,
    'typeAhead' 				: 0,
    'disableCache' 			: 1,
    'suggestionType' 			: 'career',
    'suggestionCount' 		: 8
  }
};

export const examsAutosuggestorConfig = {
  'options' : {
    'suggestionContainerClass' : 'search-college-layer',
    'bucketCount'              : 4,
    'searchVersion'            : '2',
    'tabPressedHandler'        : 'handleAutoSuggestorTabPressedExams',
    'enterPressedHandler'      : 'handleAutoSuggestorEnterPressedExams',
    'mouseClickedHandler'      : 'handleAutoSuggestorEnterPressedExams',
    'backKeyHandler'    	     : 'handleAutoSuggestorBackKeyPressedExams',
    'objectName'               : 'autoSuggestorInstanceExam',
    'searchBoxId'              : 'searchby-exam',
    'suggestionContainerId'    : 'search-exam-layer',
    'suggestionType'           : 'exam',
    'suggestionCount'          : 8,
    'autoSuggestorUrl'         : config().SHIKSHA_HOME+'/search/AutoSuggestorV2/getSuggestionsFromSolr/',
    'showGrouping'             : 1,
    'getTrendingSuggestions'	 : 1
  }
};

export const questionsAutosuggestorConfig = {
  'options' : {
    'suggestionContainerClass' 		 : 'search-college-layer',
    'bucketCount'              		 : 4,
    'searchVersion'            		 : '2',
    'tabPressedHandler'        		 : 'handleAutoSuggestorTabPressedQuestion',
    'enterPressedHandler'      		 : 'handleAutoSuggestorEnterPressedQuestion',
    'mouseClickedHandler'      		 : 'handleAutoSuggestorEnterPressedQuestion',
    'mouseClickedHandlerLastElement' : 'handleAutoSuggestorEnterPressedAskQuestion',
    'askNewQuestionURL'				 : config().SHIKSHA_ASK_HOME,
    'mouseClickedHandlerCTA' 		 : 'handleAutoSuggestorEnterPressedCTA',
    'backKeyHandler'    	   		 : 'handleAutoSuggestorBackKeyPressedQuestion',
    'objectName'               		 : 'autoSuggestorInstanceQuestion',
    'searchBoxId'              		 : 'searchby-question',
    'suggestionContainerId'    		 : 'search-question-layer',
    'removeExactMatch'				 : 1,
    'suggestionType'           		 : 'question',
    'suggestionCount'          		 : 8,
    'isMobileSearch'           		 : 0,
    'autoSuggestorUrl'         		 : config().SHIKSHA_HOME+'/search/AutoSuggestorV2/getSuggestionsFromSolr/',
    'showGrouping'             		 : 1,
    'getTrendingSuggestions'   		 : 1
  }
};
