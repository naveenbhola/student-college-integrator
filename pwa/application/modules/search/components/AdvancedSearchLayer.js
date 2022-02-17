import React from 'react';
import FullPageLayer from './../../common/components/FullPageLayer';
import config from './../../../../config/config';
import PreventScrolling from "../../reusable/utils/PreventScrolling";
import {fetchCollegeData, trackSearch} from "../actions/SearchAction";
import PopupLayer from "../../common/components/popupLayer";
import {trackEvent} from "../utils/searchUtils";
import {Link} from 'react-router-dom';

class AdvancedSearchLayer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            streamValue: null,
            courseValue: null
        }
    }
    canUseDOM() {
        return !!(typeof window !== 'undefined' && window.document && window.document.createElement);
    }
    onReset(){
        this.setState({streamValue: null, courseValue: null});
        this.props.onClose();
        this.props.onReset();
        PreventScrolling.enableScrolling(true);
    }
    onBack(){
        this.setState({streamValue: null, courseValue: null});
        PreventScrolling.enableScrolling(true, true);
        this.props.onBack();
    }
    openPopUp(layer){
        if(layer === 'stream')
            this.streamPopupLayer.open();
        if(layer === 'course')
            this.coursePopupLayer.open();
    }
    selectStream(tupleData, index, event){
        trackEvent('Advance_options_stream', 'click');
        this.setState({streamValue: tupleData});
        this.setState({courseValue: null});
        this.url = tupleData.url;
    }
    prepareStreamData(){
        const streamTuples = this.props.filtersData;
        const streamList = streamTuples.map((tuple, i) =>
            <li key={'streamTuple_'+i}  onClick={this.selectStream.bind(this, tuple, i)}><span>{tuple.name}</span></li>
        );
        return(
            <ul className="ul-list" key="streamList">
                {streamList}
            </ul>
        );
    }
    selectCourse(tupleData){
        trackEvent('Advance_options_course', 'click');
        this.url = tupleData.url;
        this.setState({courseValue: tupleData});
    }
    prepareCourseData(){
        if(!this.state.streamValue || !this.state.streamValue.subSuggestions)
            return;
        const courseTuples = this.state.streamValue.subSuggestions;
        const courseList = courseTuples.map((tuple, i) =>
            <li key={'courseTuple_'+i}  onClick={this.selectCourse.bind(this, tuple)}><span>{tuple.name}{tuple.subName?',':''} {tuple.subName}</span></li>
        );
        return(
            <ul className="ul-list" key="courseList">
                {courseList}
            </ul>
        );
    }
    generateHtml(){
        const collegeMetaData = this.props.collegeData;
        if(!collegeMetaData){
            return null;
        }
        return(
        <div className="newSearch">
            <div className="_advacnedSearch">
                <div className="contentHead"><span onClick={this.onBack.bind(this)} className="getmebck"><i className="srchspritev1 bckIco"></i></span>{collegeMetaData.name}<a href="javascript:void(0);" onClick={this.onReset.bind(this)} className="lyr-crs">&times;</a></div>
            </div>
            <div className="layerContent">
                <div className="_advanceoptns">
                    <form>
                        <fieldset className="fieldOptns">
                            <legend className="text-center text_f14">Advanced Options <span>(optional)</span></legend>
                            <div className="multiChioiceCol">
                                <p className="slctchoiceTxt">This college offers multiple courses and multiple streams. You may select stream/course from below:</p>
                                <div className="advance-dropdwn">
                                    <PopupLayer onRef={ref => (this.streamPopupLayer = ref)} heading={'Choose Stream'} data={this.prepareStreamData()}/>
                                    <p className="droptxt" onClick={this.openPopUp.bind(this, 'stream')}> {this.state.streamValue ? this.state.streamValue.name :'Choose Stream'} <i className="dropdwn_arw"></i></p>
                                </div>
                            </div>
                            <div className={this.state.streamValue ? 'advance-dropdwn' : 'advance-dropdwn disable-drop'}>
                                <PopupLayer onRef={ref => (this.coursePopupLayer = ref)} heading={'Choose Course'} data={this.prepareCourseData()}/>
                                <p className="droptxt" onClick={this.openPopUp.bind(this, 'course')}>{this.state.courseValue ? (this.state.courseValue.name + (this.state.courseValue.subName ? (', ' +this.state.courseValue.subName) : '')) :'Choose Course '}  <i className="dropdwn_arw"></i></p>

                            </div>

                        </fieldset>
                        <Link to = {this.url ? this.url : this.props.collegeData.url} onClick={this.prepareTrackingData.bind(this)} className="text-center"><button type="button" name="button" className="button button--orange">Search</button></Link>
                    </form>
                </div>
            </div>
        </div>
        );
    }

    render()
    {
        let html;
        if(this.props.openLayer)
            html = this.generateHtml();
        return (
            <FullPageLayer onRef={ref => (this.advancedSearchLayerRef = ref)} data={html} isHeaderAllowed={false} heading="Layer" onClose={this.onBack.bind(this)} isAnimationRequired = {false} disableScroll = {false} isOpen={this.props.openLayer}/>
        )
    }
    prepareTrackingData(){
        trackEvent('Advance_options_search', 'click');
        let dataObj = {};
        let data = this.props.collegeData;
        if(!data)
            return;
        if(window.referrer) {
            dataObj.referrerUrl = window.referrer;
        } else if (this.canUseDOM() && document.referrer){
            dataObj.referrerUrl = document.referrer;
        }
        dataObj.searchedKeyword = data.name;
        dataObj.selectedKeywordId = data.id
        dataObj.selectedKeywordType = data.type;
        if(this.state.streamValue){
            dataObj.selectedKeywordType += '-' + 'stream';
            if(this.state.courseValue){
                dataObj.selectedKeywordType += '-' + 'course';
            }
        }
        dataObj.landingUrl = this.url ? this.url : this.props.collegeData.url;
        dataObj.typedKeyword = this.props.typedKeyword;
        dataObj.isRecent = this.props.searchType === 'recentSearch' ? true : false;
        dataObj.isTrending = this.props.searchType === 'trendingSearch' ? true : false;
        dataObj.searchType = (this.props.searchType === 'recentSearch' ? 'close' : this.props.searchType);
        dataObj.searchType = (this.props.searchType === 'trendingSearch' ? 'close' : this.props.searchType);
        if(this.props.numOfResults)
            dataObj.numOfSuggestionsShown = this.props.numOfResults;
        dataObj.device = 'mobile';
        trackSearch(dataObj)
    }
}
AdvancedSearchLayer.defaultProps = {
    isShowLoader : false, isHeaderAllowed : true, collegeData: null, filtersData: null
}

export default AdvancedSearchLayer;
