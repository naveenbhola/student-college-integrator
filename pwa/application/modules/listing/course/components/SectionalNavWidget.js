import PropTypes from 'prop-types'
import React from 'react';
import './../assets/sectionNavTab.css';
import './../assets/courseCommon.css';
import Analytics from './../../../reusable/utils/AnalyticsTracking';


class sectionalNavWidget extends React.Component
{
	constructor(props)
	{
		super(props);
		this.state = {

		}
		this.lastScrollLeft=0,
			this.controlAnimateToDiv=false,
			this.listingScrollflag = false,
			this.listingScrollUpDownflag= false,
			this.listingRemovePaddingflag =false,
			this.listingAppBannerHeight='0',
			this.st = 0,
			this.shikshaBannerHeight = 0;
		this.showHeader = true;
	}

	componentDidMount(){
		this.lastScrollTop = window.scrollY;
		this.stickyNavTabAndCTA();
		this.makeCurrentSectionActive();

		var self = this;
		var navClass = document.querySelectorAll("[elementtofocus]");
		for (let i = 0; i < navClass.length; i++) {
			navClass[i].addEventListener('click', function(event){
				event.preventDefault();
				var ele = this.getAttribute("elementtofocus");
				self.animateTodiv(ele);
			});
		}
		this.scrollStop();
	}




	componentDidUpdate(){
		var self = this;
		this.lastScrollTop = window.scrollY;
		var navClass = document.querySelectorAll("[elementtofocus]");
		for (let i = 0; i < navClass.length; i++) {
			navClass[i].addEventListener('click', function(event){
				event.preventDefault();
				var ele = this.getAttribute("elementtofocus");
				self.animateTodiv(ele);
			});
		}
		self.scrollStop();
	}

	scrollStop() {
		// Setup scrolling variable
		var isScrolling;
		var self = this;

		// Listen for scroll events
		window.scroll = null;
		window.onscroll = function ( ) {
			self.stickyNavTabAndCTA();
			window.clearTimeout( isScrolling );
			//isScrolling = setTimeout(function() {
			self.scrollFinished();
			//}, 0);
		}
	}
	componentWillUnmount()
	{
		window.scroll = null;
		window.onscroll = function(){

		}
	}

	scrollFinished()
	{
		this.makeCurrentSectionActive();
		this.makeShikshaHeaderSticky();
	}


	offset(el) {
		var rect = el.getBoundingClientRect(),
			scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
			scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
	}

	makeCurrentSectionActive(){
		var documentScrollLeft = document.getElementsByClassName('clg-navList')[0].scrollLeft;
		if (this.lastScrollLeft != documentScrollLeft) {
			this.lastScrollLeft = documentScrollLeft;
			return false;
		}
		var tuppleObjs = document.getElementsByClassName("listingTuple");
		var sectionEndPos = 100;
		var navClass = document.querySelectorAll("[elementtofocus]");
		for (let i = 0; i < navClass.length; i++) {
			navClass[i].classList.remove("active");
		}
		var self = this;
		for(let i=0;i<tuppleObjs.length;i++){

			var sectionBottomPosition =  (parseInt(tuppleObjs[i].offsetTop) + parseInt(tuppleObjs[i].offsetHeight)- parseInt(window.scrollY));
			if(sectionBottomPosition > sectionEndPos){
				var id = tuppleObjs[i].getAttribute("id");
				var obj = document.querySelectorAll("[elementtofocus='"+id+"']");

				for(var j=0;j<obj.length;j++){
					if(typeof obj[j] == 'undefined' || typeof obj[j].offsetLeft == 'undefined') {
						return false;
					}

					var win = window;
					var windowBounds = {left : win.scrollX, right: win.scrollX + win.outerWidth};
					if(self.offset(obj[0]).left < windowBounds.left){
						document.getElementsByClassName('clg-navList')[0].scrollLeft = document.getElementsByClassName('clg-navList')[1].scrollLeft = obj[0].offsetLeft - 5;
					}else if((obj[0].offsetLeft + obj[0].offsetWidth) > windowBounds.right){
						document.getElementsByClassName('clg-navList')[0].scrollLeft = document.getElementsByClassName('clg-navList')[1].scrollLeft = (document.getElementsByClassName('clg-navList')[0].scrollLeft + parseInt(obj[0].offsetLeft + obj[0].offsetWidth) - parseInt(windowBounds.right));
					}
					obj[j].classList.add("active");
				}
				return false;
			}
		}
	}

	isFullyOnScreen(ele)
	{
		var win = window;
		var viewport = {
			top : win.scrollY,
			left : win.scrollX
		};
		viewport.right = viewport.left + win.outerWidth;
		viewport.bottom = viewport.top + win.outerHeight;
		var bounds = this.offset(ele);
		if(typeof bounds == 'undefined') {
			return false;
		}
		bounds.right = bounds.left + ele.outerWidth;
		bounds.bottom = bounds.top + ele.outerHeight;
		return (!(viewport.right < bounds.right || viewport.left > bounds.left || viewport.bottom < bounds.bottom || viewport.top > bounds.top));
	}

