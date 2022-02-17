import PropTypes from 'prop-types'
import React, {Component} from 'react';
import CategoryTupleNew from "./CategoryTupleNew";
import PCW from "./PCW";
import {DFPBannerTempalte} from "../../reusable/components/DFPBannerAd";
import Loadable from "react-loadable";
import CategoryTableComponent from "../../listing/categoryList/components/CategoryTableComponent";
import Pagination from "../../listing/categoryList/components/Pagination";
import Analytics from "../../reusable/utils/AnalyticsTracking";
import CollegeShortlistWidget from "./CollegeShortlistWidget";
import Feedback from "../../common/components/feedback/Feedback";
import SortFiltersTabs from './SortFiltersTabs';


const OCF = Loadable({
    loader: () => import('./OCF'/* webpackChunkName: 'OCF' */),
    loading() {return null},
});

const FullPageLayer = Loadable({
    loader: () => import('../../common/components/FullPageLayer'/* webpackChunkName: 'FullpageLayer' */),
    loading() {return null},
});

class CategoryPageContent extends Component {
    constructor(props){
        super(props);
        this.state = {
            fullLayer : false, loadFullPageLayer : false, OcfDownloaded : false
        };
        this.PCW_COUNT = 2;
        this.TUPLES_BEFORE_PCW = 4;
        this.scrolled = false;
    }
    componentDidMount() {
        window.addEventListener("scroll", this.onScroll);
    }
    componentWillUnmount() {
        window.removeEventListener('scroll', this.onScroll);
    }
    onScroll = () => {
        if(this.props.showOCF && !this.state.OcfDownloaded){
            OCF.preload().then(()=> {
                this.setState({OcfDownloaded: true});
            });
        }
        if(!this.scrolled){
            FullPageLayer.preload().then(() => {
                this.setState({loadFullPageLayer : true});
                this.scrolled = true;
            });
        }
    };
    openTableComponentLayer = () => {
        this.trackEvent('Data in table', 'click');
        if(!this.state.loadFullPageLayer) {
            FullPageLayer.preload().then(() => {
                this.setState({loadFullPageLayer: true});
                this.setState({fullLayer: true});
            });
        }
        else
            this.setState({fullLayer: true});
        if(document.getElementById('ctp_pwa') == null){
            return;
        }
        document.getElementById('fullLayer-container').classList.add("show");
        document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
    };

    closeTableComponentLayer = () => {
        this.setState({fullLayer: false});
        if(document.getElementById('ctp_pwa') == null){
            return;
        }
        document.getElementById('fullLayer-container').classList.remove("show");
        document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
    };

