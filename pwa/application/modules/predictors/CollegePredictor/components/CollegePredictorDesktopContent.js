import PropTypes from 'prop-types'
import React from 'react';
import CollegePredictorInfoForUser from "./CollegePredictorInfoForUser";
import ResultTypeTabs from "./ResultTypeTabs";
import CPTuple from "./CPTuple";
import PaginationComponent from "../../../common/components/PaginationComponent";

class CollegePredictorDesktopContent extends React.Component{

	render(){

		if(this.props.tuplesList == ''){
			return null;
		}

		let oddList = [], evenList = [], finalResult = [], j=1;
		this.props.tuplesList.forEach((value)=>{
			finalResult[j++] = value;
		});

		for(let i = 1; i <= finalResult.length; i++){
			if((i % 2) === 0 && finalResult[i]){
				evenList.push(<CPTuple activeTab={this.props.activeTab} search={this.props.search} key={i} tuplesList={finalResult[i]} deviceType={this.props.deviceType} gaTrackingCategory={this.props.gaTrackingCategory} />);
			}else if(finalResult[i]){
				oddList.push(<CPTuple activeTab={this.props.activeTab} search={this.props.search} key={i} tuplesList={finalResult[i]} deviceType={this.props.deviceType} gaTrackingCategory={this.props.gaTrackingCategory} />);
			}
		}

		return (<div className="fltryt card-area cp-results">
			<div className="tab-sec box clearFix">
				<CollegePredictorInfoForUser />
				<ResultTypeTabs currentPageUrl={this.props.tabUrl} activeTab={this.props.activeTab} gaTrackingCategory={this.props.gaTrackingCategory} />
			</div>
			<div className="fltlft odd-data">
				{oddList}
			</div>
			<div className="fltryt even-data">
				{evenList}
			</div>
			<div className="clearFix">
				<PaginationComponent totalItems={this.props.totalResults} currentPage={this.props.currentPageNo} currentPageUrl={this.props.currentPageUrl} queryParam="pageNo" pageSize={this.props.resultPerPage}/>
			</div>
		</div>);
	}
}

export default CollegePredictorDesktopContent;

CollegePredictorDesktopContent.propTypes = {
  activeTab: PropTypes.string,
  currentPageNo: PropTypes.number,
  currentPageUrl: PropTypes.string,
  deviceType: PropTypes.string,
  resultPerPage: PropTypes.number,
  tabUrl: PropTypes.string,
  totalResults: PropTypes.any,
  tuplesList: PropTypes.any
}