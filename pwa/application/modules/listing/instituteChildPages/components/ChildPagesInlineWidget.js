import PropTypes from 'prop-types'
import React, {Component} from 'react';
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import DownloadEBrochure from './../../../common/components/DownloadEBrochure';
import PopupLayer from './../../../common/components/popupLayer';
import {CTAResponseHTML} from './../../../../utils/commonHelper';

class ChildPagesInlineWidget extends Component {
    constructor(props) {
        super(props);
        this.state = {
            layerOpen :false,
            layerData:''
        };

    }


    trackEvent(actionLabel,label='click')
    {
      Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
    }

    componentDidMount(){
        if(this.props.device=='desktop'){
            window.callBackCutOffCTA = this.cutOffCTA.bind(this);
        }
    }


    render(){
        return(
            <section key={'childPageInlineWidget'+this.props.index}>
               <div className="_container">
                   <div className="_subcontainer text-center remove_brdr flexi_column_data">
                    {this.props.mainHeaing}
                     <div className="rvws_btnv1 btn-v1-col _borderTop">
                      {this.props.device=='desktop' ?
                          <a onClick={this.downloadEBRequest.bind(this,{'listingId':this.props.listingId,'listingName':this.props.listingName, 'listingType':'institute', 'pageType':this.props.page},this.props.actionLabel)} href="javascript:void(0);" customcallback='CutoffCTA' cta-type="cutoffcta" className={"button--purple button button--orange tupleBrochureButton brchr_"+this.props.listingId} hidereco="true"  customactiontype="Request_Call_Back">{this.props.buttonText}</a>

                          :<DownloadEBrochure actionType={this.props.actionType} heading='Thank you for your Interest.' className='button--purple' ctaName={this.props.ctaName}  buttonText={this.props.buttonText} listingId={this.props.listingId} uniqueId={this.props.ctaName+'_'+this.props.listingId} listingName={this.props.listingName} recoEbTrackid={this.props.trackingKey}  isCallReco={false} clickHandler={this.trackEvent.bind(this,'Download cutoff details','click_'+this.props.actionType)} page = {this.props.page} pdfUrl={this.props.pdfUrl}/> }
                     </div>
                  </div>
             </div>
            {<PopupLayer onRef={ref => (this.getLayer = ref)} data={this.state.layerData} heading='Thank you for your Interest.' onContentClickClose={false}/>}
       

            </section>
        )
    }


    downloadEBRequest = (params,gaLabel='NEWCTA', e) => {
        let thisObj = e.currentTarget;
        if(typeof(window) !='undefined' && typeof(ajaxDownloadEBrochure) !='undefined'){
            this.trackEvent(gaLabel,'click_Request_Call_Back');
            window.ajaxDownloadEBrochure(thisObj, params.listingId, params.listingType, params.listingName, params.pageType,this.props.trackingKey);
        }
    }

     getCTAHtml(){
        let html = CTAResponseHTML(false,this.props.listingName,this.props.actionType,this.props.pdfUrl);
        this.setState({layerData: html});
    }

    closeLayer(){
      this.setState({layerOpen : false});
    }

    cutOffCTA(){
        if(this.props.pdfUrl){
            window.open(this.props.pdfUrl, "_blank");
        }
        this.getCTAHtml();
        this.getLayer.open();
    }

}


export default ChildPagesInlineWidget;
