<?php
$TEST_API_DOMAIN = '172.16.3.107:9014';
$PROTOCOL   = (ENVIRONMENT != 'production') ? 'http://' : 'http://';
$API_DOMAIN = (ENVIRONMENT != 'production') ? $TEST_API_DOMAIN : 'apis.shiksha.jsb9.net';

$CLIENT_API_DOMAIN = (ENVIRONMENT != 'production') ? $TEST_API_DOMAIN : 'https://apis.shiksha.com';

$config['CHP_GET_HIERARCHY'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/getCHPRequiredData';
$config['CHP_BASECOURSE']    = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/getBaseCourseAndCredentials';
$config['CHP_LIST']          = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/getAllCHP';
$config['CHP_SAVECHP']       = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/saveCHPBasicDetailsWithAttributeMapping';

$config['CHP_SEARCH_BY_NAME']  = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/searchCHP';
$config['CHP_SEODATA']         = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/getSEODetails';
$config['CHP_SAVE_SEODATA']    = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/saveSEODetails';
$config['CHP_GET_WIKICONTENT'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/repo/getCHPContentById';
$config['CHP_SAVE_WIKICONTENT']= $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/saveCHPContent';

$config['CHP_GET_ALL_BASIC_INFO'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/getUrlsForPDF';

$config['SAVE_GUIDE_URL'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/cms/savePdfUrl';


$config['CHP_URL_BY_HIERACHIES']= $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/getMultipleChpUrlsByHierarchies';

$config['CHP_CREATE_CHP_CACHE'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/updateCHPCache';



$config['GET_CHP_PAGE_DATA'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/getCourseHomePage';

$config['GET_CHP_PAGE_DATA_CLIENT_API'] = $CLIENT_API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/getCourseHomePage';

/* API to get recent articles by hierarchy - created for ADP */
$config['GET_RECENT_ARTICLES'] = $PROTOCOL.$API_DOMAIN.'/apigateway/articleapi/v1/info/getRecentArticlesAndCHPs';
$config['GET_CHP_OTHER_TOPICS_UILP'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/getInterlinkingCHPsForUILP';
$config['GET_CHP_OTHER_TOPICS_CLP'] = $PROTOCOL.$API_DOMAIN.'/apigateway/coursehomepageapi/v1/info/getInterlinkingCHPsByAttributes';


