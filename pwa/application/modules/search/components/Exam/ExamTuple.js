import React, {Component} from "react";
import {Link} from 'react-router-dom';
import {stringTruncate} from '../../../../utils/stringUtility';
import "./../../assets/Exam/ExamTuple.css"
import ExamApplyNow from './../../../common/components/ExamApplyNow';
import ExamResponseDesktop from './../../../common/components/desktop/ResponseDesktop';
import {isGuideDownloaded, updateGuideTrackCookie} from "../../../examPage/utils/examPageHelper";
import PopupLayer from './../../../common/components/popupLayer';
import Analytics from "../../../reusable/utils/AnalyticsTracking";

class ExamTuple extends Component {
    constructor(props){
        super(props);
        this.state = {
            guideDownloadMsg : 'The exam guide has been sent to your email id. The email also includes important information such as Institutes ' +
                'Accepting this Exam, Related Articles, Questions & Answers, Similar Exams & more.'
        };
    }

    childLinksHtml = (linksData) =>{
        let linkshtml = [];
        let i=0;
        if(linksData && Object.keys(linksData).length === 0)
            return;
        for(let key in linksData){
            i++;
            if(!linksData[key] || !linksData[key].url)
                continue;
            linkshtml.push(
                <React.Fragment key={"examlinks"+i}>
                    <Link to={linksData[key].url} className='quick-links' onClick={this.trackEvent.bind(this, linksData[key].name, 'click')}>
                        {linksData[key].name}</Link> { Object.keys(linksData).length == i ? '' : <b> | </b>} </React.Fragment>);
        }
        return linkshtml;
    };

    trackEvent = (actionLabel, label)=>{
        if(!this.props.gaCategory)
            return;
        const categoryType = this.props.gaCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };

    examdatestable = (datesArray) =>{
        let tableHtml = [];
        if(datesArray.length !== 0){
            for(let data in datesArray){
                const startDate = this.convertnumbertoDate(datesArray[data]['start_date']);
                let endDate = null;
                if(datesArray[data]['start_date'] !== datesArray[data]['end_date']){
                    endDate =  this.convertnumbertoDate(datesArray[data]['end_date']);
                }
                tableHtml.push(<tr key={'examdate'+data}>
                    <td className='fix-tdwidth'>
                        <p>
                            { startDate  + (endDate != null ? ' - ' : ' ') }  <span className='blockcell'>{endDate}</span>
                        </p>
                    </td>
                    <td className="fix-textlength">
                        {datesArray[data]['event_name'] ? <p>{stringTruncate(datesArray[data]['event_name'], 52)}</p> : <p></p>}
                    </td>
                </tr>)
            }
        }
        return tableHtml;
    };
    closeGetUpdatesCTASuccessPopup = () => {
        if(typeof userHasLoggedIn == 'undefined'){
            window.location.reload();
        }else{
            this.PopupLayer.close();
        }
    };
    convertnumbertoDate = (fullDate)=>{
        if(!fullDate || typeof fullDate === 'undefined' || fullDate === '')
            return;
        const jsDate = new Date(fullDate);
        const year = jsDate.getFullYear();
        const month = jsDate.getMonth();
        const date = jsDate.getDate();
        let monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        let displayyear = year.toString().substr(-2);
        return date + ' ' + monthNames[month] + ' \n\' ' + displayyear;
    };
    getUpdatesTopCTACallback = (response, data) => {
        if(response['status'] === 'SUCCESS'){
            updateGuideTrackCookie(data.groupId, 'examGuide');
            this.PopupLayer.open();
            updateGuideTrackCookie(data.groupId, 'examGuide');
            isGuideDownloaded(data.groupId, 'examGuide');
        }else{
            this.setState({guideDownloadMsg : 'Some error occurred. Please try after some time.'});
            this.PopupLayer.open();
        }
    };
    render(){
        const {tupleData} = this.props;
        let formDataForDownloadGuide= {
            trackingKeyId : 2461,
            callbackFunction : 'callDownloadGuide', //callback from jQuery version in shiksha/public/js/registrationCallbacks.js
            callbackFunctionParams : {groupId : tupleData.groupId, actionType : 'exam_download_guide'},
            callbackObj : '',
            cta : 'exam_download_guide'
        };
        return (
            <div className="uilp_exam_card">
                <div className="exam_topcol">
                    <div className="exam_date auto_clear">
                        <div className="exam_name_width">
                            <p className="hide-examname">
                                <Link to={tupleData.url} onClick={this.trackEvent.bind(this, 'exam_name', 'click')}
                                      className="exam_title" >{tupleData.name}</Link>
                            </p>
                            {this.props.acceptingExam && this.props.acceptingExam.url &&
                            <Link to={this.props.acceptingExam.url} className="clgs_links">Institutes accepting exam</Link>}
                            <PopupLayer closePopup={this.closeGetUpdatesCTASuccessPopup.bind(this)} onContentClickClose={false}
                                        heading={'Download Guide'} onRef={ref => (this.PopupLayer = ref)} data={this.state.guideDownloadMsg}/>
                        </div>
                        {this.props.isMobile ? <ExamApplyNow ctaId="getUpdatesTop" ctaText="Get Updates" examGroupId={tupleData.groupId}
                                                             cta="examDownloadGuide" actionType="exam_download_guide" trackingKeyId={this.props.trackingKeyId}
                                                             gaCategory = {this.props.gaCategory}/> :
                            <ExamResponseDesktop listingId={tupleData.groupId} listingType='exam' ctaType={'guideDownload'}
                                                 actionType={formDataForDownloadGuide.cta} formData={formDataForDownloadGuide}
                                                 className="button button--orange" ctaText="Get Updates" ctaId="getUpdatesTop"
                                                 ref={myComponent => window.getUpdatesTopCTACallback = this.getUpdatesTopCTACallback.bind(this)}
                                                 gaCategory = {this.props.gaCategory}/>}
                        {/*<button type="button" className="btn-primary btn-pm">Apply Now</button>*/}
                    </div>
                    <div className="exam_impdates">
                        { tupleData.dates && tupleData.dates.length !== 0 ?
                            <table>
                                <tbody>
                                {this.examdatestable(tupleData.dates)}
                                </tbody>
                            </table>
                            : ''
                        }
                    </div>
                    {tupleData.childPageLinks && Object.keys(tupleData.childPageLinks).length !== 0 ? <div className="exams_a">
                        {this.childLinksHtml(tupleData.childPageLinks)}
                    </div>: ''}
                </div>
            </div>
        )
    }
}


export default ExamTuple;