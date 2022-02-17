import PropTypes from 'prop-types'
import React from 'react';
import InlineFilterWidget from './InlineFilterWidget';
import CPTuple from "./CPTuple";

class CollegePredictorMobileContent extends React.Component{
	getOCF = () => {
		while(this.props.ocfWidget.length > 0){
			let filterType = this.props.ocfWidget.pop();
			if(this.props.filters[filterType] && !this.checkIfFilterAlreadySelected(this.props.filters[filterType]) && this.props.filters[filterType].length > 1){
				return <InlineFilterWidget key={filterType+'_ocf'} filterType={filterType} aliasMapping={this.props.aliasMapping} currentPageUrl={this.props.currentPageUrl} heading={this.props.filterNames[filterType]} data={this.props.filters[filterType]} gaTrackingCategory={this.props.gaTrackingCategory} />;
			}
		}
		return null;
	};
	checkIfFilterAlreadySelected = (data) => {
		if(data && data.length > 0){
			for(let i=0; i<data.length; i++){
				if(data[i].checked === true){
					return true;
				}
			}
			return false;
		}
	};
	render(){
		if(this.props.tuplesList == ''){
			return null;
		}

		let finalList = [];
		this.props.tuplesList.forEach((value,index)=>{
			if(value){
				finalList.push(<CPTuple search={this.props.search} key={index} tuplesList={value} deviceType={this.props.deviceType} gaTrackingCategory={this.props.gaTrackingCategory} />);
				if(this.props.currentPageNumber === 1 && (index === 0 || index === 2 || index === 4 || index === 6)){
					finalList.push(this.getOCF());
				}
			}
		});	

		return (
		    <React.Fragment>
		    	{finalList}
            </React.Fragment>
		)
	}
}
CollegePredictorMobileContent.defaultProps = {
	currentPageNumber : 1,
	filterNames : {},
	filters : {},
	ocfWidget : []
};
export default CollegePredictorMobileContent;

CollegePredictorMobileContent.propTypes = {
  deviceType: PropTypes.string,
  tuplesList: PropTypes.array
};