    trackEvent(actionLabel, label){
        Analytics.event({category: this.props.gaTrackingCategory, action: actionLabel, label: label});
    }
    getTableComponent(){
        return <CategoryTableComponent show="true" categoryData={this.props.categoryData}/>;
    }
    /* widget pos in 0-index based */
    getPCWArray(dataArray, widgetPos, widgetSize){
        if(!dataArray) {
            return null;
        }
        const widgetStartIndex = widgetPos*widgetSize;
        if(widgetStartIndex > dataArray.length)
            return false;
        const widgetEndingIndex = (widgetStartIndex + widgetSize) < dataArray.length ? (widgetStartIndex + widgetSize) : dataArray.length;
        return dataArray.slice(widgetStartIndex, widgetEndingIndex);
    }
    generateTuples(){
        const {categoryData} = this.props;
        const instituteArray = categoryData.categoryInstituteTuple;
        let tupleArray = [];
        let showPCW = false;
        let ocfOrderIndex = 0;
        const ocfLength = this.props.showOCF && this.props.ocfOrder ? this.props.ocfOrder.length : 0;
        let pcwCount = 0, pcwIndex = 0;
        if(this.props.categoryData && this.props.categoryData.categoryInstituteTuple[0].courseTupleData.paid &&
            this.props.categoryData.totalInstituteCount > 10) {
            showPCW = !!this.props.categoryData.pcwData;
        }
        for(let index in instituteArray){
            if(!instituteArray.hasOwnProperty(index))
                continue;
            const instituteId = instituteArray[index].instituteId;
            if(this.props.showStateData && this.props.categoryData.requestData.pageNumber === 1 &&
                index == this.props.categoryData.totalInstituteCount){
                tupleArray.push(<h2 className="fallback-heading" key="headingState">
                    <p className="fallback--text">You might also be interested in:</p>
                    {this.props.categoryData.fallbackResultCount} {this.props.categoryData.requestData && this.props.categoryData.requestData.categoryData &&
                    this.props.categoryData.requestData.fallbackHeading} </h2>);
            }
            if(showPCW && index % this.TUPLES_BEFORE_PCW === 0){
                let pcwData = this.getPCWArray(this.props.categoryData.pcwData.popularInstituteTuple, pcwCount, this.PCW_COUNT);
                if(pcwData) {
                    for(let pcwTuple of pcwData){
                        tupleArray.push(
                            <CategoryTupleNew key={"pcw_insti_"+pcwTuple.instituteId} {...this.props} loadMoreCourses={this.props.loadMoreCourses[pcwTuple.instituteId]}
                                              index = {pcwIndex++} categoryTuple={pcwTuple} gaLabelPrependString="PCW" />
                        );
                    }
                    /*tupleArray.push(<PCW key={'PCW_' + pcwCount} nonPWALinks = {this.props.isPdfCall || this.props.deviceType === 'desktop'}
                                       aggregateReviewConfig={this.props.categoryData.pcwData.aggregateRatingConfig}
                                       showPCW={showPCW} tupleData={pcwData} gaTrackingCategory={this.props.gaTrackingCategory}/>);*/
                    pcwCount++;
                }
            }
            tupleArray.push(
                <CategoryTupleNew key={"insti_"+instituteArray[index].instituteId} {...this.props}
                                  index = {index} categoryTuple={instituteArray[index]}
                                  loadMoreCourses = {this.props.loadMoreCourses[instituteId]}/>
            );
            if(this.props.deviceType === 'mobile' && this.state.OcfDownloaded) {
                if(index === '2'){
                    {this.props.showOCF ?
                        tupleArray.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                             shortName={this.props.shortName} pageUrl={this.props.pageUrl}
                                             gaTrackingCategory={this.props.gaTrackingCategory}
                                             defaultAppliedFilters={this.props.defaultAppliedFilters} showAllIndiaLocation={!this.isAllIndiaPage()}
                                             filterName={"location"}/>)
                        : '' }
                }
                if(index === '5'){
                    {this.props.showOCF && ocfLength > ocfOrderIndex ?
                        tupleArray.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                             shortName={this.props.shortName} pageUrl={this.props.pageUrl}
                                             gaTrackingCategory={this.props.gaTrackingCategory}
                                             defaultAppliedFilters={this.props.defaultAppliedFilters}
                                             filterName={this.props.ocfOrder[ocfOrderIndex++]}/>)
                        : '' }
                }
                if(index === '9'){
                    {this.props.showOCF && ocfLength > ocfOrderIndex ?
                        tupleArray.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                             shortName={this.props.shortName} pageUrl={this.props.pageUrl}
                                             gaTrackingCategory={this.props.gaTrackingCategory}
                                             defaultAppliedFilters={this.props.defaultAppliedFilters}
                                             filterName={this.props.ocfOrder[ocfOrderIndex++]}/>)
                        : '' }
                }
                if(index === '14'){
                    {this.props.showOCF && ocfLength > ocfOrderIndex ?
                        tupleArray.push(<OCF key={'ocf'+index} filters={this.props.filters} displayName={this.props.displayName}
                                             shortName={this.props.shortName} pageUrl={this.props.pageUrl}
                                             gaTrackingCategory={this.props.gaTrackingCategory}
                                             defaultAppliedFilters={this.props.defaultAppliedFilters}
                                             filterName={this.props.ocfOrder[ocfOrderIndex++]}/>)
                        : '' }
                }
            }
            if (index === '2') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp">
                        <DFPBannerTempalte bannerPlace="CTP_Banner1"/>
                    </div>
                );
            }
            if (index === '7') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp">
                        <DFPBannerTempalte bannerPlace="CTP_Banner2"/>
                    </div>
                );
            }
            if (index === '11') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp ">
                        <DFPBannerTempalte bannerPlace="CTP_Banner3"/>
                    </div>
                );
            }
            if (index === '16') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp">
                        <DFPBannerTempalte bannerPlace="CTP_Banner4"/>
                    </div>);
            }
            if (index === '19') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp">
                        <DFPBannerTempalte bannerPlace="CTP_Banner5"/>
                    </div>);
            }
            if (index === '23') {
                tupleArray.push(
                    <div key={"DFPdiv" + index} className="ctp-dfp">
                        <DFPBannerTempalte bannerPlace="CTP_Banner6"/>
                    </div>);
            }

            if(index === '5' && this.showCollegeShortlistWidget()){
                tupleArray.push(<CollegeShortlistWidget gaTrackingCategory={this.props.gaTrackingCategory} key="collegeShortlistWidget"
                                                        deviceType={this.props.deviceType}/>);
            }
            if(this.props.deviceType === 'mobile' && index === '19'){
                tupleArray.push(<Feedback key={'feedback_'+index} pageId={this.props.categoryData.requestData.categoryPageId} pageType={'CTP'} deviceType={this.props.deviceType} />);
            }
        }
        return tupleArray;
    }
    showCollegeShortlistWidget(){
        if(!this.props.categoryData || !this.props.categoryData.requestData || !this.props.categoryData.requestData.appliedFilters)
            return false;
        const appliedFilters = this.props.categoryData.requestData.appliedFilters;
        if(appliedFilters.baseCourse && Array.isArray(appliedFilters.baseCourse) && appliedFilters.baseCourse.indexOf(10) !== -1)
            return true;
        if(appliedFilters.streams && Array.isArray(appliedFilters.streams) && appliedFilters.streams.indexOf(2) !== -1)
            return true;
        return false;
    }
    getTupleHTML(){
        const tuplesArray = this.generateTuples();
        let stateResults = false;
        let headingIndex = -1;
        if(this.props.deviceType === 'mobile'){
            return tuplesArray;
        }
        let col1TupleArray = [], col2TupleArray = [], col1FrTupleArray = [], col2FrTupleArray = [];
        for(let i in tuplesArray){
            if(!tuplesArray.hasOwnProperty(i)){
                return;
            }
            if(tuplesArray[i].key === 'headingState'){
                stateResults = true;
                headingIndex = i ;
                continue;
            }
            let index = parseInt(i);
            if(stateResults){
                index = index - (headingIndex + 1 );
                if(index % 2 === 0){
                    col1FrTupleArray.push(tuplesArray[i]);
                }
                else{
                    col2FrTupleArray.push(tuplesArray[i]);
                }
                continue;
            }
            if(index % 2 === 0){
                col1TupleArray.push(tuplesArray[i]);
            }
            else{
                col2TupleArray.push(tuplesArray[i]);
            }
        }
        return(
            <React.Fragment>
                <div className="clearFix">
                    <div className="fltlft odd-data">
                        {col1TupleArray}
                    </div>
                    <div className="fltryt even-data">
                        {col2TupleArray}
                    </div>
                </div>
                {stateResults ?
                <div className="clearFix">
                    {tuplesArray[headingIndex]}
                    <div className="fltlft odd-data">
                        {col1FrTupleArray}
                    </div>
                    <div className="fltryt even-data">
                        {col2FrTupleArray}
                    </div>
                </div> : null}
            </React.Fragment>
        );
    }
    render() {
        const totalInstituteCount = this.props.categoryData.totalInstituteCount ? this.props.categoryData.totalInstituteCount : 0;
        const headingMobile       = this.props.categoryData.requestData && this.props.categoryData.requestData.categoryData  ?
            this.props.categoryData.requestData.categoryData.headingMobile : 'Colleges';
        const sortType = this.props.categoryData.requestData && this.props.categoryData.requestData.sortType  ?
            this.props.categoryData.requestData.sortType : ""
        return (
            <div className="ctpSrp-contnr">
                 {this.props.showSort && <SortFiltersTabs pathname = {this.props.categoryData.requestData.categoryData.url} sortType = {sortType} gaTrackingCategory={this.props.gaTrackingCategory} /> }
                {this.getTupleHTML()}
               {this.state.loadFullPageLayer &&
                <FullPageLayer data={this.getTableComponent()} heading={`${totalInstituteCount} ${headingMobile}`} isOpen={this.state.fullLayer}
                               onClose={this.closeTableComponentLayer} desktopTableData = {this.props.deviceType === 'desktop'}/>}
                <div className="clearFix"><a onClick = {this.openTableComponentLayer} className="shwDat-link" id="rnkTbl-btn">Show data in table</a>
                {this.props.deviceType === 'desktop' && <Feedback pageId={this.props.categoryData.requestData.categoryPageId} pageType={'CTP'} deviceType={this.props.deviceType} feedbackWidgetType={'type2'} />}
                <Pagination categoryData={this.props.categoryData} gaTrackingCategory={this.props.gaTrackingCategory} isSrp = {this.props.isSrp}/>
                </div>
            </div>);
    }

    isAllIndiaPage() {
        if(this.props.categoryData && this.props.categoryData.requestData && this.props.categoryData.requestData.appliedFilters){
            const appliedFilters = this.props.categoryData.requestData.appliedFilters;
            if((appliedFilters.state && appliedFilters.state.length > 0) || (appliedFilters.city && appliedFilters.city.length > 0)){
                return false;
            }
            return true;
        }
        return false;
    }
}
export default CategoryPageContent;

