import React, { Component } from 'react';
import CustomFormLayer from './CustomFormLayer';

class OTPLayer extends Component {

	constructor(props) {
		super(props);
		this.state = {
			showOTPLayer : false
		}
		this.renderOTPLayerHtml = this.renderOTPLayerHtml.bind(this);
	}

	componentWillReceiveProps(nextProps) {
		
		if(nextProps.show == true) {
			this.setState({showOTPLayer: true});
		}
		if(nextProps.show == false) {
			this.setState({showOTPLayer: false});
		} 

	}

	render() {

		if(!this.state.showOTPLayer) {
			return null;
		}
		
		const otpLayerHTML    = this.renderOTPLayerHtml();
		const otpLayerHeading = 'Verify Mobile Number';

		return (
			<CustomFormLayer data={otpLayerHTML} heading={otpLayerHeading} isOpen={this.state.showOTPLayer} onClose={this.closeOTPLayer.bind(this)} regFormId={this.props.regFormId} />
			);

	}

	componentDidUpdate() {
		
		if(this.state.showOTPLayer) {

			if (typeof document.getElementById("one-time-pass_"+this.props.regFormId) != 'undefined' && document.getElementById("one-time-pass_"+this.props.regFormId) != null) {

				var input = document.getElementById('one-time-pass_'+this.props.regFormId);
				
			    input.addEventListener('focus', function(event) {
			        this.parentNode.classList.add('focused');
					(this.value != '')?(this.parentNode.classList.add('filled')):(this.parentNode.classList.remove('filled'));
			    });
				input.addEventListener('blur', function(event) {
			        this.parentNode.classList.remove('focused');
					(this.value != '')?(this.parentNode.classList.add('filled')):(this.parentNode.classList.remove('filled'));
			    });

			}

		}
		
	}


	renderOTPLayerHtml() {

		var isdCode = this.props.isdCode;
		isdCode     = isdCode.split("-");
		isdCode     = isdCode[0];
		var heading = (this.props.formHeading) ? this.props.formHeading : 'proceed';

		return (
			<React.Fragment>
            {
                !this.props.isLoggedInUser &&
                <div>
                	<p className="field-title">Your account is created on Shiksha.</p>
                    <p className="field-title">To {heading}, verify your mobile number using One Time Password (OTP) sent on <span id="otp-mbl">{"+"+isdCode+"-"+this.props.mobile}</span> <a href="javascript:void(0);" id="changeMobile" className="lyrEdit changeMobile" onClick={this.changeMobileClickHandler.bind(this)}><i className="editIcn"></i> Change Number</a>
                    </p>
                </div>
            }
            {
                this.props.isLoggedInUser &&
                <div>
                    <p className="field-title">One Time Password (OTP) has been sent to your mobile number <span id="otp-mbl">{"+"+isdCode+"-"+this.props.mobile}</span> <a href="javascript:void(0);" id="changeMobile" className="lyrEdit" onClick={this.changeMobileClickHandler.bind(this)}><i className="editIcn"></i> Change Number</a>
					</p>
                </div>
			}
                <div className="reg-form invalid" id={"otp_block_"+this.props.regFormId}>
                    <div className="ngPlaceholder">Enter OTP</div>
                    <input className="ngInput" type="tel" name="one-time-pass" id={"one-time-pass_"+this.props.regFormId} minLength="1" maxLength="4" onInput={this.otpCheck.bind(this)} />
                    <div className="input-helper">
                        <div className="up-arrow"></div>
                        <div id={"otp_error_"+this.props.regFormId} className="helper-text">Please Enter OTP.</div>
                    </div>
                </div>
                <div className="reg-form invalid invld-otp" id={"sendOTP_"+this.props.regFormId}>
                    <p className="ques-txt-otp flLt">Did not receive OTP?</p>
                    <a href="javascript:void(0);" className="otp-blk rsndclr rsnd-otp flRt" onClick={this.resendClickHandler.bind(this)}>Resend OTP
	                    <span className="input-helper">
	                        <span className="up-arrow"></span>
	                        <span className="helper-text" id="otp-msg">OTP Sent</span>
	                    </span>
                    </a>
                </div>
                <div className="otp-rst-lgn">
                    <a className="reg-btn usr-vfy-auth" href="javascript:void(0);" onClick={this.verifyClickHandler.bind(this)}>Verify</a>
                </div>
            </React.Fragment>
		);
	}

	closeOTPLayer(layerClose) {
		
		if(layerClose) {
			this.props.trackEventHandler(this.props.trackingKeyId, 'P2_OTP_ClosePage_Link');
		}
		this.props.closeOTPLayer();
		this.props.closeResponseLayer();
		this.setState({showOTPLayer: false});
		
	}

	otpCheck() {
		
		var input     = document.getElementById('one-time-pass_'+this.props.regFormId);
		var nonNumReg = /[^0-9]/g;
  		input.value   = input.value.replace(nonNumReg, '');		
		if (input.value.length > input.maxLength) {
      		input.value = input.value.slice(0, input.maxLength);
    	}

  	}

  	verifyClickHandler() {
  		
  		this.props.trackEventHandler(this.props.trackingKeyId, 'P2_Verify_OTP_Button_Click');
  		this.props.authenticateOTP();

  	}

  	resendClickHandler() {

  		this.props.trackEventHandler(this.props.trackingKeyId, 'Resend_OTP_Link_Click');
  		this.props.resendOTPCall();

  	}

  	changeMobileClickHandler() {

  		this.props.trackEventHandler(this.props.trackingKeyId, 'Change_Mobile_Number_Link_Click');
  		this.props.changeMobileCall();
		
  	}

}

export default OTPLayer;
