import PropTypes from 'prop-types'
import React from 'react';

class AllChildPageContentLoader extends React.Component {
    
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
        <div className="ctp_sidebar _cFilterLoader">
            <label className="nav_main_head">Filters </label>
            {filterList}
        </div>
      );
    }
    getCTPContentLoader(){
      let toupleList = [];
      for(let i=0;i<=5;i++){
      let toupleListLoader = (
      <div key={"touple_loader" + i}  className="ctpSrp-tuple">
                  <div className="ctp-SrpLst">
                     <div id="courseTuple">
                        <div className="none">
                           <div className="flexi-div">
                              <div className="ctp-SrpDiv">
                                 <a  className="fnt-w6"><span className="loader-line shimmer"></span></a>
                                 <div className="ctp-detail">
                                    <span className="loader-line shimmer wdt55"></span>
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
                           <div className="ctp-SrpBtnDiv acp-loader">
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
        <div className="acp_rightsidebar _cContentLoader">
            <div className="ctpSrp-contnr">
               {toupleList}
            </div>
         </div>
      )
    }

    getExtraData(){
      return(
          <div id="bip">
   <div className="bip">
      <section>
         <div className="ctpn_container">
            <div className="ctpn-filter-head">
               <div className="ctpn-filter-sec">
                  <div className="filterColumn">
                     <h2 className="filter_rslt"><span className="loader-line shimmer wdth15"></span></h2>
                    
                     <h2 className="filteralt_txt read-more-wrap word-break"><span className="loader-line shimmer wdth30"></span></h2>
                  </div>
                  <div className="_padaround">
                     <div className="_browsesection clearboth">
                        <div className="_sctntitle">Browse by Courses</div>
                        <div className="_browseBy acp-loader">
                           <div className="_browseRow">
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           </div>
                        </div>
                     </div>
                     <div className="_browsesection clearboth">
                        <div className="_sctntitle">Browse by Streams</div>
                        <div className="_browseBy acp-loader">
                           <div className="_browseRow">
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           <a className="_clist loader-line shimmer"></a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
   </div>
</div>
        )


    }

    getTopcard(){
        return (
          <div className="clp">
            <div className="pwa_headerv1">
              <div className="header-bgcol"></div>
              <div className="pwa-headerwrapper">
                 <div className="pwa_topwidget">
                   <div className="header_img">
                    <div className="loader-line shimmer thumbImg"></div>
                   </div>
                   <div className="text-cntr clg_dtlswidget">
                     <h1 className=""><div className="loader-line shimmer"></div></h1>
                     <p className="region-widget"><span className="loader-line shimmer"></span> <span className="loader-line shimmer"></span></p>
                     <div className="rank-widget contentloader">
                        <span className="loader-line shimmer"></span>
                     </div>
                   </div>
                   <div className="flex flex-column">
                     <div className="facts-widget contentloader">
                       <div className="loader-line shimmer"></div>
                       <div className="loader-line shimmer"></div>
                     </div>
                     <div className="topcard_btns">
                       <button type="button" name="button" className="pwa-btns loader-line shimmer"></button>
                       <button type="button" name="button" className="pwa-btns loader-line shimmer"></button>
                     </div>
                   </div>
                 </div>
              </div>
            </div>
          </div>  
        )
    }

    getLoader(fullLoader=false,filter=false,tuple=false,onlyBipSip = false){
      if(onlyBipSip){
         return(
            <React.Fragment>
                {this.getExtraData()}
            </React.Fragment>
        )
      }
      if(fullLoader){
        return(
            <React.Fragment>
                {this.getTopcard()}
                {this.getExtraData()}
            </React.Fragment>
        )
      }
      if(filter && tuple){
        return(
            <React.Fragment>
                <section className='pwa_container'>
                  <div className='acp_block'>
                    {this.getCTPFilterLoader()}
                    {this.getCTPContentLoader()}
                  </div>
                </section>
            </React.Fragment>
        )
        
      }
    }
    
    render(){
        if(this.props.onlyBipSip){
          return(
              <React.Fragment>
                {this.getLoader(false,false,false,true)}
              </React.Fragment>
            )
        }

        else if(this.props.fullLoader){
          return(
              <React.Fragment>
                {this.getLoader(true)}
                {this.getLoader(false,true,true)}
              </React.Fragment>
            )
        }

        else if(this.props.filter && this.props.tuple){
          return(
              <React.Fragment>
                    {this.getLoader(false,true,true)}
              </React.Fragment>
            )
        }else if(this.props.tuple){
          return(
              <React.Fragment>
                    {this.getCTPContentLoader()}
              </React.Fragment>
            )
        }
        else{
            return(
              <React.Fragment>
                  <div className="pwa_pagecontent">
                    <div className="pwa_container">
                      <section className="pwa_container">
                        <div className="acp_block">
                          {this.getLoader(true)}
                          {this.getLoader(false,true,true)}
                        </div>
                      </section>
                    </div>
                </div>            


              </React.Fragment>


            )


        }
    }
}
export default AllChildPageContentLoader;
AllChildPageContentLoader.defaultProps = {
  contentLoader: false,
  filterLoader: false
}

AllChildPageContentLoader.propTypes = {
  contentLoader: PropTypes.bool,
  currentPage: PropTypes.any,
  filter: PropTypes.any,
  filterLoader: PropTypes.bool,
  fullLoader: PropTypes.any,
  onlyBipSip: PropTypes.any,
  tuple: PropTypes.any
}