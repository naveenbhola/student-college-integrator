import PropTypes from 'prop-types'
import React from 'react';
import Loadable from 'react-loadable';
import ContentLoader from './../../../listing/instituteChildPages/utils/contentLoader';
import ContentLoaderMain from './../../../listing/instituteChildPages/utils/ContentLoaderMain';
import DesktopLoader from './../../../listing/instituteChildPages/components/AllChildPageContentLoader';
import AdmissionDesktopLoader from './../../../listing/instituteChildPages/components/AdmissionPageDesktopLoader';

const AllCoursePage = Loadable({
  loader: () => import('./AllChildPages'/* webpackChunkName: 'ALLCOURSEPAGE' */),
  loading: ContentLoader,
});

const AdmissionPage = Loadable({
  loader: () => import('./AdmissionPage'/* webpackChunkName: 'AdmissionPage' */),
  loading: ContentLoader,
});

const PlacementPage = Loadable({
  loader: () => import('./PlacementPage'/* webpackChunkName: 'PlacementPage' */),
  loading: ContentLoaderMain,
});

const CutOffPage = Loadable({
  loader: () => import('./CutOffPage'/* webpackChunkName: 'PlacementPage' */),
  loading: ContentLoaderMain,
});

const AllCoursePageDesktop = Loadable({
  loader: () => import('./AllCoursePageDesktop'/* webpackChunkName: 'AllCoursePageDesktop' */),
  
  loading: DesktopLoader,
});

const AdmissionPageDesktop = Loadable({
  loader: () => import('./AdmissionPageDesktop'/* webpackChunkName: 'AdmissionPageDesktop' */),
  loading: AdmissionDesktopLoader,
});

const PlacementPageDesktop = Loadable({
  loader: () => import('./PlacementPageDesktop'/* webpackChunkName: 'PlacementPageDesktop' */),
  loading: ContentLoaderMain,
});

const CutOffPageDesktop = Loadable({
  loader: () => import('./CutOffPageDesktop'/* webpackChunkName: 'PlacementPageDesktop' */),
  loading: ContentLoaderMain,
});


class AllChildPageMain extends React.Component
{
  constructor(props)
    {
      super(props);
    }

   componentDidMount(){
   } 


  render(){

    const {location} = this.props;
    var isDesktop = false;
    if(this.props.hocData == 'childPageDesktop'){
        isDesktop = true;
    }

    if(location.pathname.indexOf('/admission') != -1 )
    {
      if(isDesktop){
         return(
            <AdmissionPageDesktop {...this.props} />  
          )
      }else{
      return(
            <AdmissionPage {...this.props}  />        
        )
      }
    }else if(location.pathname.indexOf('/courses') != -1 ){
      if(isDesktop){
         return(
            <AllCoursePageDesktop {...this.props} />  
          )
      }else{
        return(
            <AllCoursePage {...this.props} />
        )
    }
    }else if(location.pathname.indexOf('/placement') != -1 ){
      if(isDesktop){
         return(
            <PlacementPageDesktop location={location} match={this.props.match} />  
          )
      }else{
        return(
            <PlacementPage location={location} match={this.props.match} />
        )
      }
    }else if(location.pathname.indexOf('/cutoff') != -1 ){
      if(isDesktop){
         return(
            <CutOffPageDesktop location={location} match={this.props.match} />  
          )
      }else{
        return(
            <CutOffPage location={location} match={this.props.match} />
        )
    }
  }
}

}

export default AllChildPageMain;

AllChildPageMain.propTypes = {
  hocData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any
}