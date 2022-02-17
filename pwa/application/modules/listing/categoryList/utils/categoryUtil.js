import {getObjectSize} from './../../../../utils/commonHelper';
export function getCategoryGTMparams(categoryData,aliasMapping,pageName = 'categoryPage')
{
	var appliedFilters = typeof categoryData != 'undefined' && typeof categoryData.requestData != 'undefined' && categoryData.requestData ? categoryData.requestData.appliedFilters : {};
	var aliasMapping = typeof aliasMapping != 'undefined' ? aliasMapping : null;
	if(!aliasMapping)
		return;
	var nameMapping = {"city":"cityId","state":"stateId","stream":"streamId","substream":"substreamId","specialization":"specializationId","baseCourse":"baseCourseId","education_type":"educationType","delivery_method":"deliveryMethod","credential":"credential","exam":"exams"};
	var gtmParams = {};
	aliasMapping = objectFlip(aliasMapping);
	for(var keyName in appliedFilters)
	{
		switch(keyName)
		{
			case 'subSpec':
			case 'etDm':
					appliedFilters[keyName].map(function(value){
						let val1 = value.split('::');
							val1.map(function(val2){
								let val3 = val2.split('_');
								if(typeof gtmParams[nameMapping[aliasMapping[val3[0]]]] == 'undefined')
										gtmParams[nameMapping[aliasMapping[val3[0]]]] = new Array();
								if(gtmParams[nameMapping[aliasMapping[val3[0]]]].indexOf(val3[1]) < 0)
								{
									gtmParams[nameMapping[aliasMapping[val3[0]]]].push(val3[1]);
								}
							})
					});
					break;
			default:
					if(typeof nameMapping[keyName] != 'undefined' && appliedFilters[keyName].length > 0)
					{
						gtmParams[nameMapping[keyName]] = appliedFilters[keyName];
					}
					break;
		}
	}
	if(pageName == 'allCoursesPage'){
		gtmParams['instituteId'] = categoryData.listingId;
		
	}
	gtmParams['pageType'] = pageName;
	gtmParams['countryId'] = 2;
	return gtmParams;
}
export function getBeaconTrackData(categoryData, pageIdentifier = 'categoryPage', childPageIdentifier = null)
{
	let beaconTrackData = {};
	beaconTrackData['pageIdentifier'] = pageIdentifier;

	let categoryPageData = typeof categoryData != 'undefined' && typeof categoryData.requestData != 'undefined' && typeof categoryData.requestData.categoryData != 'undefined' && categoryData.requestData.categoryData ? categoryData.requestData.categoryData : null;
	if(!categoryPageData)
		return beaconTrackData;
	beaconTrackData['pageEntityId'] = categoryPageData['id'];
	
	beaconTrackData['extraData'] = {};
	beaconTrackData['extraData']['cityId'] = categoryPageData['cityId'];
	beaconTrackData['extraData']['stateId'] = categoryPageData['stateId'];
	beaconTrackData['extraData']['baseCourseId'] = categoryPageData['baseCourseId'];
	beaconTrackData['extraData']['educationType'] = categoryPageData['educationType'];
	beaconTrackData['extraData']['deliveryMethod'] = categoryPageData['deliveryMethod'];
	beaconTrackData['extraData']['countryId'] = 2;
	beaconTrackData['extraData']['hierarchy'] = {};
	beaconTrackData['extraData']['hierarchy'][0] = {};
	beaconTrackData['extraData']['hierarchy'][0]['streamId'] = categoryPageData['streamId'];
	beaconTrackData['extraData']['hierarchy'][0]['substreamId'] = categoryPageData['substreamId'];
	beaconTrackData['extraData']['hierarchy'][0]['specializationId'] = categoryPageData['specializationId'];
	if(childPageIdentifier)
		beaconTrackData['extraData']['childPageIdentifier'] = childPageIdentifier;
	return beaconTrackData;
}
function objectFlip(aliasMapping)
{
	var key, tmp_ar = {};
  	for (key in aliasMapping) {
    	if (!aliasMapping.hasOwnProperty(key)) {continue;}
    	tmp_ar[aliasMapping[key]] = key;
  	}
  	return tmp_ar;
}
export function generateCatPageUrl(selectedFilters,shortName,defaultAppliedFilters,filters)
{
	var appliedFilters = {};
	selectedFilters.map(function(value)
	{
		if(value.optionType == "sub_spec" || value.optionType == 'et_dm'){
			optionKey = shortName[value.optionType],
			optionValue = value.optionValue;
		}
		else{
			var optionValArr  = value.optionValue.split(/_(.+)?/),
			optionKey = optionValArr[0],
			optionValue = optionValArr[1];
		}
		if(optionKey != '' && typeof optionKey != 'undefined')
		{
			if(typeof appliedFilters[optionKey] == 'undefined')
					appliedFilters[optionKey] = [];
				console.log('generateCatPageUrl',optionKey,optionValue);
			appliedFilters[optionKey].push(optionValue);
		}
	});
	appliedFilters['uaf'] = getDefaultFilters(defaultAppliedFilters,filters);
	getAppliedFilters(filters,appliedFilters,shortName);
	return convertObjectIntoQueryParams(appliedFilters);
}
function convertObjectIntoQueryParams(data)
{
	var str = "";
	for (var key in data) {
		for(var lkey in data[key])
		{
			if (str != "") {
	        str += "&";
		    }
		    str += key+'[]' + "=" + encodeURIComponent(data[key][lkey]);	
		}
	}
	return str;
}
function getDefaultFilters(defaultAppliedFilters,filters)
{
	let params = [];
	for(var filterType in defaultAppliedFilters)
	{
			switch(filterType){
				case 'city':
				case 'state':
					if(typeof filters['location'] != 'undefined'){
						!params.includes('location') ? params.push('location') : '';
					}
					break;
				case 'stream':
					break;
				case 'substream':
				case 'specialization':
					if(typeof filters['sub_spec'] != 'undefined'){
						params.push('sub_spec');
					}
					else if(typeof filters[filterType] != 'undefined'){
						params.push(filterType);
					}
					break;
				case 'education_type':
				case 'delivery_method':
					if(typeof filters['et_dm'] != 'undefined'){
						params.push('et_dm');
					}
					else if(typeof filters[filterType] != 'undefined'){
						params.push(filterType);
					}
					break;
				case 'course_level':
				case 'credential':
					if(typeof filters['level_credential'] != 'undefined'){
						params.push('level_credential');
					}
					else if(typeof filters[filterType] != 'undefined'){
						params.push(filterType);
					}
					break;
				default:
					if(typeof filters[filterType] != 'undefined'){
						params.push(filterType);
					}
					break;
			}
		}
	return params;
}
function getAppliedFilters(filters,appliedFilters,shortName)
{
	if(typeof filters == 'undefined' || (typeof filters !=  'undefined' && filters.length > 0) || typeof shortName == 'undefined')
		return;
	for(var filterType in filters)
	{
		if(Array.isArray(filters[filterType]) && filters[filterType].length > 0)
		{
			filters[filterType].map(function(value)
			{	
				if(value.checked == true)
				{
					if(filterType == 'location')
					{
						var optionKey = shortName[value.filterType];	
					}
					else
					{
						var optionKey = shortName[filterType];
					}
					var optionValue = value.id;				
					if(optionKey != '' && typeof optionKey != 'undefined')
					{
						if(typeof appliedFilters[optionKey] == 'undefined')
								appliedFilters[optionKey] = [];	

						//if(filterType == "sub_spec" || filterType == 'et_dm'){
							//var optionValArr  = value.id.split(/_(.+)?/);
							//optionValue = optionValArr[1];
							//optionValue = value.id;
						/*}
						else{
							optionValue = value.id;
						}*/
						appliedFilters[optionKey].push(optionValue);
					}
				}
			});
		}
	}
}
