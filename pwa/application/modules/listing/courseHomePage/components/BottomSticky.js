import React,{Component} from 'react';
import './../assets/CHPBottomSticky.css';
import {DFPBannerTempalte} from './../../../reusable/components/DFPBannerAd';
import DownloadGuide from './DownloadGuide';
import PropTypes from 'prop-types';

class BottomSticky extends Component{
	constructor(props){
		super(props);
		this.wHeight    = 0;
		this.stickyPos  = 0;
		this.defaultStickyPos = 230; // height of top banner
		this.makeSticky = this.makeSticky.bind(this); //bind function once
		this.askExperts = this.askExperts.bind(this);
	}

	componentDidMount(){
		this.stickyPos  = (document.getElementById('chpNavSticky')) ? document.getElementById('chpNavSticky').offsetTop : 0;
		this.wHeight    = (window.outerHeight>0) ? window.outerHeight : window.innerHeight; // window.innerHeight for safari
		window.addEventListener("scroll", this.makeSticky);	
	}

	componentWillUnmount(){
		window.removeEventListener("scroll", this.makeSticky);
    }

    makeSticky = () =>{
	    let wScroll    = window.scrollY;
	    this.stickyPos = (this.stickyPos) ? this.stickyPos : this.defaultStickyPos;
    	if(this.props.deviceType != 'desktop'){
	        let ele = document.getElementById('chpBtmCTA');
	        let footerPos = (document.getElementById('page-footer')) ? document.getElementById('page-footer').offsetTop : 0;
	        	footerPos = (footerPos) ? footerPos - this.wHeight : 0;
	        if(wScroll < this.stickyPos || wScroll > footerPos){
	        	ele.classList.add('hide');
	        }else if(wScroll > this.stickyPos){
	        	ele.classList.remove('hide');
	        }
	    }
    };

    askExperts(){
    	let askQuestionUrl = '/mAnA5/AnAMobile/getQuestionPostingAmpPage?fromwhere=chpAskBottom';
    	window.location.href = this.props.config.SHIKSHA_HOME+askQuestionUrl;
    }

	render(){
		return(
				<div className="ctp-SrpBtnDiv1 hide" id="chpBtmCTA">
				  <div className='stickyBanner' id='stickyBanner'><DFPBannerTempalte parentPage='shiksha' bannerPlace="sticky_banner" /></div>
				    <div className="btn-table chp">
				      <div className="dnld-btn">
				        <button className="button button--secondary" onClick={this.askExperts}>Ask Experts</button> 
				      </div> 
				      { !this.props.isPdfCall && typeof this.props.chpData != 'undefined' && this.props.chpData.guideUrl ? <DownloadGuide category={this.props.gaTrackingCategory} chpData={this.props.chpData} widget="bottomSticky" deviceType={this.props.deviceType}/> : null}
				  </div>
				</div>
			);
	}
}
BottomSticky.propTypes = {
	chpData : PropTypes.object,
	isPdfCall : PropTypes.bool,
	deviceType : PropTypes.string,
	config : PropTypes.object
}
export default BottomSticky;