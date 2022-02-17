import React from 'react';
import ReactDOM from 'react-dom';
import APIConfig from './../../../../../config/apiConfig';
import config from './../../../../../config/config';
import { postRequest,getRequest } from '../../../../../application/utils/ApiCalls';
import {cutStringWithShowMore} from '../../../../../application/utils/stringUtility';
import AmpLightBox  from '../../../../../application/modules/common/components/AmpLightbox';


class AluminiComponent extends React.Component{
  constructor(props){
    super(props);
  }

  prepareAlumniData(){
    const { alumniData } = this.props;
    var alumniGraphData = [];
    if(alumniData.salaryBuckets){
      if(alumniData.salaryBuckets[0]){
    alumniGraphData[0] = {};
    alumniGraphData[0]["Exp_Bucket"] = "0-2";
    alumniGraphData[0]["AvgCTC"] = alumniData.salaryBuckets[0]['value'];
    }
          if(alumniData.salaryBuckets[1]){
    alumniGraphData[1] = {};
    alumniGraphData[1]["Exp_Bucket"] = "2-5";
    alumniGraphData[1]["AvgCTC"] = alumniData.salaryBuckets[1]['value'];
    }
          if(alumniData.salaryBuckets[2]){
    alumniGraphData[2] = {};
    alumniGraphData[2]["Exp_Bucket"] = "5+";
    alumniGraphData[2]["AvgCTC"] = alumniData.salaryBuckets[2]['value'];
      }
    }
    return JSON.stringify(alumniGraphData);
  }



  prepareSpecializationLayer(pageType='main'){
      const data = this.props.alumniData.specializationData;
      var otherManagementFlag = false;
      var lastIndex = '';
      var self = this;
      const listItems = new Array();
      for(var index in data){
          var splz = data[index];
          var specializationName = '';
          if( splz.name== "Systems" || splz.name == "#N/A") {
              specializationName = '';
          }
          else if(splz.name == "Other Management") {
              otherManagementFlag = true;
              specializationName = '';
          }
          else if(splz.name == "Marketing") {
              specializationName = 'Sales & Marketing';
          }else if(splz.name == "HR/Industrial Relations"){
                 specializationName = 'Human Resources';
          }else if(splz.name == "Information Technology"){
                 specializationName = 'IT';
          }else{
                 specializationName = splz.name;
          }
          lastIndex = index;
          if(pageType ==='main' && specializationName){
              listItems.push(<li key={index}>
                <label htmlFor={'spec_'+specializationName.split(' ').join('_')} className='block'>{specializationName}</label>

          </li>);
        }else if ( pageType =='companymain' && specializationName ) {
          listItems.push(<li key={index}>
            <label htmlFor={pageType+'_'+specializationName.split(' ').join('_')} className='block'>{specializationName}</label>

      </li>);
    }else if (pageType =='functionmain' && specializationName) {
      listItems.push(<li key={index}>
        <label htmlFor={pageType+"_"+specializationName.split(' ').join('_')} className='block'>{specializationName}</label>

  </li>);
    }

      }
      if(pageType ==='main'){
        return(
          <ul className="ul-list" key="ullist" >
           <li key='all'><label htmlFor={'spec_all'} className='block'>All Specialization</label></li>
           {listItems}
           {(otherManagementFlag)?<li key={lastIndex+1}><label htmlFor={'spec_Other_Management'} className='block'>Other Management</label></li>:''}
       </ul>
        )
      }else if(pageType ==='companymain'){
        return(
        <ul className="ul-list" key="ullist" >
         <li key='all'><label htmlFor={pageType+'_spec_all'} className='block'>All Specialization</label></li>
         {listItems}
         {(otherManagementFlag)?<li key={lastIndex+1}><label htmlFor={pageType+'_spec_Other_Management'} className='block'>Other Management</label></li>:''}
     </ul>);
   }else if (pageType ==='functionmain') {
     return(
     <ul className="ul-list" key="ullist" >
      <li key='all'><label htmlFor={pageType+'_spec_all'} className='block'>All Specialization</label></li>
      {listItems}
      {(otherManagementFlag)?<li key={lastIndex+1}><label htmlFor={pageType+'_spec_Other_Management'} className='block'>Other Management</label></li>:''}
  </ul>);
   }



  }

