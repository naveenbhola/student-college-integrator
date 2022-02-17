import React,{Component} from 'react';
import { addingDomainToUrl } from '../../../../../application/utils/commonHelper';
import config from './../../../../../config/config';


class ScholarshipWidget extends React.Component{
  constructor(props){
    super(props);
  }
  render(){
    var scholarshipUrl = this.props.instituteUrl;
    scholarshipUrl = scholarshipUrl+"/scholarships";
    scholarshipUrl = addingDomainToUrl(scholarshipUrl,config().SHIKSHA_HOME);
    return(
      <div className='data-card m-5btm'>
        <div className="card-cmn color-w">
          <h2 className='f14 color-3 font-w6 m-btm'>Want to know more about {this.props.instituteName} Scholarships details?</h2>
          <a className='btn btn-secondary color-w color-b f14 font-w6 ga-analytic' data-vars-event-name="READ_SCHOLARSHIPS" href={scholarshipUrl} role='button' tabIndex='0'>Read About Scholarships</a>
        </div>
      </div>
    )
  }
}

export default ScholarshipWidget
