<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security.
|
*/
global $appAPIVersion;
$appVersionFolder = "v".$appAPIVersion;
/*
* Sample for API with parameters
* $route['(.*)/User/fbLogin(.*)']  = $appVersionFolder."/User/fbLogin$2";
*/
$route['default_controller']                                  = "shiksha";
$route['scaffolding_trigger']                                 = "";
$route['(.*)/registerDevice']                                 = "common_api/CommonAPI/registerDevice";
$route['(.*)/User/fbLogin']                                   = $appVersionFolder."/User/fbLogin";
$route['(.*)/User/register']                                  = $appVersionFolder."/User/register";
$route['(.*)/User/resetPassword']                             = $appVersionFolder."/User/resetPassword";
$route['(.*)/User/login']                                     = $appVersionFolder."/User/login";
$route['(.*)/User/forgotPassword']                            = $appVersionFolder."/User/forgotPassword";
$route['(.*)/User/insertUserProfileData']                     = $appVersionFolder."/User/insertUserProfileData";
$route['(.*)/Universal/followEntity']                         = $appVersionFolder."/Universal/followEntity";
$route['(.*)/AnAPost/postingIntermediatePage']                = $appVersionFolder."/AnAPost/postingIntermediatePage";
$route['(.*)/Universal/autoSuggestor(.*)']                    = $appVersionFolder."/Universal/autoSuggestor$2";
$route['(.*)/AnA/getHomepageData(.*)']                        = $appVersionFolder."/AnA/getHomepageData$2";
$route['(.*)/UserProfileBuilder/userProfileBuilderData(.*)']  = $appVersionFolder."/UserProfileBuilder/userProfileBuilderData$2";
$route['(.*)/UserProfileBuilder/insertProfileBuilderTagData'] = $appVersionFolder."/UserProfileBuilder/insertProfileBuilderTagData";
$route['(.*)/UserProfileBuilder/getCoursesList'] 	      = $appVersionFolder."/UserProfileBuilder/getCoursesList";
$route['(.*)/UserProfileBuilder/getSpecializationList']	      = $appVersionFolder."/UserProfileBuilder/getSpecializationList";
$route['(.*)/UserProfileBuilder/getCities']		      = $appVersionFolder."/UserProfileBuilder/getCities";
$route['(.*)/AnAPost/postEntity']                             = $appVersionFolder."/AnAPost/postEntity";
$route['(.*)/AnA/getQuestionDetailWithAnswers(.*)']           = $appVersionFolder."/AnA/getQuestionDetailWithAnswers$2";
$route['(.*)/AnA/getDiscussionDetailWithComments(.*)']        = $appVersionFolder."/AnA/getDiscussionDetailWithComments$2";
$route['(.*)/AnAPost/postAnswer']                             = $appVersionFolder."/AnAPost/postAnswer";
$route['(.*)/AnAPost/postComment']                            = $appVersionFolder."/AnAPost/postComment";
$route['(.*)/AnAPost/setEntityRating']                        = $appVersionFolder."/AnAPost/setEntityRating";
$route['(.*)/AnA/getCommentDetails(.*)']                      = $appVersionFolder."/AnA/getCommentDetails$2";
$route['(.*)/Search/getRelatedEntities']                      = $appVersionFolder."/Search/getRelatedEntities";
$route['(.*)/Search/getLinkedAndRelatedThread(.*)']           = $appVersionFolder."/Search/getLinkedAndRelatedThread$2";
$route['(.*)/AnA/getReplyDetails(.*)']                        = $appVersionFolder."/AnA/getReplyDetails$2";
$route['(.*)/AnA/getUserBasicDetails(.*)']                    = $appVersionFolder."/AnA/getUserBasicDetails$2";
$route['(.*)/Search/search(.*)']                              = $appVersionFolder."/Search/search$2";
$route['(.*)/AnAPost/deleteEntityFromCMS']                    = $appVersionFolder."/AnAPost/deleteEntityFromCMS";
$route['(.*)/AnAPost/getReportAbuseFormData']                 = $appVersionFolder."/AnAPost/getReportAbuseFormData";
$route['(.*)/AnAPost/setReportAbuseReason']                   = $appVersionFolder."/AnAPost/setReportAbuseReason";
$route['(.*)/AnAPost/deleteEntityRating']                     = $appVersionFolder."/AnAPost/deleteEntityRating";
$route['(.*)/Tags/getTagDetailPage(.*)']                      = $appVersionFolder."/Tags/getTagDetailPage$2";
$route['(.*)/AnAPost/closeQuestion']                          = $appVersionFolder."/AnAPost/closeQuestion";
$route['(.*)/AnAPost/shortlistEntity']                        = $appVersionFolder."/AnAPost/shortlistEntity";
$route['(.*)/AnAPost/shareEntity']                            = $appVersionFolder."/AnAPost/shareEntity";
$route['(.*)/UserProfile/getUserData(.*)']                    = $appVersionFolder."/UserProfile/getUserData$2";
$route['(.*)/UserProfile/getUserSectionwiseDetails(.*)']      = $appVersionFolder."/UserProfile/getUserSectionwiseDetails$2";
$route['(.*)/UserProfile/getPersonalProfileFormData']         = $appVersionFolder."/UserProfile/getPersonalProfileFormData";
$route['(.*)/UserProfile/submitPersonalFormData']             = $appVersionFolder."/UserProfile/submitPersonalFormData";
$route['(.*)/UserProfile/getTagsFollowed(.*)']                = $appVersionFolder."/UserProfile/getTagsFollowed$2";
$route['(.*)/UserProfile/getUsersIAmFollowing(.*)']           = $appVersionFolder."/UserProfile/getUsersIAmFollowing$2";
$route['(.*)/UserProfile/getUsersFollowingMe(.*)']            = $appVersionFolder."/UserProfile/getUsersFollowingMe$2";
$route['(.*)/UserProfile/getUserActivitiesAndStats(.*)']      = $appVersionFolder."/UserProfile/getUserActivitiesAndStats$2";
$route['(.*)/UserProfile/getEducationDetailsFormData(.*)']    = $appVersionFolder."/UserProfile/getEducationDetailsFormData$2";
$route['(.*)/UserProfile/getQuestionsByCategory(.*)']         = $appVersionFolder."/UserProfile/getQuestionsByCategory$2";
$route['(.*)/UserProfile/getDiscussionsByCategory(.*)']       = $appVersionFolder."/UserProfile/getDiscussionsByCategory$2";
$route['(.*)/UserProfile/submitEducationDetailsFormData']     = $appVersionFolder."/UserProfile/submitEducationDetailsFormData";
$route['(.*)/UserProfile/updateFollowFieldsForUser']          = $appVersionFolder."/UserProfile/updateFollowFieldsForUser";
$route['(.*)/UserProfile/setDataPrivacySettings']             = $appVersionFolder."/UserProfile/setDataPrivacySettings";
$route['(.*)/UserProfile/updateUsersAboutMeData']             = $appVersionFolder."/UserProfile/updateUsersAboutMeData";
$route['(.*)/UserProfile/uploadProfilePhoto']                 = $appVersionFolder."/UserProfile/uploadProfilePhoto";
$route['(.*)/UserProfile/addDetailWorkExperience']            = $appVersionFolder."/UserProfile/addDetailWorkExperience";
$route['(.*)/UserProfile/updateTotalWorkExperienceInYears']   = $appVersionFolder."/UserProfile/updateTotalWorkExperienceInYears";
$route['(.*)/updateGCMId']                                    = "common_api/CommonAPI/updateGCMId";
$route['(.*)/updateFCMId']                                    = "common_api/CommonAPI/updateFCMId";
$route['(.*)/trackGCMNotification(.*)']                       = "common_api/CommonAPI/trackGCMNotification$2";
$route['(.*)/User/logout']                                    = $appVersionFolder."/User/logout";
$route['(.*)/NotificationInfo/fetchInAppNotification']        = $appVersionFolder."/NotificationInfo/fetchInAppNotification";
$route['(.*)/AnA/getUserProfileData']                         = $appVersionFolder."/AnA/getUserProfileData";
$route['(.*)/AnA/getSimilarQuestions(.*)']                    = $appVersionFolder."/AnA/getSimilarQuestions$2";
$route['(.*)/UserProfile/citiesList']                         = $appVersionFolder."/UserProfile/citiesList";
$route['(.*)/User/getUserFeedBackData']                       = $appVersionFolder."/User/getUserFeedBackData";
$route['(.*)/User/getDataFromCheckSum']                       = $appVersionFolder."/User/getDataFromCheckSum";
$route['(.*)/AnA/getListOfUsersBasedOnAction(.*)']            = $appVersionFolder."/AnA/getListOfUsersBasedOnAction$2";
$route['(.*)/trackThreadView']                                = "common_api/CommonAPI/trackThreadView";
$route['(.*)/AnA/getAnAMostActiveUsers']            		  = $appVersionFolder."/AnA/getAnAMostActiveUsers";
$route['(.*)/Tags/getTagsMostActiveUsers']            		  = $appVersionFolder."/Tags/getTagsMostActiveUsers";
$route['(.*)/AnA/getThreadsMostActiveUser(.*)']               = $appVersionFolder."/AnA/getThreadsMostActiveUser$2";
$route['(.*)/AnAPost/updateFeedbackLayerShownCount']          = $appVersionFolder."/AnAPost/updateFeedbackLayerShownCount";
$route['(.*)/AnAPost/saveQdpFeedback']          	      = $appVersionFolder."/AnAPost/saveQdpFeedback";
$route['(.*)/AnA/getANAWidgetFeed']          	              = $appVersionFolder."/AnA/getANAWidgetFeed";
