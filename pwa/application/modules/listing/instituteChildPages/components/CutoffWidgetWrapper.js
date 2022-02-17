import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import {getRequest} from './../../../../utils/ApiCalls';
import '../assets/cutoffwidget.css';
import {Link} from 'react-router-dom';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import CutoffWidget from './CutoffWidget';
import ChildPagesInlineWidget from './ChildPagesInlineWidget';
import APIConfig from './../../../../../config/apiConfig';
import {getObjectSize} from './../../../../utils/commonHelper';

class CutoffWidgetWrapper extends React.Component{
	constructor(props)
    {
      super(props);
      this.pageNumber = 1;
      this.state = {
          courseTuples : this.props.data,
          showTupleLoader: false
      }
    }

    showTupleLoader = () => {
        if(this.props.isPDF){
            return null;
        }
        let {courseTupleCount, courseTupleLimit} = this.props;
        if(this.state.showTupleLoader) {
            return (<div className="cutoff_btns" key={'loader'}>Loading<span className="dots-loading"></span></div>);
        }
        else {
            if(courseTupleCount / (courseTupleLimit * this.pageNumber) > 1) {
                return (<div className="button-container">
                    <button className="button button--secondary rippleefect arrow rvrse bg-white"
                            onClick={this.updateData}>
                        Load more Cutoffs
                    </button>
                </div>);
            }
            return null;
        }
    };


    getCompleteTupleData(){

            const {childPageData} = this.props;
    		const data  = this.state.courseTuples;

            if(!data){
                return null;
            }
    		let tupleData = [];
            let count =0;
    		for(let i=0;i<data.length;i++){
                if(getObjectSize(data[i])){
                    tupleData.push(<CutoffWidget location={this.props.location} key={'tuple_'+i} config={this.props.config} isPDF={this.props.isPDF} pageUrl={this.props.pageUrl}  contentLoaderData={this.props.contentLoaderData} gaTrackingCategory={this.props.gaTrackingCategory} data={data[i]} index = {i} deviceType={this.props.device} shortTracking ={this.props.shortlistTrackingKey} ebTracking={this.props.ebTrackingKey} ViewMoreCount={this.props.ViewMoreCount} appliedCategory={childPageData.appliedCategory}/>);
                    
                }
                let showDownloadCutoffCTA = false;
                if(getObjectSize(data[i].examWiseData)){
                    showDownloadCutoffCTA = true;
                    count++;
                }
                if(!this.props.isPDF && (count==1 || count==5) && showDownloadCutoffCTA && this.props.pdfUrl){

                    tupleData.push(
                        <ChildPagesInlineWidget 
                            key={'inlineWidget_'+i}
                            buttonText='Download Cut-Off Details' 
                            device={this.props.device} 
                            gaTrackingCategory={this.props.gaTrackingCategory} 
                            listingId={this.props.listingId} 
                            listingName={this.props.listingName} 
                            page = {this.props.listingType} 
                            listingType = {this.props.listingType} 
                            fromwhere= "cutoffPage"
                            trackingKey = {this.props.trackingKey}
                            mainHeaing="Download cutoffs for all courses of this college."
                            actionType ="Download_Cutoff_Details"
                            ctaName="downloadCutoffDetails"
                            actionLabel="Download cutoff details"
                            pdfUrl = {this.props.pdfUrl}
                            index={count}
                        />
                    );    
                }

            }

    		return(
    			<React.Fragment >
    				{tupleData}
                    {this.showTupleLoader()}
    			</React.Fragment>
    		)    	
    }

    trackEvent(actionLabel,label='click')
    {
        let category = this.props.gaTrackingCategory
        Analytics.event({category : category, action : actionLabel, label : label});
    }

    updateData = () => {
        this.trackEvent('Load More Cut off','click');
	    this.setState({showTupleLoader: true});
        let {paramsObj, listingId} = this.props;
        paramsObj = JSON.parse(Buffer.from(paramsObj, 'base64').toString());
        this.pageNumber++;
        paramsObj['pn'] = this.pageNumber;
        let reqString  = JSON.stringify(paramsObj),
        queryParams = Buffer.from(reqString).toString('base64'),
        url = APIConfig.GET_CUTOFF_PAGE_DATA+'?instituteId='+listingId+'&data='+queryParams;
        getRequest(url).then((response) => {
            this.setState({showTupleLoader: false});
            let finalData = [...this.state.courseTuples, ...response.data.data.courseTuples];
            this.setState({courseTuples: finalData});
        }).catch(() => {

        });
    }
	render(){
		const {data,childPageData} = this.props;
        let hasData = false;
        for (let i = 0; i < data.length; i++) {
            if(data[i].examWiseData && Object.keys(data[i].examWiseData).length != 0 ){
                hasData = true;
                continue;
            }
        }
        if(!data || !hasData){
            return null;
        }
        let heading = 'Cut-Offs for Last Round';
        let category = 'General';
        if(childPageData.isDuMuCase){
            heading ='';
        }
        if(childPageData.appliedCategory){
            category = childPageData.appliedCategory;
            if(heading.indexOf('Cut-Off')!=-1){
                heading = heading+' ('+category+' Category)';
            }else{
                heading = heading +' Cut-off ('+category+' Category)';
            }
        }

        if(childPageData.cutOffPageType == 'icox'){
            heading = childPageData.examName+' Cut-off ('+category+' Category)';
        }
      return(
       <div id="cutofflast">
		   <h2 className="lead_h2">{heading}</h2>
           {this.getCompleteTupleData()}
		</div>
      )
	}
}

export default CutoffWidgetWrapper;

CutoffWidgetWrapper.defaultProps = {
  isPDF: false
}