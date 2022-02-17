import React from 'react';
import './../assets/Registration.css';
import {formatNumber} from '../../../../../application/utils/commonHelper';
import {showRegistrationFormWrapper, showLoginFormWrapper, signOutUserWrapper} from '../../../../utils/regnHelper';

class Registration extends React.Component
{
   constructor(props)
   {
      super(props);
   }

    showRegistrationForm(){
      let formData = {
        trackingKeyId : this.props.tarckingKey,
        callbackFunction : 'callBackAfterRegn',
        callbackFunctionParams : {}
      };
      showRegistrationFormWrapper(formData);
    }

   render()
   {
      return (
            <React.Fragment>
            <div className="pwa_card">
               <div className="sub_block">
                 <h3 className="signup-h3 text-center">Taking an Exam? Selecting a College?</h3>
                 <p className="inf-txts text-center">Find insights &amp; recommendations on colleges and exams that you <strong>won't</strong> find anywhere else</p>
                <div className="text-center">
                  <button className="nw-btn" onClick={this.showRegistrationForm.bind(this)}>Sign Up &amp; Get Started </button>
                </div>
              { (this.props.hpParams && this.props.hpParams!=null) ?
              <div>  
              <p className="background z-ind text-center"><span>On Shiksha, get access to</span></p>
              <ul className="inf-li">
                  {this.props.hpParams.instCount>0?<li><strong>{formatNumber(this.props.hpParams.instCount)}</strong> Colleges</li>:''}
                  {this.props.hpParams.examCount>0?<li><strong>{formatNumber(this.props.hpParams.examCount)}</strong> Exams</li>:''}
                  {this.props.hpParams.reviewsCount>0?<li><strong>{formatNumber(this.props.hpParams.reviewsCount)}</strong> Reviews</li>:''}
                  {this.props.hpParams.questionsAnsweredCount>0?<li><strong>{formatNumber(this.props.hpParams.questionsAnsweredCount)}</strong> Answers</li>:''}
              </ul>
              </div>
                : null}
            </div>     
          </div>
            </React.Fragment>
         )
   }
}
export default Registration;

