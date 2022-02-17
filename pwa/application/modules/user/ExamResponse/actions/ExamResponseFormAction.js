import { getRequest , postRequest } from './../../../../utils/ApiCalls';
import config from './../../../../../config/config';

const axiosConfig = {
        	headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        	withCredentials: true
    	};
const domainName               = config().SHIKSHA_HOME;
const showExamResponseFormUrl  = domainName + '/registration/RegistrationAPIs/showExamResponseForm';
const getFormByExamGroupUrl    = domainName + '/registration/RegistrationAPIs/getFormByExamGroup';
const filterBaseCoursesUrl     = domainName + '/registration/RegistrationAPIs/filterBaseCoursesByLevel';
const isValidExamResponseUrl      = domainName + '/registration/RegistrationAPIs/isValidExamResponse';
const createResponseByRedisQueueUrl      = domainName + '/response/Response/createResponseByQ';

export const showExamResponseForm = (params) => {
	
	return Promise.resolve(getRequest(showExamResponseFormUrl+"?"+params,axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in showExamResponseForm API ',err));

}

export const getFormByExamGroup = (params) => {
	
	return Promise.resolve(getRequest(getFormByExamGroupUrl+"?"+params,axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in getFormByExamGroup API ',err));

}

export const filterBaseCoursesByLevel = (params) => {
	
	return Promise.resolve(getRequest(filterBaseCoursesUrl+"?"+params,axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in filterBaseCoursesByLevel API ',err));

}

export const isValidExamResponse = (params) => {

	return Promise.resolve(postRequest(isValidExamResponseUrl,params,'',axiosConfig))
		.then((response) => {
			return response.data.data;
	    })
	    .catch((err) => console.log('error in isValidExamResponse API ',err));
}

export const createResponseByRedisQueue = (params) => {

	return Promise.resolve(postRequest(createResponseByRedisQueueUrl,params,'',axiosConfig))
		.then((response) => {
	    })
	    .catch((err) => console.log('error in createResponseByRedisQueue API ',err));
}
