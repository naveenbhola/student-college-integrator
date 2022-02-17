import PropTypes from 'prop-types'
import React from 'react';
import './../assets/campusRepWidget.css';
import './../assets/courseCommon.css';
import Lazyload from './../../../reusable/components/Lazyload';

class CampusRepComponent extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
		}
	}

	generateCampusRepHtml(){
		let campusRepData = this.props.campusRepData.campusReps;
		let html =[];
		for(let index in campusRepData){
			html.push(<div className="curr-stu" key={index}>
				<div className="curr-stuImg">
					{
						campusRepData[index]['imageUrl'] != '' && campusRepData[index]['imageUrl'] ? <Lazyload offset={100} once>
							<img src={campusRepData[index]['imageUrl']}/>
						</Lazyload> : campusRepData[index]['displayName'] && campusRepData[index]['displayName'].substring(0,1).toUpperCase()
					}
				</div>
				<div className="curr-stuDet">
					<strong>{campusRepData[index]['displayName']}</strong>
				</div>
			</div>);

		}
		return html;
	}

	render(){
		let heading = (this.props.heading!=null && this.props.heading!='')? this.props.heading:'Ask Queries to Current Students of this College';
		return(
			<React.Fragment>
				<p className='askQry-titl'>{heading}</p>
				{this.generateCampusRepHtml()}
			</React.Fragment>
		)
	}
}

export default CampusRepComponent;

CampusRepComponent.propTypes = {
	campusRepData: PropTypes.any,
	heading: PropTypes.any
}