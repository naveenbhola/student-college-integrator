import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {withRouter, Link} from 'react-router-dom';

class Layout extends Component {
	componentDidMount() {
	}

	componentWillReceiveProps(nextProps) {
		if(window && window.mobileApp && nextProps.location.pathname =='/'){
		  if(typeof (WebView) !='undefined'){
		    WebView.loadHomePage(true);
		  }
		}
	}

	render() {
		return this.props.children;
	}
}

export default withRouter(Layout);