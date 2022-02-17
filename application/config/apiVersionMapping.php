<?php
/**
 * Config file to keep the versions of the APIs created with every release
 *
 * Format : Array Key : Path of the API
 * 			Array Value : Array of all the versions in which this API was modified
 * 
 * If an API is changed in two or more releases and it is being called by the APP for a release in which it was not modified then 
 * we will pick the lower version of that API in which it was modified
 *
 * Eg. API /user/getUserData was modified in (1,4,6,7,9) versions and there is a call from APP5 then we will pick the version 4 of this API
 * 
 * @date    2015-07-10
 * @author  Romil Goel
 * @todo    none
*/

$config['verions_map'] = array(

"/registerDevice"                                 	=> array(1),
"/User/fbLogin"                                  	=> array(1),
"/User/register"                                  	=> array(1),
"/User/resetPassword"                             	=> array(1),
"/User/login"                                     	=> array(1),
"/User/forgotPassword"                            	=> array(1),
"/User/insertUserProfileData"                     	=> array(1),
"/Universal/followEntity"                         	=> array(1),
"/AnAPost/postingIntermediatePage"                	=> array(1),
"/Universal/autoSuggestor"                        	=> array(1),
"/AnA/getHomepageData"                            	=> array(1,2),
"/UserProfileBuilder/userProfileBuilderData"      	=> array(1),
"/UserProfileBuilder/insertProfileBuilderTagData" 	=> array(1),
"/UserProfileBuilder/getCoursesList"      	  		=> array(1),
"/UserProfileBuilder/getSpecializationList"       	=> array(1),
"/UserProfileBuilder/getCities"       		  		=> array(1),
"/AnAPost/postEntity"                             	=> array(1),
"/AnA/getQuestionDetailWithAnswers"               	=> array(1),
"/AnA/getDiscussionDetailWithComments"            	=> array(1),
"/AnAPost/postAnswer"                             	=> array(1),
"/AnAPost/postComment"                            	=> array(1),
"/AnAPost/setEntityRating"                        	=> array(1),
"/AnA/getCommentDetails"                          	=> array(1),
"/Search/getRelatedEntities"                      	=> array(1),
"/Search/getLinkedAndRelatedThread"               	=> array(1),
"/AnA/getReplyDetails"                            	=> array(1),
"/AnA/getSimilarQuestions"                        	=> array(1),
"/AnA/getUserBasicDetails"                        	=> array(1),
"/Search/search"                                  	=> array(1),
"/AnAPost/deleteEntityFromCMS"                    	=> array(1),
"/AnAPost/getReportAbuseFormData"                 	=> array(1),
"/AnAPost/setReportAbuseReason"                   	=> array(1),
"/AnAPost/deleteEntityRating"                     	=> array(1),
"/Tags/getTagDetailPage"                          	=> array(1),
"/AnAPost/closeQuestion"                          	=> array(1),
"/AnAPost/shortlistEntity"                        	=> array(1),
"/AnAPost/shareEntity"                            	=> array(1),
"/UserProfile/getUserData"                        	=> array(1),
"/UserProfile/getUserSectionwiseDetails"          	=> array(1),
"/UserProfile/getPersonalProfileFormData"         	=> array(1),
"/UserProfile/submitPersonalFormData"             	=> array(1),
"/UserProfile/getTagsFollowed"                    	=> array(1),
"/UserProfile/getUsersIAmFollowing"               	=> array(1),
"/UserProfile/getUsersFollowingMe"                	=> array(1),
"/UserProfile/getUserActivitiesAndStats"          	=> array(1),
"/UserProfile/getEducationDetailsFormData"        	=> array(1),
"/UserProfile/getQuestionsByCategory"             	=> array(1),
"/UserProfile/getDiscussionsByCategory"           	=> array(1),
"/UserProfile/submitEducationDetailsFormData"     	=> array(1),
"/UserProfile/updateFollowFieldsForUser"          	=> array(1),
"/UserProfile/setDataPrivacySettings"             	=> array(1),
"/UserProfile/updateUsersAboutMeData"             	=> array(1),
"/UserProfile/uploadProfilePhoto"                 	=> array(1),
"/UserProfile/addDetailWorkExperience"            	=> array(1),
"/UserProfile/updateTotalWorkExperienceInYears"   	=> array(1),
"/updateGCMId"                                    	=> array(1),
"/updateFCMId"                                    	=> array(1),
"/User/logout"                                    	=> array(1),
"/NotificationInfo/fetchInAppNotification"        	=> array(1),
"/AnA/getUserProfileData"                         	=> array(1),
"/trackGCMNotification"                           	=> array(1),
"/UserProfile/citiesList"                         	=> array(1),
"/User/getUserFeedBackData"                       	=> array(1),
"/User/getDataFromCheckSum"                       	=> array(1),
"/AnA/getListOfUsersBasedOnAction"			  		=> array(1),
"/trackThreadView"			  						=> array(1),
"/AnA/getAnAMostActiveUsers"						=> array(1),
"/Tags/getTagsMostActiveUsers"						=> array(1),
"/AnA/getThreadsMostActiveUser"						=> array(1),
"/AnA/getANAWidgetFeed"	        					=> array(1),
"/AnAPost/updateFeedbackLayerShownCount"			=> array(1),
"/AnAPost/saveQdpFeedback"					=> array(1)
);
