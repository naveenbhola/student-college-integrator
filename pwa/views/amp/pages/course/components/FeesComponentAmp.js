import React, {Component} from 'react';
import {cutStringWithShowMore} from '../../../../../application/utils/stringUtility';
import {capFirstLetterInWord} from '../../../../../application/modules/listing/course/utils/listingCommonUtil';
import ScholarshipWidget from './Scholarships';
import config from './../../../../../config/config';
// import Analytics from '../../../../../application/modules/reusable/utils/AnalyticsTracking';

class FeesComponentAmp extends React.Component{
  constructor(props)
	{
		super(props);
		this.state ={
            categoryName : 'general',
            displayCatName : 'General',
            feeStructureData : {}
        };
	}
  isActive(catName)
    {
        return this.state.categoryName == catName;
    }
    generateHtml(feesData)
      {
          const domainName = config().SHIKSHA_HOME;
          let html = [];
          var counter = 0;
          let getFeeDetailHtml = [];
          getFeeDetailHtml.push(<section key="getFeedtl" className="wd50 i-block" amp-access="NOT bMailed" amp-access-hide="1">
                            <a className="btn btn-primary color-o color-f f14 font-w7 wd50 tab-cell ga-analytic" href={domainName+"/muser5/UserActivityAMP/getResponseAmpPage?listingId="+this.props.courseId+"&actionType=checked_fee_details&fromwhere=coursepage&pos=sticky&fromFeeDetails=true"} data-vars-event-name="Get_Fee_Details" title={this.props.instituteName}>Get Fee Details</a>
                      </section>)
          for(var feeKey in feesData['feesInfo'])
          {
              let htmlContent = [];
              htmlContent.push(<h3 key="fee-h" className='color-6 pos-rl f14 font-w4'>Total Fees</h3>);
              let viewStrHtml = [];

                  viewStrHtml.push(
                    <React.Fragment>
                       <a key="fees-struc"  className='block f14 color-b ga-analytic' data-vars-event-name="FEE_VIEWFEE_STRUCTURE" on={`tap:${feeKey}-fee-data`}> View Fees Structure <i className='blu-arrw'></i></a>
                       <amp-lightbox id={feeKey+'-fee-data'} layout="nodisplay">
                         <div className="lightbox">
                            <a className="cls-lightbox color-f font-w6 t-cntr" on={`tap:${feeKey}-fee-data.close`} role="button" tabIndex="0">&times;</a>
                               <div className="m-layer">
                                    <div className="min-div color-w pad10">
                                       <h3 className="color-3 font-w6 m-btm f14">Fee Structure</h3>
                                        {this.generateFeeStructureLayer(feeKey,feesData['feesInfo'][feeKey],feesData['otpAndHostelFees'],feesData['totalIncludes'])}
                                     </div>
                               </div>
                         </div>
                       </amp-lightbox>
                   </React.Fragment>
                );

              htmlContent.push(<div className="getFeedtl"><p key="fee-val"><strong  className="color-3 f26 font-w6 m-3btm">{feesData['feesInfo'][feeKey]} </strong>{getFeeDetailHtml} </p></div>);

              if(typeof feesData.totalIncludes != 'undefined' && feesData.totalIncludes != null)
              {
                  htmlContent.push(<React.Fragment><p className='color-6 f12 l-16 font-w4 word-break' amp-access="bMailed" amp-access-hide="1" key="fee-comp">(Fees Components : {feesData.totalIncludes})</p></React.Fragment>);

              }
             // htmlContent.push(<React.Fragment>{getFeeDetailHtml}</React.Fragment>);
              htmlContent.push(<React.Fragment><div amp-access="bMailed" amp-access-hide="1">{viewStrHtml}</div></React.Fragment>);
              var otpHostelHtml = [];
              var otpHtml = [];
              var hostelHtml = [];
              if(typeof feesData['otpAndHostelFees'] != 'undefined')
              {
                  if(typeof feesData['otpAndHostelFees'][feeKey]['otp'] != 'undefined' && feesData['otpAndHostelFees'][feeKey]['otp'].trim() != '')
                  {
                      otpHtml.push(<div className='tab-cell' key="otp">
                             <p key="otp-label" className='f14 color-6 font-w4'>One Time Payment</p>
                              <p key="otp-val-stng" className='pos-rl fee-c'>
                                <strong className='f16 color-3 font-w6'> {feesData['otpAndHostelFees'][feeKey]['otp']} </strong>
                                <a className='pos-rl' on='tap:one-time-pay' role='button' tabIndex='0'><i className="cmn-sprite clg-info i-block v-mdl"></i></a>

                              </p>
                    </div>);
                  }
                  if(typeof feesData['otpAndHostelFees'][feeKey]['hostel'] != 'undefined' &&feesData['otpAndHostelFees'][feeKey]['hostel'].trim() != '')
                  {
                          hostelHtml.push(<div className='tab-cell' key="hostel">
                              <p className='f14 color-6 font-w4'>Hostel Fees (Yearly)</p>
                              <p className="pos-rl">  <strong className='f16 color-3 font-w6'>{feesData['otpAndHostelFees'][feeKey]['hostel']}</strong></p>
                          </div>);
                  }

              }
              otpHostelHtml.push(
                <React.Fragment>
                { (otpHtml.length !=0 || hostelHtml != 0) ? <div className='margin-20 table fee-tab'>
                  {otpHtml}{hostelHtml}
                </div>: ''}

              </React.Fragment>);
              counter ++;
              html.push(
                <React.Fragment>
                   <input type="radio" name="feesTypes"  id={`feeFilt_${feeKey}`} className="hide disp"  checked={(this.isActive(feeKey) || feeKey === 'noneAvailable') ? 'checked' : null} key={feeKey+"-feesdata"} />
                    <div className= 'table tob1' >
                        <p className="color-3 f13 font-w6 txt-cptl"><span className="color-6 font-w4">Showing Info for </span>{`"${feeKey.split('_').join(' ')} Category"`}   </p>
                        {htmlContent}
                        <div amp-access="bMailed" amp-access-hide="1">{otpHostelHtml}</div>
                    </div>

                </React.Fragment>);
          }
          return html;
      }


