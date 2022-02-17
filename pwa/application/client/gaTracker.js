import React, { Component } from 'react';
import Analytics from './../modules/reusable/utils/AnalyticsTracking';

export default function gaTracker(WrappedComponent, pageReq=null, options = {}, data = null) {
  const trackPage = (page) => {
    Analytics.set({
      page,
      ...options
    });
    Analytics.pageview(page);
  };

  const HOC = class extends Component {
    componentDidMount() {
      var page = this.props.location.pathname;
      if(this.props.location.search != '')
        page += this.props.location.search;
      trackPage(page);
    }

    componentWillReceiveProps(nextProps) {
      var currentPage = this.props.location.pathname;
      var nextPage = nextProps.location.pathname;
      if(this.props.location.search != '')
        currentPage += this.props.location.search;
      if(nextProps.location.search != '')
        nextPage += nextProps.location.search;
      if (currentPage !== nextPage) {
        trackPage(nextPage);
      }
    }

    render() {
      return <WrappedComponent {...this.props} hocData={data} pageReq={pageReq} />;
    }
  };

  return HOC;
}
