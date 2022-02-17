import {getRequest} from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';

export const fetchCourseDetailPageData = (params, config, isServerCall) => (dispatch) => {
	var API = (isServerCall) ? APIConfig.GET_COURSE_FROM_SERVER : APIConfig.GET_COURSE;
	config = typeof config != 'undefined' ? config : {};
	var url = API+'?'+params;
	return getRequest(url, config).then((response) => { dispatch({
			type : 'courseData',
			data : response.data.data
	})});
}

//Precache top two also viewed and similar courses
export const getAlsoViewedAndSimilarCourses = (alsoViewedArr, similarCourseArr, currentCourseId) =>{

	var alsoViewed = new Array(), similarCourse = new Array();
	
	if(alsoViewedArr != null && alsoViewedArr.length>0){
		alsoViewed = alsoViewedArr.slice(0,1); 
	}

	if(similarCourseArr != null && similarCourseArr.length>0){
		//similarCourse = similarCourseArr.slice(0,2); 
	}

	var tmpArr = new Array(), finalCourseArr = new Array();
    	tmpArr = alsoViewed.concat(similarCourse);

    for(var i in tmpArr){
    	finalCourseArr.push(tmpArr[i].courseId);
    }

    if(currentCourseId){
    	finalCourseArr.push(currentCourseId);
    }

	finalCourseArr.map((courseId)=>{
		if(courseId){
			var url = APIConfig.GET_COURSE+'?courseId='+courseId;
			getRequest(url);
		}
	});
}

export const preFetchPrimaryInstitute = instituteId => {
	var url = APIConfig.GET_INSTITUTE+'?instituteId='+instituteId;
	getRequest(url);
}

export const storeCourseDataForPreFilled = (coursedata) => (dispatch) => {
	console.log(coursedata);
	if(coursedata && typeof coursedata != 'undefined')
	{
		return	dispatch({
					type : 'catpageCourse',
					data : coursedata
			})
	}
	return	dispatch({
					type : 'catpageCourseEmpty',
					data : {}
			})	
}
