import PropTypes from 'prop-types'
import React from 'react';
import './../assets/importantDates.css';
import './../assets/courseCommon.css';
import PopupLayer from './../../../common/components/popupLayer';
import FullPageLayer from './../../../common/components/FullPageLayer';
import { getRequest} from '../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {getObjectSize,getObjectSlice} from './../../../../utils/commonHelper';

class ImportantDatesComponent extends React.Component
{
    constructor(props)
    {
        super(props);
        this.state = {
            importantDatesData: [],
            layerHeading:'Important Dates',
            examName : 'all',
            selectedExamName : 'All',
            layerOpen : false
        }
        this.impDatesLayerData = [];
        this.maxDisplayDates   = 3;
        this.closeLayer        = this.closeLayer.bind(this);
    }



    formatImportantDatesData(viewType='page',examName="All",datesLayerData){
        const {importantDates} = this.props;
        var importantDatesData = importantDates.importantDates;//Object.values

        var datesCount = getObjectSize(importantDatesData);
        if(viewType == 'layer' && typeof datesLayerData != 'undefined')
        {
            importantDatesData = datesLayerData.importantDates;
            datesCount = getObjectSize(datesLayerData.importantDates);
        }
        var upcomingShown = false;
        var pastDates = [];
        var upcomingIndex ='';
        var impDatesHtml = [];
        var maxToShow = this.maxDisplayDates;
        var upcomingClassShown = false;
        for(let index in importantDatesData) {
            if(importantDatesData[index]['showUpcoming'] && !upcomingShown){
                upcomingShown = true;
                upcomingIndex = index;
            }
        }

        if(viewType == 'page'){
            if(upcomingShown){
                if(datesCount >= maxToShow){
                    if((parseInt(upcomingIndex)+parseInt(maxToShow)) > datesCount){
                        upcomingIndex = parseInt(datesCount) - parseInt(maxToShow);
                    }
                    importantDatesData = getObjectSlice(importantDatesData,upcomingIndex,parseInt(upcomingIndex)+parseInt(maxToShow));
                }

            }
            else{
                importantDatesData = getObjectSlice(importantDatesData,0,maxToShow);
            }
        }

        else if(viewType == 'layer'){
            //show 3 past dates with in last 8 months
            for(let index in importantDatesData) {

                if(examName!= 'All' && examName != importantDatesData[index]['examName']){
                    importantDatesData[index]= null;
                    continue;
                }

                if(importantDatesData[index]['start_date'] == ''){
                    importantDatesData[index]['start_date'] = 1;
                }
                if(!importantDatesData[index]['showUpcoming']){
                    pastDates.push(importantDatesData[index]);
                    importantDatesData[index]= null;
                }

            }

            var tempObject = [];
            for(let k in importantDatesData)
            {
                if(importantDatesData[k] != null)
                {
                    tempObject.push(importantDatesData[k]);
                }
            }

            importantDatesData = tempObject;

            /*importantDatesData = importantDatesData.filter(function(val) {
               return val != null;
            });*/

            if(getObjectSize(importantDatesData)==0){
                //pastDates.slice((0-3),3);
                importantDatesData = pastDates.slice(parseInt(pastDates.length)-parseInt(maxToShow),pastDates.length);
            }
            else{
                //importantDates.push(pastDates.slice((0-3),3));
                importantDatesData = pastDates.slice(parseInt(pastDates.length)-parseInt(maxToShow),pastDates.length).concat(importantDatesData);
            }
        }
        for(var index in importantDatesData){
            var upcomingClass = '';
            var upcomingHtml  = '';
            var disableLft = '';
            var disableRgt = '';
            if(!importantDatesData[index]['showUpcoming']){
                upcomingClass = 'disabled-date';
                disableLft = 'disbl-date';
                disableRgt = 'disbl-div';
            }else if(importantDatesData[index]['showUpcoming'] && !upcomingClassShown){
                upcomingClass = 'current';
                upcomingClassShown = true;
                upcomingHtml = <span className="upcoming-tap">Up-coming</span>;
            }
            // let examNameString = importantDatesData[index]['examName']!=null && typeof importantDatesData[index]['examName'] != 'undefined' && importantDatesData[index]['examName'].toUpperCase();
            // let eventNameString = importantDatesData[index]['eventName']!=null && typeof importantDatesData[index]['eventName'] != 'undefined' && importantDatesData[index]['eventName'].toUpperCase();
            impDatesHtml.push(<div className={"circle-block " +upcomingClass} key={index}><div className="circ"></div><div className={"l-cnt " +disableLft}><p>{importantDatesData[index]['displayString']}</p></div><div className={"r-cnt " + disableRgt}><p className="">{importantDatesData[index]['eventName']}</p>{upcomingHtml}</div></div>);
        }
        return impDatesHtml;
    }



