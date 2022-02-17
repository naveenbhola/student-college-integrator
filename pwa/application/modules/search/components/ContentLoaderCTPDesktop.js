import React from 'react';

class ContentLoaderCTPDesktop extends React.Component {
    
    constructor(props){
        super(props);
    }

    getCTPFilterLoader(){
      let filterList = [];
      for(let i=0;i<=5;i++){
      let filterLoader = (<div key={"filter_loader" + i} className="filter-block">
             <h2 className="f14_bold"><span className="loader-line shimmer wdt85"></span></h2>
             <div className="filter-content">
                <div className="fix-scroll">
                    <ul className="sidebar-filter">
                      <li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
                        <span className="loader-line shimmer"></span></label>
                      </li>
                      <li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
                        <span className="loader-line shimmer wdt85"></span></label>
                      </li>
                      <li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
                        <span className="loader-line shimmer"></span></label>
                      </li>
                      <li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
                        <span className="loader-line shimmer wdt85"></span></label>
                      </li>
                      <li className="enable"> <label className="checkbox-label" htmlFor="chck-city-278">
                        <span className="loader-line shimmer"></span></label>
                      </li>
                    </ul>
                </div>
             </div>
          </div>);
          filterList.push(filterLoader);
      }       
      return(
          <React.Fragment>
            <label className="nav_main_head">Filters </label>
            {filterList}
          </React.Fragment>
      );
    }
    getCTPContentLoader(){
      let toupleList = [];
      for(let i=0;i<=5;i++){
      let toupleListLoader = (
      <div key={"touple_loader" + i}  className="ctpSrp-tuple">
                  <div className="ctp-SrpLst">
                     <div className="ctp-SrpDiv">
                        <div className="ctpSrp-Lft"><a >
                              <div className="loader-line shimmer thumbImg"></div>
                        </a></div>
                        <div className="ctpSrp-Rgt">
                           <p className="ctpIns-tl"><a ><span className="loader-line shimmer"></span></a></p>
                           <p className="ctp-ctyy"><span className="loader-line shimmer wdt85"></span></p>
                           <p><span className="loader-line shimmer wdt85"></span></p>
                           
                        </div>
                     </div>
                     <div id="courseTuple">
                        <div id="299267" className="none">
                           <div className="flexi-div">
                              <div className="ctp-SrpDiv">
                                 <a  className="fnt-w6"><span className="loader-line shimmer"></span></a>
                                 <div className="ctp-detail">
                                    <ul className="full-shimer">
                                       <li><span className="loader-line shimmer"></span></li>
                                       <li className="seprator"><span> | </span></li>
                                       <li><span className="loader-line shimmer"></span></li>
                                       <li className="seprator"><span> | </span></li>
                                       <li><span className="loader-line shimmer"></span></li>
                                    </ul>
                                 </div>
                                 <div className="ctp-detail">
                                    <label><span className="loader-line shimmer"></span></label>
                                    <div className="ctp-Det-info">
                                       <p><span><span className="loader-line shimmer"></span></span> </p>
                                       <span className="link"> <span className="loader-line shimmer"></span></span>
                                    </div>                                    
                                 </div>
                              </div>
                           </div>
                           <div className="ctp-SrpBtnDiv">
                                    <span className="loader-line shimmer button"></span>
                                    <span className="loader-line shimmer button"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
      );
          toupleList.push(toupleListLoader);
      }
      return(
        <div className="ctp_rightsidebar _cContentLoader">
            <div className="ctpSrp-contnr">
                <div className="fltlft odd-data">
                    {toupleList}
                </div>
                <div className="fltryt even-data">
                    {toupleList}
                </div>
            </div>
         </div>
      )
    }
    getLoader(){
      if(this.props.filterLoader){
        return(
            <React.Fragment>
                    {this.getCTPFilterLoader()}
            </React.Fragment>
        )
      }
      if(this.props.contentLoader){
        return(
            <React.Fragment>
                <div className="fltryt card-area ctp_rightsidebar">
                    {this.getCTPContentLoader()}
                </div>
            </React.Fragment>
        )
    }else{
        return(
            <React.Fragment>
                <div className="fltlft filter-area">
                    <div className="ctp_sidebar _cFilterLoader">
                    {this.getCTPFilterLoader()}
                    </div>
                </div>
                <div className="fltryt card-area ctp_rightsidebar">
                    {this.getCTPContentLoader()}
                </div>
            </React.Fragment>
        )
      }
    }
    
    render(){
        return (
            <React.Fragment>
                {this.getLoader()}
            </React.Fragment>
        );
    }
}
export default ContentLoaderCTPDesktop;
ContentLoaderCTPDesktop.defaultProps = {
    contentLoader : false, filterLoader : false
};