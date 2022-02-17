import PropTypes from 'prop-types'
import React, {Component} from 'react';
import {storeCourseDataForPreFilled} from "../../listing/course/actions/CourseDetailAction";
import {storeInstituteDataForPreFilled} from "../../listing/institute/actions/InstituteDetailAction";
import {bindActionCreators} from "redux";
import {Link} from "react-router-dom";
import {connect} from 'react-redux';
import Analytics from "../../reusable/utils/AnalyticsTracking";
import {addingDomainToUrl} from "../../../utils/commonHelper";
import Lazyload from "../../reusable/components/Lazyload";
import {removeDomainFromUrl} from "../../../utils/urlUtility";
import Anchor from "../../reusable/components/Anchor";
import Shortlist from "../../common/components/Shortlist";
import AggregateReview from './../../listing/course/components/AggregateReviewWidget';
import {getRupeesDisplayableAmount} from "../../listing/course/utils/listingCommonUtil";
import DownloadEBrochure from "../../common/components/DownloadEBrochure";
import {getRequest} from "../../../utils/ApiCalls";
import APIConfig from "../../../../config/apiConfig";
import config from './../../../../config/config';
import ApplyNow from "../../common/components/ApplyNow";
import '../assets/CategoryTupleNew.css';

class CategoryTupleNew extends Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            listings : null, remainingCourseIds: null, showMoreCoursesLoader : false
        };
        this.aggregateRatingConfig = this.props.categoryData.aggregateRatingConfig;
    }

    prepareExamList(examList, examUrls) {
        let elist = [];
        let examSet = new Set();
        for (let i in examList) {
            let e = examList[i];
            if (examSet.has(e.exam_name) || e.exam_name == null)
                continue;
            examSet.add(e.exam_name);
            if (typeof examUrls[e.exam_id] != 'undefined') {
                elist.push(<Link key={'examLink' + i}
                                 onClick={this.trackEvent.bind(this, 'Popular_Colleges', 'Exam_click')}
                                 to={examUrls[e.exam_id]}>{' ' + e.exam_name}</Link>);
                elist.push(',');
            } else {
                elist.push(<span className='exm-name' key={'e' + i}>{' ' + e.exam_name}</span>);
                elist.push(',');
            }
        }
        elist.pop();
        if (examSet.size === 0) {
            return null;
        }
        return (
            <span className='avail_exams allexam'>
                {elist}
            </span>
        );
    }

    handleClickOnCourse(instituteData, courseData) {
        this.trackEvent('ChooseCLP', 'Click');
        if (typeof courseData == 'object' && typeof instituteData == 'object') {
            courseData.instituteName = instituteData.name;
            courseData.instituteUrl = instituteData.url;
            this.props.storeCourseDataForPreFilled(courseData);
        }
    }

    downloadEBRequest = (params, e) => {
        let thisObj = e.currentTarget;
        if (typeof window != 'undefined' && typeof ajaxDownloadEBrochure != 'undefined') {
            this.trackEvent('DownloadBrochure', 'Response');
            window.ajaxDownloadEBrochure(thisObj, params.courseId, "course", params.courseName, this.props.pageType, this.props.ebTrackid,
                this.props.recoEbTrackid, 0, 0, this.props.recoShrtTrackid);
        }
    };

    addToShortlist = (params, e) => {
        if (typeof window != 'undefined' && typeof addShortlistFromSearch != 'undefined') {
            this.trackEvent('Shortlist', 'Response');
            let pageType = this.props.pageType;
            if (this.props.pageType === 'searchPage') {
                pageType = 'SearchV2';
            }
            let otherParams = {recoEbTrackid: this.props.recoEbTrackid, recoShrtTrackid: this.props.recoShrtTrackid};
            window.addShortlistFromSearch(params.courseId, this.props.srtTrackId, pageType, otherParams);
        }
    };

    handleClickOnInstitute(instituteData, fromWhere = '', trackCategory = null, trackEvent = null) {
        if (trackEvent && trackCategory ) {
            this.trackEvent(trackCategory, trackEvent);
        } else {
            if (this.props.index)
                this.trackEvent('ILP_' + (parseInt(this.props.index) + 1), 'tuple_click');
            else
                this.trackEvent('ILP', 'tuple_click');
        }
        if (typeof instituteData != 'undefined' && typeof instituteData == 'object') {
            let data = [];
            data.instituteName = instituteData.name;
            data.ownership = instituteData.ownership;
            data.autonomous = instituteData.autonomous;
            data.nationalImportance = instituteData.nationalImportance;
            data.univeristyTypeWithSpecification = instituteData.universityTypeWithSpecification;
            data.ugcApproved = instituteData.ugcApproved;
            data.naacAccreditation = instituteData.naacAccreditation;
            data.establishYear = instituteData.estbYear;
            data.aiuMember = instituteData.aiuMember;
            data.headerImage = instituteData.instituteThumbUrl;
            if (instituteData && instituteData.courseTupleData && instituteData.courseTupleData.displayLocationString) {
                data.location = instituteData.courseTupleData.displayLocationString;
            }
            if (fromWhere === 'AdmissionLink') {
                data.extraHeading = 'Admission 2019 - Cutoffs, Eligibility & Dates';
                data.pageName = 'Admission';
                data.fromWhere = "admissionPage";
            } else if(fromWhere === 'PlacementLink'){
                data.pageName = 'Placement';
                data.fromWhere = 'placementPage';
                data.showFullSectionLoader = true;
                data.extraHeading = 'Placement - Highest & Average Salary Package';
            }
            this.props.storeInstituteDataForPreFilled(data);
        }
    }
    courseStatusData(courseData) {
        const courseName = courseData.courseStatusData.split(' by ');
        const ele = <Link to={courseData.courseStatusUrl} onClick={this.trackEvent.bind(this, 'Offered_By', 'click')}> {courseName[1]} </Link>;

        return (
            <ul>
                <li className="offered-by">{courseName[0]} by {ele}</li>
            </ul>
        )
    }


    getCourseTuple(tupleData, tupleType, instituteUrl, instituteData) {
        let ratingData = null;
        if (typeof tupleData.aggregateReviewData != 'undefined' && tupleData.aggregateReviewData &&
            typeof this.aggregateRatingConfig != 'undefined' && this.aggregateRatingConfig) {
            ratingData = {...this.aggregateRatingConfig, 'aggregateReviewData': tupleData.aggregateReviewData};
        }
        let moreClass = 'none';
        if (typeof tupleType != 'undefined' && tupleType === 'more') {
            moreClass = "brdr-Top";
        }
        if (!tupleData.courseUrl)
            return;
        let examsCount = 0;
        let examList = null;
        if (tupleData.showExams && tupleData.eligibility.length > 0 &&
            typeof this.props.categoryData != 'undefined' && this.props.categoryData.examsUrl) {
            examsCount = this.getExamsUniqueCount(tupleData.eligibility);
        }
        if(examsCount > 0){
            examList = this.prepareExamList(tupleData.eligibility, this.props.categoryData.examsUrl);
        }
        let prevDataPresent;
        //TODO what is coursestatusdata and url
        return (
            <div id={tupleData.courseId} key={tupleData.courseId} className={moreClass}>
                <div className='flexi-div'>
                    <div className="ctp-SrpDiv">
                        {this.isNonPWAPage() ?
                            <a href={addingDomainToUrl(tupleData.courseUrl, this.props.config.SHIKSHA_HOME)} className="fnt-w6"
                               onClick={this.handleClickOnCourse.bind(this, instituteData, tupleData)}>{tupleData.name.length > 55 ?
                                tupleData.name.substring(0, 52) + '...' : tupleData.name}</a> :
                            <Link to={tupleData.courseUrl} className="fnt-w6"
                                  onClick={this.handleClickOnCourse.bind(this, instituteData, tupleData)}>{tupleData.name.length > 55 ?
                                tupleData.name.substring(0, 52) + '...' : tupleData.name}</Link>}
                        <div className="ctp-detail">
                            <ul>
                                {ratingData &&
                                <li>
                                    <AggregateReview uniqueKey={'Course_' + tupleData.courseId} isPaid={false}
                                                     reviewsCount={tupleData.reviewCount} reviewUrl={tupleData.allReviewsUrl}
                                                     aggregateReviewData={ratingData} showAllreviewUrl={true}
                                                     config={config()} showReviewBracket={true}
                                                     gaTrackingCategory={this.props.gaTrackingCategory}/>
                                    {prevDataPresent = true}
                                </li>
                                }
                                {prevDataPresent && tupleData.fees ? <li><span> | </span>{prevDataPresent = false}</li> : ''}
                                {tupleData.fees ? <li>Total Fees: &#8377; {getRupeesDisplayableAmount(tupleData.fees)}{prevDataPresent = true}</li> : ''}
                                {prevDataPresent && tupleData.durationValue > 0 ?
                                    <li><span> | </span>{prevDataPresent = false}</li> : ''}
                                {tupleData.durationValue > 0 ? <li>{tupleData.durationValue} {tupleData.durationUnit}{prevDataPresent = true}</li> : ''}
                                {prevDataPresent && tupleData.courseExtraInfo.extraInfo.educationType ?
                                    <li><span> | </span></li> : ''}
                                {tupleData.courseExtraInfo.extraInfo.educationType  ?
                                    <li>{tupleData.courseExtraInfo.extraInfo.educationType}</li> : ''}
                            </ul>
                        </div>

                        {examsCount > 0 && examList ?
                            <div className="ctp-detail">
                                <label>Exams:
                                    {examList}
                                </label>
                            </div> : '' }
                        {(tupleData.courseStatusData && tupleData.courseStatusUrl) ?
                            (<div className="ctp-detail QRP-list">
                                    {this.courseStatusData(tupleData)}
                            </div>) : null
                        }
                    </div>
                </div>
                <div className="ctp-SrpBtnDiv">
                    {tupleData.lda && this.props.showUSPLda ?
                        <div><span className="application-msg">{tupleData.lda}</span></div> : ''}
                    {this.getShortListButtonHTML(tupleData)}
                    {this.props.showOAF && tupleData.onlineFormInfo  ? this.showOnlineApply(tupleData, instituteData) :
                        this.getDownloadBrochureHtml(tupleData)}
                </div>
            </div>

        );
    }
    applyOnline = redirectUrl => () => {
        redirectUrl += "?tracking_keyid=" + this.props.applyNowTrackId;
        window.location.href = redirectUrl;
    };

    showOnlineApply(info, instituteData) {
        //displayDate = data.onlineFormData['of_last_date'];
        if (this.props.deviceType === "desktop") {
            return (
                
                        <button type='button' id="applynow" className='oafcta-btn' onClick={this.applyOnline(info.onlineFormInfo.of_external_url)}>
                            <i className="d-arrow"/>Start Application
                        </button>
                    );
        }
        return (<ApplyNow courseId={info.courseId} instituteName={instituteData.name} isInternal={info.onlineFormInfo.external}
                          showLastDate={false} ctaName='Start Application' trackid={this.props.applyNowTrackId}/>);
    }

    getExamsUniqueCount(examList) {
        let examSet = new Set();
        for (let i in examList) {
            examSet.add(examList[i].exam_name);
        }
        return examSet.size;
    }

    getCourseData(info, loadeMoreCoursesData) {
        this.trackEvent('more_courses_link', 'click');
        const self = this;
        const numberOfCoursesToLoad = 5;
        let courseIdArr;
        if (this.state.remainingCourseIds) {
            courseIdArr = this.state.remainingCourseIds;
        } else {
            courseIdArr = loadeMoreCoursesData;
        }
        const cloneCourseIdArr = courseIdArr.slice();
        const courseIdsToLoadArr = cloneCourseIdArr.splice(0, numberOfCoursesToLoad);
        let courseIdStr = '';
        for (let i in courseIdsToLoadArr) {
            courseIdStr += '&courseIds=' + courseIdsToLoadArr[i];
        }
        const url = APIConfig.GET_CTP_LOADMORE;
        const params = 'instituteId=' + info.instituteId + courseIdStr;
        this.setState({showMoreCoursesLoader : true});
        getRequest(url + '?' + params).then((response) => {
            let responseData = response.data.data;
            let courseData = responseData.courseTupleList;
            courseData.map(function (courseInfo) {
                let moreCoursesHtml = self.state.listings;
                if(!moreCoursesHtml){
                    moreCoursesHtml = [];
                }
                moreCoursesHtml = moreCoursesHtml.concat(self.getCourseTuple(courseInfo, 'more', info.url, info));
                self.setState({showMoreCoursesLoader : false, listings: moreCoursesHtml, remainingCourseIds: cloneCourseIdArr});
            });
        });
    }

    isNonPWAPage() {
        return (this.props.isPdfCall || this.props.deviceType === 'desktop');
    }

    isPWAPage() {
        return this.props.isPdfCall;
    }

    getShortListStarHTML(tupleData) {
        if (this.props.deviceType === 'mobile')
            return (<Shortlist listingId={tupleData.courseTupleData.courseId}
                               trackid={(typeof (this.props.srtTrackId) != 'undefined' && this.props.srtTrackId) ? this.props.srtTrackId : ''}
                               recoShrtTrackid={(typeof (this.props.recoShrtTrackid) != 'undefined' && this.props.recoShrtTrackid) ?
                                   this.props.recoShrtTrackid : ''} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer"
                               actionType="NM_category_shortlist" pageType="NM_Category" sessionId="" visitorSessionid=""
                               isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this, 'Shortlist', 'Response')}/>);
        return(<i id = {'shrt_'+tupleData.courseTupleData.courseId}
        onClick = {this.addToShortlist.bind(this, {'courseId': tupleData.courseTupleData.courseId})}
        className = {"ctp-shrtlst rippleefect tupleShortlistButton shrt_"+tupleData.courseTupleData.courseId} data-type = "shortlistStar"> </i>);
    }

    getShortListButtonHTML(tupleData) {
        if (this.props.deviceType === 'mobile')
            return (<Shortlist listingId={tupleData.courseId} trackid={this.props.srtTrackId} recoShrtTrackid={this.props.recoShrtTrackid}
                               recoEbTrackid={this.props.recoEbTrackid} recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer"
                               actionType="NM_category_shortlist" pageType="NM_Category" sessionId="" visitorSessionid=""
                               isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this, 'Shortlist', 'Response')}
                               isButton={true} showRecoLayer={this.props.showRecoLayer} />);
        return(<button type="button" name="button" instid={tupleData.instituteId} product="Category" track="on" courseid={tupleData.courseId}
                       className = {'ctp-btn ctpComp-btn button button--secondary rippleefect tupleShortlistButton shrt' + tupleData.courseId}
                       onClick={this.addToShortlist.bind(this, {'courseId': tupleData.courseId})} id={'shrt' + tupleData.courseId}>
                <span className="tup-view-details">Shortlist</span>
            </button>)
    }

    getDownloadBrochureHtml(tupleData){
        if(this.props.deviceType === 'mobile')
            return(<DownloadEBrochure listingId={tupleData.courseId} listingName={tupleData.name} trackid={(typeof (this.props.ebTrackid) != 'undefined' &&
                                   this.props.ebTrackid) ? this.props.ebTrackid : this.config.downloadBrochureTrackingId}
                               recoEbTrackid={(typeof (this.props.recoEbTrackid) != 'undefined' && this.props.recoEbTrackid) ? this.props.recoEbTrackid : 273}
                               recoShrtTrackid={(typeof (this.props.recoShrtTrackid) != 'undefined' && this.props.recoShrtTrackid) ?
                                   this.props.recoShrtTrackid : 1089} isCallReco={this.props.isCallReco}
                               clickHandler={this.trackEvent.bind(this, 'DownloadBrochure', 'Response')} showRecoLayer={this.props.showRecoLayer} />);

        return(<button onClick={this.downloadEBRequest.bind(this, {'courseId': tupleData.courseId, 'courseName': tupleData.name})}
                       cta-type="download_brochure" courseid={tupleData.courseId}
                       className={"ctp-btn ctpBro-btn button button--orange rippleefect tupleBrochureButton brchr_" + tupleData.courseId}>
                        <span id={'ebTxt' + tupleData.courseId} instid={tupleData.instituteId} product="Category" track="on"
                              courseid={tupleData.courseId}>Apply Now
                        </span>
            </button>);
    }
    getTupleHTML() {
        const {loadMoreCourses} = this.props;
        const loadeMoreCoursesData = loadMoreCourses ? loadMoreCourses.slice(1) : [];
        const tupleData = this.props.categoryTuple;

        if (!tupleData["courseTupleData"] || !tupleData["courseTupleData"]["courseUrl"])
            return null;
        const moreCourseLength = this.state.remainingCourseIds  ?
            this.state.remainingCourseIds.length : (loadeMoreCoursesData ? loadeMoreCoursesData.length : 0);

        return <div className="ctpSrp-tuple">
            <div className="ctp-SrpLst">
                <div className="ctp-SrpDiv">
                    <div className="ctpSrp-Lft">
                        {this.isNonPWAPage() ?
                            <a href={addingDomainToUrl(tupleData.url, this.props.config.SHIKSHA_HOME)}
                               onClick={this.trackEvent.bind(this, 'ChooseILP', 'click')}>
                                {this.props.isImageLazyLoad ?
                                    <Lazyload offset={100} once>
                                        <img alt={tupleData.name} src={tupleData.instituteThumbUrl}/>
                                    </Lazyload> :
                                    <img alt={tupleData.name} src={tupleData.instituteThumbUrl}/>}
                            </a> :
                            <Link to={tupleData.url} onClick={this.handleClickOnInstitute.bind(this, tupleData)}>
                                {this.props.isImageLazyLoad ?
                                    <Lazyload offset={100} once>
                                        <img alt={tupleData.name} src={tupleData.instituteThumbUrl}/>
                                    </Lazyload> :
                                    <img alt={tupleData.name} src={tupleData.instituteThumbUrl}/>}
                            </Link>}
                    </div>
                    <div className="ctpSrp-Rgt">
                        <p className="ctpIns-tl">
                            {this.isNonPWAPage() ?
                                <a href={addingDomainToUrl(tupleData.url, this.props.config.SHIKSHA_HOME)}
                                   onClick={this.handleClickOnInstitute.bind(this, tupleData)}>{tupleData.name.length > 55 ?
                                    tupleData.name.substring(0, 52) + '...' : tupleData.name}</a> :
                                <Link to={removeDomainFromUrl(tupleData.url)}
                                      onClick={this.handleClickOnInstitute.bind(this, tupleData)}>{tupleData.name.length > 55 ?
                                    tupleData.name.substring(0, 52) + '...' : tupleData.name}</Link>}
                        </p>
                        <p className="ctp-cty">{tupleData.courseTupleData.displayLocationString}</p>
                        {tupleData.multiLocationInstituteUrl ?
                            <a href={tupleData.multiLocationInstituteUrl}>+more branches</a> : ''}
                        {this.getUilpLinks(tupleData, this.isPWAPage())}
                        {this.getShortListStarHTML(tupleData)}
                    </div>
                    {tupleData.courseTupleData.usp && this.props.showUSPLda ?
                        <div className="highligt-msg"><i className="pwa-header-icon bulb"/>
                            <span className="highlight-msg-txt">{tupleData.courseTupleData.usp}</span>
                        </div> : ''}
                </div>
                <div id="courseTuple">
                    {this.getCourseTuple(tupleData.courseTupleData, '', tupleData.url, tupleData)}
                    {this.state.listings}
                    {this.state.showMoreCoursesLoader ? this.getShowMoreCourseLoader() : '' }
                </div>
            </div>
            {moreCourseLength > 0 ?
                <div className="ctp-shwMr">
                    {moreCourseLength > 1 ?
                        <a onClick={this.getCourseData.bind(this, tupleData, loadeMoreCoursesData)}>+{moreCourseLength} more
                            courses</a> :
                        <a onClick={this.getCourseData.bind(this, tupleData, loadeMoreCoursesData)}>+{moreCourseLength} more
                            course</a>}
                </div> : ''}
        </div>
    }
    getShowMoreCourseLoader(){
        return(<div>
            <div className="flexi-div">
                <div className="ctp-SrpDiv"><a className="fnt-w6"><span className="loader-line shimmer"/></a>
                    <div className="ctp-detail">
                        <ul className="full-shimer">
                            <li><span className="loader-line shimmer"/></li>
                            <li><span className="loader-line shimmer"/></li>
                            <li><span className="loader-line shimmer"/></li>
                        </ul>
                    </div>
                    <div className="ctp-detail">
                        <div className="ctp-Det-info"><p><span><span className="loader-line shimmer"/></span></p>
                            <span className="link"> <span className="loader-line shimmer"/></span></div>
                    </div>
                </div>
            </div>
        </div>);
    }
    getUilpLinks(instituteData, anchor) {
        const {admissionUrl} = instituteData;
        const {placementUrl} = instituteData;
        if (!admissionUrl && !placementUrl)
            return null;
        if (anchor)
            return (<p className='ctp_ctgry'>
                {admissionUrl && <a onClick={this.trackEvent.bind(this, 'Popular_Colleges', 'Admission_details_Click')}
                                    href={addingDomainToUrl(admissionUrl, this.props.config.SHIKSHA_HOME)} className="vw-adLnk">Admissions 2019</a>}
                {placementUrl && <a onClick={this.trackEvent.bind(this, 'Placement_Page', 'click')}
                   href={addingDomainToUrl(placementUrl, this.props.config.SHIKSHA_HOME)} className="vw-adLnk">Placements</a> }
                </p>);
        return (<p className='ctp_ctgry'>
            {admissionUrl && <Anchor onClick={this.handleClickOnInstitute.bind(this, instituteData, 'AdmissionLink', 'Popular_Colleges',
                'Admission_details_Click')} to={admissionUrl} className="vw-adLnk">Admissions 2019</Anchor>}
            {placementUrl && <Anchor to={placementUrl} onClick={this.handleClickOnInstitute.bind(this, instituteData, 'PlacementLink', 'Placement_Page',
                'click')} className="vw-adLnk">Placements</Anchor> }
            </p>);
    }

    trackEvent = (actionLabel, label) => {
        if (!this.props.gaTrackingCategory)
            return;
        if(this.props.gaLabelPrependString && this.props.gaLabelPrependString.length > 0){
            label = "PCW_" + label;
        }
        Analytics.event({category: this.props.gaTrackingCategory, action: actionLabel, label: label});
    };

    render() {
        return this.getTupleHTML();
    }
}

