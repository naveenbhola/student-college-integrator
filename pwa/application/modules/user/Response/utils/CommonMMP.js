import React from 'react';
import { isUserLoggedIn } from './../../../../utils/commonHelper';
import { showRegistrationFormWrapper } from './../../../../utils/regnHelper';
import mmpConfig from './../config/mmpConfigForScrollPage';


export function setVariablesForScrollableMMP(obj)
{
    let pageType = obj.pageType;
    let stream = obj.stream;
    let baseCourses = obj.baseCourseId;
    let educationType = obj.educationType;
    let deliveryMethod = obj.deliveryMethod;
    let substream = obj.substream;
    let specialization = obj.specialization;
    specialization = [specialization];

    let showMMPform = 0;
    let showCustomFields = 0;
    let trackingKeyId =  0;

    let params = getParametersForPageType(pageType);

    showMMPform = params['showMMPform'];
    showCustomFields = params['showCustomFields'];
    trackingKeyId = params['trackingKeyId'];

    let hideSubstream = 0;

    if(stream!=undefined && stream!='' && baseCourses!=undefined && baseCourses!='' && baseCourses.length<2){
        hideSubstream = 1;
    }

    let specializationHidden = 1;

    if(baseCourses!=undefined && baseCourses.length>1){
        specializationHidden = 0;
    }

    if((document.referrer.indexOf('google') != -1) && !isUserLoggedIn() && showMMPform==1){
        let customFields = {};

        if(showCustomFields==1 && stream!=undefined && stream!='' && stream != mmpConfig['governmentExamStream']){

            customFields = {
                'stream': {
                        'value' : stream,
                        'hidden' : 1
                }
            };

            // for substreamspec custom fields
            if(hideSubstream==0){
                customFields['subStreamSpec'] = {
                    'hidden' : 0
                }
            }
            else if(substream!=undefined && substream!=''){
                if(specialization!=undefined && specialization!=''){
                    customFields['subStreamSpec'] = {
                        'value' : {
                            [substream] : specialization
                        },
                        'hidden' : specializationHidden
                    }
                }
                else{
                    customFields['subStreamSpec'] = {
                        'value' : {
                            [substream] : ['']
                        },
                        'hidden' : specializationHidden
                    }
                }
            }
            else if(specialization!=undefined && specialization!=''){
                customFields['subStreamSpec'] = {
                    'value' : {
                        'ungrouped' : specialization
                    },
                    'hidden' : specializationHidden
                }
            }
            else{
                customFields['subStreamSpec'] = {
                    'hidden' : hideSubstream
                }
            }

            // for basecourse

            if(baseCourses!=undefined && baseCourses!='' && specializationHidden==1){
                customFields['baseCourses'] = {
                    'value' : baseCourses,
                    'hidden' : 1
                }
            }

            // for educationType

            if(educationType!=undefined && educationType!=''){
                if(educationType==mmpConfig['partTimeEducationType']){
                    defaultDeliveryMethod = mmpConfig['defaultDeliveryMethod'];
                    if(deliveryMethod==undefined || deliveryMethod==''){
                        deliveryMethod = defaultDeliveryMethod;
                    }
                }
                else{
                    deliveryMethod = [educationType];
                }

                customFields['educationType'] = {
                    'value' : deliveryMethod,
                    'hidden' : 1
                }
            }
        }
        customFields['nonInteractive'] = true;

        let formData = 
        {
            'trackingKeyId' : trackingKeyId,
            'customFields': customFields,
            'httpReferer' : document.referrer
        };

        MMPLayerCommon(formData);
    }
}

// for on scroll popup

function MMPLayerCommon(formData)
{
    let isScrolling;
    let flag=0;
    let scrollsNumber = 2;
    let default_height = window.outerHeight;

    window.addEventListener('scroll',function(event) 
    {
        if(flag==0)
        {
            window.clearTimeout(isScrolling);

            isScrolling = setTimeout(function() 
            {
                let height_after_scroll = window.scrollY;
                if(default_height*scrollsNumber<=height_after_scroll)
                {
                    flag=1;
                    showRegistrationFormWrapper(formData);
                }               

            },1000);
        }

    },false);
}


function getParametersForPageType(pageType){

    let parameters = {};

    switch (pageType) {
        case "examPage":
            parameters['showMMPform'] = 1;
            parameters['trackingKeyId'] = 1911;
            parameters['showCustomFields'] = 1;
            break;
        default:
            parameters['showMMPform'] = 0;
            parameters['trackingKeyId'] = 0;
            parameters['showCustomFields'] = 0;
    }

    return parameters;
}
