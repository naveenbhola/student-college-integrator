import React  from 'react';
import PropTypes from 'prop-types';
import {showRegistrationFormWrapper} from '../../../../utils/regnHelper';

class UserRegistration extends React.Component {
  constructor(props){
    super(props);
    this.doRegistration = this.doRegistration.bind(this);
  }

  doRegistration = () => {
    if(this.props.deviceType == 'desktop'){
        showRegistrationFormWrapper(this.props.formData);
    }else{
        window.location.href = this.props.formData; // AMP login and registration url
    }
  }

  render() {
    let ctaHtml = '';
    switch(this.props.ctaTag){
      case 'button':
        ctaHtml = <button id={this.props.buttonId} className={this.props.className+' '+this.props.buttonIdentifier} onClick={this.doRegistration}>{this.props.ctaText}</button>;
        break;
    }
    return (
        ctaHtml
    );
  }
}
UserRegistration.defaultProps = {
  buttonId : '',
  ctaTag : 'button',
  className : 'button button--orange',
  ctaText : 'Get Updates',
  ctaType : 'other',
  deviceType : 'mobile',
  buttonIdentifier : '',
  url : '/muser5/UserActivityAMP/getLoginAmpPage?fromwhere=pwa'
};

UserRegistration.propTypes = {
  buttonId: PropTypes.string,
  ctaTag: PropTypes.string,
  className: PropTypes.string,
  ctaText: PropTypes.string,
  ctaType: PropTypes.string,
  deviceType: PropTypes.string,
  buttonIdentifier: PropTypes.string
};
export default UserRegistration;
