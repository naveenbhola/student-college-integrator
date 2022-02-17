import PropTypes from 'prop-types'
import React from 'react';
import Loadable from 'react-loadable';
import './../assets/anaWidget.css';
import './../assets/courseCommon.css';
import {cutStringWithShowMore} from './../../../../utils/stringUtility';
import CampusRepWidget from './CampusRepComponent';
import {getRequest} from '../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {addingDomainToUrl} from './../../../../utils/commonHelper';
import FullPageLayer from './../../../common/components/FullPageLayer';
import {numberFormatter} from '../../../../utils/MathUtils';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import {ILPConstants, ULPConstants} from './../../categoryList/config/categoryConfig';



const QNALayer = Loadable({
    loader: () => import('./../../../common/components/desktop/QNALayer'/* webpackChunkName: 'desktopQNALayer' */),
    loading() {return null}
});

class AnAComponent extends React.Component
{
  constructor(props)
  {
    super(props);
      this.state = {
          campusRepData:{},
          modalIsOpen: false
     }
      if(this.props.page == 'institute'){
        this.config = ILPConstants();
        }else{
            this.config = ULPConstants();
      }

    this.openAskModal = this.openAskModal.bind(this);
    this.closeAskModal = this.closeAskModal.bind(this);
    this.fetchCampusAmbassadorData = false;
  }

  componentDidMount()
  {
      window.addEventListener("scroll", this.onScroll);
  }

    onScroll = () => {
        if(!this.fetchCampusAmbassadorData) {
            this.fetchCampusAmbassadorData = true;
            const {courseId,instituteId,page} = this.props;
            if(page == "coursePage" && courseId != '' && typeof courseId != 'undefined' && typeof instituteId != 'undefined' && instituteId != '') {
                this.fetchCampusAmbassadorWidget(courseId,instituteId);
            } else if( typeof instituteId != 'undefined' && instituteId != ''){
                this.fetchCampusAmbassadorWidget(courseId,instituteId);
            }
        }
    }  
    
    componentWillUnmount(){
        window.removeEventListener('scroll', this.onScroll);
    }


    fetchCampusAmbassadorWidget(courseId = null,instituteId){

        let url = null;
        if(this.props.page =="coursePage"){
            url = APIConfig.GET_CA_WIDGET;
            getRequest(url+'?listingId='+courseId+'&listingType=course&instituteId='+instituteId)
                .then((response) =>{
                    this.setState({
                        campusRepData: response.data.data
                    });
                })
        }
        else {
            url = APIConfig.GET_CA_WIDGET_FOR_INSTITUTE;
            getRequest(url+'?listingId='+instituteId+'&listingType=institute')
                .then((response) =>{
                    this.setState({
                        campusRepData: response.data.data
                    });
                })
        }
    }

    componentWillReceiveProps(nextProps)
    {
        let newCourseId = nextProps.courseId;
        let prevCourseId = this.props.courseId;
        if(this.props.fromWhere=='admissionPage'){
            return null;
        }
        if(this.props.page == "coursePage" && newCourseId != prevCourseId && typeof newCourseId != 'undefined' && typeof prevCourseId != 'undefined' && prevCourseId != '' && newCourseId != '' && nextProps.instituteId != '' && typeof nextProps.instituteId != 'undefined')
        {
            this.fetchCampusAmbassadorWidget(newCourseId,nextProps.instituteId);
        }
        else if(nextProps.instituteId != '' && typeof nextProps.instituteId != 'undefined'){
            this.fetchCampusAmbassadorWidget(newCourseId,nextProps.instituteId);
        }
    }


