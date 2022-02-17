import React from 'react';
import { isUserLoggedIn } from './../../../utils/commonHelper';
import { isValidExamResponse, createResponseByRedisQueue } from './../../user/ExamResponse/actions/ExamResponseFormAction';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import { isValidResponseUser, createResponse } from './../../user/Response/actions/ResponseFormAction';

export function viewedResponse(obj)
{
    let listingId = obj.listingId;
    let trackingKeyId = obj.trackingKeyId;
    let actionType = obj.actionType;
    let listingType = obj.listingType;

    if(isUserLoggedIn() && listingId!=undefined && trackingKeyId!=undefined && actionType!=undefined && listingId!="" && trackingKeyId!="" && actionType!="")
    {
        if(listingType=='exam')
        {
            // examViewedResponse(listingId,actionType,listingType,trackingKeyId);
            examViewedResponseByCron(listingId,actionType,listingType,trackingKeyId);

        }
        else if(listingType=='course')
        {
            courseViewedResponse(listingId,actionType,listingType,trackingKeyId);
        }
    }
}

function trackResponseEvent(eventAction, eventLabel) 
{

    var eventCategory = 'Response National';
    if(isUserLoggedIn()){
        eventCategory = 'Mobile Verification'
    }
    var finalLabel    = 'PWA_';
    finalLabel += eventLabel;
    Analytics.event({category : eventCategory, action : eventAction, label : finalLabel});

}

function examViewedResponse(listingId,actionType,listingType,trackingKeyId)
{
    var params = 'examGroupId='+listingId+'&isViewedCall=yes&isPWACall=true';

    isValidExamResponse(params).then((response) => {
        if(typeof(response) !='undefined' && response == true) {

            var params = 'listing_id='+listingId+'&action_type='+actionType+'&listing_type='+listingType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse=yes';

            createResponse(params).then((response) => {
                
                if(response.courseResponse.status == 'SUCCESS') {
                
                    trackResponseEvent(trackingKeyId,'Response_Creation_Successful');

                }

            });
        }
    });
}

function courseViewedResponse(listingId,actionType,listingType,trackingKeyId)
{
    var params = 'clientCourseId='+listingId+'&checkPrefFields=no&isViewedCall=yes&isPWACall=true';

    isValidResponseUser(params).then((response) => {
        if(typeof(response) !='undefined' && response.isValidUser == true) {

            var params = 'listing_id='+listingId+'&action_type='+actionType+'&listing_type='+listingType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse=yes';

            createResponse(params).then((response) => {
                
                if(response.courseResponse.status == 'SUCCESS') {
                
                    trackResponseEvent(trackingKeyId,'Response_Creation_Successful');

                }

            });
        }
    });
}

function examViewedResponseByCron(listingId,actionType,listingType,trackingKeyId)
{
    var params = 'listing_id='+listingId+'&action_type='+actionType+'&listing_type='+listingType+'&tracking_keyid='+trackingKeyId+'&isViewedResponse=yes';
    createResponseByRedisQueue(params).then((response) => {

    });
}