  getCompaniesData(){
    let data = this.props.alumniData.specializationData;


    let compainesArray = [];
    var otherManagementFlag = false;
    var lastIndex = '';
    var self = this;
    let naukriData = {};
    let functionNaukriData = {};
      var specializationName = 'All Specialization';
    if(specializationName === 'All Specialization'){
      for(var i in data){
          var splz = data[i];
          var companyData = splz.companyData;
          var businessData = splz.functionalAreaData;
          for(var index in companyData){
              var cData = companyData[index];
              naukriData[cData.name] = naukriData[cData.name] || [];
              naukriData[cData.name].push(cData.count);
          }
          for(var index in businessData){
            var fnData = businessData[index];
            functionNaukriData[cData.name] = functionNaukriData[cData.name] || [];
            functionNaukriData[cData.name].push(cData.count);
          }
      }

    }
    var final_response = [];
    var totalAlumni = 0;
    Object.keys(naukriData).forEach(function (key){
        var total_emp = naukriData[key].reduce((a, b) => a + b, 0);
        totalAlumni = totalAlumni + total_emp ;
        final_response.push({'name':key, 'count':total_emp});
    });
    final_response.sort(function(obj1, obj2){
      return obj2.count - obj1.count;
    });
    var functional_FinalData = [];
    Object.keys(functionNaukriData).forEach(function (key){
        var total_emp = functionNaukriData[key].reduce((a, b) => a + b, 0);
        totalAlumni = totalAlumni + total_emp ;
        functional_FinalData.push({'name':key, 'count':total_emp});
    });
    functional_FinalData.sort(function(obj1, obj2){
      return obj2.count - obj1.count;
    });
    var items = final_response.slice(0, 3);

    compainesArray.push(<React.Fragment>
      <input type="radio" name="placement" key={i} value="spec_all" id="spec_all" class="hide st" checked='checked' />
      <div className="table tob1">
          <p className="color-3 f14 f12 font-w6 n-border-color padtb0"><span class="i-block color-6 font-w4">Showing info for</span>  "All Specialization Category"</p>
            <div className="col-prime pad10">
                <p className='f14 color-6 m-btm font-w6'>Companies they work for</p>
                   <ul className='al-ul'>
                     {this.getCompaniesbarData(final_response, 'companydata')}
                   </ul>
                 <label htmlFor='companymain_spec_all' className='block'>
                   <a className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr' on={'tap:companies-view-more-list'} role='button' tabIndex='0' className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr'>View more</a>
                 </label>
            </div>
            <div className="col-prime pad10 border-top">
              <p className='f14 color-6 m-btm font-w6'>Business functions they are in</p>
               <ul className='al-ul'>
                  {this.getCompaniesbarData(functional_FinalData, 'functional')}
                </ul>

              <label htmlFor="functionmain_spec_all" className='block'>
                <a className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr' on={'tap:functions-view-more-list'} role='button' tabIndex='0' className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr'>View more</a>
              </label>
            </div>

      </div>
    </React.Fragment>);

    for(let i in data){
      var splz = data[i];
      if( splz.name== "Systems" || splz.name == "#N/A") {
          specializationName = '';
      }
      else if(splz.name == "Other Management") {
          otherManagementFlag = true;
          specializationName = 'Other Management';
      }
      else if(splz.name == "Marketing") {
          specializationName = 'Sales & Marketing';
      }else if(splz.name == "HR/Industrial Relations"){
             specializationName = 'Human Resources';
      }else if(splz.name == "Information Technology"){
             specializationName = 'IT';
      }else{
             specializationName = splz.name;
      }

      compainesArray.push(
        <React.Fragment>
            <input type="radio" name="placement" key={i} value={"spec_"+specializationName.split(' ').join('_')} id={"spec_"+specializationName.split(' ').join('_')} class="hide st"  />
                <div className='table tob1'>
                    <p className="color-3 f14 f12 font-w6 n-border-color padtb0"><span class="i-block color-6 font-w4">Showing info for</span> {` "${specializationName} Category"`}</p>
                   <div className="col-prime pad10">
                       <p className='f14 color-6 m-btm font-w6'>Companies they work for</p>
                          <ul className='al-ul'>
                            {this.getCompaniesbarData(data[i]['companyData'], 'companydata')}
                          </ul>
                        <label htmlFor={'companymain_'+specializationName.split(' ').join('_')} className='block'>
                          <a className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr' on={'tap:companies-view-more-list'} role='button' tabIndex='0' className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr'>View more</a>
                        </label>
                   </div>
                   <div className="col-prime pad10 border-top">
                     <p className='f14 color-6 m-btm font-w6'>Business functions they are in</p>
                      <ul className='al-ul'>
                         {this.getCompaniesbarData(data[i]['functionalAreaData'], 'functional')}
                       </ul>

                     <label htmlFor={'functionmain_'+specializationName.split(' ').join('_')} className='block'>
                       <a className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr' on={'tap:functions-view-more-list'} role='button' tabIndex='0' className='read-more-trigger block m-top color-b t-cntr f14 font-w6 v-arr'>View more</a>
                     </label>
                   </div>
                </div>

        </React.Fragment>
      );
  }


  return compainesArray;
 }

