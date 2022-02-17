import React from 'react';
import MobileFilters from "../../../listing/categoryList/components/FiltersComponent";
import Anchor from './../../../reusable/components/Anchor';
import {event} from "../../../reusable/utils/AnalyticsTracking";

class CPMobileFilter extends React.Component{
	constructor(props){
		super(props);
		this.makeSticky = this.makeSticky.bind(this); //bind function once
		this.filterTop = 0;
		this.footerPos = 0;
	}

	componentDidMount(){
		this.footerPos = (document.getElementById('page-footer')) ? document.getElementById('page-footer').offsetTop : 0;
		this.filterTop = (document.getElementById('mobileFilter')) ? document.getElementById('mobileFilter').offsetTop : 0;
		window.addEventListener("scroll", this.makeSticky);
	}

	componentWillUnmount(){
		window.removeEventListener("scroll", this.makeSticky);
    }

	makeSticky = () =>{
        let wScroll = window.scrollY;
        let filterEle = document.getElementById('mobileFilter');
        if(wScroll > this.filterTop && wScroll<this.footerPos){
        	filterEle.classList.add('fixedTop');	
        }else{
        	filterEle.classList.remove('fixedTop');	
        }
    };
	
	render(){
        let filtersWithSearch = ['location', 'specialization', 'institute'];
		return (
			<div className="fixedTop-wrapper">
				<div className="shadow-box" id="mobileFilter">
					<MobileFilters
						gaTrackingCategory={this.props.gaTrackingCategory}
						shortName={this.props.aliasMapping}
						filterOrder={this.props.filterOrder}
						filters={this.props.filters}
						displayName={this.props.nameMapping}
						pageUrl={this.props.pageUrl}
						urlHasParams={true}
						hasFilterData={true}
						isAllPredictorPage={true}
						selectedFiltersCount={this.props.selectedFiltersCount}
	                    filtersWithSearch={filtersWithSearch}
					/>
					<div className="table specialization-tab">
						<span className="table-cell"><Anchor onClick={()=>{event({category : this.props.gaTrackingCategory, action : 'Colleges_Tab', label : 'click'})}} className={(this.props.activeTab == 'branch') ? '' : 'activeTab'} to={this.props.currentPageUrl+'&tab=college'}><span id="tabCollege" className={(this.props.activeTab == 'college') ? 'tab active' : 'tab'}><i className="college-icon"></i>Colleges</span></Anchor></span>
						<span className="table-cell"><Anchor onClick={()=>{event({category : this.props.gaTrackingCategory, action : 'Specialization_Tab', label : 'click'})}} className={(this.props.activeTab == 'college') ? '' : 'activeTab'} to={this.props.currentPageUrl+'&tab=branch'}><span id="tabSpecialization" className={(this.props.activeTab == 'branch') ? 'tab active' : 'tab'}><i className="specialization-icon"></i>Specialization</span></Anchor></span>
					</div>
				</div>
			</div>
		);
	}
}
CPMobileFilter.defaultProps = {
	gaTrackingCategory : 'AllCollegePredictorResultDesktop'
};
export default CPMobileFilter;
