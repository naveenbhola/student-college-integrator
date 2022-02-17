import React from 'react';
import { connect } from 'react-redux';
import {getObjectSize,sectionLoder,mobileSectionLoder} from './../../../../utils/commonHelper';
import TopWidget from './../../institute/components/TopWidgetCommon';
import config from './../../../../../config/config';
import PlacementPageContentLoader from './../components/PlacementPageContentLoader';
import CutOffPageContentLoader from './../components/CutOffPageContentLoader';
import ContentLoader from './../../institute/utils/contentLoader';



class ContentLoaderMain extends React.Component {


	getTopCard(deviceType){
		const {contentLoaderData} = this.props;
		let topCardHtml = [], PageHeading ='';

		if(typeof contentLoaderData != 'undefined' && getObjectSize(contentLoaderData) >0 && typeof contentLoaderData.PageHeading !='undefined' && contentLoaderData.PageHeading!=''){
			PageHeading = contentLoaderData.PageHeading ;
		}

		if(typeof contentLoaderData != 'undefined' && getObjectSize(contentLoaderData) >0 && contentLoaderData.instituteTopCardData && !contentLoaderData.showFullLoader){
			topCardHtml.push(<div className='ilp' key='top_card'>
				<TopWidget showChangeBranch ={false} instituteId={contentLoaderData.listingId} data={contentLoaderData} config={config} location = {contentLoaderData.location} page ={contentLoaderData.listingType} extraHeading ={PageHeading} fromWhere={contentLoaderData.fromWhere} isDesktop={this.props.deviceType == 'desktop'}/>
				</div>)
		}else{
			return(<ContentLoader onlyTopCard={true} />);
			}

			return topCardHtml;
	}

	getMobileLoader(){
		let mobileLoaderHtml = [];

		mobileLoaderHtml.push(<React.Fragment key="mobile_loader">
					{mobileSectionLoder()}
					{mobileSectionLoder()}
					{mobileSectionLoder()}
		      	</React.Fragment> );

		return mobileLoaderHtml;
	}

	leftLoader(){
	    return(
	        <div className="pwa_leftCol">
	            <section>
	                {mobileSectionLoder()}
	            </section>
	            <section>
	                {mobileSectionLoder()}
	            </section>
	        </div>
	    )

	}
	rightLoader(){
	    return(
	        <div className="pwa_rightCol">
	            <section>
	                {mobileSectionLoder()}
	            </section>
	        </div>
	    )
	}

	getDesktopLoader(){
		let desktopLoaderHtml = [];
		desktopLoaderHtml.push(<React.Fragment key="desktop_loader">
                    {this.leftLoader()}
                    {this.rightLoader()}
                </React.Fragment>);

		return desktopLoaderHtml;
	}

	getSectionLoader(deviceType){
		
		const {contentLoaderData} = this.props;

		if(typeof contentLoaderData == 'undefined' || getObjectSize(contentLoaderData) ==0 || contentLoaderData.showFullLoader)
		{
			return this.getMobileLoader(); 
		}

		if(contentLoaderData.showFullSectionLoader)
			return deviceType == 'mobile' ? this.getMobileLoader() : this.getDesktopLoader();

		if(contentLoaderData.fromWhere == 'placementPage')
			return <PlacementPageContentLoader contentLoaderData={contentLoaderData} deviceType={deviceType}/>
		else if(contentLoaderData.fromWhere == 'cutoffPage')
			return <CutOffPageContentLoader contentLoaderData={contentLoaderData} deviceType={deviceType}/>

	}

	render(){

		const {contentLoaderData,deviceType} = this.props;
		let contentLoaderHtml = [];
		contentLoaderHtml.push(	<React.Fragment key="content_loader">
				{this.getTopCard(deviceType)}
	       		{this.getSectionLoader(deviceType)}
		      </React.Fragment>);


		if(deviceType == 'mobile'){
			return (<section className="ilp loaderDiv_common">
	                  <div className="loader-ContDiv">
	                  	{contentLoaderHtml}
	                  </div>
			       	</section>);
		}else{
			return (<div className="ilp courseChildPage pwa_admission">
		               <div className='pwa_pagecontent ap'>
		                 <div className='pwa_container'>				                  	
		                 {contentLoaderHtml}
	                      </div>
	                    </div>
	                  </div>);
		} 
	}
}

function mapStateToProps(state)
{
    return {
        contentLoaderData : state.contentLoaderData,     
    }
}
export default connect(mapStateToProps)(ContentLoaderMain);
//export default contentLoader;