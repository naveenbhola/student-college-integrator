import React from 'react';
import  './../assets/topSection.css';
import {Link} from 'react-router-dom';
import { connect } from 'react-redux';
import {formatNumber} from './../../../utils/commonHelper';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {rippleEffect} from './../../reusable/utils/UiEffect';

class TopSection extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {
			srchTab : 'clg',
			pathName : null
		}
	}
    setReferrer(){
        window.referrer = window.location.href;
    }
	trackEvent(event)
	{
		rippleEffect(event);
		Analytics.event({category : 'SEARCH_MOBILE', action : 'Search Box', label : 'click'});
	}
	render()
	{
		const {config} = this.props;
		return (
				<React.Fragment>
					<section className="msgBnr">
					    <div id="searchInnerCont" className="_container">
					        <h1>
					            Find Colleges, Courses & Exams that are Best for You
					        </h1>
					        {/*<Link to={{ pathname : '/mba/course/-1653',search : ''}}>asa</Link>*/}
					        <div className="home-rvBx">
					            <ul>
					                <li>
					                    <strong>{ this.props.shiskhaCount && formatNumber(this.props.shiskhaCount.instCount)}</strong>Colleges
					                </li>
					                <li>
					                    <strong>{this.props.shiskhaCount && formatNumber(this.props.shiskhaCount.examCount)}</strong>Exams
					                </li>
					                <li>
					                    <strong>{ this.props.shiskhaCount && formatNumber(this.props.shiskhaCount.reviewsCount)}</strong>Reviews
					                </li>
					                <li>
					                    <strong>{ this.props.shiskhaCount && formatNumber(this.props.shiskhaCount.questionsAnsweredCount)}</strong>Answers
					                </li>
					            </ul>
					        </div>
					    </div>
					    <div className="clg-exBx">
					        <div id="mainTabs1" className="tbSec">
					        </div>
					        <div className="tbc">
					            <div data-index="1" className="active">
					                <ul>
					                    <li>
					                        <div id="srchContTab1" className="srBx choose-srch ripple dark" onClick={this.trackEvent.bind(this)}>
					                        <Link to={{ pathname : '/searchLayer'}} onClick={this.setReferrer.bind(this)} className="inpt _hmsrchTab">
                                                Search Colleges, Exams, QnA &amp; Articles
					                           <span className="search-field-btn"><i className="msprite search-icn"></i>
					                           </span>
					                        </Link>
					                        </div>
					                    </li>
					                </ul>
					            </div>
					        </div>
					    </div>
					</section>
				</React.Fragment>
			)
	}
}
function mapStateToProps(state)
{
    return {
        shiskhaCount : state.hpdata.nationalHPParams,
        config : state.config
    }
}


export default connect(mapStateToProps)(TopSection);
