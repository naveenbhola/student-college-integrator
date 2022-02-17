import React from 'react';

function prepareBeaconURL(pageIdentifier, pageEntityId = 0, productId = 0, BEACON_TRACK_URL = '')
{
	let rand = Math.floor(Math.random() * 9999999) + 1000000;
	BEACON_TRACK_URL = (typeof BEACON_TRACK_URL == 'undefined' || !BEACON_TRACK_URL) ? '' : BEACON_TRACK_URL; 
	let url = BEACON_TRACK_URL+'/'+rand+'/'+productId+'/'+pageEntityId+'+'+pageIdentifier;
	return url;
}

function track(beaconTrackData, BEACON_TRACK_URL)
{
	let pageIdentifier = beaconTrackData['listing_type'];
    let pageEntityId   = beaconTrackData['listing_id'];
    let productId      = beaconTrackData['product_Id'];
    
	let beaconTrackURL = prepareBeaconURL(pageIdentifier, pageEntityId, productId, BEACON_TRACK_URL);

	var img;
	if(!document.getElementById('beacon_index_img'))
	{   
		img = document.createElement('img');
		img.id = 'beacon_index_img';
		img.width = 1;
		img.height = 1;
		img.class ='hide';
		document.head.appendChild(img);
	}
	else
	{
		img = document.getElementById('beacon_index_img');
	}
	img.src = beaconTrackURL;
}

export default track;
