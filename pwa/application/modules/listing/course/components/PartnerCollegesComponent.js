import PropTypes from 'prop-types'
import React from 'react';
import './../assets/partnerColleges.css';
import './../assets/courseCommon.css';

class PartnerColleges extends React.Component
{
	constructor(props)
	{
		super(props);
	}
	formatDuration(number,type)
	{
		switch(type)
		{
			case 'hours':
				return number > 1 ? number + ' Hours' : number + ' Hour';
			case 'days':
				return number > 1 ? number + ' Days' : number + ' Day';
			case 'weeks':
				return number > 1 ? number + ' Weeks' : number + ' Week';
			case 'months' :
				return number > 1 ? number + ' Months' : number + ' Month';
			case 'years' :
				return number > 1 ? number + ' Years' : number + ' Year';
			default :
				return '';
		}
	}
	render()
	{
		var self = this;
		const isAmp = this.props.isAmp;
		// var numberOfEleToDisplay = 4;
		var partnersColleges = this.props.data;
		//partnersColleges = partnersColleges.slice(0,numberOfEleToDisplay);
		return(
			<React.Fragment>
				{ (isAmp)?<section>
					<div className="data-card m-5btm">
						<h2 className="color-3 f16 heading-gap font-w6">Partner Colleges</h2>
						<div className="card-cmn color-w">
							<ul className="prtnrs">
								{
									partnersColleges.map(function(partners,index)
									{
										return (<li className="pos-rl" key={"partners_"+index+'_'+partners.partnerId}>
											<a className="block" href={partners.url}>
												<div className="block">
													<p className="f14 color-6 m-btm font-w6 word-break">
														{(partners.partnerName.length > 84)? partners.partnerName.substr(0,81)+"...":partners.partnerName}
													</p>
													<span className="block f12 color-9 ">Duration {self.formatDuration(partners.durationValue,partners.durationUnit)}</span>
												</div>
											</a>
										</li>)
									})
								}
							</ul>
						</div>
					</div>
				</section>:	<section className='partnerCollegeBnr listingTuple' id="partner">
					<div className='_container'>
						<h2 className='tbSec2'>Partner Colleges</h2>
						<div className='_subcontainer'>
							<ul className='prtnr-list'>
								{
									partnersColleges.map(function(partners,index)
									{
										return (<li key={"partners_"+index+'_'+partners.partnerId}>
											<a href={partners.url}>{partners.partnerName}</a>
											<span>Duration {self.formatDuration(partners.durationValue,partners.durationUnit)}</span>
										</li>)
									})
								}
							</ul>
						</div>
					</div>
				</section>}
			</React.Fragment>
		);
	}
}

PartnerColleges.defaultProps = {
	isAmp : false
}

export default PartnerColleges;

PartnerColleges.propTypes = {
	data: PropTypes.any,
	isAmp: PropTypes.bool
}