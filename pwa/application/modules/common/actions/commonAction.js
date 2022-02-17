import {getRequest} from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';
import config from './../../../../config/config';

export const fetchInitialData = (url,type,config) => (dispatch) => {
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url,config).then((response) => {
		//console.log(response);
		dispatch({
			type : type,
			data : response.data.data
		})
	}).catch(function(err){});
}

export const getCompareList = (courseIds) => (dispatch) => {
	var query = '';
    for(var i in courseIds){
        query += ((i>0) ? '&':'')+'compareCourseIds='+courseIds[i];
    }
    if(query){
    	getRequest(APIConfig.GET_COMPARE_COUNT+'?'+query).then((response) => {
			if(response.data.data){
				dispatch({
					type : 'compareList',
					data : response.data.data
				})
			}
    	}).catch(function(err){});
    }
}

export const getUserDetails = () => (dispatch) => {
	    const axiosConfig = {
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              withCredentials: true
        };
        const domainName = config().SHIKSHA_HOME;
		getRequest(domainName+'/mcommon5/MobileSiteHamburgerV2/getProfileData?from=pwa', axiosConfig)
		.then((response) =>{
		if(response.data){
		    dispatch({
				type : 'shikshaUser',
				data : response.data
			})
		}
		})
		.catch((err)=> console.log('no data found for user loggedIn',err));
}

export const clearReduxData = (type) => (dispatch) => {
	dispatch({
		type : type,
		data : {}
	})
};

export const fetchAmpHamburgerData = (url,type,config) =>{
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url,config).then((response) => {
		return response.data
	}).catch(function(err){});
}

export const fetchAlumniData = (url,type,config) => (dispatch) => {
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url,config).then((response) => {
		dispatch({
			type : type,
			data : response.data.data
		})
	}).catch(function(err){});
}


export const getFooterLinks = (url, deviceType) => (dispatch) => {
	if(deviceType === 'desktop'){
		return getRequest(url).then((response) => { 
		dispatch({
			type : 'footerLinks',
			data : response.data.data
			})
		}).catch(function(err){});
	}else{
		return dispatch({
			type : 'footerLinks',
			data : {}
			});
	}
};

export const fetchImportantDatesData = (url,type,config) => (dispatch) => {
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url,config).then((response) => {
		dispatch({
			type : type,
			data : response.data.data
		})
	}).catch(function(err){});
}

export const fetchCampusRepData = (url,type,config) => (dispatch) => {
	config = typeof config != 'undefined' ? config : {};
	return getRequest(url,config).then((response) => {
		dispatch({
			type : type,
			data : response.data.data
		})
	}).catch(function(err){});
}

export const getGNBLinks = (url, deviceType) => (dispatch) => {
	if(deviceType === 'desktop'){
		return getRequest(url).then((response) => { 
		//console.log( "commonAction",response.data.data);		
			dispatch({
			type : 'gnbLinks',
			data : response.data.data
			})			
		}).catch(function(err){});
	}else{	
		return dispatch({
		type : 'gnbLinks',
		data : {}
		});
	}	
}

export const getGNBLinksStaticObj = (gnbData) => (dispatch) => {
		dispatch({
			type: 'gnbLinks',
			data: gnbData
		})

};

export const getFooterLinksStaticObj = (footerData) => (dispatch) => {
		return dispatch({
			type : 'footerLinks',
			data : footerData
		});
};

