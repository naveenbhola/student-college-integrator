import React from 'react';
import ReactDOM from 'react-dom';
import styles from './../assets/expertGuidance.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {rippleEffect} from './../../reusable/utils/UiEffect';

class ExpertGuidanceWidget extends React.Component
{
	constructor(props)
	{
		super(props);
		this.trackEvent = this.trackEvent.bind(this);
	}
	trackEvent(event)
    {
    	rippleEffect(event);
        Analytics.event({category : 'HPbody', action : 'AskNow', label : 'Click'});
    }
	render()
	{
		const {config} = this.props;
		return (
				<section className="expBnr">
				    <div className="_container">
				        <h2 className="tbSec2">Expert Guidance</h2>
				        <div className="_ask">
				            <h3>Ask &amp; Answer</h3>
				            <p>Get answers on career &amp; education from India's largest education community</p>
				            <div className="exDta">
				                <ul className="table">
				                    <li className="table-cell">
				                        <i className="Quick"></i>
				                        <div>
				                            <strong>1000+</strong>
				                            <span>Experts</span>
				                        </div>
				                    </li>
				                    <li className="table-cell">
				                        <i className="Reliable"></i>
				                        <div>
				                            <strong>Reliable</strong>
				                            <span>Answers</span>
				                        </div>
				                    </li>
				                    <li className="table-cell">
				                        <i className="Experts"></i>
				                        <div>
				                            <strong>Quick</strong>
				                            <span>Response</span>
				                        </div>
				                    </li>
				                </ul>
				            </div>
				            <div className="button-container">
				                <a className="AskBtn " href={config.SHIKSHA_HOME+"/mAnA5/AnAMobile/getQuestionPostingAmpPage"} onClick={this.trackEvent}>
                                  <button type="button" name="button" className="button button--orange ripple dark">Ask Now</button>
				                </a>
				            </div>
				        </div>
				    </div>
				</section>
			);
	}
}
export default ExpertGuidanceWidget;
