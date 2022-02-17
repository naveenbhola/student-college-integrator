import React from 'react';
import  './../assets/contentLoaderDesktop.css';
import { connect } from 'react-redux';

class contentLoaderDesktop extends React.Component{
	constructor(props)
   	{
      super(props);
      this.state = {};
   	}

   	render(){

		return (
			<React.Fragment>		 	
				<section className="pwa_pagecontent chp">
					<div className="dfp-loader">
						<div className='dfp-bg-ep'></div>
						<div className='dfp-bg-ep'></div>
					</div>
				   <div className="pwa_container">
					   <section className="loaderDiv">
					       <div className="_conatiner">
							   <div className="_subcontainer">
							      <div className="rwd-col">
							         <div className="exam_wrap">
							            <h1 className="event_name"><div className="loader-line shimmer wdt85"></div></h1>
							            <div className="event_status">
							               <div className="loader-line shimmer wdt75"></div> 
							            </div>
							         </div>
							      </div>
							      <div className="rwd-right">
							         <div className="flex-mob">
										 <div className="loader-line shimmer hgt30 wdt50"></div>
										 <div className="loader-line shimmer hgt30 wdt50"></div>
							         </div>
							      </div>
							   </div>
							</div>
						   <div className="_container">
						      <div className="_subcontainer">
						      	<div className="chp-navList">
							         <div className="loader-container clearfix">
							            <ul className="inline-list menu-item-list">
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							            </ul>
							            <ul className="inline-list menu-item-list">
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							            </ul>
							            <ul className="inline-list menu-item-list">
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							               <li className="wdt20">
							                  <div className="loader-ContDiv content-center">
							                     <div className="loader-line shimmer wdt85"></div>
							                  </div>
							               </li>
							            </ul>
							         </div>
						         </div>
						      </div>
						   </div>
						</section>

					      <div className="pwa_leftCol">
					         <section className="loaderDiv">
					            <div className="_container">
					               <h2 className="tbSec2">
					               	<div className="loader-line shimmer"></div>
					               </h2>
					               <div className="_subcontainer">
					                  <div className="loader-container">
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt75"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                  </div>
					               </div>
					            </div>
					         </section>
					         <section className="loaderDiv">
					            <div className="_container">
					               <h2 className="tbSec2">
					               	<div className="loader-line shimmer"></div>
					               </h2>
					               <div className="_subcontainer">
					                  <div className="loader-container">
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt75"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                  </div>
					               </div>
					            </div>
					         </section>
					      </div>
					      <div className="pwa_rightCol">
					         <section className="loaderDiv">
					            <div className="_container">
					               <div className="_subcontainer">
					                  <div className="loader-container">
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt75"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                  </div>
					               </div>
					            </div>
					         </section>
					         <section className="loaderDiv">
					            <div className="_container">
					               <div className="_subcontainer">
					                  <div className="loader-container">
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt75"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                     <div className="loader-ContDiv">
					                        <div className="loader-line shimmer"></div>
					                        <div className="loader-line shimmer wdt85"></div>
					                     </div>
					                  </div>
					               </div>
					            </div>
					         </section>
					      </div>
				   </div>
				</section>
		       
		       
	       </React.Fragment>
		      
			);
		}
}

function mapStateToProps(state)
{
    return {
        loaderData : state.courseHomePageLoaderData
    }
}
export default connect(mapStateToProps)(contentLoaderDesktop);