 getCompaniesbarData(data, dataType, isLayer=false){
   let companiesbarArray = [];

   if(data !=null && data !='undefined'){
      for(let i in data){
        let maximumEmployeesInCompanies = 0;
        let no_of_emps = data[i]['count'];
        if(i==0){
            maximumEmployeesInCompanies = no_of_emps;
        }
        let barWidth = Math.round(Math.abs(((no_of_emps/maximumEmployeesInCompanies)*100)));
        if( i < 3 || isLayer){

          companiesbarArray.push(
            <React.Fragment>

                 <li>
                   <label htmlFor="" className='block f14 color-3 m-3btm'>
                     <strong className='f14 color-3 font-w6'>{data[i]['count']}</strong>
                     <span className='f12 pad3'>Employees</span>  | {data[i]['name']}
                   </label>
                   <div className='ps-bar bg-clr-e border-e4 block pos-rl'>
                     <p className={'status-bar pos-abs'+ (dataType ==='companydata' ? ' bg-f7' : ' bg-5f')}  style={{width:`${barWidth}%`}}></p>
                   </div>
                 </li>

            </React.Fragment>
          )
        }

      }
   }
   return companiesbarArray;
 }



layerHtmlData(data, dataType){
const { alumniData } = this.props;
let alumniCountByPlacements = alumniData.alumniCountByPlacements;

return(
  <amp-lightbox id={dataType+'-view-more-list'} layout='nodisplay'>
    <div className='lightbox'>
      <div className='color-w full-layer'>
        <div className='pos-fix f14 color-3 bck1 pad10 font-w6'>
           Employment details of {alumniCountByPlacements} alumni
           <a className='cls-lightbox color-3 font-w6 t-cntr' role='button' tabIndex='0' on={`tap:${dataType}-view-more-list.close`}>&times;</a>
         </div>
         <div className='col-prime pad10 margin-50'>
           <div className='f12 color-0 pos-rl m-btm'>
                View By Specialization
                <div className='dropdown-primary pos-abs color-w border-all tab-cell pos-abs' on={`tap:${dataType}_spec-list-amp`} role='button' tabIndex='0'>
                  <span className='option-slctd block color-6 f12 font-w6'>All Specializations</span>
                </div>

            </div>
            {this.showLayerData(data, dataType)}

         </div>
      </div>
    </div>
  </amp-lightbox>
);
}

showLayerData(data, datatype){

  let getlayerData = [];
  let naukriData = {};
  let functionNaukriData = {};
  var otherManagementFlag = false;
  var specializationName = 'All Specialization';
  if(specializationName === 'All Specialization'){
    for(var i in data){
        var splz = data[i];
        var companyData = splz.companyData;
        var businessData = splz.functionalAreaData;
        for(var index in companyData){
            var cData = companyData[index];
            naukriData[cData.name] = naukriData[cData.name] || [];
            naukriData[cData.name].push(cData.count);
        }
        for(var index in businessData){
          var fnData = businessData[index];
          functionNaukriData[cData.name] = functionNaukriData[cData.name] || [];
          functionNaukriData[cData.name].push(cData.count);
        }
    }

  }

  var final_response = [];
  var totalAlumni = 0;
  Object.keys(naukriData).forEach(function (key){
      var total_emp = naukriData[key].reduce((a, b) => a + b, 0);
      totalAlumni = totalAlumni + total_emp ;
      final_response.push({'name':key, 'count':total_emp});
  });
  final_response.sort(function(obj1, obj2){
    return obj2.count - obj1.count;
  });
  var functional_FinalData = [];
  Object.keys(functionNaukriData).forEach(function (key){
      var total_emp = functionNaukriData[key].reduce((a, b) => a + b, 0);
      totalAlumni = totalAlumni + total_emp ;
      functional_FinalData.push({'name':key, 'count':total_emp});
  });
  functional_FinalData.sort(function(obj1, obj2){
    return obj2.count - obj1.count;
  });
  if(datatype ==='companies'){
    getlayerData.push(<React.Fragment>
      <input type="radio" name="placement1" key={i} value={"companymain_spec_all"} id={"companymain_spec_all"} class="hide st" checked='checked' />
      <div className="al-ul">
          <p>All Specializations</p>
          <p className='f14 color-6 m-btm font-w6'>Companies they work for</p>

                   <ul className='al-ul m-top'>
                     {this.getCompaniesbarData(final_response, 'companydata', true)}
                   </ul>
      </div>
    </React.Fragment>) ;
  }else{
    getlayerData.push(<React.Fragment>
      <input type="radio" name="placement1" key={i} value={"functionmain_spec_all"} id={"functionmain_spec_all"} class="hide st" checked='checked' />
      <div className="al-ul">
          <p>All Specializations</p>
          <p className='f14 color-6 m-btm font-w6'>Business functions they work for</p>

                   <ul className='al-ul m-top'>
                     {this.getCompaniesbarData(functional_FinalData, 'functional', true)}
                   </ul>
      </div>
    </React.Fragment>)
  }

  for(let i in data){
    var splz = data[i];
    if( splz.name== "Systems" || splz.name == "#N/A") {
        specializationName = '';
    }
    else if(splz.name == "Other Management") {
        otherManagementFlag = true;
        specializationName = 'Other Management';
    }
    else if(splz.name == "Marketing") {
        specializationName = 'Sales & Marketing';
    }else if(splz.name == "HR/Industrial Relations"){
           specializationName = 'Human Resources';
    }else if(splz.name == "Information Technology"){
           specializationName = 'IT';
    }else{
           specializationName = splz.name;
    }
      if(datatype ==='companies'){
        getlayerData.push(
          <React.Fragment>
              <input type="radio" name="placement1" key={i} value={"companymain_"+specializationName.split(' ').join('_')} id={"companymain_"+specializationName.split(' ').join('_')} class="hide st"  />

                     <div className="al-ul">
                       <p className="color-3f14 f12 font-w6 n-border-color"><span class="i-block color-6 font-w4">Showing info for</span> {` "${specializationName} Category"`}</p>
                       <p className='f14 color-6 margin-20 font-w6'>Companies they work for</p>
                            <ul className='al-ul m-top'>
                              {this.getCompaniesbarData(data[i]['companyData'], 'companydata', true)}
                            </ul>
                     </div>


          </React.Fragment>
        )
    }else{
      getlayerData.push(
        <React.Fragment>
            <input type="radio" name="placement1" key={i} value={"functionmain_"+specializationName.split(' ').join('_')} id={"functionmain_"+specializationName.split(' ').join('_')} class="hide st"  />
                   <div className="al-ul">
                     <p className="color-3f14 f12 font-w6 n-border-color"><span class="i-block color-6 font-w4">Showing info for</span> {` "${specializationName} Category"`}</p>
                     <p className='f14 color-6 margin-20 font-w6'>Business functions they work for</p>
                          <ul className='al-ul m-top'>
                            {this.getCompaniesbarData(functional_FinalData, 'functional', true)}
                          </ul>
                   </div>


        </React.Fragment>
      )
    }
  }


  return getlayerData;
}