    formatImportantDatesLayerData(examName,datesLayerData){
        var layerHtml = [];
        var examDropdownHtml = [];
        let contentHtml = this.formatImportantDatesData('layer',examName,datesLayerData);
        if(typeof this.props.importantDates != 'undefined' && ((getObjectSize(this.props.importantDates.examsHavingDates) > 0 && this.props.importantDates.isCourseDates) || (getObjectSize(this.props.importantDates.examsHavingDates) > 1 && !this.props.importantDates.isCourseDates))) {
            examDropdownHtml = <div className="dropdown-primary arrw-cls full-width"><label className="option-slctd" value="View Dates by Exam" onClick={this.openExamSelectionLayer.bind(this)}>{examName}</label></div>;
        }
        layerHtml.push(<div className="date-sec no-bdr" key="drop-down">{examDropdownHtml}<div className='ss'><div className='bar-line'>{contentHtml}</div></div></div>);
        this.impDatesLayerData[examName] = {};
        this.impDatesLayerData[examName] = layerHtml;
        this.setState({layerOpen : true,isShowLoader : false,examName : examName,selectedExamName : examName});
    }

    openExamSelectionLayer() {
        this.examSelectionLayer.open();
    }

    openImpDatesLayer(courseId,examId,examName){
        if(typeof examName == 'undefined' || examName == ''){
            examName = 'All';
        }
        if(typeof examId == 'undefined' || examId == ''){
            examId = '';
        }
        this.fetchImportantDatesLayerData(courseId,examId,examName,examName);
    }
    closeLayer()
    {
        this.setState({layerOpen : false});
    }

    activeExam(examName,selectedExamName)
    {
        this.setState({ examName :  examName,selectedExamName : selectedExamName});
    }

    fetchImportantDatesLayerData(courseId,examId,examName,selectedExamName)
    {
        var self  = this;
        let params = 'courseId='+courseId;
        if(examId != '' && typeof examId != 'undefined' && examId != 'All')
        {
            params += '&examIds='+examId;
        }
        this.setState({layerOpen : true,isShowLoader : true});
        let url = APIConfig.GET_IMPORTANT_DATES;
        getRequest(url+'?'+params).then(function(resposne){
            self.formatImportantDatesLayerData(selectedExamName,resposne.data.data);
        }).catch(function(){

        });
    }

    generateExamSelectionLayer()
    {
        var html = [];
        const {courseId} = this.props;
        var self = this;
        if(typeof this.props.importantDates != 'undefined' && getObjectSize(this.props.importantDates.examsHavingDates) > 0) {
            var examsHavingDates = this.props.importantDates.examsHavingDates;
            var examSelectionHtml = [];
            var index = 0;
            for(let i in examsHavingDates)
            {
                examSelectionHtml.push(<li key={examsHavingDates[i]['examId']} onClick={self.fetchImportantDatesLayerData.bind(self,courseId,examsHavingDates[i]['examId'],index,examsHavingDates[i]['examName'])}><span>{examsHavingDates[i]['examName']}</span></li>)
                index++;
            }
            html.push(<ul className="ul-list imp-list" key="ullist"><li key='all' onClick={self.fetchImportantDatesLayerData.bind(self,courseId,'','All','All')}><span>All</span></li>{examSelectionHtml}</ul>);
        }
        return html;
    }

    trackEvent()
    {
        Analytics.event({category : 'CLP', action : 'DatesExpand', label : 'Subwidget'});
    }

    handleOptionClick(courseId)
    {
        this.openImpDatesLayer(courseId,'','All');
        //this.trackEvent();
    }

    render(){
        const {courseId} = this.props;
        let impDatesHtml = this.formatImportantDatesData();
        let impDatesLayerHtml = this.impDatesLayerData[this.state.selectedExamName];
        // var border_top = (this.props.admissionCount>0)?'border-top':'';
        return (

            <div className='date-sec border_top'>
                {this.props.admissionCount>0 && <h3>Important Dates</h3>}
                <div className='ss bar-line-col'>
                    <div className='bar-line'>
                        {impDatesHtml}
                    </div>
                </div>
                <PopupLayer onRef={ref => (this.examSelectionLayer = ref)} data={this.generateExamSelectionLayer()} heading='Select an Exam'/>
                <FullPageLayer desktopTableData={this.props.deviceType == 'desktop' ? true : false} data={impDatesLayerHtml} heading={this.state.layerHeading} onClose={this.closeLayer} isOpen={this.state.layerOpen} isShowLoader={this.state.isShowLoader}/>
                {this.props.importantDates.showImportantViewMore && <div className='button-container mb4'>
                    <button type="button" className='button button--secondary arrow' onClick={this.handleOptionClick.bind(this,courseId)}>View All Dates</button>
                </div>}

            </div>

        )
    }
}

export default ImportantDatesComponent;

ImportantDatesComponent.propTypes = {
    admissionCount: PropTypes.any,
    courseId: PropTypes.any,
    deviceType: PropTypes.any,
    importantDates: PropTypes.any
}