    generateAnAWidgetHtml(){
        var questiondata = this.props.anaWidget.questionsDetail;
        var questionOrder = this.props.anaWidget.questionOrder;
        var anaHtml = [];
        for(var index in questionOrder) {
            if(questiondata[questionOrder[index]]['questionId']==null){
                continue;
            }
            anaHtml.push(
            	<div className="qstn-div" key={'ques_'+index}>
                    <div className="qstn-det">
                        <strong>Q.</strong>
                        <a href={questiondata[questionOrder[index]]['url']} onClick={this.trackEvent.bind(this,'QDP','Click')} >{questiondata[questionOrder[index]]['title']}</a>
                    </div>
                    <div className="ana-det">
                        <strong>A.</strong>
                        <input type="checkbox" className="read-more-state hide" id={"answerTxt_"+index}/>
                        <p className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(questiondata[questionOrder[index]]['answerText'],300,'answerTxt_'+index,'more',false,false,true)}}></p>
                        <p className="ansr-usr">
                            Answered by
                            <strong>{questiondata[questionOrder[index]]['answerOwnerName']+' '+questiondata[questionOrder[index]]['answerOwnerLevel']}</strong>
                        </p>
                        {questiondata[questionOrder[index]]['msgCount']>1 && <a href={questiondata[questionOrder[index]]['url']} onClick={this.trackEvent.bind(this,'Read_All_Answer','Click')} className="nrml-link rd-link">{'Read All Answers ('+questiondata[questionOrder[index]]['msgCount']+')'}</a>}
                    </div>
                </div>);
        }

        return anaHtml;

    }

    closeAskModal() {
    	this.setState({modalIsOpen: false});
    	document.getElementById('fullLayer-container').classList.remove("show");
    	document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
  	}

    trackEvent(actionLabel,label){
        const {page,fromWhere} = this.props;
        let category = 'ULP';
        if(fromWhere == "admissionPage"){
            category = 'AdmissionPage_PWA';
        }
        else if(fromWhere == "admissionPageDesktop"){
            category = 'AdmissionPage_PWA_Desk';
        }
        else if(fromWhere == "placementPage"){
            category = 'PlacementPage_PWA';
        }
        else if(fromWhere == "placementPageDesktop"){
            category = 'PlacementPage_PWA_Desk';
        }
        else if(page == "coursepage"){
            category = 'CLP';
        }
        else if(page == "institute"){
            category = 'ILP';
        }else if(page === 'examPage'){
            category = 'ExamPage';
        }
        if(this.props.gaTrackingCategory){
            category = this.props.gaTrackingCategory;
        }
        Analytics.event({category : category, action : actionLabel, label : label});
    }

    openAskModal() {
        QNALayer.preload().then(()=>{
            this.setState({modalIsOpen: true});
        });
        document.getElementById('fullLayer-container').classList.add("show");
        document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
    }

    afterOpenAskModal() {
    }

    closeAskModal() {
        this.setState({modalIsOpen: false});
        document.getElementById('fullLayer-container').classList.remove("show");
        document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
    }

    openANALayer(){
        if(this.props.deviceType == 'desktop'){
            this.trackEvent('AskQuestion','Response');
            this.openAskModal();
            return;
        }
        const {config,page,location} = this.props;
        this.trackEvent('AskQuestion','Response');
        var cityUrl = null;
        var localityUrl = null;
        if(typeof location != "undefined")
        {
            if(location.city_id != null && location.city_id != 0){
                cityUrl = '&city='+location.city_id;
            }
            if(location.locality_id != null && location.locality_id != 0){
                localityUrl = '&locality='+location.locality_id;
            }
        }
        var AskQuestionUrl = null;
        if(page == "coursePage"){
            AskQuestionUrl = '/mAnA5/AnAMobile/getQuestionPostingAmpPage?courseId='+this.props.courseId+'&listingId='+this.props.instituteId+'&fromwhere=coursepage'+cityUrl+localityUrl;
        }
        else if(page == "courseHomePage")
        {
            AskQuestionUrl = '/mAnA5/AnAMobile/getQuestionPostingAmpPage';
        }else if(page =="examPage"){
            AskQuestionUrl = '/mAnA5/AnAMobile/getQuestionPostingAmpPage?'+'examId='+this.props.examId+'&groupId='+this.props.groupId+'&examPageType='+this.props.activeSection+'&fromwhere=examPagePWA';
        }
        else{
            AskQuestionUrl = '/mAnA5/AnAMobile/getQuestionPostingAmpPage?'+'&listingId='+this.props.instituteId+'&fromwhere=institute'+cityUrl+localityUrl;
        }
        window.location.href = config.SHIKSHA_HOME+AskQuestionUrl;
    }

    getQnaHtml() {
        return (<QNALayer
            isLayerOpen={this.state.modalIsOpen}
            closeModal={this.closeAskModal}
            postingForPage={this.props.page}
            postingType="layer"
            qPostingTitle = 'Need guidance on career and education? Ask our experts'
            postingForType = 'question'
            /*courseIdQP = "1234"*/
            /*instituteId = "5678"*/
            responseAction = 'ques_post'
            /*qtrackingPageKeyId = "234"
            dtrackingPageKeyId = "567"*/
            quesDiscKeyId = {this.props.trackingKeyId}
            entityId = "0"
            tagEntityType = {this.props.groupId != null ? 'Exam' : ''}
            instituteCourses = {[]}
            courseViewFlag = {false}
            examResponseId={this.props.groupId != null ? this.props.groupId : 0}
        />);
    }

    render(){
        var quesShown = (this.props.anaWidget != null && this.props.anaWidget.questionsDetail!=null && typeof this.props.anaWidget.questionsDetail != 'undefined') ? Object.keys(this.props.anaWidget.questionsDetail).length : 0;
        var totalQues = this.props.anaWidget != null ? this.props.anaWidget.totalNumber : 0;
        var allQuestionURL = null;
        if(this.props.anaWidget){
            allQuestionURL = addingDomainToUrl(this.props.anaWidget.allQuestionURL,this.props.config.SHIKSHA_HOME);
        }
        let heading  = this.props.heading?this.props.heading:'';
        let anaPdfUrl = this.props.pdfUrl;
        
        return(
            <section className='anaBnr listingTuple' id="ana">
                <div className="_container">

                    {totalQues>0?<h2 className='tbSec2'>Ask & Answer <span>(Showing {quesShown} of {numberFormatter(totalQues)} Q&A)</span></h2>:<h2 className='tbSec2'>Ask & Answer</h2>}
                    {totalQues>0 &&
                    <div className='_subcontainer'>
                        <div className='ana-div'>
                            { this.generateAnAWidgetHtml()}
                        </div>
                        <div className='button-container ana--wrap'>
                           { anaPdfUrl && <DownloadEBrochure actionType='Download_Top_Questions' className='button button--crimson' buttonText='Download Top Q&A' ctaName="downloadTopQuestions" heading='Downloading Top Questions.' uniqueId={'downloadTopQuestions_'+this.props.listingId} listingId={this.props.listingId} listingName={this.props.instituteName} recoEbTrackid={this.config.DownloadTopQuestionsCTA} clickHandler={this.trackEvent.bind(this,'NEWCTA','click_download_top_questions_cta')}  isCallReco={false}  page = {this.props.page} pdfUrl={anaPdfUrl} /> }
                            {totalQues>2 && <a className='button button--secondary rippleefect dark arrow' href={allQuestionURL} onClick={() => this.trackEvent('AllQuestions','Click')}>View All Questions </a>}
                        </div>
                    </div>
                    }
                    <div className='ask-qryDv'>
                        {this.state.campusRepData != null && <CampusRepWidget campusRepData={this.state.campusRepData} heading={heading} />}
                        <textarea className='qst-txtar' onClick={this.openANALayer.bind(this)} placeholder="Type your question here..."></textarea>

                        <div className='button-container'>
                            <a className='' onClick={this.openANALayer.bind(this)}><button type="button" name="button" className="button button--orange rippleefect dark">Ask Question</button></a>
                        </div>
                    </div>
                </div>
                {
                    this.props.deviceType === 'desktop' && this.state.modalIsOpen ?
                        <FullPageLayer additionalCSS='ovrflowVsbl' data={this.getQnaHtml()} heading={'Ask your Question'}  subHeading={true} onClose={this.closeAskModal.bind(this)} isOpen={this.state.modalIsOpen} />
                        : null
                }
            </section>

        )
    }
}
AnAComponent.defaultProps = {
    isExamResponse : false,
    trackingKeyId : 904,
    activeSection : 'homepage'
};
export default AnAComponent;

AnAComponent.propTypes = {
    activeSection: PropTypes.string,
    anaWidget: PropTypes.any,
    config: PropTypes.any,
    courseId: PropTypes.any,
    deviceType: PropTypes.any,
    examId: PropTypes.any,
    fromWhere: PropTypes.any,
    groupId: PropTypes.any,
    heading: PropTypes.any,
    instituteId: PropTypes.any,
    isExamResponse: PropTypes.bool,
    location: PropTypes.any,
    page: PropTypes.any,
    trackingKeyId: PropTypes.number
}