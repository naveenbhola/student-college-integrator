import React from 'react';
export function getFormattedScore(value,unit,maxvalue){
    if(value == '' || typeof value == 'undefined') {
        return '--';
    }
    var eligibilityVal = '--';
    switch (unit) {
        case 'percentage':
            eligibilityVal = value+"%";
            break;
        case 'percentile':
            eligibilityVal = value+"%tile";
            break;
        case 'score/marks':
            eligibilityVal = 'Marks - '+value+"/"+maxvalue;
            break;
        case 'CGPA':
            eligibilityVal = 'CGPA - '+value+"/"+maxvalue;
            break;
        case 'rank':
            eligibilityVal = 'Rank - '+value;
            break;
        default:
            eligibilityVal = '--';
            break;
    }
    return eligibilityVal;
}

export function capFirstLetterInWord(str)
{
   return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

export function divideStringFormat(str,seperator)
{
    if(typeof seperator != 'undefined')
        return capFirstLetterInWord(str.split(seperator).join(' '));
    else
        return capFirstLetterInWord(str);
}

export function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

export function str_split (string, splitLength) {
    if (splitLength === null) {
      splitLength = 1
    }
    if (string === null || splitLength < 1) {
      return false
    }

    string += ''
    var chunks = []
    var pos = 0
    var len = string.length

    while (pos < len) {
      chunks.push(string.slice(pos, pos += splitLength))
    }
    return chunks
  }

export function moneyFormatIndia(num) {
    var explrestunits = "" ;
    if(num.length > 3) {
        var lastthree = num.substr((num.length)-3, (num.length));
        var restunits = num.substr(0, strlen(num.length)-3); // extracts the last three digits
        restunits = ((restunits.length)%2 == 1)?"0".restunits:restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        var expunit   = str_split(restunits, 2);
        for(i=0; i<expunit.length; i++) {
            // creates each of the 2's group and adds a comma to the end
            if(i==0) {
              explrestunits = explrestunits + parseInt(expunit[i]) + ","; // if is first value , convert into integer
            } else {
              explrestunits = explrestunits + expunit[i] + ",";
            }
        }
        var thecash = explrestunits + lastthree;
    } else {
        var thecash = num;
    }
    return thecash; // writes the final format where $currency is the currency symbol.
  }

export function convertSalaryIntoLakh(salary){
    return Number((salary/100000).toFixed(1));
}

export function trim(str) {
	return str.replace(/(^[,|]\s*)|([,|]\s*$)/g, "")
}

export function getRupeesDisplayableAmount(amount,decimalPointPosition = 2, shortUnitText = false)
{
    var finalAmount = amount;
    if(amount < 100000)
    {
        finalAmount = amount.toLocaleString();
    }
    else if(amount < 10000000)
    {
        finalAmount = amount / 100000;
        finalAmount = finalAmount.toFixed(decimalPointPosition);
        if(shortUnitText){
            finalAmount += " L";
        }
        else{
            finalAmount += (finalAmount == 1)? " Lakh" : " Lakh";
        }
    }
    else
    {
        finalAmount = amount / 10000000;
        finalAmount = finalAmount.toFixed(decimalPointPosition);
        if(shortUnitText){
            finalAmount += " Cr";
        }
        else{
            finalAmount += (finalAmount == 1)? " Crore" : " Crores";
        }
    }

    return finalAmount;
}
export function getCourseTrackingParams(courseData){
    var gtmParams = {};
    var beaconTrackData = {};
    var streamArray = new Array();
    var subStreamArray = new Array();
    var specArray = new Array();
    var courseTypeInformation = typeof courseData.entryCourseTypeInformation != 'undefined' && courseData.entryCourseTypeInformation ? courseData.entryCourseTypeInformation : {};
    beaconTrackData['extraData'] = {};
    if(typeof courseTypeInformation != 'undefined' && courseTypeInformation)
    {
        let hierarichies = courseTypeInformation['hierarchy'];
        if(hierarichies && Array.isArray(hierarichies))
        {
            var beaconEntryHieraries = {};
            var counter = 0;
            hierarichies.map(function(value){
                if(typeof value['stream_id'] != 'undefined' && value['stream_id'] > 0)
                        streamArray.push(value['stream_id']);
                if(typeof value['substream_id'] != 'undefined' && value['substream_id'] > 0)
                        subStreamArray.push(value['substream_id']);
                if(typeof value['specialization_id'] != 'undefined' && value['specialization_id'] > 0)
                        specArray.push(value['specialization_id']);
                beaconEntryHieraries[counter++] = {'stremId' : value['stream_id'], 'substreamId' :  value['substreamId'] , "specializationId" : value['specialization_id'] , 'primaryHierarchy' : value['primaryHierarchy']};
            })
            beaconTrackData['extraData']['hierarchy'] = beaconEntryHieraries;
        }
        if(streamArray.length > 0)
            gtmParams['stream'] = streamArray.join(',');
        if(subStreamArray.length > 0)
            gtmParams['substream'] = subStreamArray.join(',');
        if(specArray.length > 0)
            gtmParams['specialization'] = specArray.join(',');

        if(courseTypeInformation['base_course'] > 0){
            gtmParams['baseCourseId'] = courseTypeInformation['base_course'];
            beaconTrackData['extraData']['baseCourseId'] = courseTypeInformation['base_course'];
        }
        if(courseTypeInformation['credential'] && typeof courseTypeInformation['credential']['id'] != 'undefined'){
            gtmParams['credential']  = courseTypeInformation['credential']['id'];
            beaconTrackData['extraData']['credential'] = courseTypeInformation['credential']['id'];
        }
        if(courseTypeInformation['course_level'] && typeof courseTypeInformation['course_level']['id'] != 'undefined'){
            beaconTrackData['extraData']['level'] = courseTypeInformation['course_level']['id'];
        }
    }
    var currentLocation = typeof courseData.currentLocation != 'undefined' && courseData.currentLocation ? courseData.currentLocation : null;
    if(currentLocation){
        gtmParams['cityId']    = currentLocation['city_id'];
        gtmParams['stateId']   = currentLocation['state_id'];
        gtmParams['countryId'] = 2;
        beaconTrackData['extraData']['cityId'] = currentLocation['city_id'];
        beaconTrackData['extraData']['stateId'] = currentLocation['state_id'];
        beaconTrackData['extraData']['countryId'] = 2;
    }
    
    beaconTrackData['extraData']['childPageIdentifier'] = "courseListingPage";

    gtmParams['pageType'] = 'courseListing';
    var educationType = typeof courseData.educationType != 'undefined' && courseData.educationType ? courseData.educationType : null;
    if(educationType && typeof educationType['id'] != 'undefined'){
        gtmParams['educationType'] = educationType['id'];
        beaconTrackData['extraData']['educationType'] = educationType['id'];
    }
    var deliveryMethod = typeof courseData.deliveryMethod != 'undefined' && courseData.deliveryMethod ? courseData.deliveryMethod : null;
    if(deliveryMethod && typeof deliveryMethod['id'] != 'undefined'){
        gtmParams['deliveryMethod'] = deliveryMethod['id'];
        beaconTrackData['extraData']['deliveryMethod'] = deliveryMethod['id'];
    }

    if(courseData.instituteId){
        gtmParams['instituteId'] = courseData.instituteId;
    }
    var examIds = new Array();
    if(courseData.eligibility && typeof courseData.eligibility.exams != 'undefined'){
        for(var examKey in courseData.eligibility.exams){
            if(courseData.eligibility.exams[examKey]['id'] > 0)
                    examIds.push(courseData.eligibility.exams[examKey]['id']);
        }
    }
    if(examIds.length > 0)
        gtmParams['exams'] = examIds.join(',');

    beaconTrackData['pageIdentifier'] = "CLP";
    beaconTrackData['pageEntityId'] = courseData.courseId ? courseData.courseId : 0;


    return {'gtmParams' : gtmParams, 'beaconTrackData' : beaconTrackData};
}
 

export function getInstituteTrackingParams(instituteData){
    var gtmParams = {};
    var beaconTrackData = {};
    beaconTrackData['extraData'] = {};

    var currentLocation = typeof instituteData.currentLocation != 'undefined' && instituteData.currentLocation ? instituteData.currentLocation : null;
    if(currentLocation){
        gtmParams['cityId']    = currentLocation['city_id'];
        gtmParams['stateId']   = currentLocation['state_id'];
        gtmParams['countryId'] = 2;
        beaconTrackData['extraData']['cityId'] = currentLocation['city_id'];
        beaconTrackData['extraData']['stateId'] = currentLocation['state_id'];
        beaconTrackData['extraData']['countryId'] = 2;
    }
    let beaconPageName = instituteData.listingType+"ListingPage" ;
    beaconTrackData['extraData']['childPageIdentifier'] = beaconPageName;
    gtmParams['pageType'] = 'instituteDetailPage';
    if(instituteData.listingType == "university"){
        gtmParams['pageType'] = 'universityDetailPage'
    }

    if(instituteData.listingId){
        gtmParams['instituteId'] = instituteData.listingId;
    }

    var examScanArray = [];
    if(instituteData.adminssionData && instituteData.adminssionData.examList && instituteData.adminssionData.examList !=""){
        
        instituteData.adminssionData.examList.forEach(element => {
            examScanArray.push(element.name);
        });
        gtmParams['exams'] = examScanArray;
    }

    if(instituteData.streamMappingId != null){
        gtmParams['streamId'] = instituteData.streamMappingId;
    }

    if(instituteData.courseWidget && instituteData.courseWidget.baseCourseIds && (instituteData.courseWidget.baseCourseIds).length == 1)
    {
        gtmParams['baseCourseId'] = instituteData.courseWidget.baseCourseIds[0];
    }
    if(instituteData.courseWidget && instituteData.courseWidget.streamsIds && (instituteData.courseWidget.streamsIds).length == 1)
    {
        gtmParams['stream'] = instituteData.courseWidget.streamsIds[0];
    }

    // if($userId > 0)
    // {
    //     $userWorkExp = $this->userStatus[0]['experience'];
    //     if($userWorkExp >= 0)
    //         $displayData['gtmParams']['workExperience'] = $userWorkExp;
    // }
    beaconTrackData['pageIdentifier'] = "UILP";
    beaconTrackData['pageEntityId'] = instituteData.listingId ? instituteData.listingId : 0;


    return {'gtmParams' : gtmParams, 'beaconTrackData' : beaconTrackData};
}

export function renderColumnStructure(classObject,ampcheck)
{
    var columnStructure = [];
    ampcheck;
    let ownershipHtml = typeof classObject.renderOwnershipInfo == 'function' ?classObject.renderOwnershipInfo() : '';
    if(ownershipHtml != '' && typeof ownershipHtml != 'undefined' && ownershipHtml)
        columnStructure.push(ownershipHtml);
    
    let establishedHtml = typeof classObject.renderEstablishedInfo == 'function' ?classObject.renderEstablishedInfo() : '';
    if(establishedHtml != '' && typeof establishedHtml != 'undefined' && establishedHtml)
        columnStructure.push(establishedHtml);


    let accreditationInfoHtml = typeof classObject.renderAccreditationInfo == 'function' ?classObject.renderAccreditationInfo() : '';
    if(accreditationInfoHtml != '' && typeof accreditationInfoHtml != 'undefined' && accreditationInfoHtml)
        columnStructure.push(accreditationInfoHtml);
        
    let universityTypeInfoHtml = typeof classObject.renderUniversityTypeInfo == 'function' ?classObject.renderUniversityTypeInfo() : '';
    if(universityTypeInfoHtml != '' && typeof universityTypeInfoHtml != 'undefined' && universityTypeInfoHtml)
        columnStructure.push(universityTypeInfoHtml);


    let approvalHtml = typeof classObject.renderApprovalsInfo == 'function' ?classObject.renderApprovalsInfo() : '';
    if(approvalHtml != '' && typeof approvalHtml != 'undefined')
        columnStructure.push(approvalHtml);

    let recognitionHtml = typeof classObject.renderRecoginitionInfo == 'function' ?  classObject.renderRecoginitionInfo() : '';
    if(recognitionHtml != '' && typeof recognitionHtml != 'undefined' && recognitionHtml)
        columnStructure.push(recognitionHtml);

    let feeValueHtml = typeof classObject.renderFeeValue == 'function'? classObject.renderFeeValue() : '';
    if(feeValueHtml != '' && typeof feeValueHtml != 'undefined')
        columnStructure.push(feeValueHtml);
    let mediumHtml = typeof classObject.renderMediumHtml == 'function' ? classObject.renderMediumHtml() : '';
    if(mediumHtml != '' && typeof mediumHtml != 'undefined')
        columnStructure.push(mediumHtml);
    let difficultyHtml = typeof classObject.renderDifficultyHtml == 'function' ? classObject.renderDifficultyHtml() : '';
    if(difficultyHtml != '' && typeof difficultyHtml != 'undefined')
        columnStructure.push(difficultyHtml);



    
     let courseStatusHtml = typeof classObject.renderCourseStatusInfo == 'function' ?classObject.renderCourseStatusInfo() : '';
    if(courseStatusHtml != '' && typeof courseStatusHtml != 'undefined' && courseStatusHtml)
        columnStructure.push(courseStatusHtml);    

    let AIUMemberInfoHtml = typeof classObject.renderAIUMemberInfo == 'function' ?classObject.renderAIUMemberInfo() : '';
    if(AIUMemberInfoHtml != '' && typeof AIUMemberInfoHtml != 'undefined' && AIUMemberInfoHtml)
        columnStructure.push(AIUMemberInfoHtml);
    
    let importanceInfoHtml = typeof classObject.renderImportanceInfo == 'function' ?classObject.renderImportanceInfo() : '';
    if(importanceInfoHtml != '' && typeof importanceInfoHtml != 'undefined' && importanceInfoHtml)
        columnStructure.push(importanceInfoHtml);
    

    var counter = 0;
    let colHtml = [];
    let rowHtml = [];
    columnStructure.map(function(keyData,index)
            {
                counter += 1;
                if(ampcheck){
                  rowHtml.push(<div key={index} className='tab-cell-b t-width-b'>{keyData}</div>);
                }else {
                  rowHtml.push(<div key={index} className='clg-col'>{keyData}</div>);
                }

                if(counter%2 == 0){

                    (ampcheck) ? colHtml.push(<li key={index} className='v-top'>{rowHtml}</li>) : colHtml.push(<li key={index}>{rowHtml}</li>);
                    rowHtml = [];
                }
            });
    if(rowHtml.length > 0)
    {
        (ampcheck) ? colHtml.push(<li key="final" className='v-top'>{rowHtml}</li>) : colHtml.push(<li key="final">{rowHtml}</li>);
    }
    if(colHtml.length > 0)
        return colHtml;
}


export function renderColumnStructureCommon(classObject,page)
{
    var columnStructure = [];
    let ownershipHtml = typeof classObject.renderOwnershipInfo == 'function' ?classObject.renderOwnershipInfo() : '';
    if(ownershipHtml != '' && typeof ownershipHtml != 'undefined' && ownershipHtml && page == 'institute')
        columnStructure.push(ownershipHtml);
    
    let courseStatusHtml = typeof classObject.renderCourseStatusInfo == 'function' ?classObject.renderCourseStatusInfo() : '';
    if(courseStatusHtml != '' && typeof courseStatusHtml != 'undefined' && courseStatusHtml && page == 'institute')
        columnStructure.push(courseStatusHtml);    

    let universityTypeInfoHtml = typeof classObject.renderUniversityTypeInfo == 'function' ?classObject.renderUniversityTypeInfo() : '';
    if(universityTypeInfoHtml != '' && typeof universityTypeInfoHtml != 'undefined' && universityTypeInfoHtml && page == 'university' )
        columnStructure.push(universityTypeInfoHtml);

    let recognitionHtml = typeof classObject.renderRecoginitionInfo == 'function' ?  classObject.renderRecoginitionInfo() : '';
    if(recognitionHtml != '' && typeof recognitionHtml != 'undefined' && recognitionHtml && page == 'university')
        columnStructure.push(recognitionHtml);

    let AIUMemberInfoHtml = typeof classObject.renderAIUMemberInfo == 'function' ?classObject.renderAIUMemberInfo() : '';
    if(AIUMemberInfoHtml != '' && typeof AIUMemberInfoHtml != 'undefined' && AIUMemberInfoHtml && page == 'university')
        columnStructure.push(AIUMemberInfoHtml);

    let importanceInfoHtml = typeof classObject.renderImportanceInfo == 'function' ?classObject.renderImportanceInfo() : '';
    if(importanceInfoHtml != '' && typeof importanceInfoHtml != 'undefined' && importanceInfoHtml )
        columnStructure.push(importanceInfoHtml);
    

    let accreditationInfoHtml = typeof classObject.renderAccreditationInfo == 'function' ?classObject.renderAccreditationInfo() : '';
    if(accreditationInfoHtml != '' && typeof accreditationInfoHtml != 'undefined' && accreditationInfoHtml)
        columnStructure.push(accreditationInfoHtml);


    let rankingHtml = typeof classObject.renderRankingHtml == 'function' ?classObject.renderRankingHtml() : '';
    if(rankingHtml != '' && typeof rankingHtml != 'undefined' && rankingHtml)
        columnStructure.push(rankingHtml);
    

    var counter = 0;
    let colHtml = [];
    let rowHtml = [];
    columnStructure.map(function(keyData,index)
            {
                if(counter>0 ){
                    rowHtml.push(<span key={'pipe'+index} className="flex-divider">|</span>);
                }
                rowHtml.push(<div key={index} className="flexbox-divs">{keyData}</div>);
                counter += 1;
            });
    if(rowHtml.length > 0)
    {
        colHtml.push(<div key="facts-widget" className="facts-widget">{rowHtml}</div>);
    }
    if(colHtml.length > 0)  
        return colHtml;
}