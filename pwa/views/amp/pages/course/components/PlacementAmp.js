import React from 'react';
import config from './../../../../../config/config';
import { getRupeesDisplayableAmount, convertSalaryIntoLakh, trim } from '../../../../../application/modules/listing/course/utils/listingCommonUtil';
import AmpLightBox from '../../../../../application/modules/common/components/AmpLightbox';


class PlacementComponentAmp extends React.Component{
  constructor(props){
    super(props);
  }
  prepareRecruitmentCompniesData(){
    var originalData = this.props.recruitmentCompanies;
    if(this.props.recruitmentCompanies.length>8){
      var temperedData = originalData.slice(0,9)
    }
  }

  prepareGraphData(){
    const {placementData} = this.props;
    var placementGraphData = {};
    if(placementData){
      placementGraphData['min'] = placementData['min_salary'];
      placementGraphData['median'] = placementData['median_salary'];
      placementGraphData['max'] = placementData['max_salary'];
      placementGraphData['avg'] = placementData['avg_salary'];
    }
    return JSON.stringify(placementGraphData);
  }

  showDownloadBroucherBtns(){
    const{placementData,intershipData, clientCourseId, courseUrl} = this.props;
    const domainName = config().SHIKSHA_HOME;
    let broucherArray = [];
    if((placementData !=null && intershipData !=null) && (intershipData['report_url']!=null && intershipData['report_url']!=null)){
      broucherArray.push(  <a  className='btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic' on="tap:placementReportBrochuer" role="button" tabIndex="0" data-vars-event-name="PLACEMENT_REPORT">Download Placement Report</a>);
    }else if(intershipData !=null && intershipData['report_url'] !=null){
        broucherArray.push(<React.Fragment>
          <section className="" amp-access="NOT validuser" amp-access-hide="1">
              <a className="internshipReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href={domainName+'/muser5/UserActivityAMP/getResponseAmpPage?listingId='+clientCourseId+'&actionType=intern&fromwhere=coursepage'}  data-vars-event-name="INTERN_REPORT">Download Internship Report</a>
          </section>

          <section className="" amp-access="validuser" amp-access-hide="1" tabIndex="0">
                <a className="internshipReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href={domainName+''+courseUrl+'?actionType=intern'}data-vars-event-name="INTERN_REPORT">Download Internship Report
                </a>
          </section>

        </React.Fragment>);
    }else if(placementData && placementData['report_url'] !=null){
      broucherArray.push(<React.Fragment>
        <section className="" amp-access="NOT validuser" amp-access-hide="1">
            <a className="placementReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href={domainName+'/muser5/UserActivityAMP/getResponseAmpPage?listingId='+clientCourseId+'&actionType=placement&fromwhere=coursepage'} data-vars-event-name="PLACEMENT_REPORT">Placement Report</a>
        </section>

        <section className=" " amp-access="validuser" amp-access-hide="1" tabIndex="0">
            <a className="placementReport btn btn-primary color-o color-f f14 font-w7 ask-btn ga-analytic" href={domainName+''+courseUrl+'?actionType=placement'} data-vars-event-name="PLACEMENT_REPORT">
                  Download Placement Report
            </a>
        </section>
      </React.Fragment>)
    }
    return broucherArray;

  }

  getPlacementsData(){
    const domainName = config().SHIKSHA_HOME;
    const{clientCourseId, courseUrl} = this.props;
    return(
       <React.Fragment>
         <ul className='color-6'>
         <section className="" amp-access="NOT validuser" amp-access-hide="1">
             <li>
               <a className="ga-analytic"
                 href={domainName+'/muser5/UserActivityAMP/getResponseAmpPage?listingId='+clientCourseId+'&actionType=intern&fromwhere=coursepage'} data-vars-event-name="INTERN_REPORT">Internship Report</a>
               </li>

         </section>

         <section className="" amp-access="validuser" amp-access-hide="1" tabIndex="0">
             <li>
               <a className="ga-analytic" href={domainName+''+courseUrl+'?actionType=intern'} data-vars-event-name="INTERN_REPORT">Internship Report
               </a>

             </li>
         </section>
         <section className="" amp-access="NOT validuser" amp-access-hide="1">
             <li>
                   <a className="ga-analytic" href={domainName+'/muser5/UserActivityAMP/getResponseAmpPage?listingId='+clientCourseId+'&actionType=placement&fromwhere=coursepage'} data-vars-event-name="PLACEMENT_REPORT">Placement Report</a>
             </li>
         </section>

         <section className=" " amp-access="validuser" amp-access-hide="1" tabIndex="0">
             <li>
               <a className="ga-analytic" href={domainName+''+courseUrl+'?actionType=placement'} data-vars-event-name="PLACEMENT_REPORT">
                   Placement Report
               </a>
             </li>
         </section>
   </ul>
       </React.Fragment>
    )
  }

