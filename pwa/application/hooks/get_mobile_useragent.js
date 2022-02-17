export function get_mobile_useragent(req, res, config){
	let COOKIE_DOMAIN = (typeof config != 'undefined' && config) ? config().COOKIE_DOMAIN : ".shiksha.com";
	let whitelistFlag = whitelistDeviceList(req.headers['user-agent']);
	let isMobile = 1;
	if(req.cookies['user_force_cookie'] == 'YES'){
		res.cookie('ci_mobile', '', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() - 2592000)});
		res.cookie('ci_mobile_js_support', '', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() - 2592000)});
		res.cookie('ci_mobile_capbilities', '', { domain:COOKIE_DOMAIN, expires: new Date(Date.now() - 2592000)});
		return false;
	}
	if(whitelistFlag && req.cookies['user_force_cookie'] != 'YES'){
		res.cookie('ci_mobile', 'mobile', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() - 2592000)});
		res.cookie('ci_mobile_js_support', 'yes', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() - 2592000)});
		res.cookie('user_force_cookie', 'YES', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() - 2592000)});
		//return res.redirect('/');
		return isMobile;
	}
	if (!whitelistFlag){
		if(typeof req.headers['x-mobile'] != 'undefined' && req.headers['x-mobile'] != ''){
			isMobile = (req.headers['x-mobile'] == "True" ) ? 1 : 0;
		}else{
			let MobileDetect = require('mobile-detect');
			let detect = new MobileDetect(req.headers['user-agent']);
			let logPath = "/var/log/node/logs/";
			const fs = require('fs');
			fs.appendFile(logPath+"/get_mobile_useragent.access.log", 
				"\n"+JSON.stringify(req['headers'],null,2)+"\n"+"global.isMobileRequest=="+global.isMobileRequest+"\n"+"Date==="+new Date()+"\n"+"mobile-detect==="+detect.mobile()+"\n", 
				(err) => {
				  	if (err) {
				    	console.log("Error while writing to file", err);
				  	}
				});
			if(detect.mobile() == null){
				isMobile = 0;
			}else{
				isMobile = 1;
			}
		}

		if(isMobile){
			res.cookie('mobile_site_user', 'national', { domain:COOKIE_DOMAIN, expires: new Date(Date.now() + 2592000)});
		}
		if(isMobile && req.cookies['user_force_cookie'] != 'YES' && !whitelistFlag){
			let mobileDeviceCapbilities = LoadDeviceCapabilities(req.headers['x-akamai-device-characteristics']);
			if(mobileDeviceCapbilities['ajax_support_javascript'] == 'true'){
				res.cookie('ci_mobile', 'mobile', {domain:COOKIE_DOMAIN,  expires:  new Date(Date.now() + 2592000)});
				res.cookie('ci_mobile_js_support', 'yes', { domain:COOKIE_DOMAIN, expires:  new Date(Date.now() + 2592000)});
				res.cookie('ci_mobile_capbilities', JSON.stringify(mobileDeviceCapbilities), { domain:COOKIE_DOMAIN, expires: new Date(Date.now() + 2592000)});
			}
		}else{
			    res.clearCookie('ci_mobile', {domain:COOKIE_DOMAIN, expires: new Date(0)});
				res.clearCookie('ci_mobile_js_support', { domain:COOKIE_DOMAIN, expires: new Date(0)});
				res.clearCookie('ci_mobile_capbilities', { domain:COOKIE_DOMAIN, expires: new Date(0)});			
		}
	}
	return (isMobile ? true : false);
}

function whitelistDeviceList(userAgent)
{
	return false;
	let whitelist = ['iPad'];
	for(let i = 0; i < whitelist.length; i++){
		if(userAgent.indexOf(whitelist[i]) != -1 && userAgent.indexOf('Mac OS')){
			return true;
		}
	}
	return false;
}

function LoadDeviceCapabilities(xAkamaiDeviceCharacteristics)
{
	let mobileDeviceCapbilities = new Array();
  if( (typeof xAkamaiDeviceCharacteristics!='undefined') && xAkamaiDeviceCharacteristics!= "" ){
    mobileDeviceCapbilities = convertAkamaiHeaderToArray(xAkamaiDeviceCharacteristics);
	}
	return mobileDeviceCapbilities;
}

function convertAkamaiHeaderToArray(requestDevice){
	let returnArray = new Array();
	let tempArray = new Array();
	if(requestDevice != ""){
		tempArray = requestDevice.split(";");
		for(var key in tempArray){
			var featureSplit = new Array();
			featureSplit = tempArray[key].split("=");
			if(featureSplit[0]){
				let keyName = featureSplit[0];
				returnArray[keyName] = (typeof featureSplit[1] !== 'undefined')?featureSplit[1]:"";
			}
		}
	}
	return returnArray;
}
