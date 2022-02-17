import React from 'react';
import './../assets/ExamMenu.css';
import Anchor from './../../reusable/components/Anchor';
import Analytics from './../../../modules/reusable/utils/AnalyticsTracking';
import {isUserLoggedIn} from "../../../utils/commonHelper";
import PropTypes from 'prop-types';

class ExamMenu extends React.Component{
	constructor(props)
	{
		super(props);
		this.state      = {sectionPos:0, listHeight:0};
		this.footerPos  = 0;
		this.makeSticky = this.makeSticky.bind(this); //bind function once
		this.wHeight    = 0;
		this.menuOffsetTop = 0;
		this.stickyPosition= 0
		if(this.props.deviceType == 'desktop'){
			this.desktopMenuSticky = this.desktopMenuSticky.bind(this);
		}
	}

	componentDidMount(){
		let footerId       = (this.props.deviceType == 'desktop') ? 'footer' : 'page-footer';
		this.footerPos     = (document.getElementById(footerId)) ? document.getElementById(footerId).offsetTop : 0;
		this.wHeight    = (window.outerHeight>0) ? window.outerHeight : window.innerHeight; // window.innerHeight for safari

		if(this.props.deviceType == 'desktop'){
			let totalChild = document.getElementById('listContainer').childNodes.length;
			this.stickyPosition  = document.getElementById('pwa_examwraper').offsetTop-10;
			if(totalChild>4){
				document.getElementById('expended').classList.remove('hide');
			}
			window.addEventListener("scroll", this.desktopMenuSticky);
			this.manageTopSectionPos();
		}else{
			
			
	        this.menuOffsetTop = (document.getElementById('menu-section')) ? document.getElementById('menu-section').offsetTop : 0;
			window.addEventListener("scroll", this.makeSticky);
			this.setSectionPosition();
		}
	}

	setSectionPosition(){
		if(document.getElementById('listContainer')){
			let listHeight   = document.getElementById('listContainer').offsetHeight;
			let secPos       = this.menuOffsetTop + listHeight;
			this.setState({sectionPos: secPos, listHeight:listHeight});	
		}
	}

	componentWillUnmount(){
		window.removeEventListener("scroll", this.makeSticky);
		window.removeEventListener("scroll", this.desktopMenuSticky);
    }

    desktopMenuSticky = () =>{
    	let wScroll = window.scrollY;
    	let topCard = document.getElementById('examPageHeader');
    	let stikcyTitle = document.getElementById('pageTitle');
    	let originalTitle = stikcyTitle.getAttribute('data-originalHeading');
    	if(wScroll > this.stickyPosition && wScroll < (this.footerPos-100)){
			stikcyTitle.innerHTML = document.getElementById('stikcyTitle').innerHTML;
    		topCard.classList.add('sticky');
    		topCard.style.top = '-'+topCard.offsetHeight+'px';
    		
    		if(this.props.changeCTATrackingKeyIdWhenSticky){
				this.props.changeCTATrackingKeyIdWhenSticky();
			}
    	}else{
			stikcyTitle.innerHTML = originalTitle;
			topCard.classList.remove('sticky');	
    		if(this.props.changeCTATrackingKeyIdWhenNonSticky){
				this.props.changeCTATrackingKeyIdWhenNonSticky();
			}
    	}
    }

