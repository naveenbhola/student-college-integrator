import React from 'react';
import CPInnerTuple from './CPInnerTuple';
import Anchor from './../../../reusable/components/Anchor';
import { getRequest } from './../../../../utils/ApiCalls';
import {makeInputObjectForResultPage} from './../utils/collegePredictorHelper';
import APIConfig from './../../../../../config/apiConfig';
import {event} from './../../../reusable/utils/AnalyticsTracking';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import {storeInstituteDataForPreFilled} from './../../../listing/institute/actions/InstituteDetailAction';

class CPTuple extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			text : 'View More',
			viewMoreData : {},
			showViewMore : this.props.tuplesList.showViewMore,
			showHideAll  : false
		};
		this.link = this.props.deviceType !== 'desktop';
		this.clpIds = [];
	}

	createExamList(tuples){
		let innerTuple = [];
		if(tuples && tuples.exams.length>0){
			tuples.exams.forEach((value,index)=>{
				let item = <div className="er-detail" key={index+1}>
					{value.examURL && <React.Fragment><span className="sroundBrdr"><Anchor onClick={()=>{event({category : this.props.gaTrackingCategory, action : value.examName+(this.props.activeTab === 'branch'?'Specialization':'College'), label : 'click'})}} to={value.examURL} key={index}>{value.examName}</Anchor></span></React.Fragment>}
					{(value.roundNo) ? <React.Fragment><label>Round</label><span className="black">{value.roundNo}</span></React.Fragment> : null}
					{(value.roundNo && value.result) ? <span className="pipe">|</span> : null}
					{(value.result) ? <React.Fragment><label>{(value.resultType === "rank") ? 'Rank' : 'Score'}</label><span className="black">{value.result}</span></React.Fragment>:null}
					{value.homeState && <span className="quota lead1">Home State</span>}
				</div>;
				innerTuple.push(item);
			});
		}
		return innerTuple;
	}

	createInnerTuple(tuples, collegeName){
		let innerTuple = [];
		if(tuples && tuples.length>0){
			tuples.forEach((value,index)=>{
				if(value.id && this.clpIds.indexOf(value.id) ==-1){
					this.clpIds.push(value.id)
				}
				let examList = this.createExamList(value);
				innerTuple.push(<CPInnerTuple gaTrackingCategory={this.props.gaTrackingCategory} activeTab={this.props.activeTab} key={'cp'+index} tuples={value} exams={examList} link={this.link} deviceType={this.props.deviceType} collegeName={collegeName}/>);
			});
		}
		return innerTuple;
	}

	getViewMore(){
		event({category : this.props.gaTrackingCategory, action : 'View_More_'+(this.props.activeTab === 'branch'?'Colleges':'Specialization'), label : 'click'});
		this.setState({text : 'Loading More...'});
		let viewMoreInputs = {};
		if(this.props.tuplesList.heading && !this.props.tuplesList.headingURL){
			viewMoreInputs.specId = this.props.tuplesList.headingId;
		}else{
			viewMoreInputs.instId = this.props.tuplesList.headingId;
		}
		viewMoreInputs.exCLPs = this.clpIds;
		let params = makeInputObjectForResultPage(this.props.search, viewMoreInputs);

		const axiosConfig = {headers: {'Content-Type': 'application/x-www-form-urlencoded'},withCredentials: true};
      	getRequest(APIConfig.GET_COLLEGE_PREDICTOR_VIEW_MORE+'?data='+params,axiosConfig)
      	.then((response) =>{
            if(response.data && response.data.data){
            	const finalList = [...this.state.viewMoreData, ...response.data.data.tuples];
            	if(response.data.data.tuples && response.data.data.tuples.length>0){
            		this.setState({text : 'View More', viewMoreData : finalList, showViewMore : response.data.data.showViewMore, showHideAll : true});
            	}else{
            		this.setState({text : 'View More', viewMoreData : {}, showViewMore : false});
            	}
            }
      	}).catch((err)=> console.log('View More::',err));
	}

	hideAll(eleId){
		if(eleId){
			let ele = document.querySelector('#crd_'+eleId);
			ele.scrollIntoView();
		}
		this.clpIds = this.clpIds.slice(0, 3);
		event({category : this.props.gaTrackingCategory, action : 'Hide_All_'+(this.props.activeTab === 'branch'?'Colleges':'Specialization'), label : 'click'});
		this.setState({text : 'View More', viewMoreData : {}, showViewMore : true, showHideAll : false});
	}

	instituteClick = (collegeName) =>{
    	if(this.props.deviceType != 'desktop'){
    		var data = {};
	        data.instituteName = collegeName;
	    	this.props.storeInstituteDataForPreFilled(data);	
    	}
    	event({category : this.props.gaTrackingCategory, action : 'ILP_Name_College', label : 'click'})
    }

	render(){
			let enableLoadMore = (this.state.showViewMore) ? true : ((this.state.showHideAll) ? true : false);
			let collegeName = (this.props.tuplesList.heading && this.props.tuplesList.headingURL) ? this.props.tuplesList.heading:'';
			return (
				<div className="shortlist-tuple">	
					<div className="instituteCard" id={'crd_'+this.props.tuplesList.headingId}>
						
						{this.props.tuplesList.heading && !this.props.tuplesList.headingURL && <p className="instituteName"><strong>{this.props.tuplesList.heading}</strong></p>}
						{this.props.tuplesList.heading && this.props.tuplesList.headingURL && <p className="instituteName"><Anchor className="blackLink" to={this.props.tuplesList.headingURL} link={this.link} onClick={()=>{this.instituteClick(collegeName)}}><strong>{this.props.tuplesList.heading}</strong></Anchor></p>}

						{this.props.tuplesList.location && <p className="instituteLocation">{this.props.tuplesList.location}</p>}
					</div>
					
					{this.createInnerTuple(this.props.tuplesList.tuples, collegeName)}

					{this.state.viewMoreData && Object.keys(this.state.viewMoreData).length>0 && this.createInnerTuple(this.state.viewMoreData, collegeName)}

					{enableLoadMore && 
						<div className="loadMore">
							<div className="flex column">
								{this.state.showViewMore && <strong className="link more" onClick={(this.state.text == 'View More') ? this.getViewMore.bind(this) : null}>{this.state.text}{this.state.text == 'View More' && <i className="arrow down"></i>}</strong>}
								{this.state.showHideAll && <a className="link less" onClick={this.hideAll.bind(this,this.props.tuplesList.headingId)}>Hide All<i className="arrow up"></i></a>}
							</div>
						</div>
					}
				</div>
			);
	}
};

function mapDispatchToProps(dispatch){
	return bindActionCreators({storeInstituteDataForPreFilled},dispatch);
}
export default connect(null, mapDispatchToProps)(CPTuple);