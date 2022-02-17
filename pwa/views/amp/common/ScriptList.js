import config from './../../../config/config';

export default function ScriptList(listingType,listingId){
	const domainName = config().SHIKSHA_HOME;
	var ampAuthorizationText = "fromwhere=pwa&";
	if(listingType == "coursePage"){
		ampAuthorizationText += "courseId=" + listingId;
	}
	else{
		ampAuthorizationText += "instituteId=" + listingId;
	}
	var string = ' <script id="amp-access" type="application/json">{"authorization":"'+domainName+'/mcommon5/MobileSiteHamburgerV2/ampAuthorization?'+ampAuthorizationText+'&viewedResKey=1207&rid=READER_ID&url=CANONICAL_URL&ref=DOCUMENT_REFERRER&_=RANDOM", "pingback": "'+domainName+'/mcommon5/MobileSiteHamburgerV2/ampAuthorization?'+ampAuthorizationText+'&viewedResKey=1207&rid=READER_ID&url=CANONICAL_URL&ref=DOCUMENT_REFERRER&_=RANDOM&callType=pingback", "noPingback":true, "login":{"sign-in":"'+domainName+'/muser5/UserActivityAMP/getLoginAmpPage?rid=READER_ID&url=CANONICAL_URL", "sign-out": "'+domainName+'/muser5/MobileUser/logout"}, "authorizationFallbackResponse":{"error": true, "access": false, "subscriber": false, "validuser":false, "bMailed":false, "shortlisted":false, "compared":false, "contact":false, "GuideMailed":false, "examSubscribe":false}}</script> <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script> <script async src="https://cdn.ampproject.org/v0.js"></script> <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script> <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script> <script async custom-element="amp-access" src="https://cdn.ampproject.org/v0/amp-access-0.1.js"></script> <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.2.js"></script> <script async custom-element="amp-lightbox-gallery" src="https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js"></script> <script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script> <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script> <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script> <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>';
		return string;
}