	makeSticky = () =>{
        let wScroll = window.scrollY;
        let menuEle = document.getElementById('menuBox');
        let menuList= document.getElementById('listContainer');
        let ctaEle  = document.getElementById('examBtmCTA');
        let iconEle = document.getElementById('expended');
        let wHeight = (window.outerHeight>0) ? wScroll+window.outerHeight : wScroll+window.innerHeight; // window.innerHeight for safari
        
        if(wScroll > this.state.sectionPos && wHeight < this.footerPos){
        	if(!menuList.classList.contains('clickCall')){
        		iconEle.classList.remove('expended');
        		menuList.classList.add('collapse');	
        	}
        	document.getElementById('main_menu_section').style.height = this.state.listHeight+'px';
        	menuEle.classList.add('sticky');
        	if(ctaEle){
	        	ctaEle.classList.remove('hide');
	        	ctaEle.classList.add('exm-BtmsSticky');
        	}
        }else if(wScroll > this.state.sectionPos && wHeight > this.footerPos){
        	if(!menuList.classList.contains('clickCall')){
	        	iconEle.classList.remove('expended');
	        	menuList.classList.add('collapse');
        	}
        	document.getElementById('main_menu_section').style.height = this.state.listHeight+'px';
        	menuEle.classList.add('sticky');
        }else{
        	menuEle.classList.remove('sticky');
        	document.getElementById('main_menu_section').style.height = '';
        	if(ctaEle){
        		ctaEle.classList.add('hide');
        		ctaEle.classList.remove('exm-BtmsSticky');	
        	}
        }

        let footerOffset = document.getElementById('page-footer').offsetTop;
        let thresoldPos = (footerOffset - this.wHeight);
        if(wScroll > thresoldPos && ctaEle){	
        	ctaEle.classList.add('hide');
        	ctaEle.classList.remove('exm-BtmsSticky');
        }else if(wScroll > this.state.sectionPos && wScroll < thresoldPos && ctaEle){	
        	ctaEle.classList.remove('hide');
        	ctaEle.classList.add('exm-BtmsSticky');
        }
    };

	toggleMenu(e){
	    var ele = e.currentTarget;
	    if(this.props.deviceType == 'desktop'){
	    	this.desktopMenuHandler(ele);
	    }else{
	    	this.mobileMenuHandler(ele);
	    }   
	}

	mobileMenuHandler(ele){
		let menuEle = document.getElementById('listContainer');
	    	menuEle.classList.add('clickCall');
	    if(menuEle.classList.contains("collapse")){
	    	ele.classList.add('expended');
	    	menuEle.classList.remove('collapse');
	    }else{
	        ele.classList.remove('expended');
	        menuEle.classList.add('collapse');
	    }
	    this.manageTOCPosition();
	    this.setSectionPosition();
	    this.closeTOC();
	}

	desktopMenuHandler(ele){
		let menuEle = document.getElementById('listContainer');
		if(ele.classList.contains("expended")){
	    	ele.classList.remove('expended');
	    	let childNode = menuEle.childNodes;
			let totalChild= childNode.length;
			let lastChild = menuEle.lastChild;
			if(lastChild.classList.contains("active") && totalChild>5){
				childNode[totalChild-2].classList.add('hide');
				childNode[totalChild-3].classList.add('hide');
			}else if(lastChild.classList.contains("active") && totalChild<=5){
				childNode[totalChild-2].classList.add('hide');
			}else if(totalChild<=5){
				lastChild.classList.add('hide');
			}else if(totalChild>5){
				let items = menuEle.getElementsByTagName("li");
				for (let i = 0; i < items.length; ++i) {
				    if(items[i].classList.contains("active")){
				  		if(i>3){
				  			childNode[i-1].classList.add('hide');
				  			childNode[i+1].classList.add('hide');
				  		}else{
				  			childNode[4].classList.add('hide');
				  			childNode[5].classList.add('hide');
				  		}
				    }
				}
			}

	    }else{
	        ele.classList.add('expended');
			let items = menuEle.getElementsByTagName("li");
			for (let i = 0; i < items.length; ++i) {
			  if(items[i].classList.contains("hide")){
			  		items[i].classList.remove("hide");
			  }
			}
	    }
	    this.manageTopSectionPos();
	}

	manageTopSectionPos(){
		document.getElementById('topCover').style.minHeight = document.getElementById('examPageHeader').offsetHeight+'px';
	}

	manageTOCPosition(){
		if(document.getElementById('tocBox')){
			var tocPosition = document.getElementById('listContainer').offsetHeight;
	    	document.getElementById('tocBox').style.top = tocPosition+'px';	
		}
	}

	closeTOC(){
		if(document.getElementById("tocBox") && document.getElementById("tocBox").classList.contains('toc-fixed')){
			document.getElementById("table-cnt").checked = false;
		}
	}