	stickyNavTabAndCTA()
	{
		var ftrh = (typeof document.getElementById('page-footer') != 'undefined' && document.getElementById('page-footer') != null) ? document.getElementById('page-footer').offsetTop : 0;
		var sticky = document.getElementById('fixed-card'),
			scroll = window.scrollY,
			additionalScroll = scroll+window.innerHeight+20;
		var sectionTab = (typeof document.getElementById('tab-section') != 'undefined' && document.getElementById('tab-section') != null)  ? document.getElementById('tab-section').offsetTop : 0;
		this.showHeader  = true;
		if(scroll > sectionTab && additionalScroll< ftrh){
			sticky.classList.add('fix');
			sticky.style.display = "block";
			document.getElementById('clpBtmSticky').style.display = "block";
			//document.getElementById('stickyCTA').style.display = "block";
			//document.getElementsByClassName('.popover').style.display = "block";
		}else if(scroll < sectionTab){
			sticky.classList.remove('fix');
			sticky.style.display = "none";
			document.getElementById('page-header').style.display = "table";
			document.getElementById('clpBtmSticky').style.display = "none";
		}else{
			document.getElementById('clpBtmSticky').style.display = "none";
			if(this.props.noNavBar){
				document.getElementById('page-header').style.display = "block";
				this.showHeader  = false;
			}
		}
	}


	scrollToY(scrollTargetY, speed, easing) {
		// scrollTargetY: the target scrollY property of the window
		// speed: time in pixels per second
		// easing: easing equation to use
		scrollTargetY = scrollTargetY || 0;
		speed = speed || 2000;
		easing = easing || 'easeOutSine';
		var scrollY = window.scrollY,
			currentTime = 0;

		// min time .1, max time .8 seconds
		var time = Math.max(.1, Math.min(Math.abs(scrollY - scrollTargetY) / speed, .8));

		// easing equations from https://github.com/danro/easing-js/blob/master/easing.js
		var easingEquations = {
			easeOutSine: function (pos) {
				return Math.sin(pos * (Math.PI / 2));
			},
			easeInOutSine: function (pos) {
				return (-0.5 * (Math.cos(Math.PI * pos) - 1));
			},
			easeInOutQuint: function (pos) {
				if ((pos /= 0.5) < 1) {
					return 0.5 * Math.pow(pos, 5);
				}
				return 0.5 * (Math.pow((pos - 2), 5) + 2);
			}
		};

		// add animation loop
		function tick() {
			currentTime += 1 / 60;

			var p = currentTime / time;
			var t = easingEquations[easing](p);

			if (p < 1) {
				window.requestAnimationFrame(tick);

				window.scrollTo(0, scrollY + ((scrollTargetY - scrollY) * t));
			} else {
				window.scrollTo(0, scrollTargetY);
			}
		}

		// call it once to get started
		tick();
	}

	animateTodiv(focusId){
		/* if(focusId.indexOf('=') !== -1 || this.state.controlAnimateToDiv || typeof document.getElementById(focusId) != 'undefined'){
           return;
         }*/
		if(document.getElementById(focusId) ==null){
			return null;
		}
		this.controlAnimateToDiv = true;
		var sections = document.getElementById("fixed-card").getElementsByTagName("a");
		var first = sections[0];
		var additionalTop ;
		var curIndex = 0;
		var focusIndex = 0;
		for(var i = 0;i<sections.length;i++){
			if(sections[i].classList.contains('active')){
				curIndex = i;
			}
			if(sections[i].getAttribute('elementtofocus') == focusId){
				focusIndex = i;
			}
		}
		//if click on already active tab
		if(curIndex == focusIndex){
			this.controlAnimateToDiv = false;
			return;
		}
		additionalTop = document.getElementById('fixed-card').offsetHeight || 68;
		if(focusId == first.getAttribute('elementtofocus')){
			document.getElementsByClassName('clg-navList')[0].scrollLeft = document.getElementsByClassName('clg-navList')[1].scrollLeft =0;
		}
		//to add additional height of sticky header
		if(focusIndex <= curIndex){
			additionalTop += document.getElementsByClassName('header')[0].offsetHeight;
		}
		var scrollTop = document.getElementById(focusId).offsetTop - parseInt(additionalTop);
		this.scrollToY(scrollTop,1500, 'easeInOutQuint');
		this.makeCurrentSectionActive();
		this.controlAnimateToDiv = false;

	}

