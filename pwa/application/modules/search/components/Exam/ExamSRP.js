import React, {Component} from "react";
import {connect} from "react-redux";
import {bindActionCreators} from "redux";
import {fetchExamSRPData, fetchIntegratedSRPData} from "../../../search/actions/SearchAction";
import "./../../assets/Exam/ExamSrp.css";
import ExamTuple from "./ExamTuple";
import SRPNavbar from "./../SRPNavbar";
import ExamSrpLoader from "./ExamSrpLoader";
import Pagination from "../../../listing/categoryList/components/Pagination";
import PreventScrolling from "../../../reusable/utils/PreventScrolling";
import {getQueryVariable, parseQueryString} from "../../../../utils/commonHelper";
import ErrorMsg from "../../../common/components/ErrorMsg";
import ZeroResultPage from "./../../../listing/categoryList/components/ZrpPage";
import ElasticSearchTracking from "../../../reusable/utils/ElasticSearchTracking";
import {CategoryPage} from "../../../../../routes/loadableRoutes";
import {DesktopCTP} from "../../../../../routes/loadableRoutesDesktop";
import {disableGuideDownload} from "../../../examPage/utils/examPageHelper";

class ExamSRP extends Component {
    constructor(props){
        super(props);
        this.state = {
            isShowLoader : false, tabsData : {}
        };
        this.detectPage();
    }
    componentDidMount(){
        window.scrollTo(0, 0);
        if(!ExamSRP.isServerSideRenderedHTML()) {
            this.initialFetchSRPData(this.props.location);
        }
        else if(!ExamSRP.isErrorPage()) {
            this.trackBeacon();
            if(this.props.location.state && this.props.location.state.tabsData){
                this.setState({tabsData : this.props.location.state.tabsData})
            } else  {
                this.getTabData(this.props.location);
            }
        }
        if(this.props.hocData === "examSrpDesktop"){
            window.addEventListener("mousemove", this.loadSRPDesktop);
        }
        else{
            window.addEventListener("touchstart", this.loadSRPMobile);
        }
        disableGuideDownload("examGuide");
    }
    componentWillUnmount() {
        if(this.props.hocData === "examSrpDesktop"){
            window.removeEventListener("mousemove", this.loadSRPDesktop);
        }
        else{
            window.removeEventListener("touchstart", this.loadSRPMobile);
        }
    }

    componentWillReceiveProps(nextProps) {
        let nextHash = this.getBase64UrlParams(nextProps.location);
        let prevHash = this.getBase64UrlParams(this.props.location);
        if(prevHash !== nextHash) {
            this.detectPage();
            window.scrollTo(0, 0);
            this.initialFetchSRPData(nextProps.location);
        }
    }
    loadSRPDesktop = () => {
      DesktopCTP.preload();
    };
    loadSRPMobile = () => {
        CategoryPage.preload();
    };
    detectPage(){
        switch(this.props.hocData){
            case "examSrpDesktop" :
                this.isMobile = false;
                this.gaTrackingCategory = "EXAM_SRP_MOBILE";
                break;
            case "examSrpMobile" :
                this.isMobile = true;
                this.gaTrackingCategory = "EXAM_SRP_DESKTOP";
                break;
        }
    }

    trackBeacon() {
        const {examData, config} = this.props;
        if(examData) {
            let trackingParams;
            trackingParams = {};
            trackingParams['pageIdentifier'] = 'search';
            trackingParams['pageEntityId'] = 0;
            trackingParams['extraData'] = {} ;
            trackingParams['extraData']['childPageIdentifier'] = 'examSRPPage';
            ElasticSearchTracking(trackingParams,config.BEACON_TRACK_URL);
        }
    }
    initialFetchSRPData(location) {
        const self = this;
        this.setState({isShowLoader : true});
        const paramsObj = this.getUrlParams(location);
        const params =  btoa(JSON.stringify(paramsObj));
        let fetchPromise = this.props.fetchExamSRPData(params);
        fetchPromise.then(function(){
            self.setState({isShowLoader : false});
            if(location.state && location.state.tabsData){
                self.setState({tabsData : location.state.tabsData})
            } else {
                self.getTabData(location);
            }
            self.trackBeacon();
        });
    }
    getTabData(location){
        const searchedKeyword = decodeURIComponent(parseQueryString(location.search)['q']);
        if(this.state.tabsData && this.state.tabsData.searchedKeyword && this.state.tabsData.searchedKeyword === searchedKeyword){
            return;
        }
        fetchIntegratedSRPData(searchedKeyword).then((response) => {
            if(!(response.status === 200 && response && response.data && response.data.data && response.data.data.tabs)){
                return;
            }
            this.setState({'tabsData' : {'searchedKeyword' : searchedKeyword , 'tabsData' : response.data.data.tabs}});
        }). catch(function(thrown) {
            //console.log(thrown);
        });
    }
   
