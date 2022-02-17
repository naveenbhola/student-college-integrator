import React from 'react';
import { connect } from 'react-redux';
import {getObjectSize} from './../../../../utils/commonHelper';
import TopWidget from './../../institute/components/TopWidgetCommon';
import config from './../../../../../config/config';
import ContentLoader from './../../institute/utils/contentLoader';

class contentLoader extends React.Component {

	render(){
		const {contentLoaderData} = this.props;

		if(typeof contentLoaderData == 'undefined' || getObjectSize(contentLoaderData) ==0 || contentLoaderData.showFullLoader )
		{
			return(
				<ContentLoader />
			)
		}
		let PageHeading ='';
		if(typeof contentLoaderData != 'undefined' && getObjectSize(contentLoaderData) >0 && typeof contentLoaderData.PageHeading !='undefined' && contentLoaderData.PageHeading!=''){
			PageHeading = contentLoaderData.PageHeading ;
		}
		return (
			 <React.Fragment>
				<section className="loaderDiv">
		                  <div className="loader-ContDiv">
		                  		{typeof contentLoaderData != 'undefined' && getObjectSize(contentLoaderData) >0 ?
		                  			<div className='ilp'>
				                  		 {contentLoaderData.instituteTopCardData && <TopWidget showChangeBranch ={false} instituteId={contentLoaderData.Id} data={contentLoaderData} config={config} location = {contentLoaderData.location} page = 'institute' extraHeading ={PageHeading} fromWhere={contentLoaderData.fromWhere}/>}
			                  		</div>
			                  		 :
			                  		 <div className="clp">
			                  		   <div className="pwa_headerv1">
			                  		     <div className="header-bgcol">
			                  		      <div className="banner-bg loader-line shimmer"></div>
			                  		     </div>
			                  		     <div className="pwa-headerwrapper">
			                  		        <div className="pwa_topwidget">
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


		                  		}		                       
		                  </div>
	       		</section>

	       		<section className="loaderDiv">
	       		    <div className="_container">
	       		        <div className="_subcontainer">
	       		        <div className="loader-container">
	       		           <div className="loader-ContDiv">
	       		                <div className="loader-line shimmer"></div>
	       		                <div className="loader-line shimmer wdt75"></div>
	       		                <div className="loader-line shimmer wdt75"></div>
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
	       		                <div className="loader-line shimmer wdt75"></div>
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
	       		                <div className="loader-line shimmer wdt75"></div>
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
			)
	}
}

function mapStateToProps(state)
{
    return {
        contentLoaderData : state.contentLoaderData,     
    }
}
export default connect(mapStateToProps)(contentLoader);
//export default contentLoader;