import PropTypes from 'prop-types'
import React from 'react';
import PopupLayer from './../../../common/components/popupLayer';
import './../assets/changeBranch.css';
import './../assets/courseCommon.css';
import {Link} from 'react-router-dom';
import PreventScrolling from './../../../reusable/utils/PreventScrolling';

function bindingFunctions(functions)
{
	functions.forEach((f) => (this[f] = this[f].bind(this)));
}

class ChangeBranch extends React.Component
{
	constructor(props)
	{
		super(props);
		bindingFunctions.call(this,[
			'openChangeBrachLayer',
			'closeChangeBrachLayer'
		]);
	}
	renderChangeBranchLink()
	{

		this.locationLayerData = [];
		const {data,location} = this.props;
		var isMultilocation = Object.keys(data.locations).length > 1 ? true : false;
		if(!isMultilocation)
			return null;
		var locationsArray = data.locations;//Object.values(data.locations);
		var otherLocationsArray = [];
		var locationWiseArray = [];
		var courseUrl = location.pathname;
		var incrmnt = 0;
		for(var i in locationsArray){
			if(locationsArray[i].localityId == 0)
			{
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
			<ul className='prm-lst chng-bList'>
				{this.prepareLocalityWiseHtml(locationWiseArray,courseUrl)}
				{this.prepareNonLocalityWiseHtml(otherLocationsArray,courseUrl)}
			</ul>
		</div>)

		return (<React.Fragment key="chnge-brnch"><a href="javascript:void(0);" className='link-blue-small chng-arw chng-brnch' onClick={this.openChangeBrachLayer}>Change branch<i className='chBrnch-ico'></i></a>
			<PopupLayer onRef={ref => (this.changeBranch = ref)} data={this.locationLayerData} heading="Change Branch" onContentClickClose={false}/>
		</React.Fragment>);
	}
	openChangeBrachLayer()
	{
		this.changeBranch.open();
	}
	closeChangeBrachLayer()
	{
		PreventScrolling.enableScrolling();
		this.changeBranch.open();
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
			for(let localityName in locationWiseArray[cityName])
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
				localityHtml.push(<li key={listingLocationId}><Link to={{ pathname : courseUrl,search : searchParams}} onClick={this.closeChangeBrachLayer}>{localityName}</Link></li>);
			}
			localityWiseHtml.push(
				<li key={"city_locality"+count}>
					<input type="checkbox" className='hide accrdn-check'  id={"city_locality"+count}/>
					<label htmlFor={"city_locality"+count}>{cityHtml}</label>
					<ul className='scn-lst brnc-opn'>{localityHtml}</ul></li>);
			count++;
		}
		return localityWiseHtml;
	}
	prepareNonLocalityWiseHtml(otherLocationsArray,courseUrl)
	{
		var otherLocationsHtml = [];
		for(let cityName in otherLocationsArray)
		{
			let isMainLocation 	  = otherLocationsArray[cityName].main;
			let searchParams = "?city="+otherLocationsArray[cityName].cityId;
			if(isMainLocation)
			{
				searchParams = '';
			}
			otherLocationsHtml.push(<li key={"city"+otherLocationsArray[cityName].cityId}><label><Link to={{ pathname: courseUrl,search : searchParams}} onClick={this.closeChangeBrachLayer}><p>{cityName}</p></Link></label></li>);
		}
		return otherLocationsHtml;
	}
	render()
	{
		return this.renderChangeBranchLink();
	}
}

export default ChangeBranch;

ChangeBranch.propTypes = {
	config: PropTypes.any,
	data: PropTypes.any,
	location: PropTypes.any
}