	trackNavEvent(nextPageName)
	{
		let deviceLabel = (this.props.deviceType === 'desktop') ? 'DESK' : 'MOB';
		let eventCategory = '';
		if(this.props.activeSection === 'homepage'){
			eventCategory = 'EXAM PAGE';
		}else{
			eventCategory = 'EXAM ' + this.props.sectionNameMapping[this.props.activeSection].toUpperCase() + ' PAGE';
		}
		let actionLabelPrefix = nextPageName.toUpperCase().replace(' ', '_');
		let actionLabel = actionLabelPrefix + '_NAVIGATION_' + eventCategory.replace(' ','_') +'_'+deviceLabel;
		let labelName = isUserLoggedIn() ? 'Logged-In' : 'Non-Logged In';
		Analytics.event({category : eventCategory, action : actionLabel, label : labelName});
	}

	createLinks(){
		let listItem = new Array()
		let sectionName = this.props.sectionName;
		let sectionUrl  = this.props.sectionUrl;
		let sectionNameMapping = this.props.sectionNameMapping;
		let activeSection = this.props.activeSection;
		let item = '';
		let bucketLenth = Math.ceil(sectionName.length/3);
		let liCount = 0;
		for(var i=0;i<sectionName.length;i+=3){
			liCount++;
			let activeLi = '';
			if(sectionName[i+2]){
				if(activeSection == sectionName[i] || activeSection == sectionName[i+1] || activeSection == sectionName[i+2]){
					activeLi = 'active';
				}
				item = <React.Fragment>
						<Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i]])} className={(activeSection == sectionName[i]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i]]} >{sectionNameMapping[sectionName[i]]}</Anchor>
						<Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i+1]])} className={(activeSection == sectionName[i+1]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i+1]]} >{sectionNameMapping[sectionName[i+1]]}</Anchor>
						<Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i+2]])} className={(activeSection == sectionName[i+2]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i+2]]} >{sectionNameMapping[sectionName[i+2]]}</Anchor></React.Fragment>
			}else if(sectionName[i+1]){
				if(activeSection == sectionName[i] || activeSection == sectionName[i+1]){
					activeLi = 'active';
				}
				item = <React.Fragment>
						<Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i]])} className={(activeSection == sectionName[i]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i]]} >{sectionNameMapping[sectionName[i]]}</Anchor>
						<Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i+1]])} className={(activeSection == sectionName[i+1]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i+1]]} >{sectionNameMapping[sectionName[i+1]]}</Anchor>
						</React.Fragment>
			}else{
				if(activeSection == sectionName[i]){
					activeLi = 'active';
				}
				item = <Anchor onClick={this.trackNavEvent.bind(this,sectionNameMapping[sectionName[i]])} className={(activeSection == sectionName[i]) ? "sec-a active" : "sec-a"} to={sectionUrl[sectionName[i]]} >{sectionNameMapping[sectionName[i]]}</Anchor>;
			}
			
			if(item && this.props.deviceType == 'desktop' && liCount>4){
				listItem.push(<li key={i} className={activeLi+' hide'}>{item}</li>);
			}else if(item){
				listItem.push(<li key={i} className={activeLi}>{item}</li>);
			}
			
		}
		return listItem;
	}

	render()
	{
		if(this.props.sectionName =='' || this.props.sectionName ==null){
			return null;
		}
		return (
			<React.Fragment>
				<div id="main_menu_section">
					<div className="nav-col nav-bar color-w pos-rl" id="menu-section">
		              <div className="nav-tabs nav-lt">
							   <div className="chp-nav" id="menuBox">
							      <div className="chp-navList">
							         <span className={(this.props.deviceType=='desktop') ? "expnd-circle hide" : "expnd-circle"} id="expended" onClick={this.toggleMenu.bind(this)}><span className="rippleefect ib-circle"><i className="expnd-switch"></i></span></span>
							            <ul className="l2Menu-list collapse" id="listContainer">
							               {this.createLinks()}
							            </ul>
							      </div>
							   </div>
							</div>            
						</div>
						</div>
			</React.Fragment>
		)
	}
}
ExamMenu.defaultProps = {
	deviceType : 'mobile'
};

ExamMenu.propTypes = {
	deviceType : PropTypes.string,
	wHeight : PropTypes.number, 
	changeCTATrackingKeyIdWhenSticky : PropTypes.func, 
	changeCTATrackingKeyIdWhenNonSticky : PropTypes.func,
	activeSection : PropTypes.string,
	sectionNameMapping : PropTypes.object,
	sectionUrl : PropTypes.object,
	sectionName : PropTypes.array
}

export default ExamMenu;