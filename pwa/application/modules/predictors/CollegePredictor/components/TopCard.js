import PropTypes from 'prop-types'
import React from 'react';
import './../assets/TopCard.css';
import Anchor from './../../../reusable/components/Anchor';
import {Ucfirst} from "../../../../utils/commonHelper";
import { getRequest } from './../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import ToolTipMsg from './TooltipMsg';
import {storeSaveList} from './../actions/CollegePredictorAction';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {event} from './../../../reusable/utils/AnalyticsTracking';

class TopCard extends React.Component{
	constructor(props)
	{
		super(props);
		this.makeSticky = this.makeSticky.bind(this); //bind function once
		this.wHeight    = 0;
		this.state = {
			showMoreVisible : true,
			showMoreData : false,
			showSaveListMsg : false,
			saveListClick : false,
			prevUrl : '',
			heading : false
		};
	}

	componentDidMount(){
		this.wHeight    = (window.outerHeight>0) ? window.outerHeight : window.innerHeight; // window.innerHeight for safari
		window.addEventListener("scroll", this.makeSticky);	
	}

	componentWillUnmount(){
		window.removeEventListener("scroll", this.makeSticky);
    }

    makeSticky = () =>{
	    let wScroll = window.scrollY;
    	if(this.props.deviceType != 'desktop'){
	        let ele = document.getElementById('cpSticky');
	        let footerPos = (document.getElementById('page-footer')) ? document.getElementById('page-footer').offsetTop : 0;
	        	footerPos = (footerPos) ? footerPos - this.wHeight : 0;
	        if(wScroll > footerPos){
	        	ele.classList.add('hide');
	        }else{
	        	ele.classList.remove('hide');
	        }
	    }
       
        if(wScroll > 20 && this.state.showSaveListMsg){
    		this.hideSaveListMsg();	
        }
    };

	getUserCriteria = () => {
		if(typeof this.props.topCardData === 'undefined' || this.props.topCardData.length === 0){
			return [];
		}
		let allCriteria1 = [], allCriteria2 = [], i = 0;
		this.props.topCardData.map((criteria) => {
			if(i < this.props.criteriaToShow){
				allCriteria1.push(<p key={criteria.examName}><strong>{criteria.examName}</strong> {Ucfirst(criteria.resultType)} {criteria.result}{criteria.resultType === 'rank' ? ' AIR' : ''}, {criteria.categoryName}</p>);
			}else{
				allCriteria2.push(<p key={criteria.examName}><strong>{criteria.examName}</strong> {Ucfirst(criteria.resultType)} {criteria.result}{criteria.resultType === 'rank' ? ' AIR' : ''}, {criteria.categoryName}</p>);
			}
			i++;
		});
		return [allCriteria1, allCriteria2];
	};
	
	showAllCriteria = () => {
		event({category : this.props.gaTrackingCategory, action : 'Show_Inputs', label : 'click'});
		this.setState({showMoreVisible : false, showMoreData : true});
	};

	saveList(){
		event({category : this.props.gaTrackingCategory, action : 'Save_List', label : 'click'});
		let storeData = {};
		let url = Buffer.from(this.props.currentPageUrl).toString('base64');
		if(this.state.prevUrl == url){
			window.scrollTo(0,0);
			this.setState({showSaveListMsg: true, heading : true});
			return true;
		}
		this.setState({saveListClick : true, prevUrl : url});
		const axiosConfig = {headers: {'Content-Type': 'application/x-www-form-urlencoded'},withCredentials: true};
		getRequest(APIConfig.GET_COLLEGE_PREDICTOR_SAVE_LIST+'?url='+url,axiosConfig)
			.then((response) =>{
				if(response.data && response.data.status == 'success'){
					window.scrollTo(0,0);
					this.setState({showSaveListMsg : true, saveListClick : false, heading : false});
					storeData.url = this.props.currentPageUrl;
					this.props.storeSaveList(storeData);
				}
			}).catch((err)=> console.log('Save List::',err));
	}

	hideSaveListMsg(){
		this.setState({showSaveListMsg : false, saveListClick : false, heading : false});
	}
	trackEvent = (action, label) => {
		event({category : this.props.gaTrackingCategory, action : action, label : label});
	};

	render()
	{
		let criteriaData = this.getUserCriteria();
		return (
			<div className="shadow-box box clearFix">
				{this.state.showSaveListMsg && <ToolTipMsg heading={this.state.heading} hideSaveListMsg={this.hideSaveListMsg.bind(this)} />}
				<div className="fltlft rsltArea">
					<div className="flex left">
						<div className="flex-item result">
							<strong className="h1">{this.props.collegeCount > 1 ? this.props.collegeCount + ' Colleges' : this.props.collegeCount + ' College'}</strong>
							<span className="fw-nrml"> found for</span>
						</div>
						<div className="flex-item modify">
							<Anchor to={this.props.modifySearchUrl} onClick={this.trackEvent.bind(this, 'Modify_search', 'click')}>{<strong className="link modifySrch lead1"><i className="refresh-icon"></i> Modify Search	</strong>}</Anchor>
						</div>
					</div>
					<div className={this.props.deviceType === 'desktop' ? 'usr-exm-inpts' : ''}>
						{criteriaData[0]}
						{this.state.showMoreVisible && typeof this.props.topCardData !== 'undefined' && this.props.topCardData.length > this.props.criteriaToShow && <a href='javascript:void(0);' onClick={this.showAllCriteria}> + {this.props.topCardData.length-this.props.criteriaToShow} more</a>}
						{this.state.showMoreData && typeof this.props.topCardData !== 'undefined' && this.props.topCardData.length > this.props.criteriaToShow && criteriaData[1]}
					</div>
				</div>
				<div className={(this.props.deviceType != 'desktop') ? "fltryt mobileSticky" : "fltryt" } id="cpSticky">
					<div className="table cta-cont">
						<div className="table-cell">
							<button className="button button--secondary" onClick={(this.state.saveListClick) ? null : this.saveList.bind(this)}>{(this.state.saveListClick) ? 'Wait...' : 'Save List'}</button>
						</div>
						
					</div>
				</div>
			</div>
		)
	}
}
TopCard.defaultProps = {
	modifySearchUrl : '/college-predictor',
	collegeCount : 0,
	topCardData : [],
	criteriaToShow : 2
};

function mapDispatchToProps(dispatch){
	return bindActionCreators({storeSaveList},dispatch);
}
export default connect(null, mapDispatchToProps)(TopCard);

TopCard.propTypes = {
  collegeCount: PropTypes.number,
  deviceType: PropTypes.string,
  modifySearchUrl: PropTypes.string
}