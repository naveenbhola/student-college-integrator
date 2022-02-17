import React, {Component} from 'react';
import {connect} from 'react-redux';

class AskBtn extends Component {
  render(){
    return (
      <a href="javascript:void(0);" onClick={this.props.onClick}>{this.props.ctaLabel}</a>
    );
  }
}

export default AskBtn;