CategoryPageContent.propTypes = {
  applyNowTrackId: PropTypes.number,
  categoryData: PropTypes.object.isRequired,
  config: PropTypes.object,
  defaultAppliedFilters: PropTypes.any,
  deviceType: PropTypes.string.isRequired,
  displayName: PropTypes.any,
  ebTrackid: PropTypes.number.isRequired,
  filters: PropTypes.any,
  gaTrackingCategory: PropTypes.any.isRequired,
  isPdfCall: PropTypes.bool,
  isSrp: PropTypes.bool,
  loadMoreCourses: PropTypes.object,
  ocfOrder: PropTypes.array,
  pageType: PropTypes.string,
  pageUrl: PropTypes.any,
  recoEbTrackid: PropTypes.number.isRequired,
  recoShrtTrackid: PropTypes.number.isRequired,
  shortName: PropTypes.any,
  showOAF: PropTypes.bool.isRequired,
  showOCF: PropTypes.bool,
  showUSPLda: PropTypes.bool.isRequired,
  srtTrackId: PropTypes.number.isRequired,
  showStateData : PropTypes.bool,
  showSort : PropTypes.bool
};

CategoryPageContent.defaultProps = {
  isPdfCall: false,
  showOCF: false,
  showStateData : false,
  showSort: false
};
