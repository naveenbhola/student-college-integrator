import PropTypes from 'prop-types'
import React, {Component} from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import './../assets/naukriCompanyList.css';
import NaukriPlacementGraph from './NaukriPlacementGraph';
import Gradient from './../../../common/components/Gradient';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import Analytics from './../../../reusable/utils/AnalyticsTracking';



class naukriGraphComponent extends Component {
    constructor(props) {
        super(props);
    }

    handlePlacementClick(){
        var data = {};
        this.trackEvent('VIEW_PLACEMENT_DETAILS','click');
        if(this.props.data.listingId){
            data.listingId = this.props.data.listingId;
        }
        if(this.props.data.instituteTopCardData){
            data.instituteTopCardData = this.props.data.instituteTopCardData;
        }
        if(this.props.data.reviewWidget){
            data.reviewWidget = this.props.data.reviewWidget;
        }
        if(this.props.data.currentLocation){
            data.currentLocation = this.props.data.currentLocation;
        }
        if(this.props.data.aggregateReviewWidget !=='undefined'){
            data.aggregateReviewWidget = this.props.data.aggregateReviewWidget;
        }
        if(this.props.data.anaCountString !=='undefined'){
            data.anaCountString = this.props.data.anaCountString;
        }        
        if(this.props.data.listingType){
            data.listingType = this.props.data.listingType;
        }
        data.fromWhere = 'placementPage';  
        data.showFullSectionLoader = true;
        data.anaWidget = {};
        data.allQuestionURL = '';
        data.showFullLoader = false;
        data.PageHeading = 'Placement - Highest & Average Salary Package';
        this.props.storeChildPageDataForPreFilled(data);
    }

    trackEvent(eventAction,label='Click')
    {
        Analytics.event({category : this.props.gaCategory, action : eventAction, label : label});
    }

    render(){
        const {naukriSalaryData} = this.props;
        let addContainerClass = '';
        if(this.props.showGradient){
          addContainerClass = 'gradientHeight';
        }
        return(
            <section className="naukri_alumini listingTuple" id="placements">
                <div className="_container ctpn-filter-sec">
                    <h2 className="tbSec2">Alumni Employment</h2>
                    <span className='naukri-logo-txt'>Powered by 
                        <i className="naukri-img"></i>
                    </span>
                    <div className={'_subcontainer '+addContainerClass}>
                        <div className="salry_dtlsblock">
                            <div className="salary_graph">
                                <p className="graph_title"><strong>Salary of Alumni</strong> <span>(Annual) (In INR)</span></p>
                                <div>
                                    <div className="pwa_googlechart">
                                        <NaukriPlacementGraph salaryData = {naukriSalaryData}/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    {this.props.showGradient && <Gradient heading='View Placement Details' onClick={this.handlePlacementClick.bind(this)} url={this.props.placementUrl} />}
                </div>
            </section>
        )

    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}

export default connect(null,mapDispatchToProps)(naukriGraphComponent);


