import React from 'react';
import  './../assets/contentLoader.css';
import { connect } from 'react-redux';

class contentLoader extends React.Component{
	constructor(props)
   	{
      super(props);
      this.state = {};
   	}

   	render(){

   		let imgUrl = (typeof(this.props.loaderData) != 'undefined' && this.props.loaderData.imgUrl) ? this.props.loaderData.imgUrl : null;

		return (
			<React.Fragment>		 	
		       <section className="loaderDiv">
		           <div className="_container  banner" style={{"backgroundImage" : 'url('+imgUrl+')'}}>
		             <div className="_subcontainer">
			               <div className="loader-container chp-contentl-top">
			                  <div className="content-center heading">
			                       <div className="loader-heading">{(typeof(this.props.loaderData) != 'undefined' && this.props.loaderData.title) ? this.props.loaderData.title : null}</div>
			                  </div>
			                  <ul className="inline-list content-center">
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
				              <div className="content-center"><button className="button button--orange" name="button">Download Guide</button></div>
			               </div>
		             </div>
	               </div>
   		           <div className="_container">
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
		                  	<li className="wdt20">
			                  <div className="loader-ContDiv content-center">
			                       <div className="loader-line shimmer wdt85"></div>
			                  </div>
		                  	</li>
		                  </ul>
		             </div>
		           </div>
		       </section>

		       <section className="loaderDiv">
		           <div className="_container">
		           	<h2 className="tbSec2"><div className="loader-line shimmer wdt20"></div></h2>
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
export default connect(mapStateToProps)(contentLoader);