	slideUp(element) {
		var elem = document.getElementById(element);
		elem.style.transition = "all 2s ease-in-out";
		elem.style.height = "0px";
	}
	slideDown(element) {
		var elem = document.getElementById(element);
		elem.style.transition = "all 2s ease-in-out";
		elem.style.height = "400px";
	}


	makeShikshaHeaderSticky(){
		if (window.scrollY >= this.listingAppBannerHeight && !this.listingScrollflag)
		{
			if(typeof document.getElementById('mobAPPBanner') != 'undefined' && document.getElementById('mobAPPBanner') != null)
			//this.slideUp(document.getElementById('mobAPPBanner'));
				this.listingScrollflag = true;
			this.listingRemovePaddingflag = false;
		}else if(window.scrollY<= this.listingAppBannerHeight ){
			if(typeof document.getElementById('mobAPPBanner') != 'undefined' && document.getElementById('mobAPPBanner') != null)
			//this.slideDown(document.getElementById('mobAPPBanner'));

				this.listingScrollflag = false;
		}


		this.st = window.scrollY;
		if(this.st==this.lastScrollTop){
			//do nothing;
		}else if(this.st < this.lastScrollTop && this.listingScrollflag){//up scroll
			if(this.st > (this.listingAppBannerHeight+this.shikshaBannerHeight) && !this.listingRemovePaddingflag) {
				this.listingRemovePaddingflag = true;
			}
			if(this.st < (this.listingAppBannerHeight+this.shikshaBannerHeight)) {
				document.getElementById('fixed-card').style.display = "none";
				document.getElementById('page-header').style.position = "relative";
			}

			var bannerHeight = 0;
			this.listingScrollUpDownflag = true;
			if(document.getElementById('mobAPPBanner').style.display!='none')
			{
				bannerHeight = document.getElementById('mobAPPBanner').clientHeight;
			}
			var totalHeight = 40;

			if (this.st >= document.getElementById('tab-section').offsetTop && this.listingScrollUpDownflag) {
				this.listingScrollUpDownflag = false;
				totalHeight += bannerHeight;
				document.getElementById('page-header').style.position = "fixed";
				document.getElementById('page-header').style.display = "table";
				document.getElementById('fixed-card').style.borderTop = "1px solid #eee;";
				if(!window.mobileApp){
					document.getElementById('fixed-card').style.top = totalHeight+"px";
				}
			}
		}else{//down scroll
			this.listingScrollUpDownflag = false;
			if (document.getElementById('fixed-card').style.display == 'block' && this.st >= document.getElementById('tab-section').offsetTop && !this.listingScrollUpDownflag)
			{
				document.getElementById('page-header').style.position = "relative";
				if(!this.props.noNavBar && !this.showHeader){
					document.getElementById('page-header').style.display = "none";
				}
				document.getElementById('fixed-card').style.display = "block";
				document.getElementById('fixed-card').style.top="0px";
				this.listingScrollUpDownflag = true;
			}
			else
			{
				if(document.getElementById('fixed-card').style.display == "none"){
					document.getElementById('page-header').style.position = "relative";
				}
			}
		}
		this.lastScrollTop = this.st;
	}

	trackEvent(data)
	{
		if(typeof data == 'undefined')
			return;

		Analytics.event(data);
	}

	createSectonalNavHtml(){
		var sectionalData = (typeof this.props.sectionalNavObj != 'undefined')?this.props.sectionalNavObj:{};
		var sectionalCount = (typeof this.props.sectionalNavCount != 'undefined')?this.props.sectionalNavCount:{};
		var sectionalGaObj = (typeof this.props.sectionalGaObj != 'undefined')?this.props.sectionalGaObj:{};
		//var sectionalKeys = Object.keys(sectionalData);
		//var sectionalValues = Object.values(sectionalData);
		var sectionhtml = [];
		var index = 0;
		for(var i in sectionalData)
		{
			var activeEleClass = '';
			if(index == 0){
				activeEleClass = 'active';
			}
			sectionhtml.push(<li key={'section_'+index} onClick={this.trackEvent.bind(this,sectionalGaObj[i])}><a className={"sec-a rippleefect "+activeEleClass} elementtofocus={i}>{sectionalData[i]} {sectionalCount[i] && <span> ({sectionalCount[i]}) </span>}</a></li>);
			index++;
		}
		return sectionhtml;
	}

	render(){

		// if(this.props.ShowNavBar){
		// 	return (
		// 		null
		// 		);
		// }
		return (
			<div className='clg-nav'>
				<div className='clg-navList'>
					<ul>
						{this.createSectonalNavHtml()}
					</ul>
				</div>
			</div>
		);

	}
}

export default sectionalNavWidget;

sectionalNavWidget.propTypes = {
	noNavBar: PropTypes.any,
	sectionalGaObj: PropTypes.any,
	sectionalNavCount: PropTypes.any,
	sectionalNavObj: PropTypes.any
}