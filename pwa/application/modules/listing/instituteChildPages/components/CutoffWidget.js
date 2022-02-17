import React from 'react';
import '../assets/cutoffwidget.css';
import {Link} from 'react-router-dom';
import {jsUcfirst} from './../../../../utils/stringUtility';
import Shortlist from './../../../common/components/Shortlist';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {getObjectSize,parseQueryParams,convertObjectIntoQueryString,addingDomainToUrl} from './../../../../utils/commonHelper';
import {withRouter} from 'react-router-dom';
import {storeCourseDataForPreFilled} from './../../course/actions/CourseDetailAction';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

class CutoffWidget extends React.Component{
    constructor(props)
    {
      super(props);
      this.state = {
        examIdsForFullText:[]
      }
    }

    trackEvent(actionLabel,label='click')
    {
        let category = this.props.gaTrackingCategory
        Analytics.event({category : category, action : actionLabel, label : label});
    }

    showFullData(examId){
        let currentExamIds = this.state.examIdsForFullText;
        currentExamIds.push(examId);
        this.setState({'examIdsForFullText':currentExamIds});
    }

    getGenderCheck(){
        const {data,index} = this.props;
        let maleGender = true;
        if(data.genderApplied == 'female'){
            maleGender = false;
        }
        if(data.genderAvailable.length==1){
            return null;
        }
        return(
            <div className="inline-selection" key={'inline-selection_'+index}>
                <input type="radio" onChange={this.onGenderChanged} className="radio-input" checked={maleGender} name={'gender'+index} id={'gender'} value="all" />
                <label htmlFor={'gender'} onClick={this.trackEvent.bind(this,'male_filter','click')}>All</label>
                <input type="radio" onChange={this.onGenderChanged} className="radio-input" checked={!maleGender} name={'gender'+index} id={'gender-'} value="female" />
                <label htmlFor={'gender-'} onClick={this.trackEvent.bind(this,'female_filter','click')}>Female</label>
            </div>
        )
    }

    onGenderChanged =  (e) => {
            if(this.props.contentLoaderData){
                this.props.contentLoaderData();
            }
            let gender = e.currentTarget.value;
            let params = {};
            if(this.props.location.search !=''){
                params = parseQueryParams(this.props.location.search);
            }
            params['gn'] = [];
            params['gn'].push(gender);       
            const queryString = convertObjectIntoQueryString(params);
            let url = this.props.location.pathname;
            url += '?' + queryString;
            
            this.props.history.push(url);
            
    } 

    handleClickOnCourse(courseData, gaTrack = 'click')
    {
      this.trackEvent(gaTrack);
      if(typeof courseData != 'undefined' && typeof courseData == 'object')
        {
          var data = {};
          data.courseId = courseData.courseId;
          data.name = courseData.courseName;
          this.props.storeCourseDataForPreFilled(data);
        }
    }

    getMainTable(){
            const {data,ViewMoreCount}  = this.props;
            let mainTable = [];
            let tableData = [];
            let innerRow = [];
            let examWiseData = Object.values(data.examWiseData);
            let roundsCount = 0;
            let colSpanCount = 0;
            for(let i=0;i<examWiseData.length;i++){
                let roundData  = Object.entries(examWiseData[i].tupleData);
                let rankBy = 'rank';
                tableData = [];
                if(examWiseData[i].examName){
                    let heading = examWiseData[i].examName.toUpperCase();
                    if(examWiseData[i].tupleType =='cat'){
                        let category = 'General';
                        if(this.props.appliedCategory){
                            category = this.props.appliedCategory;
                        }
                        heading += ' ('+jsUcfirst(category)+' Category)';
                    }
                    colSpanCount = 0;
                    if(examWiseData[i].tupleHeading !=null && examWiseData[i].tupleHeading.length>0){
                        colSpanCount = examWiseData[i].tupleHeading.length;
                    }else{
                        colSpanCount = getObjectSize(roundData[0][1])+1;
                    }
                    tableData.push( <tr key={'head_'+i}><th key={'th_'+i} colSpan={colSpanCount}>{heading}</th></tr>);    
                }
                if(examWiseData[i].tupleHeading){
                    innerRow=[];
                    for(let tupleDataIndex=0;tupleDataIndex<examWiseData[i].tupleHeading.length;tupleDataIndex++){
                        let heading = '';
                        rankBy = 'rank';
                        if(examWiseData[i].tupleHeading[tupleDataIndex] == 'homestate'){ heading = 'Home State'}
                        if(examWiseData[i].tupleHeading[tupleDataIndex] == 'allindia'){ heading = 'All India'}
                        if(examWiseData[i].rankBy){ rankBy = examWiseData[i].rankBy; }
                        rankBy = rankBy.replace('percentage','%');
                        rankBy = rankBy.replace('percentile','%ile');
                        if(tupleDataIndex>0){
                            innerRow.push(<td key={'heading_'+tupleDataIndex}><strong className='block'>{heading}</strong><span className='block'>{'Cut-off by '+rankBy}</span></td>);
                        }else{
                            innerRow.push(<td key={'heading_'+tupleDataIndex}><strong className='block'>{examWiseData[i].tupleHeading[tupleDataIndex]}</strong></td>);
                        }
                    }
                    tableData.push(<tr key={'outerTr_'+i}>{innerRow}</tr>);    
                }

                let tableRow = this.getInnerTable(examWiseData[i]);
                let roundsCount = roundData.length;
                tableData.push(tableRow);

                mainTable.push(<div key={'table_wraper'+i} className='table_wraper'><table><tbody>{tableData}</tbody></table></div>);

                if(roundsCount>=ViewMoreCount && this.state.examIdsForFullText.indexOf(examWiseData[i].examId) ==-1){
                    mainTable.push(<div className='table_gradient' key={'table_gradient'+i} onClick={this.showFullData.bind(this,examWiseData[i].examId)} > <a className='viewBtn arrow rvrse'>View more</a> </div>);
                }
                if(examWiseData[i].examUrl){
                    let Heading = 'Click to see '+examWiseData[i].examName+' Cut-Off for All Rounds';
                    let queryParams = (this.props.location && this.props.location.search != "")? this.props.location.search + "&" :"?";
                    let url = this.props.pageUrl+'-'+examWiseData[i].examUrl+ queryParams + 'courseId='+data.courseId;
                    mainTable.push(<p key={'p_'+i} className="lead"><Link to={url} onClick={this.trackEvent.bind(this,'cutoff_all_rounds','click')}>{Heading}</Link></p>);
                }
                if(examWiseData[i].remark){
                    mainTable.push(<div key={'cutoff_roundup'+i} className="cutoff_roundup">{examWiseData[i].remark}</div>);   
                }
            }

            return(
                <div className="cutoff_page" key={'cutoff_page'+this.props.index}>
                    {mainTable}
                </div>
            )       
    }

