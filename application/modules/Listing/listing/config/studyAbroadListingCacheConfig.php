<?php 

    /*****Category Page*****/
    $config['universityObjectFieldsCTP'] = array(
        "id","name","fundingType","type","universityLocation","seoURL","universityDefaultImgUrl","brochureURL","announcement","accomodationDetails","accomodationWebsite","livingExpenses"
    );

    $config['courseObjectFieldsCTP'] = array(
        "courseId","name","universityId","level","seoURL", "packtype", "isRmcEnabled", "courseExams","customFees", "transportation", "insurance", "tuition","roomBoard", "duration", "feeCurrency","scholarshipURLUniversityLevel", "scholarshipURLDeptLevel", "scholarshipURLCourseLevel","desiredCourseId","categoryId","subCategoryId"
    );

    /*****Search Page*****/
    $config['universityObjectFieldsSRP'] = array(

    );

    $config['courseObjectFieldsSRP'] = array(

    );

    $config['courseObjectFieldsRMCButton'] = array(
        "courseId","name","isRmcEnabled","applicationDetailId"
    );

    /*****Country Page*****/
    $config['universityObjectCountryHome'] = array(
        "id","name","universityLocation","seoURL","fundingType","media","establishmentYear"
    );

    /*****Ranking Page*****/
    $config['universityObjectFieldsRanking'] = array(
        "id","name","fundingType","type","universityLocation","seoURL","establishmentYear","brochureURL","accomodationDetails","accomodationWebsite"
    );
    $config['courseObjectFieldsRanking'] = array(
        "courseId","name","universityId","seoURL","customFees","packtype", "transportation", "insurance","tuition","roomBoard", "duration","feeCurrency","courseExams","desiredCourseId","categoryId","subCategoryId"
    );
?>