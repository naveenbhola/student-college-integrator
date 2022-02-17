function slideUp(element) {
	  var elem = document.getElementById(element);
	  elem.style.transition = "all 2s ease-in-out";
	  elem.style.height = "0px";
	}
function slideDown(element) {
  var elem = document.getElementById(element);
  elem.style.transition = "all 2s ease-in-out";
  elem.style.height = "400px";
}

export function stickyNavTabAndCTA()
	{
	  	var ftrh = (typeof document.getElementById('page-footer') != 'undefined' && document.getElementById('page-footer') != null) ? document.getElementById('page-footer').offsetTop : 0; 
		  var sticky = document.getElementById('fixed-card'),
		  scroll = window.scrollY,
		  additionalScroll = scroll+window.innerHeight+20;
		  var sectionTab = (typeof document.getElementById('tab-section') != 'undefined' && document.getElementById('tab-section') != null)  ? document.getElementById('tab-section').offsetTop : 0;
		  if(scroll > sectionTab && additionalScroll< ftrh){
		  	if(sticky)
		  	{
		  		sticky.classList.add('fix');
		    	sticky.style.display = "block";
		  	}
		  }else if(sticky && scroll < sectionTab){
		    sticky.classList.remove('fix');
		    sticky.style.display = "none";
		    document.getElementById('page-header').style.display = "table";
		  }
	}

export function makeShikshaHeaderSticky(params){

	  if (window.scrollY >= params.listingAppBannerHeight && !params.listingScrollflag)
	  {
		  if(typeof document.getElementById('mobAPPBanner') != 'undefined' && document.getElementById('mobAPPBanner') != null) 
		  		//slideUp(document.getElementById('mobAPPBanner'));
		  params.listingScrollflag = true;
		  params.listingRemovePaddingflag = false;
	  }else if(window.scrollY<= params.listingAppBannerHeight ){
		  if(typeof document.getElementById('mobAPPBanner') != 'undefined' && document.getElementById('mobAPPBanner') != null)
		    	//slideDown(document.getElementById('mobAPPBanner'));
	 	  
	    params.listingScrollflag = false;
	  }
		

	  params.st = window.scrollY;
	  if(params.st==params.lastScrollTop){
	  	 //do nothing;
	  }else if(params.st < params.lastScrollTop && params.listingScrollflag){//up scroll
	    if(params.st > (params.listingAppBannerHeight+params.shikshaBannerHeight) && !params.listingRemovePaddingflag) {
	      params.listingRemovePaddingflag = true;
	    }
	    if(params.st < (params.listingAppBannerHeight+params.shikshaBannerHeight)) {
	    	document.getElementById('fixed-card').style.display = "none";	     		
	     	document.getElementById('page-header').style.position = "relative";
	    }
	    if (document.getElementById('tab-section') && params.st >= document.getElementById('tab-section').offsetTop && params.listingScrollUpDownflag) {
	      params.listingScrollUpDownflag = false;
	      document.getElementById('page-header').style.display = "table";
	      document.getElementById('page-header').style.position = "fixed";

	      var bannerHeight = 0;
	      if(document.getElementById('mobAPPBanner').style.display!='none')
	      {
	      	bannerHeight = document.getElementById('mobAPPBanner').clientHeight;
	      }

	      var totalHeight = 40;
	      if(document.getElementById('fixed-card'))
	      {
	      	totalHeight += bannerHeight;
	      	if(window.mobileApp){
	      		totalHeight =0;
	      	}
			document.getElementById('fixed-card').style="border-top: 1px solid #eee;top:"+totalHeight+"px";
	      }
	    }   
	  }else{//down scroll
	    params.listingScrollUpDownflag = false;
	    if (document.getElementById('fixed-card') && document.getElementById('tab-section') && document.getElementById('fixed-card').style.display == 'block' && params.st >= document.getElementById('tab-section').offsetTop && !params.listingScrollUpDownflag)
	    {
	      document.getElementById('page-header').style.display = "none";
	      document.getElementById('page-header').style.position = "relative";
	      document.getElementById('fixed-card').style.display = "block";
	      document.getElementById('fixed-card').style.top="0px";
	      params.listingScrollUpDownflag = true;
	    }
	    else
	    {
	    	if(document.getElementById('fixed-card') && document.getElementById('fixed-card').style.display == "none")
	    			document.getElementById('page-header').style.position = "relative";
	    }
	  }
	  params.lastScrollTop = params.st;	
}

export function getEncodedUrlParams(url){
	let paramsObj = {};
	paramsObj['url'] = url;
	return btoa(JSON.stringify(paramsObj));
}

export function isErrorPage()
{
	let html404 = document.getElementById('notFound-Page');
	return (html404 && html404.innerHTML);
}

export function isServerSideRenderedHTML(id)
{
  let htmlNode = document.getElementById(id);
  return ((htmlNode && htmlNode.innerHTML) || isErrorPage());
}