    getInnerTable(examWiseData){
        const {ViewMoreCount} = this.props;
        let {tupleData,tupleHeading,tupleOrder,examId,rankBy}  = examWiseData;
        let tableData = tupleData;
        let roundsCount = 0;
        let TableRow = [];
        let innerTableTd = [];
        if(tupleOrder){
            for(let i =0; i<tupleOrder.length; i++){
                let heading = tupleOrder[i];
                let roundData = tableData[heading];
                roundsCount++;
                innerTableTd = [];
                innerTableTd.push(<td key={'heading_'+i}>{heading}</td>);
                let tableTD = this.getDataBasedOnHeading(tupleHeading,roundData,rankBy);                
                innerTableTd.push(tableTD);
                TableRow.push(<tr key={'tr_'+i}>{innerTableTd}</tr>);
                if(roundsCount>=ViewMoreCount && this.state.examIdsForFullText.indexOf(examId) ==-1){
                    break;
                }
            }
        }else{
            tableData = Object.entries(tableData);
            for(let [heading,roundData] of tableData){
              roundsCount++;
              innerTableTd = [];
              innerTableTd.push(<td key={'row_td'+roundsCount}>{heading}</td>);  
                let tableTD = this.getDataBasedOnHeading(tupleHeading,roundData,rankBy);                
                innerTableTd.push(tableTD);
                TableRow.push(<tr key={'row_tr'+roundsCount}>{innerTableTd}</tr>);
                if(roundsCount>=ViewMoreCount && this.state.examIdsForFullText.indexOf(examId) ==-1){
                    break;
                }

            }    
        }
        return (
            <React.Fragment key={'fragment_'+this.props.index}>
                {TableRow}
            </React.Fragment>
        )
    }

    getDataBasedOnHeading(tupleHeading,roundData,rankBy){
        let innerTableTd = [];
        if(tupleHeading != null &&  tupleHeading.length>0){
            for(let i=0;i<tupleHeading.length;i++){
                if(i>0){
                    if(roundData){
                        innerTableTd.push(this.addTableTd(roundData[tupleHeading[i]],rankBy));
                    }else{
                        innerTableTd.push(<td key={'row_td1_'+Math.random()}>-</td>);        
                    }
                }
            }
        }
        else{
            if(!roundData){
                innerTableTd.push(<td key={'row_td11_'}>-</td>);
            }else{
                roundData = Object.values(roundData); 
                for(let tableIndex in roundData){
                   innerTableTd.push(this.addTableTd(roundData[tableIndex],rankBy));
                } 
            }
        }
        return(<React.Fragment key={'innerFragment'+this.props.index}>
                {innerTableTd}
                </React.Fragment>
            )
    }

    addTableTd(TableTdData,rankBy){

        if(TableTdData){ 
            if(rankBy =='percentile'){
                TableTdData = TableTdData+'%ile';
            }    
        return (<td key={'addTableTd'+Math.random()}>{TableTdData}</td>) }
        else{ return(<td key={'addTableTd'+Math.random()}>-</td>) }
    }

