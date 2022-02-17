import React from 'react';
import ReactDOM from 'react-dom';
import styles from './../assets/latestUpdate.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {rippleEffect} from './../../reusable/utils/UiEffect';
import { connect } from 'react-redux';

class LatestUpdateWidget extends React.Component
{
	constructor(props)
	  {
	    super(props);
	    this.state = {
		latestUpdateData: [],
	    }
	    this.trackEvent = this.trackEvent.bind(this);
	  }

	trackEvent(event)
    {
    	rippleEffect(event);
        Analytics.event({category : 'HPbody', action : 'ViewUpdates', label : 'Click'});
    }

	render()
	{
		return (
				<section className="artBnr">
			      <div className="_container">
			          <h2 className="tbSec2">Latest Updates</h2>
			          <div className="_ask">
			              <div className="LUpdate">
			                  <ul>
								{typeof this.props.latestUpdateData != 'undefined' && this.props.latestUpdateData.map((update,index) => {
							     	return <li key={index} className="ripple dark" onClick={(event) => rippleEffect(event)}><a className="lead" href={update.url}>{update.blogTitle}</a></li>
							    })} 
			                  </ul>
			              </div>
			              <div className='button-container'>
			                  <a id="AllUpd" href={this.props.config.SHIKSHA_HOME + "/articles-all"} onClick={this.trackEvent}>
			                    <button type="button" name="button" className="button button--secondary ripple dark arrow">View All Updates </button></a>
			              </div>
			          </div>
			      </div>
			    </section>
			);
	}
}
//export default LatestUpdateWidget;
function mapStateToProps(state)
{
    return {
        latestUpdateData : state.hpdata.latestArticles,
        config : state.config

    }
}

export default connect(mapStateToProps)(LatestUpdateWidget);
