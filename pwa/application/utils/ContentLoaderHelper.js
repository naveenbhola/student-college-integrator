import React from 'react';

export function contentLoaderHelper(pageData,extraData,pageType,pageUrl){

	let data = {} , PageHeading="" , i = 0;

	let dataArr = ['listingId','listingType','instituteTopCardData','reviewWidget','currentLocation','aggregateReviewWidget','anaCountString','filters'];
	for(i=0;i<dataArr.length - 1;i++){
		if(pageData[dataArr[i]] && pageData[dataArr[i]] != 'undefined' ){
			data[dataArr[i]] = pageData[dataArr[i]];
		}
	}
	
	data.anaWidget = {};
	data.allQuestionURL = ''; 
	data.showFullLoader = false;
	if(extraData.showFullLoader){
		data.showFullLoader = extraData.showFullLoader;
	}
	console.log(data.showFullLoader);
	data.pageUrl = pageUrl;
	data.selectedFiltersCount = pageData.selectedFiltersCount;
	data.aboutCollege = pageData.aboutCollege;
	data.childPageData = pageData;
	data.showFullSectionLoader = false;

	if(extraData && extraData.clickType && extraData.clickType == 'exam'){
	     data.examOCF=true;
	     data.selectedTag = extraData.id;
	 }else if(extraData && extraData.clickType == 'institute'){
	     data.collegeOCF= true;
	     data.selectedTag = extraData.id;
	 }
	data.PageHeading = 'Cut off & Merit List 2019';
	if(PageHeading != 'ALL' && pageData.seoData && pageData.seoData.headingSuffix){
	    data.PageHeading = pageData.seoData.headingSuffix;
	}
	if(extraData && extraData.scrollPosition == 0){
		data.scrollPosition = extraData.scrollPosition;
	}else{
		data.scrollPosition = -1;
	}
	data.fromWhere = "cutoffPage";

    return data;
}
