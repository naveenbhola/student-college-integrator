import mobileRenderer from './mobileRenderer';
import desktopRenderer from './desktopRenderer';
export default (req, store, fromWhere, isInvalid) => {
	let countryName = req['headers']['GEOIP_COUNTRY_NAME'];
	if(process.env.NODE_ENV != 'production') { // && req.query.hideGdpr (hide bydefault gdpr banner by default on test)
		countryName = 'india';
	}
	let hideGdpr = 0;
	if(typeof countryName != 'undefined' && countryName.toLowerCase() == 'india') {
		hideGdpr = 1;
	}
	
	let returnData;
	/*if(fromWhere=='homePage'){
		if(!global.isMobileRequest){
			const fs = require('fs');
			fs.appendFile(logPath+"/homepage.access.log", "\n"+JSON.stringify(req['headers'],null,2)+"\n"+"global.isMobileRequest=="+global.isMobileRequest+"\n"+"Date==="+new Date()+"\n");
		}
	}*/
	let extraData = {isUserLoggedIn : global.isUserLoggedIn};
	if(global.isMobileRequest === true){
		returnData = mobileRenderer(req, store, fromWhere, isInvalid, extraData);
	}else{
		returnData = desktopRenderer(req, store, fromWhere, isInvalid, extraData);
	}
	returnData.hideGdpr = hideGdpr;
	return returnData;
}
