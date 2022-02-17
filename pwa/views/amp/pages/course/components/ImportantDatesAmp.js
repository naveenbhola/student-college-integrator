import React  from 'react';
import Analytics from '../../../../../application/modules/reusable/utils/AnalyticsTracking';
import {getObjectSize,getObjectSlice} from '../../../../../application/utils/commonHelper';

class ImportantDatesAmp extends  React.Component{
  constructor(props){
    super(props);

   this.impDatesLayerData = [];
   this.maxDisplayDates   = 3;
  }
  formatImportantDatesData(viewType='page',examName="All",datesLayerData){
        const {importantDates} = this.props;

        var importantDatesData = importantDates.importantDates;//Object.values
        var datesCount = getObjectSize(importantDatesData);
        var maxToShow = this.maxDisplayDates;
        if(viewType == 'layer' && typeof datesLayerData != 'undefined')
        {
            importantDatesData = datesLayerData;
            datesCount = getObjectSize(datesLayerData);
            maxToShow = datesCount;
        }
        var upcomingShown = false;
        var pastDates = [];
        var upcomingIndex ='';
        var impDatesHtml = [];
        var upcomingClassShown = false;
        for(var index in importantDatesData) {

          if(importantDatesData[index]['showUpcoming'] && !upcomingShown){
                upcomingShown = true;
                upcomingIndex = index;
            }
        }

        if(viewType == 'page' || viewType == 'layer'){
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
        
        for(var index in importantDatesData){
          var upcomingClass = '';
          var upcomingHtml  = '';
          var disableLft = '';
          var disableRgt = '';
          var fadeout= '';
            if(!importantDatesData[index]['showUpcoming']){
                upcomingClass = 'disable-color';
                disableLft = 'disable-color';
                disableRgt = 'disable-color';
                fadeout='bg-clr-f1';
            }else if(importantDatesData[index]['showUpcoming'] && !upcomingClassShown){
                upcomingClass = 'current';
                upcomingClassShown = true;
                fadeout='';
                upcomingHtml = <span className="f11 color-6 color-r">Up-coming</span>;
            }
            let examNameString = importantDatesData[index]['examName']!=null && typeof importantDatesData[index]['examName'] != 'undefined' && importantDatesData[index]['examName'].toUpperCase();
            let eventNameString = importantDatesData[index]['eventName']!=null && typeof importantDatesData[index]['eventName'] != 'undefined' && importantDatesData[index]['eventName'].toUpperCase();
          impDatesHtml.push(<div className={"crc-blck " +upcomingClass} key={index}><div className={"crc " +upcomingClass+' '+fadeout }></div><div className={"l-cnt i-block v-top " +disableLft}><p className={"f12 color-3 font-w6 " +disableLft}>{importantDatesData[index]['displayString']}</p></div><div className={"r-cnt i-block v-top " + disableRgt}><p className={"f12 color-3 font-w6 " +disableRgt}>{importantDatesData[index]['eventName']}</p>{upcomingHtml}</div></div>);
        }
        return impDatesHtml;
  }
  formatImportantDatesLayerData(impdates){
    var layerHtml = [];
    //console.log('data.llldss', impdates.importantDates);
    let datesLayerData = impdates.examsHavingDates;
    if(impdates.examWiseDates != null){
      for(var index in datesLayerData){
        var examId = datesLayerData[index]['examId'];
        var examName = datesLayerData[index]['examName'];
        let contentHtml = this.formatImportantDatesData('layer',examName,impdates.examWiseDates[examId]);
        layerHtml.push(<div id="imp_dates_div"><input type="radio" name="types" value={"imp_dates_"+examId} id={"imp_dates_"+examId} className="hide st"  /><div  className="table tob1 margin-20"><p className="color-3 f14 font-w6">{examName} Dates</p><div className="bar-line pos-rl margin-20"><div className='ss'><div className='bar-line'>{contentHtml}</div></div></div></div></div>); 
      }
      let contentHtml = this.formatImportantDatesData('layer','All',impdates.examWiseDates['All']);
      layerHtml.push(<div id="imp_dates_div"><input type="radio" name="types" value="imp_dates_All" id="imp_dates_All" className="hide st"  checked={true}/><div  className="table tob1 margin-20"><div className="bar-line pos-rl margin-20"><div className='ss'><div className='bar-line'>{contentHtml}</div></div></div></div></div>);
    }
    
    return layerHtml;

	}

  generateExamSelectionLayer()
  {
      var html = [];
      const {courseId} = this.props;
      var self = this;
      var examDropdownHtml = [];
      if(typeof this.props.importantDates != 'undefined' && ((getObjectSize(this.props.importantDates.examsHavingDates) > 0 && this.props.importantDates.isCourseDates) || (getObjectSize(this.props.importantDates.examsHavingDates) > 1 && !this.props.importantDates.isCourseDates))) {
  			examDropdownHtml = <div className="dropdown-primary pos-abs color-w border-all tab-cell pos-abs" on="tap:exam-list" role="button" tabIndex='0'><span className="option-slctd block color-6 f12 font-w6 ga-analytic" data-vars-event-name="IMPDATES_CHOOSE_EXAM">Choose Exam</span></div>;
  		}
      if(typeof this.props.importantDates != 'undefined' && getObjectSize(this.props.importantDates.examsHavingDates) > 0) {
          var examsHavingDates = this.props.importantDates.examsHavingDates;
          var examSelectionHtml = [];
          var index = 0;
          for(var i in examsHavingDates)
          {
              examSelectionHtml.push(<li key={examsHavingDates[i]['examId']}><label className="block" htmlFor={'imp_dates_' + examsHavingDates[i]['examId']}>{examsHavingDates[i]['examName']}</label></li>)
              index++;
          }
          html.push(
            <React.Fragment>
              {examDropdownHtml}
              <amp-lightbox id="exam-list" layout="nodisplay" scrollable>
                <div className="lightbox" on="tap:exam-list.close" role="button" tabIndex="0">
                  <a className="cls-lightbox color-f font-w6 t-cntr">&times;</a>
                  <div className="m-layer">
                      <div className="min-div color-w catg-lt">
                        <ul className="color-6" key="ullist">
                        <li key='all'><label htmlFor={'imp_dates_All'} className="block">All</label></li>{examSelectionHtml}</ul>
                      </div>
                  </div>
                 </div>
              </amp-lightbox>
            </React.Fragment>
          );
      }
      return html;
  }
  trackEvent()
  {
    Analytics.event({category : 'CLP', action : 'DatesExpand', label : 'Subwidget'});
  }
  render(){
    const {courseId} = this.props;
    let impDatesHtml = this.formatImportantDatesData();
    let impDatesLayerHtml = this.impDatesLayerData['All'];
    var border_top = (this.props.admissionCount>0)?'border-top':'';
    return(
      <div className={(this.props.admissionCount>0) ?"imp-date pad-top border-top margin-20": "imp-date pad-top "}>
         <h3 className="f14 color-6 font-w6 m-btm">Important dates</h3>
         <div className="ss pos-rl">
            <div className="bar-line padlr0 pos-rl">
              {impDatesHtml}
            </div>
         </div>
         {this.props.importantDates.showImportantViewMore &&
             <a className="block m-top color-b t-cntr f14 font-w6 v-arr ga-analytic" on="tap:imp-date" role="button" tabIndex="0" data-vars-event-name="IMPDATES_VIEWALL_DATES">View all dates</a>
           }
           <amp-lightbox id="imp-date" layout="nodisplay" scrollable>
              <div className="lightbox">
                <div className="color-w full-layer">
                <div className="pos-fix f14 color-3 bck1 pad10 font-w6">
                      Important Dates
                    <a className="cls-lightbox color-3 font-w6 t-cntr" on="tap:imp-date.close" role="button" tabIndex="0">×</a>

                 </div>
                 <div className="col-prime pad10 margin-50">
                 <div className="f12 color-0 pos-rl m-btm">
                   {this.generateExamSelectionLayer()}

                 </div>
                  {this.formatImportantDatesLayerData(this.props.importantDatesAmp)}
                 </div>
                </div>
              </div>
          </amp-lightbox>
      </div>
    )
  }
}


export default ImportantDatesAmp;
