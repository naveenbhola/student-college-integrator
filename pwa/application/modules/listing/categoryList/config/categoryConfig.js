export const defaultLocationLayerSteamIds = new Array(3,6,8);//DESIGN_STREAM => 3, IT_SOFTWARE_STREAM => 6,ANIMATION_STREAM =>8
export const srpConstants = (isDesktop) => {
    if(isDesktop){
        return {
            shortlistTrackingId : 512,
            downloadBrochureTrackingId : 513
        }
    }
    return {
        shortlistTrackingId : 1863,
        downloadBrochureTrackingId : 1865
    }
}

export const ctpConstants = (isDesktop) => {
    if(isDesktop){
        return {
            shortlistTrackingId : 215,
            downloadBrochureTrackingId : 213,
            PCW_COUNT : 2,
            TUPLES_BEFORE_PCW : 4
        }
    }
    return {
        shortlistTrackingId : 271,
        downloadBrochureTrackingId : 269,
        PCW_COUNT : 2,
        TUPLES_BEFORE_PCW : 4
    }
}

export const allCourseConstants = () => {
    const constants = {
        shortlistTrackingId : 1135,
        downloadBrochureTrackingId : 1134,
        shortlistTrackingIdTopWidget:1927,
        downloadBrochureTrackingIdTopWidget:1925,
        shortlistTrackingIdRecoLayer:1142,
        downloadBrochureTrackingIdRecoLayer:1141
    }
    return constants;
}

export const ILPConstants = () => {
    const constants = {
        shortlistTrackingIdTopWidget:1771,
        downloadBrochureTrackingIdTopWidget:1769,
        shortlistTrackingIdRecoLayer:1208,
        downloadBrochureTrackingIdRecoLayer:1063,
        RequestCallBackCTA: 3283, 
        GetFreeCounsellingCTA: 3285,
        GetAdmissionDetailCTA:3291,
        GetScholarshipDetailCTA: 3295,
        DownloadTopQuestionsCTA:3293,
        DownloadTopReviewsCTA:3289,
        DownloadCourseListCTA:3287
    }
    return constants;
}

export const ULPConstants = () => {
    const constants = {
        shortlistTrackingIdTopWidget:1767,
        downloadBrochureTrackingIdTopWidget:1765,
        shortlistTrackingIdRecoLayer:1070,
        downloadBrochureTrackingIdRecoLayer:1077,
        RequestCallBackCTA: 3301, 
        GetFreeCounsellingCTA: 3303,
        GetAdmissionDetailCTA:3309,
        GetScholarshipDetailCTA: 3313,
        DownloadTopQuestionsCTA:3311,
        DownloadTopReviewsCTA:3307,
        DownloadCourseListCTA:3305
    }
    return constants;
}

export const AdmissionPageConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:2021,
        downloadBrochureTrackingIdTopWidget:2019, 
        shortlistTrackingIdRecoLayer:1100,
        downloadBrochureTrackingIdRecoLayer:1099,
        shortlistTrackingIdBottomSticky:2023,
        downloadBrochureTrackingIdBottomSticky:1145,
        intrestingCourseTrackingId:1086

       }
    return constants;
}


export const allCoursePageDesktopConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:2445,
        downloadBrochureTrackingIdTopWidget:2443, 
        shortlistTrackingIdRecoLayer:2457,
        downloadBrochureTrackingIdRecoLayer:2459,
        compareTrackingIdRecoLayer:2455,
        shortlistTrackingIdSticky:2449,
        downloadBrochureTrackingIdSticky:2447,
       }
    return constants;
}

export const AdmissionPageDesktopConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:1186,
        downloadBrochureTrackingIdTopWidget:974, 
        shortlistTrackingIdRecoLayer:1035,
        downloadBrochureTrackingIdRecoLayer:1033,
        intrestingCourseTrackingId:1101,
        shortlistTrackingIdSticky:1192,
        downloadBrochureTrackingIdSticky:975
       }
    return constants;
}

export const PlacementPageConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:3269,
        downloadBrochureTrackingIdTopWidget:3225, 
        shortlistTrackingIdRecoLayer:3271,
        downloadBrochureTrackingIdRecoLayer:3275,
        shortlistTrackingIdBottomSticky:3237,
        downloadBrochureTrackingIdBottomSticky:3241,

       }
    return constants;
}
export const PlacmentPageDesktopConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:3259,
        downloadBrochureTrackingIdTopWidget:3223, 
        shortlistTrackingIdRecoLayer:3261,
        downloadBrochureTrackingIdRecoLayer:3265,
        shortlistTrackingIdSticky:3235,
        downloadBrochureTrackingIdSticky:3239
       }
    return constants;
}

export const CutoffPageDesktopConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:3655,
        downloadBrochureTrackingIdTopWidget:3659, 
        shortlistTrackingIdRecoLayer:3261,
        downloadBrochureTrackingIdRecoLayer:3265,
        shortlistTrackingIdSticky:3663,
        downloadBrochureTrackingIdSticky:3667
       }
    return constants;
}

export const CutoffPageConstants = () => {
       const constants = {
        shortlistTrackingIdTopWidget:3653,
        downloadBrochureTrackingIdTopWidget:3657, 
        shortlistTrackingIdRecoLayer:3261,
        downloadBrochureTrackingIdRecoLayer:3265,
        shortlistTrackingIdSticky:3661,
        downloadBrochureTrackingIdSticky:3665
       }
    return constants;
}