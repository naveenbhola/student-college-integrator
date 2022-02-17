import React from 'react';
import {dfpConfig} from '../../common/config/dfpBannerConfig';
import DFPBannerUtil from './../utils/DFPBanner';
import { connect } from 'react-redux';
import {getObjectSize} from './../../../utils/commonHelper';
import {decodeHtmlEntities} from './../../../utils/stringUtility';


const shikshaCommonDFP = ['footer', 'footer_desktop', 'searchLayer','sticky_banner','sticky_banner_desktop'];

class DFPBanner extends React.Component{
	componentDidMount()
	{
		this.publishBanner();
	}
	componentDidUpdate()
	{
		this.publishBanner();
	}
	publishBanner()
	{
		const {bannerPlace,dfpParams} = this.props;	
		if(typeof dfpParams == "undefined" || typeof dfpParams.parentPage == 'undefined' || (typeof dfpParams == 'object' && getObjectSize(dfpParams) == 0))
			return;

		DFPBannerUtil.pushBannerToGoogleDFP(dfpParams);

		let currentConfig = typeof dfpConfig[dfpParams.parentPage] != 'undefined' ? dfpConfig[dfpParams.parentPage][bannerPlace] : null;
	    if(typeof currentConfig == 'undefined' || !currentConfig)
	    {
	        currentConfig = dfpConfig['DFP_Others'][bannerPlace];
	    }
	    if(typeof currentConfig != 'undefined' && currentConfig)
    		DFPBannerUtil.displayDFPBanner(currentConfig['elementId']);
	}
	render()
	{
		if(this.props.isMobile == 'undefined' || this.props.isMobile === true){
			return null;
		}
		const {dfpParams,bannerPlace,parentPage} = this.props;
		let dfpStyle = {};
		if(parentPage && dfpConfig[parentPage] && dfpConfig[parentPage][bannerPlace]){
			dfpStyle.height = dfpConfig[parentPage][bannerPlace]['height'];
			dfpStyle.width = dfpConfig[parentPage][bannerPlace]['width'];
			if(shikshaCommonDFP.indexOf(bannerPlace) == -1){
				dfpStyle.background = '#eee';
			}
		}else {
			dfpStyle.height = 0;
			//dfpStyle.width = 0;
			dfpStyle.margin = 0;
		}
		if(typeof dfpParams == "undefied" || typeof dfpParams.parentPage == 'undefined' || (typeof dfpParams == 'object' && getObjectSize(dfpParams) == 0))
			return <div className="dfp-wraper"><div className="dfp-add"><div className='dfp-f-h' style={dfpStyle}></div></div></div>;

		let currentConfig = typeof dfpConfig[dfpParams.parentPage] != 'undefined' ? dfpConfig[dfpParams.parentPage][bannerPlace] : null;
	    if(typeof currentConfig == 'undefined' || !currentConfig)
	    {
	        currentConfig = dfpConfig['DFP_Others'][bannerPlace];
	    }

	    if(typeof currentConfig == 'undefined' || !currentConfig)
			return <div className="dfp-wraper"><div className="dfp-add"><div className='dfp-f-h' style={dfpStyle}></div></div></div>;
		return (
					<div className="dfp-wraper">
			          <div className="dfp-add">
			            <div id={currentConfig['elementId']} className="dfp-f-h" style={dfpStyle}>
			            </div>
			          </div>
			        </div>
			)
	}
}

class DFPBannerComponent extends React.Component{

	constructor(props)
	{
	      super(props);	      
	}

	componentDidMount()
	{
		this.displayDFPBanner();

		window.addEventListener("scroll", this.onScroll);
	}
	
	componentWillUnmount(){
		window.removeEventListener("scroll", this.onScroll);
	}
	
	onScroll = () => {
		if(document.getElementById('stickyBanner') && document.getElementById('crossStickyBanner') && document.getElementById('stickyBanner').offsetHeight>0){
			document.getElementById('crossStickyBanner').style.display = "block";
		}
	};

	componentDidUpdate()
	{
		this.displayDFPBanner();
	}
	displayDFPBanner()
	{
		const {dfpParams,bannerPlace} = this.props;
		if(typeof dfpParams == "undefined" || typeof dfpParams.parentPage == 'undefined' || (typeof dfpParams == 'object' && getObjectSize(dfpParams) == 0))
			return;
		var currentConfig;
		if(shikshaCommonDFP.indexOf(bannerPlace) !== -1 && (typeof dfpConfig[dfpParams.parentPage] == 'undefined' || (typeof dfpConfig[dfpParams.parentPage] != 'undefined' && typeof dfpConfig[dfpParams.parentPage][bannerPlace] == 'undefined')))
		{
			currentConfig = dfpConfig['shiksha'][bannerPlace];
		}

		else if(bannerPlace) {
			currentConfig = typeof dfpConfig[dfpParams.parentPage] != 'undefined' ? dfpConfig[dfpParams.parentPage][bannerPlace] : null;
		    if(typeof currentConfig == 'undefined' || !currentConfig)
		    {
		        currentConfig = dfpConfig['DFP_Others'][bannerPlace];
		    }
		}
		if(typeof currentConfig != 'undefined' && currentConfig)
		{
			DFPBannerUtil.displayDFPBanner(currentConfig['elementId']);
		}
	}

