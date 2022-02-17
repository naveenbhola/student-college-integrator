import React from 'react';
import { connect } from 'react-redux';
import {getObjectSize} from './../../../../utils/commonHelper';
import {renderColumnStructureCommon} from './../../course/utils/listingCommonUtil';

class contentLoader extends React.Component {


	instituteLocationName(){
	    let locationName = '';
	    const {catpageInstitute} = this.props;
		if(catpageInstitute.location){
	    	return catpageInstitute.location;
	    }
	    if(catpageInstitute){
	        if(catpageInstitute.locality_name){
	               locationName +=  catpageInstitute.localityName;
	        }
	        if(catpageInstitute.cityName && this.showCityName()){
	            if(locationName !=''){
	                locationName += ', ';       
	            }
	            locationName += catpageInstitute.cityName;
	        }
	    }
	    return locationName; 
	}

	showCityName(){
        const {catpageInstitute} = this.props;
        let instituteName = '';
        let cityName = '';
        if(catpageInstitute && catpageInstitute.instituteName){
             instituteName = catpageInstitute.instituteName.toLowerCase();
        }
        if(catpageInstitute && catpageInstitute.cityName){
             cityName = catpageInstitute.cityName.toLowerCase();
        }
        return (instituteName.indexOf(cityName) == -1)
           
    }

	renderNameLink(){
	    const {catpageInstitute} = this.props;
	    var pageHtml = '';
		if(catpageInstitute.extraHeading){
			return(
					<h1 className='inst-name'>
	                	<a href='javascript:void(0)'>{catpageInstitute.instituteName} </a>
	                	{catpageInstitute.extraHeading}
	                </h1>
				)
		}
		else{
			return(
					<h1 className='inst-name'>
	                	{catpageInstitute.instituteName}
	                </h1>
				)
		}
	}

	renderNameInfo()
	{

	    const {catpageInstitute} = this.props;
	    return (
	        <React.Fragment>
	           <div className="text-cntr clg_dtlswidget">
	            	{this.renderNameLink()}
	                {this.instituteLocationName() !='' ?  
	                <span className="ilp-loc"> <i className= 'clg_location_icon'></i>{this.instituteLocationName()}</span>
	           		: null}
	            </div>
	            </React.Fragment>
	    )
	}

	renderOwnershipInfo(){
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.ownership){
	    	var ownership = catpageInstitute.ownership;
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>{ownership.charAt(0).toUpperCase() + ownership.substr(1)}</span>
	            </React.Fragment>
	        )
	    }
	    return null;

	}

	renderCourseStatusInfo(){
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.autonomous){
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>Autonomous</span>
	            </React.Fragment>
	        )
	    }
	    return null;

	}
	renderImportanceInfo(){
	    
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.nationalImportance){
	        return (
	            <React.Fragment>
	                <span className='clg-tip'>Institute of National Importance</span>
	            </React.Fragment>    
	        )
	    }
	    return null;
	}
	renderRecoginitionInfo(){
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.ugcApproved){
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>UGC Approved</span>
	            </React.Fragment>
	        )
	    }
	    return null;        
	}

	renderUniversityTypeInfo(){
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.univeristyTypeWithSpecification){
	    	var universityType = catpageInstitute.univeristyTypeWithSpecification;
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>{universityType.charAt(0).toUpperCase() + universityType.substr(1)}</span>
	            </React.Fragment>
	        )
	    }
	    return null;        
	}

	renderAIUMemberInfo(){
	    const {catpageInstitute} = this.props;
	        if(catpageInstitute && catpageInstitute.aiuMember){
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>AIU Member</span>
	            </React.Fragment>
	        )
	    }
	    return null;      
	}

	renderAccreditationInfo(){
	    const {catpageInstitute} = this.props;
	    if(catpageInstitute && catpageInstitute.naacAccreditation){
	        return(
	            <React.Fragment>
	                <span className='clg-tip'>{"NAAC Accreditation ( " + catpageInstitute.naacAccreditation + ")"}</span>
	            </React.Fragment>
	        )    
	    }
	    return null;
	    
	}
	generateBHSTHtml(){
		const {catpageInstitute} = this.props;
	    var bhst = [];
	    if(catpageInstitute && catpageInstitute.fromWhere=='admissionPage'){
	        bhst.push(<div className="addtnl-col" key="bhst">
	                <p className="txt-later">  Find details of the Admission Process - Eligibility, Dates and Cut Offs </p>
	            </div>)
	    }
	    return bhst;
	}
	render(){
		const {catpageInstitute} = this.props;
		const instituteHeaderCss = { "display": "block", "fontSize": "1.25rem", "lineHeight": "23px", "fontWeight": "700"}
		return (
			 <React.Fragment>
						<div className="ilp">
			                  		   <div className="pwa_headerv1">
			                  		     {catpageInstitute && catpageInstitute.headerImage?<div className={"header-bgcol mobile"} style={{"backgroundImage" : "url('"+catpageInstitute.headerImage+"')"}}>
			                  		       </div>:<div className="header-bgcol">
			                  		       	<div className="banner-bg loader-line shimmer"></div>
			                  		     </div>}
			                  		     <div className="pwa-headerwrapper">
			                  		        <div className="pwa_topwidget">
			 			{typeof catpageInstitute != 'undefined' && getObjectSize(catpageInstitute) >0 ?
			 			<div>	
			 				{this.renderNameInfo()}
				 			<div className="flex flex-column">
				 				{renderColumnStructureCommon(this, "institute")}
				 				<div className="topcard_btns" id="CTASection">
				 					<a data-type="button"  className="ctp-shrtlst rippleefect ctp-btn ctpComp-btn rippleefect tupleShortlistButton" >Shortlist</a>
				 					<a href="javascript:void(0);" className="ctp-btn ctpBro-btn rippleefect tupleBrochureButton">Apply Now</a>
				 				</div>
			 				</div>
			 			</div>:
			 						         (<React.Fragment>    	    	
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
												{typeof catpageInstitute != 'undefined' && getObjectSize(catpageInstitute) >0 ? <div className="topcard_btns" id="CTASection">
			                  		            	<a data-type="button"  className="ctp-shrtlst rippleefect ctp-btn ctpComp-btn rippleefect tupleShortlistButton" >Shortlist</a>
			                  		            	<a href="javascript:void(0);" className="ctp-btn ctpBro-btn rippleefect tupleBrochureButton">Apply Now</a>
			                  		            </div> :
			                  		            <div className="topcard_btns" id="CTASection">
			                  		            	<a data-type="button"  className="ctp-shrtlst ctp-btn " ><div className="loader-line shimmer"></div></a>
			                  		            	<a href="javascript:void(0);" className="ctp-btn  "><div className="loader-line shimmer"></div></a>
			                  		            </div> 			                  		            

			                  		        }
			                  		          </div>
			                  		          </React.Fragment>)
			 						     }
	                  		            {this.generateBHSTHtml()}
			                  		        </div>
			                  		     </div>
			                  		   </div>
			                  		 </div>        		 
		       
			   {!this.props.onlyTopCard ? 
			   	(	<React.Fragment>
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
		       ) :null
			   }               		 
		       
		      </React.Fragment>
			)
	}
}

function mapStateToProps(state)
{
    return {
        catpageInstitute : state.catpageInstitute,     
    }
}
export default connect(mapStateToProps)(contentLoader);
//export default contentLoader;