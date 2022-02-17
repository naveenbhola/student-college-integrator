import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';

import {parseQueryParams} from '../../../../utils/commonHelper';
import {showResetPasswordLayerWrapper} from '../../../../utils/regnHelper';

class ResetPasswordLayer extends Component {
  componentDidMount(){
    let params = {};
    if(this.props.location.search != ''){
      params = parseQueryParams(this.props.location.search);
      if(params.resetpwd == 1){
        showResetPasswordLayerWrapper(params.uname, params.usremail, '', params.usrgrp);
      }
    }
  }
  render(){
    return null;
  }
}

export default withRouter(ResetPasswordLayer);