CategoryTupleNew.defaultProps = {
    isImageLazyLoad : true,
    showInTable : true,
    isSrp : false,
    showOAF : false,
    showUSPLda : false,
    recoShrtTrackid : 0,
    recoEbTrackid : 0,
    showRecoLayer : true,
    gaLabelPrependString : ''
};

function mapDispatchToProps(dispatch) {
    return bindActionCreators({storeCourseDataForPreFilled, storeInstituteDataForPreFilled}, dispatch);
}

export default connect(null, mapDispatchToProps)(CategoryTupleNew);

CategoryTupleNew.propTypes = {
    aggregateRatingConfig: PropTypes.object,
    applyNowTrackId: PropTypes.number,
    categoryData: PropTypes.object.isRequired,
    config: PropTypes.object.isRequired,
    deviceType: PropTypes.string.isRequired,
    ebTrackid: PropTypes.number,
    gaTrackingCategory: PropTypes.string.isRequired,
    isCallReco: PropTypes.bool,
    isImageLazyLoad: PropTypes.bool,
    isPdfCall: PropTypes.bool,
    isSrp: PropTypes.bool,
    loadMoreCourses: PropTypes.array,
    pageType: PropTypes.string,
    recoEbTrackid: PropTypes.number,
    recoShrtTrackid: PropTypes.number,
    showInTable: PropTypes.bool,
    showOAF: PropTypes.bool,
    showUSPLda: PropTypes.bool,
    srtTrackId: PropTypes.number,
    storeCourseDataForPreFilled: PropTypes.any,
    storeInstituteDataForPreFilled: PropTypes.any,
    showRecoLayer: PropTypes.bool,
    gaLabelPrependString : PropTypes.string
};

