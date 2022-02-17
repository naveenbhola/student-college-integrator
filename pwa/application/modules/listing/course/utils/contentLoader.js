import React from 'react';
import { connect } from 'react-redux';
import {getObjectSize} from './../../../../utils/commonHelper';
import {getRupeesDisplayableAmount,renderColumnStructure} from './listingCommonUtil';

class contentLoader extends React.Component {

	getCourseInfo(data)
	{
	    if(typeof data == 'undefined')
	        return null;
	    var courseInfo = data.courseExtraInfo;
	    var courseInfoHtml = [];
	    var pushPipe = false;
	    if(typeof courseInfo != 'undefined' && courseInfo != null && typeof courseInfo.courseLevel != 'undefined' && courseInfo.courseLevel != null && courseInfo.courseLevel != 'None')
	    {
	        if(typeof courseInfo.courseCredential != 'undefined' && courseInfo.courseCredential != null && courseInfo.courseCredential != 'None')
	        {
	            courseInfoHtml.push(<li key="courseCredential">{courseInfo.courseLevel} {courseInfo.courseCredential}</li>);
	        }
	        else
	        {
	            courseInfoHtml.push(<li key="credential">{courseInfo.courseLevel}</li>);
	        }
	        pushPipe = true;    
	    }
	    else if(typeof courseInfo != 'undefined' &&  courseInfo == null && typeof courseInfo.courseCredential != 'undefined' && courseInfo.courseCredential != null && courseInfo.courseCredential != 'None')
	    {
	        courseInfoHtml.push(<li key="credential">{courseInfo.courseCredential}</li>);
	        pushPipe = true;
	    }
	    if(pushPipe)
	    {
	        courseInfoHtml.push(<li key="sep1" className="sep-pipe">|</li>);
	        pushPipe = false;
	    }
	    var extraInfo = typeof data.courseExtraInfo != 'undefined' && data.courseExtraInfo && data.courseExtraInfo.extraInfo;
	    if(typeof extraInfo != 'undefined' && extraInfo && extraInfo.educationType != null && typeof extraInfo.educationType != 'undefined' && extraInfo.educationType != "")
	    {
	        courseInfoHtml.push(<li key="education">{extraInfo.educationType}</li>);
	        pushPipe = true;
	    }
	    if(pushPipe)
	    {
	        courseInfoHtml.push(<li key="sep2" className="sep-pipe">|</li>);
	        pushPipe = false;
	    }
	    if(!isNaN(data.durationValue) &&  typeof data.durationUnit != 'undefined' && data.durationUnit != null  && data.durationUnit != "")
	    {
	        courseInfoHtml.push(<li key="duration">{data.durationValue + ' '+data.durationUnit}</li>);
	        pushPipe = true;
	    }
	    if(courseInfoHtml.length > 0)
	        return courseInfoHtml;
	}
	renderApprovalsInfo()
	{
		const {catpageCourse} = this.props;
		if(catpageCourse.recognition == null || catpageCourse.recognition.length == 0)
			return;
		var recogHtml = [];
		if(catpageCourse.recognition.length > 0)
		{
			recogHtml.push(<p key="rec-hdng"><span className='clg-label-tip'>Recognition</span></p>);
		    recogHtml.push(<span key="recognition">{catpageCourse.recognition[0].name} Approved</span>);
		}
		return (<React.Fragment key="approvals-main">
			         	{recogHtml}
			    </React.Fragment>
		    );
	}
	renderFeeValue()
	{
		const {catpageCourse} = this.props;
		var feeValue = catpageCourse.fees;
		const feesUnit = catpageCourse.feesUnit;
		if(!feeValue || typeof feeValue == 'undefined' || !feesUnit  || typeof feesUnit != 'string')
			return;
		if(feeValue && typeof feeValue != 'undefined')
		{
			feeValue = feesUnit +' '+getRupeesDisplayableAmount(feeValue);
		}
		return (
             		<React.Fragment key="fee-main">
             			<p><span className='clg-label-tip'>Total Fees </span></p>
             			<span>{feeValue}</span>
             		</React.Fragment>
             	);
	}
	render(){
		const {catpageCourse} = this.props;
		return (
			 <React.Fragment>
				<section className="loaderDiv">
		           <div className="_container">
		             <div className="_subcontainer">
		               <div className="loader-container">
		                  <div className="loader-ContDiv">
		                  		{typeof catpageCourse != 'undefined' && getObjectSize(catpageCourse) >0 ?
		                  			<div>
				                  		<div className="clp-hdr">
				                  			<div>
				                  				<span>
				                  					{typeof catpageCourse != "undefined" && catpageCourse && <React.Fragment><a href={catpageCourse.instituteUrl}>{catpageCourse.instituteName } {typeof catpageCourse.displayLocationString != "undefined" && catpageCourse.displayLocationString != "" && ", "+catpageCourse.displayLocationString}</a> <i className="pwa-shrtlst-ico tupleShortlistButton"></i></React.Fragment> }
				                  				</span>
				                  				{typeof catpageCourse != 'undefined' && catpageCourse.name != "" && <h1>{catpageCourse.name}</h1>}
				                  				{typeof catpageCourse != 'undefined' && catpageCourse.courseName != "" && <h1>{catpageCourse.courseName}</h1>}
				                  				<ul className="caption-list">
				                  					{this.getCourseInfo(catpageCourse)}
				                  				</ul>
				                  			</div>
				                  		</div> 
				                  		<div className='clg-detail'>
				                  			<ul>
				                  				{renderColumnStructure(this)}
				                  			</ul>
				                  		</div>
			                  		</div>
			                  		 : 		<div>
				                  					<div className="loader-line shimmer"></div>
		                       						<div className="loader-line shimmer hgt25"></div>
		                       						<div className="loader-line shimmer wdt75"></div>
	                       						</div>
		                  		}
		                       
		                  </div>

		                  {typeof catpageCourse != 'undefined' && getObjectSize(catpageCourse) >0 ?	
		                  	<div className="dnld-btn">
		                  		<a className="btnYellow tupleBrochureButton">Apply Now</a>
		                  		<div></div>
		                  	</div>	:
		                  	<React.Fragment>
				                 <div className="loader-ContflexBox">
	                                     <div className="loader-ContBox">
	                                         <div className="loader-line shimmer"></div>
	                                          <div className="loader-line shimmer wdt85"></div>
	                                     </div>
	                                     <div className="loader-ContBox">
	                                         <div className="loader-line shimmer"></div>
	                                          <div className="loader-line shimmer wdt85"></div>
	                                     </div>
	                                 </div>
	                                 <div className="loader-ContflexBox">
	                                     <div className="loader-ContBox">
	                                         <div className="loader-line shimmer"></div>
	                                          <div className="loader-line shimmer wdt85"></div>
	                                     </div>
	                                     <div className="loader-ContBox">
	                                         <div className="loader-line shimmer"></div>
	                                          <div className="loader-line shimmer wdt85"></div>
	                                     </div>
	                                 </div>
                              </React.Fragment>
							}
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
        catpageCourse : state.catpageCourse,     
    }
}
export default connect(mapStateToProps)(contentLoader);
//export default contentLoader;