  render(){
    const domainName = config().SHIKSHA_HOME;
    const { alumniData } = this.props;
    let alumniCountByPlacements = alumniData.alumniCountByPlacements;
    var graphData = this.prepareAlumniData();
    let disclaimer = "Disclaimer: Shiksha alumni salary and employment data relies entirely on salary information provided by individual users on Naukri. To maintain confidentiality, only aggregated information is made available. Salary and employer data shown here is only indicative and may differ from the actual placement data of college. Shiksha offers no guarantee or warranty as to the correctness or accuracy of the information provided & will not be liable for any financial or other loss directly or indirectly arising or related to use of the same.";

      return(

          <React.Fragment>
            {  (Object.keys(alumniData).length !=0) ?
          <section>
                      <div className='data-card m-5btm'>
                          <h2 className='color-3 f16 heading-gap font-w6'>Alumni Employment Stats</h2>
                           <div className='card-cmn color-w'>
                             <p className='f12 color-6 font-w4'>Data based on resumes from <a><i class="cmn-sprite n-logo"></i></a>  , India's no.1 job site</p>
                             <div className='margin-20 pos-rl border-all'>
                                <h3 className='pos-abs f14 color-6 color-w font-w6 pad3 avg-sl'>
                                   Average annual salary details of {alumniData.alumniCountBySalaries} alumni of this course
                                   <span className='f12 color-6 font-w4'>(by work experience) (In <b>INR</b> )</span>
                                </h3>
                                <amp-iframe width="400" height="300" layout="responsive" sandbox="allow-scripts allow-popups" scrolling="no" frameborder="0" src  = {domainName+'/mobile_listing5/CourseMobile/getAlumniStatsChartAmp/'+graphData}></amp-iframe>
                             </div>
                             <div className='card-cm color-w margin-20'>
                                <div className='comp-bus-bar pos-rl m-top border-all'>
                                  <div className='dropdown-primary alum-dt' on='tap:spec-list-amp' role='button' tabIndex='0'>
                                    <span className='option-slctd block color-6 f12 font-w6'>All Specializations</span>
                                    <AmpLightBox onRef={ref => (this.PopupLayer = ref)} data={this.prepareSpecializationLayer('main')} id="spec-list-amp" heading="Specialization" taponPage={true}/>
                                  </div>
                                  <h3 className='pos-abs f14 color-6 color-w font-w6 pad3 emp-al pos-rl'>Employment details of {alumniCountByPlacements}  alumni</h3>
                                   <div className="margin-30">
                                      {this.getCompaniesData()}
                                  </div>

                                </div>
                             </div>
                             <input type="checkbox" className="read-more-state hide" id="naukriDesclaimer" />
                             <p className='read-more-wrap' id='naukriDesclaimer' key="naukriDesclaimer" dangerouslySetInnerHTML={{ __html : cutStringWithShowMore(disclaimer ,83,'naukriDesclaimer','more',true,true,false,true)}}></p>
                           </div>
                      </div>
                       {this.layerHtmlData(alumniData.specializationData,'companies')}
                       {this.layerHtmlData(alumniData.specializationData,'functions')}
                       <AmpLightBox onRef={ref => (this.PopupLayer = ref)} data={this.prepareSpecializationLayer('companymain')} id="companies_spec-list-amp" heading="Specialization" taponPage={true}/>
                       <AmpLightBox onRef={ref => (this.PopupLayer = ref)} data={this.prepareSpecializationLayer('functionmain')} id="functions_spec-list-amp" heading="Specialization" taponPage={true}/>
                  </section> : ''}
                </React.Fragment>

      )
    }
}


export default AluminiComponent;