  render(){
    const{recruitmentCompanies,placementData,intershipData, clientCourseId} = this.props;
    var isDomesticPlacementAvailable =false;
    const domainName = config().SHIKSHA_HOME;
    if(placementData){
      if(placementData['min_salary'] || placementData['median_salary'] || placementData['avg_salary'] || placementData['max_salary'] ){
          isDomesticPlacementAvailable  = true;
      }
    }
    var data =  this.prepareGraphData();
    var count = 0;
    return(
      <section>
       <div className='data-card m-5btm'>
         <h2 className="color-3 f16 heading-gap font-w6">Placements <span className="f13">(as provided by college)</span></h2>
         <div className='card-cmn color-w'>
          {this.props.placementData!=null && this.props.placementData.percentage_batch_placed!=null?
           <div className='m-btm'>
             <p class="padlr0"><strong class="f22 color-6 font-w6">{this.props.placementData.percentage_batch_placed}% </strong><span> of Total Batch placed</span></p>
           </div>:''}
           {(isDomesticPlacementAvailable)?
              <React.Fragment>
                <div className="m-btm graph-div m20 pos-rl">
                    <h3 className="f14 color-6 pos-abs">Domestic Placements  <span className="font-w4 color-6 f13">  (Annual) (In <b>{placementData['salary_unit_name']}</b>)</span></h3>
                    <amp-iframe width="600" height="300"  class="frame-graph" layout="responsive" sandbox="allow-scripts allow-popups" scrolling="no" frameborder="0" src = {domainName+'/mobile_listing5/CourseMobile/getNaukriPlacementChartsAMP/'+data}>
                    </amp-iframe>
                </div>
              </React.Fragment>:''
            }
            {(placementData && (placementData.total_international_offers!=null || placementData.max_international_salary!=null)) ?
          <div className='m-btm graph-div m20 pos-rl'>
            <h3 className='f14 color-6 pos-abs'>International Placements <span className='font-w4 color-3 f13'>(Annual)</span> </h3>
            { placementData.total_international_offers !=null ?
              <div className='tab-cell pad10'>
                <p className='f14 color-6'>Total offers</p>
                <p className='f16 color-6 font-w6'>{placementData.total_international_offers}</p>
              </div> : ''
            }
            { placementData !=null && placementData.max_international_salary !=null ?
              <div className='tab-cell pad10'>
                <p className='f14 color-6'>Maximum Salary</p>
                <p className='f16 color-6 font-w6'>{placementData.max_international_salary_unit_name} {getRupeesDisplayableAmount(placementData.max_international_salary)}</p>
              </div> : ''
            }

          </div>: ''
        }

         {this.showDownloadBroucherBtns()}
        <AmpLightBox id="placementReportBrochuer" data={this.getPlacementsData()} heading='Placement Data' />

           { (recruitmentCompanies !=null && recruitmentCompanies.length >0 )?
            <React.Fragment>
                <h3 className='f14 color-6 font-w6 m-top'>Companies / Recruiters who visited the campus</h3>
                 <input type='checkbox' className='read-more-state hide' id='placement'/>
                 <div className='m-5top read-more-wrap'>
                    {
                      recruitmentCompanies.map((item, index) => {
                        if(index < 10){
                          if(recruitmentCompanies.length == 1) {
                            return(
                              <span key={index} className='hid l-18 f12 color-3'>{item.companyName}</span>
                           )
                          }else {
                             return(
                              <React.Fragment>
                                <span className='hid l-18 f12 color-3' key={`data_${index}`}>{item.companyName}</span>
                                {(!(index == recruitmentCompanies.length-1)) && <b key={`bold_${index}`}className='font-w4 color-c pad3 f12'> | </b>}
                              </React.Fragment>
                            )
                          }
                        }else{
                          if(recruitmentCompanies.length == 1) {
                            return(
                               <span className="hid l-18 f12 color-3 listinline read-more-target" key={`index_${index}`}>{ item.companyName }</span>
                            )
                         } else {
                           return(
                             <React.Fragment>
                               <span className="hid l-18 f12 color-3 listinline read-more-target">{ item.companyName }</span>
                               {(!(index == recruitmentCompanies.length-1)) && <b className="font-w4 color-c pad3 f12 listinline read-more-target" key={`boldl_${index}`}> | </b>}
                             </React.Fragment>
                           )
                          }
                        }
                      })

                   }
                   { recruitmentCompanies.length > 10 ? <label htmlFor="placement" className="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr">View More</label> : '' }
                 </div>
             </React.Fragment> : ''
           }
         </div>
       </div>
      </section>
    )
  }
}

export default PlacementComponentAmp;