    render(){
        const {data,deviceType,index} = this.props;
        if(!getObjectSize(data.examWiseData)){
            return null;
        }

        let courseUrl = this.props.isPDF ? addingDomainToUrl(data.courseUrl, this.props.config.SHIKSHA_HOME) : data.courseUrl ;
        let offeredByUrl = this.props.isPDF ? addingDomainToUrl(data.offeredByUrl, this.props.config.SHIKSHA_HOME) : data.offeredByUrl ;

      return(
           <section id="" key={'tuple_'+Math.random()}>
              <div className="_subcontainer" key={'sub_'+this.props.index}>
                 <div className="cutoff_head" key={'head_'+this.props.index}>
                    {data.courseUrl && deviceType=='mobile' ? <Link key={'courselink_'+index} to={courseUrl} onClick={this.handleClickOnCourse.bind(this,data,'CLP_name')}>{data.courseName}</Link>:''}
                    {data.courseUrl && deviceType=='desktop' ? <a key={'courseachor_'+index} href={courseUrl} onClick={this.trackEvent.bind(this,'CLP_name','click')}>{data.courseName}</a>:''}
                    {data.location ? 
                        ( 
                            <p className='cutoff_loc' key={'cutoff_loc_'+index}>
                                <i className='clg_location_icon' key={'clg_location_icon_'+index}></i>
                                <b key={'btag_'+index}>{data.location}</b>            
                            </p>
                        )
                        :''} 
                    <div className="cutoff_linkflex" key={'cutoff_linkflex_'+this.props.index}>
                        {data.offeredByUrl ? (<p key={'instP_'+index} className={this.props.data.genderAvailable.length==1 ? "cutoff_offerby full_width_col" : "cutoff_offerby"}> Offered by 
                            {data.offeredByUrl && deviceType=='mobile' ? <Link key={'instlink_'+index} className='mrglft' to={offeredByUrl} onClick={this.trackEvent.bind(this,'IULP_name','click')}>{' '+data.offeredBy}</Link>:''}
                            {data.offeredByUrl && deviceType=='desktop' ? <a key={'instachor_'+index} className='mrglft' href={offeredByUrl} onClick={this.trackEvent.bind(this,'IULP_name','click')}>{' '+data.offeredBy}</a>:''}
                          </p>) : ''}
                        {this.getGenderCheck()}
                    </div>
                 </div>
                 {this.getMainTable()}

                <div className="cutoff_btns pdf_display_none" key={'cutoff_btns_'+this.props.index}>
                    {this.props.deviceType == 'mobile' ?
                      <Shortlist className="button button--secondary" actionType="NM_course_shortlist" listingId={data.courseId} trackid="344"  recoEbTrackid={this.props.shortTracking} recoShrtTrackid="1097" recoPageType="EBrochure_RECO_Layer" recoActionType="EBrochure_RECO_Layer" pageType="NM_Ranking" sessionId="" visitorSessionid="" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Shortlist','tuple_click')} isButton={true} page = {"cutoffPage"}/> :

                       <button type="button" name="button" onClick={this.downloadEBRequest.bind(this,{'listingId':data.courseId,'listingName':data.courseName, 'listingType':'course', 'ebTrackid' :this.props.shortTracking, 'pageType':'ND_Ranking'}, 'Shortlist')}  cta-type="shortlist" className={"ctp-btn ctpComp-btn button button--secondary rippleefect tupleShortlistButton shrt"+data.courseId} courseid={data.courseId} customcallback="listingShortlistCallback" customactiontype="ND_Cutoff"><span id={'shrt'+data.courseId} product="Ranking" track="on" courseid={data.courseId}>Shortlist</span></button> }

                    {this.props.deviceType == 'mobile' ? <DownloadEBrochure className="button button--orange" buttonText="Apply Now" listingId={data.courseId} trackid={this.props.ebTracking} recoEbTrackid="1096" recoShrtTrackid="1097" isCallReco={this.props.isCallReco} clickHandler={this.trackEvent.bind(this,'Apply Now','tuple_click')} page ={"cutoffPage"}/> : 

                        <button className="button button--orange button button--orange tupleBrochureButton" type="button" name="button" onClick={this.downloadEBRequest.bind(this,{'listingId':data.courseId, 'listingName':data.courseName, 'listingType':'course', 'ebTrackid' :this.props.ebTracking, 'pageType':'ND_Ranking'}, 'applynow')}  cta-type="download_brochure"  courseid={data.courseId} listingid={data.courseId} type={'course'}><span id={'ebTxt'+data.courseId} product="Cutoff" track="on" courseid={data.courseId}>Apply Now</span></button>}
                 </div>
              </div>
           </section>
      )
    }

    downloadEBRequest = (params, gaLabel, e) => {
      let thisObj = e.currentTarget;
      if(typeof(window) !='undefined' && typeof(ajaxDownloadEBrochure) !='undefined'){
        this.trackEvent(gaLabel, 'Response');
        window.ajaxDownloadEBrochure(thisObj, params.listingId, params.listingType, params.listingName, params.pageType, params.ebTrackid, 1104, 0, 0, 1105);
      }
    }

}
function mapDispatchToProps(dispatch){
  return bindActionCreators({storeCourseDataForPreFilled}, dispatch);
}

export default connect(null,mapDispatchToProps)(withRouter(CutoffWidget));
