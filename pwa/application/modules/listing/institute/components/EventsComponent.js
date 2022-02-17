import PropTypes from 'prop-types'
import React from 'react';
import './../assets/css/events.css';
import './../../course/assets/courseCommon.css';
import {cutStringWithShowMore} from './../../../../utils/stringUtility';
import Lazyload from './../../../reusable/components/Lazyload';
import Analytics from './../../../reusable/utils/AnalyticsTracking';


const Events = (props) => {

	var eventsUrl = (props.data.eventsUrl)?props.data.eventsUrl: "";
	var numberOfEvents = 0;
	var defaultUrl = (props.data.defaultUrl)?props.data.defaultUrl: "";
	var maxDisplayEvents = 3;
	let ampmaxDisplayEvents = 2;
	let isAmp = props.isAmp;
	var category = 'ILP';
	if(props.page == "university"){
		category = 'ULP';
	}
	const trackEvent = () => (Analytics.event({category : category, action : 'Eventswidget', label : 'Click'}));


	return(
		<React.Fragment>
			{(!isAmp) ?
				<section className="clg-EvntLst listingTuple" id="Events">
					<div className="_container">
						<h2 className="tbSec2">College Events</h2>
						<div className="_subcontainer">
							<input type="checkbox" className = "read-more-state1 hide" id="eventslist" name="expandData" />
							<ul className="read-more-wrap">
								{
									props.data.eventsDetails.map(function(events,index){
										numberOfEvents++;
										return (
											<li key={"events_"+index} className={index >= maxDisplayEvents ? 'read-more-target1' : 'lt'}>
												<div className="evnt-fig">
													<Lazyload offset={100} once>
														<img src={(eventsUrl[events.event_id])?eventsUrl[events.event_id]:defaultUrl} alt="Image is not available"/>
													</Lazyload>
												</div>
												<div className="event-det">
													<strong>{events.event_name}</strong>
													<input type="checkbox" className='read-more-state hide' id={"readMr-evnt_"+index}/>
													<p className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(events.description,80,"readMr-evnt_"+index,'more',true,false)}}></p>
												</div>
											</li>
										)

									})
								}

							</ul>

							{numberOfEvents > maxDisplayEvents && <div className="button-container">
								<label htmlFor="eventslist"  onClick={trackEvent} className="read-more-trigger vwal-Link" >View All <i className="chBrnch-ico"></i></label>
							</div>}

						</div>
					</div>
				</section>:
				<section>
					<div className='data-card m-5btm'>
						<h2 className='color-3 f16 heading-gap font-w6'>College Events</h2>
						<div className='card-cmn color-w'>
							<input type="checkbox" className="read-more-state-out hide" id="post-evnt" name="expandsData" />
							<ul className='sc-ul clg-ev-ul read-more-wrap'>
								{
									props.data.eventsDetails.map(function(events,index){
										numberOfEvents++;
										return(
											<li key={`events_${index}`} className={index >= ampmaxDisplayEvents ? 'read-more-target-out' : ''}>
												<div className='clgeve-img'>
													<amp-img src={(eventsUrl[events.event_id])?eventsUrl[events.event_id]:defaultUrl} alt="Image is not available" height="58" width="68"></amp-img>
												</div>
												<p className='m-5btm color-3 f14 font-w7'>{events.event_name}</p>
												<input type="checkbox" className='read-more-state hide' id={"ampreadmr-evnt_"+index} />
												<p className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(events.description,80,"ampreadmr-evnt_"+index,'more',true,false,false,true)}}></p>
											</li>
										)
									})
								}
							</ul>
							<React.Fragment>
								{numberOfEvents > ampmaxDisplayEvents &&
								<label htmlFor="post-evnt"  on={'tap:',trackEvent} className="read-more-trigger t-cntr color-b f14 color-b block ga-analytic font-w6 v-arr" >View All </label>
								}
							</React.Fragment>
						</div>
					</div>
				</section>
			}
		</React.Fragment>
	);
};

Events.defaultProps = {
	isAmp: false
}

export default Events;

Events.propTypes = {
  data: PropTypes.any,
  isAmp: PropTypes.bool,
  page: PropTypes.any
}