    generateFeeStructureLayer(categoryName,totalFees,jsonData,totalIncludesHtml)
      {
          var html = [];
          var durationHtml = [];
          var isHtmlExist = false;
          var totalincludesData=[];
          if(typeof totalIncludesHtml != 'undefined' && totalIncludesHtml != null){
            totalincludesData.push(<p className='color-6 f14 word-break'>{totalIncludesHtml}</p>)
          }
          if(jsonData && typeof jsonData[categoryName]['durationWise'] != 'undefined')
          {
              for(var duration in jsonData[categoryName]['durationWise'])
              {
                  var periodType = jsonData[categoryName]['durationWise'][duration].periodType;
                  durationHtml.push(<li key={categoryName+'-'+'duration'+ duration}>
                      <span className="year f14 color-3">{capFirstLetterInWord(periodType) +' '+parseInt(duration)}</span>
                      <span className="t-fee f14 color-3">{jsonData[categoryName]['durationWise'][duration].value}</span>
                  </li>);
                  isHtmlExist = true;
              }
          }

              durationHtml.push(<li key='total-duration' className='total-sum'>
                                  <span className='year'>Total fees</span>
                                  <span className='full-fee font-w7 f14 color-6'>{totalFees}</span>
                          </li>);
          html.push(<div key={categoryName+'layer'}>
                      <div>
                      <ul className='speech-cont m-btm'>{durationHtml}</ul>
                      { totalincludesData.length !=0 ? <p className="color-6 f14">Fee components</p> : ''}
                      {totalincludesData}
                      </div></div>);
          return html;
      }
      generateCategorySelectionLayer()
      {
          var html = [];
          var self = this;
          const categoryNameMapping = this.props.feesData.categoryNameMapping;
          if(Object.keys(categoryNameMapping).length > 0) {
              var categories = Object.keys(categoryNameMapping).sort();
              var layerHtml = categories.map(function(index){
                  return <li key={index}><label htmlFor={`feeFilt_${index}`}  className="block">{categoryNameMapping[index]} Category</label></li>
              });
              html.push(<ul key="color-6" className='ul-list'>{layerHtml}</ul>);
          }
          return html;
      }

  render(){
    const {feesData} = this.props;
    var html = this.generateHtml(feesData);
    const categoryNameMapping = this.props.feesData.categoryNameMapping;
    return(
      <section>
      <div className="data-card m-5btm" id="fee">
        <h2 className="color-3 f16 heading-gap font-w6 pos-rl">
            Fees
            { Object.keys(categoryNameMapping).length > 1 ? <div className="dropdown-primary tab-cell" on="tap:fee-cat-list" role="button" tabIndex="0">
                <span className="option-slctd block color-6 f12 font-w6 ga-analytic" id="optnSlctd" data-vars-event-name="FEE_CHOOSE_CATEGORY">Choose Category</span>
            </div> : ''}

        </h2>
        <div className="card-cmn color-w">
               {html}
               { typeof feesData['description'] != 'undefined' && feesData['description'].length > 0 &&
               <div className="m-top" amp-access="bMailed" amp-access-hide="1">

                     <React.Fragment>
                       <input type="checkbox" className='read-more-state hide' id={"fees_desc_1"}/>
                       <p className='read-more-wrap word-break' dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(feesData['description'],210,"fees_desc_1",'more',true,false,false,true)}}></p>
                     </React.Fragment>

                </div>
              }
               {
                   feesData['showDisclaimer'] &&  <div className="margin-20 f12 color-9 font-w4"> <p className='f12 color-9 font-w4'>Disclaimer: Total fees has been calculated bases year/semester 1 fees provided by the college. The actual fees may vary.</p></div>
               }
        </div>


        <amp-lightbox id="fee-cat-list"  layout="nodisplay" scrollable>
           <div className="lightbox" on="tap:fee-cat-list.close" role="button" tabIndex="0">
              <a className="cls-lightbox color-f font-w6 t-cntr" >&times;</a>
                 <div className="m-layer">
                      <div className="min-div color-w catg-lt">
                          {this.generateCategorySelectionLayer() }
                       </div>
                   </div>
           </div>
        </amp-lightbox>
        <amp-lightbox id="one-time-pay"  layout="nodisplay" scrollable>
           <div className="lightbox">
              <a className="cls-lightbox color-f font-w6 t-cntr" on="tap:one-time-pay.close">&times;</a>
                 <div className="m-layer">
                      <div className="min-div color-w catg-lt pad10">
                          <div className='m-btm padb'>
                            <strong className='block m-btm color-3 f14 font-w6'>One Time Payment</strong>
                            <p className='color-3 l-18 f12'>Note - Applicable if you want to pay the complete fees at one go.</p>
                           </div>
                       </div>
                   </div>
           </div>
        </amp-lightbox>
      </div>
      <ScholarshipWidget instituteName={this.props.instituteName} instituteUrl={this.props.instituteUrl} />
      </section>
    )
  }
}

export default FeesComponentAmp;