	handleClickOnCrossButton(){
		document.querySelectorAll('.stickyBanner').forEach((item, index) => {
      		item.classList.add('display-none');
    	})

	}

	render()
	{
		
		if(typeof window !='undefined' && window.mobileApp && this.props.bannerPlace=='sticky_banner'){
			return null;
		}
		const {dfpParams,bannerPlace,isAmp,parentPage} = this.props;
		let dfpStyle = {};
		if(parentPage && dfpConfig[parentPage] && dfpConfig[parentPage][bannerPlace]){
			dfpStyle.height = dfpConfig[parentPage][bannerPlace]['height'];
			dfpStyle.width = dfpConfig[parentPage][bannerPlace]['width'];
			if(shikshaCommonDFP.indexOf(bannerPlace) == -1){
				dfpStyle.background = '#eee';
			}
		}else {
			//dfpStyle.height = 0;
			//dfpStyle.width = 0;
			//dfpStyle.margin = 0;
		}
		if(typeof dfpParams == "undefined" || typeof dfpParams.parentPage == 'undefined' || (typeof dfpParams == 'object' && getObjectSize(dfpParams) == 0))
			return <div className="dfp-wraper"><div className="dfp-add"><div className='dfp-f-h' style={dfpStyle}></div></div></div>;
		var currentConfig;
		if(shikshaCommonDFP.indexOf(bannerPlace) !== -1 && (typeof dfpConfig[dfpParams.parentPage] == 'undefined' || (typeof dfpConfig[dfpParams.parentPage] != 'undefined' && typeof dfpConfig[dfpParams.parentPage][bannerPlace] == 'undefined')))
		{
			currentConfig = dfpConfig['shiksha'][bannerPlace];
		}
		else if(bannerPlace == "Client" &&  dfpConfig[dfpParams.parentPage]['client'] == 0)
			return <div className="dfp-wraper"><div className="dfp-add"><div className='dfp-f-h' style={dfpStyle}></div></div></div>;
		else if(bannerPlace) {
			currentConfig = typeof dfpConfig[dfpParams.parentPage] != 'undefined' ? dfpConfig[dfpParams.parentPage][bannerPlace] : null;
		    if(typeof currentConfig == 'undefined' || !currentConfig)
		    {
		        currentConfig = dfpConfig['DFP_Others'][bannerPlace];
		    }
		}
		if(typeof currentConfig == 'undefined' || !currentConfig)
		{
			return <div className="dfp-wraper"><div className="dfp-add"><div className='dfp-f-h' style={dfpStyle}></div></div></div>;
		}
		let dfpParamsString ;
		if(isAmp){
			dfpParamsString = JSON.stringify(dfpParams);
		}
		return (
			 <React.Fragment>
			{!isAmp && (bannerPlace == 'sticky_banner' || bannerPlace == 'sticky_banner_desktop') ?
				 (<a href='javascript:void(0);' id='crossStickyBanner' onClick={this.handleClickOnCrossButton.bind(this)} >
				 &times;
			 </a>)
				 :null}
			{(!isAmp)?(<div className="dfp-wraper">
		        	  <div className="dfp-add">
		        	    <div id={currentConfig['elementId']} className="dfp-f-h" style={dfpStyle}>
		        	    </div>
		        	  </div>
		        	</div>):<div className="dfp-amp-ad">	
		        	<amp-ad width={currentConfig['width']} height={currentConfig['height']} type="doubleclick" data-slot={currentConfig['slotId']} json={'{"targeting":'+decodeHtmlEntities(dfpParamsString)+'}'}></amp-ad>
		        </div>}
		        </React.Fragment>
			);
	}
}

function mapStateToPropsDfpParams(state)
{
	return {
		dfpParams : typeof state.dfpParams != 'undefined' ? state.dfpParams : {},
	}
}

DFPBannerComponent.defaultProps = {
  isAmp : false
}

const DFPBannerLeaderBoard = connect(mapStateToPropsDfpParams)(DFPBanner);
const DFPBannerTempalte = connect(mapStateToPropsDfpParams)(DFPBannerComponent);



export  {
	DFPBannerLeaderBoard,
	DFPBannerTempalte
}
