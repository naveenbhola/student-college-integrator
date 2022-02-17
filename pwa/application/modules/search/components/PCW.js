import React from 'react';
import AggregateReview from "../../listing/course/components/AggregateReviewWidget";
import config from './../../../../config/config';
import {Link} from 'react-router-dom';
import './../assets/pcw.css';
import Analytics from "../../reusable/utils/AnalyticsTracking";
import Lazyload from "../../reusable/components/Lazyload";
import {bindActionCreators} from "redux";
import {storeInstituteDataForPreFilled} from "../../listing/institute/actions/InstituteDetailAction";
import { connect } from 'react-redux';
import {addingDomainToUrl} from "../../../utils/commonHelper";

const domainName  = config().SHIKSHA_HOME;
class PCW extends React.Component {
    constructor(props) {
        super(props);
    }
    trackEvent(actionLabel, label) {
        Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
    }
    handleClickOnInstitute(instituteData) {
        this.trackEvent('ILP','PCW_click');
        if(instituteData  && typeof instituteData === 'object') {
            let data =  [];
            data.instituteName = instituteData.name;
            data.ownership = instituteData.ownership;
            data.autonomous = instituteData.autonomous;
            data.nationalImportance = instituteData.nationalImportance;
            data.univeristyTypeWithSpecification = instituteData.universityTypeWithSpecification;
            data.ugcApproved = instituteData.ugcApproved;
            data.naacAccreditation = instituteData.naacAccreditation;
            data.establishYear = instituteData.estbYear;
            data.aiuMember = instituteData.aiuMember;
            data.location = instituteData.displayLocationString;


            this.props.storeInstituteDataForPreFilled(data);
        }
    }
    generatePCWList(){
        let pcwTuples = this.props.tupleData;
        return pcwTuples.map((tuple, i) =>
        <li key={'pcwTuple_' + i} className="_flexirowPCW">
            {this.props.nonPWALinks ?
                <a href = {addingDomainToUrl(tuple.url, domainName)} onClick = {this.trackEvent.bind(this,'ILP','PCW_click')}>
                    <div className="flexi_img">{(this.props.isImageLazyLoad) ?
                        <Lazyload offset={100} once><img alt= {tuple.name} src={tuple.instituteThumbUrl} /></Lazyload> :
                        <img alt= {tuple.name} src={tuple.instituteThumbUrl} /> }
                    </div>
                </a> :
                <Link to = {tuple.url} onClick = {this.handleClickOnInstitute.bind(this, tuple)}>
                <div className="flexi_img">{(this.props.isImageLazyLoad) ?
                    <Lazyload offset={100} once><img alt= {tuple.name} src={tuple.instituteThumbUrl} /></Lazyload> :
                    <img alt= {tuple.name} src={tuple.instituteThumbUrl} /> }
                </div>
            </Link>}
                <div className="flexi_columnPCW">
                    {this.props.nonPWALinks ? <a href = {addingDomainToUrl(tuple.url, domainName)} onClick = {this.trackEvent.bind(this,'ILP','PCW_click')}>
                        <p className="_clgname">{tuple.name}</p> </a> :
                    <Link to = {tuple.url} onClick = {this.handleClickOnInstitute.bind(this, tuple)}>
                        <p className="_clgname">{tuple.name}</p> </Link>}
                    <p className="ctp-cty">{tuple.displayLocationString}</p>
                    <div className="ratingv1">{tuple.aggregateReviewData ?
                        <div className="clg-col single-col">
                        <AggregateReview isPaid={false} showPopUpLayer = {true} uniqueKey= {'institute_'+tuple.instituteId} showAllreviewUrl={true}
                                         reviewsCount={tuple.aggregateReviewData['totalCount']}
                                         aggregateReviewData = {{'aggregateReviewData' : tuple.aggregateReviewData,
                                             'aggregateRatingDisplayOrder' : this.props.aggregateReviewConfig.aggregateRatingDisplayOrder}}
                                         reviewUrl={tuple.reviewUrl} gaTrackingCategory={this.props.gaTrackingCategory + "_PCW"} config={config()}/> </div>: null}
                    </div>
                </div>
        </li>);
    }
    render(){
        if(!this.props.showPCW){
            return (null);
        }
        return(
            <div className="wrapperAround">
                <ul className = 'ctp_popular'>
                    {this.generatePCWList()}
                </ul>
            </div>
        );
    }
}
PCW.defaultProps = {
    showPCW : true, isImageLazyLoad: true, nonPWALinks : false
};

function mapDispatchToProps(dispatch){
    return bindActionCreators({storeInstituteDataForPreFilled }, dispatch);
}

export default connect(null, mapDispatchToProps)(PCW);
