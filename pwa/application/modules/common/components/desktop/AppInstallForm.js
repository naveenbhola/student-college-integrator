import React, {Component} from 'react';
import { connect } from 'react-redux';

import { postRequest, getRequest } from '../../../../utils/ApiCalls';
import Formsy from 'formsy-react';
import MyInput from './mobileNumberInput';

class AppInstallForm extends Component {
  constructor(props){
    super(props);
    this.state = {
      canSubmit: false,
      formSubmitted: false
    };
  }
  submit(formData){
    this.disableButton();
    let axiosConfig = {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      withCredentials: false
    }
    let postData = {'phoneNo' : formData.phoneNumberForApp, sessionId : window.sessionID};
    postRequest(this.props.config.SHIKSHA_HOME+'/messageBoard/MsgBoard/submitPhoneNoForApp', postData, 'desktop', axiosConfig).then((response) => {
      this.setState({formSubmitted : true});
    }).catch(function(err){});
  }
  enableButton(){
    this.setState({ canSubmit: true });
  }
  disableButton(){
    this.setState({ canSubmit: false });
  }
  render(){
    let htmlToBeShown = (
      <Formsy onValidSubmit={this.submit.bind(this)} onValid={this.enableButton.bind(this)} onInvalid={this.disableButton.bind(this)}>
      <li>
        <MyInput
          className="phone-field"
          placeholder="Enter 10 digit mobile number"
          name="phoneNumberForApp"
          value=""
          validations={{isNumeric:true, isLength:10, customValidation: function(values, value){
            var regExp = /^[7-9][0-9]*$/;
            return regExp.test(value) ? true : (value.trim()=='') ? true : 'Mobile number should start with 7, 8 or 9';
          }}}
          validationError="Please enter your 10 digit mobile number."
          required
        />
      </li>
      <li>
        <button type="submit" className="submit-btn" disabled={!this.state.canSubmit}>Submit</button>
      </li>
      </Formsy>
    );
    if(this.state.formSubmitted){
      htmlToBeShown = (
        <li>
          <p style={{color: '#3E4847', marginTop: '4px', position: 'relative'}}>Message sent. You will receive the App download link on your mobile number shortly.</p>
        </li>
      );
    }
    return (
      <React.Fragment>
        <div className="installApp-banner" id="installAppBanner" style={{top: '100px', left: '317px', display: 'block'}}>
          <div className="install-app-fields">
              <ul id="_form">
                {htmlToBeShown}
              </ul>
          </div>
          <a href="javascript:void(0);" onClick={this.props.whenAppInstallFormClosed} className="banner-rmv-mark">×</a>
        </div>
        <div className="layer-bg" id="layerBg" style={{display: 'block'}}></div>
      </React.Fragment>
    );
  }
}

function mapStateToProps(store){
  return {
      config : store.config
  }
}

export default connect(mapStateToProps)(AppInstallForm);
