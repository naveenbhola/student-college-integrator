import React from 'react';
import  './../assets/contentLoader.css';

class contentLoader extends React.Component{
	constructor()
   	{
      super();
   	}

   	render(){
		return (
			<React.Fragment>
				<div className="examPwa econtent_loader">
				   <section>
				      <div className="_container">
				         <div className="_subcontainer">
				            <div className="pwa_breadcumbs">
				            	<span><div className="loader-line shimmer wdt15"></div></span>
				            	<span><div className="loader-line shimmer wdt30"></div></span>
				            </div>
				            <div className="exam_wrap">
				               <h1 className="event_name">
						      		<div className="loader-line shimmer hgt20 wdt85"></div>
				               	</h1>
				               <div className="event_status">
						      		<div className="loader-line shimmer wdt85"></div>
				               </div>
				            </div>
				            <div className="discuss-block">
					      		<div className="loader-line shimmer wdt50"></div>
				            </div>
				            <div className="admit-sctn">
					      		<div className="loader-line shimmer wdt50"></div>
				            </div>
				            <div className="flex-mob">
								<div className="loader-line shimmer hgt30 wdt50"></div>
								<div className="loader-line shimmer hgt30 wdt50"></div>
				            </div>
				         </div>
				      </div>
				    </section>
				    <section>
				      <div className="nav-col nav-bar color-w pos-rl">
				         <div className="nav-tabs nav-lt">
				            <div className="chp-nav">
				               <div className="chp-navList">				                  
				                  <ul className="l2Menu-list">
				                     <li className="active">
							      		<a className="sec-a"><div className="loader-line shimmer wdt85"></div></a>
							      		<a className="sec-a"><div className="loader-line shimmer wdt85"></div></a>
							      		<a className="sec-a"><div className="loader-line shimmer wdt85"></div></a>
				                     </li>
				                  </ul>
				               </div>
				            </div>
				         </div>
				      </div>
				    </section>
					   <section>
					      <div className="toc-block">
					         <div className="css-acrdn">
					            <label className="block-label">
						      		<div className="loader-line shimmer wdt85"></div>
					            </label>
					         </div>
					      </div>
					</section>
					<section>
					   <div className="_container">
					      <h2 className="tbSec2">
					      		<div className="loader-line shimmer hgt20 wdt50"></div>
					      </h2>
					      <div className="_subcontainer">
					      		<div className="loader-line shimmer"></div>
					      		<div className="loader-line shimmer wdt85"></div>
					      </div>
					   </div>
					</section>
					<section>
					   <div className="_container">
					      <h2 className="tbSec2">
					      		<div className="loader-line shimmer hgt20 wdt50"></div>
					      </h2>
					      <div className="_subcontainer">
					      		<div className="loader-line shimmer"></div>
					      		<div className="loader-line shimmer wdt85"></div>
					      </div>
					   </div>
					</section>
				</div>		 	
	       </React.Fragment>
			);
		}
}

export default contentLoader;
