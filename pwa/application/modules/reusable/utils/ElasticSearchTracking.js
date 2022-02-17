import React from 'react';
function getCurrentPageURL()
{           
	let protocol = location.protocol;
	let pathName = location.pathname;
	let host = location.host;                
	let searchParams = location.search;
	return protocol+'//'+host+pathName+searchParams;
}

function getBeaconTrackingURL(pageIdentifier, pageEntityId = 0, extraData = [],BEACON_TRACK_URL = '')
{
	
	let rand = Math.floor(Math.random() * 9999999) + 1000000;
	let referer = document.referrer;
	let pageURL = encodeURIComponent(getCurrentPageURL());
	extraData = encodeURIComponent(JSON.stringify(extraData));

	BEACON_TRACK_URL = (typeof BEACON_TRACK_URL == 'undefined' || !BEACON_TRACK_URL) ? '' : BEACON_TRACK_URL; 

	let url = BEACON_TRACK_URL+'/'+rand+'/'+pageIdentifier+'/'+pageEntityId+'?pageURL='+pageURL+'&pageReferer='+referer+'&extraData='+extraData;
	return url;
}

function track(beaconTrackData,BEACON_TRACK_URL)
{
	let pageIdentifier = beaconTrackData['pageIdentifier'];
    let pageEntityId = beaconTrackData['pageEntityId'];
    let extraData = beaconTrackData['extraData'];
	window.pageContext = {
		'pageIdentifier': pageIdentifier,
		'pageEntityId': pageEntityId,
		'shikshaEntitiesData': extraData
	};
	let beaconTrackURL = getBeaconTrackingURL(pageIdentifier,pageEntityId,extraData,BEACON_TRACK_URL);

	var img;
	if(!document.getElementById('beacon_track_img'))
	{   
		img = document.createElement('img');
		img.id = 'beacon_track_img';
		img.width = 1;
		img.height = 1;
		img.class ='hide';
		document.head.appendChild(img);
	}
	else
	{
		img = document.getElementById('beacon_track_img');
	}
	img.src = beaconTrackURL;
}

export default track;
