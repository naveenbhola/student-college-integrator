import PropTypes from 'prop-types'
import React from 'react';
import PopupLayer from './../../../common/components/popupLayer';
import {Link} from 'react-router-dom';
import Lazyload from './../../../reusable/components/Lazyload';
import AggregateReview from '../../../listing/course/components/AggregateReviewWidget';
import CollegeWidgetListLayer from './CollegeWidgetListLayer';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {storeInstituteDataForPreFilled} from './../actions/InstituteDetailAction';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';


function bindingFunctions(functions)
{
    functions.forEach((f) => (this[f] = this[f].bind(this)));
}




class CollegeListComponent extends React.Component {

    constructor(props){
        super(props);
        bindingFunctions.call(this,[
            'collegeWidgetLayer'
        ]);
    }

    trackEvent()
    {
        Analytics.event({category : 'ULP', action : 'CollegesWidget', p : 'click'});
    }

    handleClickOnInstitute(instituteData,instituteId){
        this.trackEvent();
        if(typeof instituteData != 'undefined' && instituteId ) {
            var data =  {};
            data.instituteName = instituteData.name;
            data.instituteId = instituteId;
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

    renderNameInfo(instituteData)
    {
        if(typeof instituteData == 'undefined' || !instituteData)
            return null;
        return (
            <p className="_clgname">
                {instituteData.shortName != '' &&  instituteData.shortName != null ? instituteData.shortName : instituteData.name}
            </p>
        )
    }
    renderCollegeImage(collegeimageData, instituteData)
    {
        if (instituteData.mediaUrl != null ) {
            return(
                <div className="flexi_img">
                    <Lazyload offset={100} once>
                        <img src={instituteData.mediaUrl} alt={instituteData.name}/>
                    </Lazyload>
                </div>
            )
        }
        return(
            <div className="flexi_img">
                <Lazyload offset={100} once>
                    <img src={collegeimageData.defaultImageUrl} alt ="default image"/>
                </Lazyload>
            </div>
        )

    }
    renderReviewInfo(aggregateReviewData,instituteId,reviewUrl)
    {
        if(typeof aggregateReviewData['aggregateReviewData'] != 'undefined' && typeof aggregateReviewData['aggregateReviewData'][instituteId] != 'undefined' && aggregateReviewData['aggregateReviewData'][instituteId])
        {

            return (<div className="ratingv1">
                    {<AggregateReview isPaid={false} showPopUpLayer = {false} uniqueKey= {'institute_'+instituteId} showAllreviewUrl={true} reviewsCount={aggregateReviewData['aggregateReviewData'][instituteId]['totalCount']}  aggregateReviewData = {{'aggregateReviewData' : aggregateReviewData.aggregateReviewData[instituteId],'aggregateRatingDisplayOrder' : aggregateReviewData.aggregateRatingDisplayOrder}} reviewUrl={reviewUrl} config={this.props.config}/>}
                </div>

            )
        }
        return null;
    }

    renderCollegeInfo(collegelistWidget)
    {
        var self = this;
        if(typeof collegelistWidget['collegeWidgetData'] == 'undefined' || (typeof collegelistWidget['collegeWidgetData'] != 'undefined' && (collegelistWidget['collegeWidgetData'] == null || (collegelistWidget['collegeWidgetData']['topInstituteOrder'] && collegelistWidget['collegeWidgetData']['topInstituteOrder'].length == 0))))
        {
            return null;
        }
        let htmlData = [];
        for(let index in collegelistWidget['collegeWidgetData']['topInstituteOrder'])
        {
            let instituteId = collegelistWidget['collegeWidgetData']['topInstituteOrder'][index];
            if(typeof collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId] != 'undefined')
            {
                htmlData.push(<li className="_flexirow rippleefect" key={"collegelist_"+index}>
                    <Link to={{pathname: collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId].instituteUrl}} title={collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId].shortName != '' &&  collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId].shortName != null ? collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId].shortName : collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId].name } onClick={this.handleClickOnInstitute.bind(this,collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId],instituteId)}>
                        {self.renderCollegeImage(collegelistWidget['collegeWidgetData'], collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId])}
                        <div className="flexi_column">
                            {self.renderNameInfo(collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId])}
                            {typeof collegelistWidget['agrregateReviewsData'] != 'undefined'  && collegelistWidget['agrregateReviewsData'] && collegelistWidget['agrregateReviewsData']['aggregateReviewData'] && self.renderReviewInfo(collegelistWidget['agrregateReviewsData'],instituteId,collegelistWidget['collegeWidgetData']['topInstituteData'][instituteId]['reviewUrl'])}
                        </div>
                    </Link>
                </li>);
            }
        }
        return htmlData;
    }

    renderCollegeTitle(){
        const {collegeName} = this.props;
        return (
            <span>{collegeName}</span>
        );
    }


    collegeWidgetLayer = () =>
    {
        this.defaultpopup.open();
    }


    render() {
        const {collegelistWidget} = this.props;

        return (
            <section className='listingTuple' id='Colleges'>
                <div className="collegedpts listingTuple">
                    <div className="_container">
                        <h2 className="tbSec2">Colleges/Departments
                            <span className="clg-label-tip">
                                <React.Fragment>
                                 <i className="clg-info-icon" onClick={this.collegeWidgetLayer}></i>
                                 <PopupLayer onRef={ref => (this.defaultpopup = ref)} data={<p className="infotxt"> Colleges can be of 2 types – Constituent colleges and Affiliated colleges. Constituent colleges are colleges which are maintained by the university itself. Affiliated colleges are educational institutions that operate independently, but also have a formal collaborative agreement with a university for the purpose of awarding the degree with some level of control or influence over its academic policies, standards or programs.</p>} heading="Colleges/Departments" />
                                </React.Fragment>
                             </span>
                        </h2>
                        <div className="_subcontainer">
                            <div className="_titleDpt">
                                <strong>{this.props.collegelistWidget.collegeWidgetData.constituentCollegeText } {this.renderCollegeTitle()}</strong>
                            </div>
                            <div className="wrapperAround">
                                {this.renderCollegeInfo(collegelistWidget)}
                            </div>
                            <CollegeWidgetListLayer renderCollegeInfo={this.renderCollegeInfo.bind(this)} collegelistWidget={collegelistWidget} listingId={this.props.listingId}/>
                        </div>
                    </div>
                </div>
            </section>)
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({storeInstituteDataForPreFilled }, dispatch);
}

export default connect(null,mapDispatchToProps)(CollegeListComponent);

CollegeListComponent.propTypes = {
    collegeName: PropTypes.any,
    collegelistWidget: PropTypes.any,
    config: PropTypes.any,
    listingId: PropTypes.any,
    storeInstituteDataForPreFilled: PropTypes.any
}