    render(){
        if(this.state.isShowLoader) {

            return this.showLoader();
        }
        const {location, examData} = this.props;

        let nextHashValue = this.getBase64UrlParams(location);
        if(!examData || typeof examData == "undefined" || Object.keys(examData).length === 0) {
            return <ErrorMsg />;
        }
       if(examData.examUrlHash  && nextHashValue && examData.examUrlHash !== nextHashValue) {
            return this.showLoader();
        }

       if((examData && Array.isArray(examData.tupleData) && examData.tupleData.length === 0)) {
            return <ZeroResultPage isDesktop={true} isSrp = {this.isSrp} keyword = {decodeURIComponent(parseQueryString(this.props.location.search)['q'])} config={this.props.config}/>
       }
        const examTuples = examData.tupleData;
        const acceptingExamMapping = examData.acceptingExamMapping;
        const examList = examTuples.map((tuple, i) =>
            <ExamTuple key={"examTuple" + i} acceptingExam={acceptingExamMapping && acceptingExamMapping.hasOwnProperty(tuple.examId) ?
                acceptingExamMapping[tuple.examId] : null} tupleData = {tuple} isMobile={this.props.hocData === "examSrpMobile"}
            gaCategory = {this.props.hocData === "examSrpMobile" ?  'Exam_SRP_Mobile' : 'Exam_SRP_Desktop'} trackingKeyId={2463}/>

        );
        return (
            <div className="examstable-grid">
               <SRPNavbar tabsData = {this.state.tabsData} activeTab={'Exams'} keyword={decodeURIComponent(parseQueryString(this.props.location.search)['q'])}
                          showHeading={this.props.hocData === "examSrpDesktop"} isMobile={this.props.hocData === "examSrpMobile"}
                          gaCategory = {this.props.hocData === "examSrpMobile" ? 'SEARCH_MOBILE' : 'SEARCH_DESKTOP'}/>
                 <div id="examSRPCont" className='pwaexams_container'>
                     {this.props.hocData === "examSrpMobile" ? <div className='examHeading'>{examData.resultCount} Exams for '
                         <strong>{decodeURIComponent(parseQueryString(this.props.location.search)['q'])}'</strong></div> : ''}
                    <div className='listof_exams auto_clear'>{examList}</div>
                    <Pagination categoryData={examData} isSrp = {true} gaTrackingCategory = 'exam_SRP' />
                  </div> 
             </div>  
            );
    }

    getUrlParams(locationParams){
        if(!PreventScrolling.canUseDOM()){
            return "";
        }
        let queryParams;//new URLSearchParams(locationParams.search);
        queryParams = parseQueryString(locationParams.search);
        let paramsObj = {};
        for(let key of Object.keys(queryParams)) {
            let keyArr = key.split(/[[\]]{1,2}/);
            if(keyArr[0] !== '') {
                paramsObj[keyArr[0]] = getQueryVariable(keyArr[0], locationParams.search);//queryParams.getAll(key);
            }
        }

        return paramsObj;
    }

    showLoader(){
        let tabsData = this.state.tabsData;
        if(tabsData && Object.keys(this.state.tabsData).length === 0 && this.props.location && this.props.location.state &&
            this.props.location.state.tabsData && Object.keys(this.props.location.state.tabsData).length > 0){
            tabsData = this.props.location.state.tabsData;
        }
        return (<div className="examstable-grid">
                    <SRPNavbar tabsData = {tabsData} activeTab={'Exams'} showHeading={this.props.hocData === "examSrpDesktop"}
                               keyword={decodeURIComponent(parseQueryString(this.props.location.search)['q'])} isMobile={this.props.hocData === "examSrpMobile"} gaCategory = {this.props.hocData === "examSrpMobile" ? 'SEARCH_MOBILE' : 'SEARCH_DESKTOP'}/>
                    <ExamSrpLoader />
                </div> ) ;
    }

    getBase64UrlParams(locationParams)
    {
        if(!PreventScrolling.canUseDOM()){
            return "";
        }
        const paramsObj = this.getUrlParams(locationParams);
        return btoa(JSON.stringify(paramsObj));
    }

    static isErrorPage() {
        let html404 = document.getElementById('notFound-Page');
        return (html404 && html404.innerHTML);
    }
    static isServerSideRenderedHTML() {
        let htmlNode = document.getElementById('examSRPCont');
        return ((htmlNode && htmlNode.innerHTML) || ExamSRP.isErrorPage());
    }
}

function mapStoreToProps(store)
{
    return {
        examData : store.examData,
        config : store.config
    }
}

function mapDispatchToProps(dispatch){
    return bindActionCreators({fetchExamSRPData }, dispatch);
}
export default connect(mapStoreToProps, mapDispatchToProps)(ExamSRP);
