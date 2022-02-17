import PropTypes from 'prop-types'
import React from 'react';
import Loadable from 'react-loadable';
import ContentLoader from '../instituteChildPages/utils/contentLoader';
import  './assets/commonPdf.css';

const AllCoursePage = Loadable({
  loader: () => import('./AllCoursePdf'/* webpackChunkName: 'AllCoursePdf' */),
  loading: ContentLoader,
});

const AdmissionPage = Loadable({
  loader: () => import('./AdmissionPdf'/* webpackChunkName: 'AdmissionPdf' */),
  loading: ContentLoader,
});

const CutOffPage = Loadable({
  loader: () => import('./CutOffPdf'/* webpackChunkName: 'AdmissionPdf' */),
  loading: ContentLoader,
});


class ListingChildPages extends React.Component
{
  constructor(props)
    {
      super(props);
    }

   componentDidMount(){
   } 


  render(){
    console.log("Inside ListingChildPages");
    const isPdfGenerator = true;
    const {location} = this.props;
    var isDesktop = false;
    if(this.props.hocData == 'childPageDesktop'){
        isDesktop = true;
    }

    if(location.pathname.indexOf('/admission') != -1 )
    {
      return(
        <div className="pdf--wrapper">
            <AdmissionPage location={location} match={this.props.match} isPdfGenerator={isPdfGenerator} />
            </div>
        )
    }else if(location.pathname.indexOf('/courses') != -1 ){
        return(
          <div className="pdf--wrapper">
             <AllCoursePage location={location} match={this.props.match} />
          </div>
        )
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
         return(
          <div className="pdf--wrapper">
            <CutOffPage location={location} match={this.props.match} isPdfGenerator={isPdfGenerator}/>  
          </div>
          )
    }

  }

}

export default ListingChildPages;

ListingChildPages.propTypes = {
  hocData: PropTypes.any,
  location: PropTypes.any,
  match: PropTypes.any
}