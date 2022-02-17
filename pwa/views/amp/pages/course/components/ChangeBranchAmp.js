import React from 'react';
import AmpLightBox  from '../../../../../application/modules/common/components/AmpLightbox';
import {Link} from 'react-router-dom';


class ChangeBranchAmp extends React.Component
{
	constructor(props)
	{
		super(props);

	}
	renderChangeBranchLink()
	{
    let id  = this.props.id;
		this.locationLayerData = [];
		const {data,config,location} = this.props;
		var isMultilocation = Object.keys(data.locations).length > 1 ? true : false;
		if(!isMultilocation)
			return null;
		var locationsArray = data.locations;//Object.values(data.locations);
		var otherLocationsArray = [];
		var locationWiseArray = [];
		var courseUrl = data.courseUrl;
		var incrmnt = 0;
		for(var i in locationsArray){
			if(locationsArray[i].localityId == 0)
			{
				let locationUrl = courseUrl+"?city="+locationsArray[i].cityId;
                otherLocationsArray[locationsArray[i].cityName] = locationsArray[i];
			}
			else
			{
				if(typeof locationWiseArray[locationsArray[i].cityName] == 'undefined')
					locationWiseArray[locationsArray[i].cityName] = {};

				locationWiseArray[locationsArray[i].cityName][locationsArray[i].localityName] = locationsArray[i];
			}
		}

		this.locationLayerData.push(<div key={"loctn-layer"+incrmnt} className='loc-list-col'>
			<ul className='color-6'>
					{this.prepareLocalityWiseHtml(locationWiseArray,courseUrl)}
					{this.prepareNonLocalityWiseHtml(otherLocationsArray,courseUrl)}
			</ul>
		</div>)

		return (<React.Fragment key="chnge-brnch"><a  className='color-b f12 block v-arr wd-55' on={"tap:"+id}>Change branch<i className="arw-icn"></i></a>
	<AmpLightBox onRef={ref => (this.changeBranch = ref)} data={this.locationLayerData} id={id} heading="Change Branch" onContentClickClose={false} taponPage={true}/>
			</React.Fragment>);
	}


	prepareLocalityWiseHtml(locationWiseArray,courseUrl)
	{
		var localityWiseHtml = [];
		var count = 0;
		for(let cityName in locationWiseArray)
		{
			var cityHtml = [];
			cityHtml.push(<p key={"localitywise"+count} className='tgl-lnk'>{cityName}<i className='cbr-info'>&nbsp;</i>
			</p>);
			var localityHtml = [];
			for(var localityName in locationWiseArray[cityName])
			{
				let cityId            = locationWiseArray[cityName][localityName].cityId;
				let localityId        = locationWiseArray[cityName][localityName].localityId;
				let listingLocationId = locationWiseArray[cityName][localityName].listingLocationId;
				let isMainLocation 	  = locationWiseArray[cityName][localityName].main;
				let searchParams = "?city="+cityId+'&locality='+localityId;
				if(isMainLocation)
				{
					searchParams = '';
				}
				localityHtml.push(<li key={listingLocationId}><Link to={{ pathname : courseUrl,search : searchParams}} className='block'>{localityName}</Link></li>);
			}
			localityWiseHtml.push(
        <amp-accordion key={"city_locality"+count}>
           <section expanded={'expanded'.toString()} className='seats-drop'>
             <h4 className='color-w f14 pad8 font-w6 color-3'>{cityHtml}</h4>
             <div className='res-col loc-layer'>{localityHtml}</div>
           </section>
        </amp-accordion>
					);
			count++;
		}
		return localityWiseHtml;
	}
	prepareNonLocalityWiseHtml(otherLocationsArray,courseUrl)
	{
		var otherLocationsHtml = [];
		var SHIKSHA_HOME = this.props.config.SHIKSHA_HOME;
		for(let cityName in otherLocationsArray)
		{
			let isMainLocation 	  = otherLocationsArray[cityName].main;
			let searchParams = "?city="+otherLocationsArray[cityName].cityId;
			if(isMainLocation)
			{
				searchParams = '';
			}
			otherLocationsHtml.push(<li key={"city"+otherLocationsArray[cityName].cityId}><a href={SHIKSHA_HOME+courseUrl+searchParams} className='block'>{cityName}</a></li>);
		}
		return otherLocationsHtml;
	}
	render()
	{
		return this.renderChangeBranchLink();
	}
}

export default ChangeBranchAmp;
