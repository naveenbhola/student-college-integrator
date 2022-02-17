import React from 'react';
import './../assets/BackTop.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';

class BackTop extends React.Component{
	constructor()
	{
		super();
		this.show = false;
		this.afterFold = 1.5;
		this.enableBackToTop = this.enableBackToTop.bind(this);
        	this.defaultBottom = 50;
	        this.bottomStickyList = ['examBtmCTA','clpBtmSticky','stickyBanner','cpSticky','cp-btmSticky','chpBtmCTA']; // these are the bottom sticky ID's    
	}

	componentDidMount(){
		window.addEventListener("scroll", this.enableBackToTop);
	}

	componentWillUnmount(){
		window.removeEventListener("scroll", this.enableBackToTop);
    }

    trackEvent(){   
        Analytics.event({category : 'SHIKSHA_PWA', action : 'BACK_TO_TOP_CLICK', label : 'SHIKSHA_PWA_BACK_TO_TOP'});
    }

    enableBackToTop = () =>{
        let ele     = document.getElementById('backTop');
    	let wScroll = window.scrollY;
    	let wHeight = (window.outerHeight>0) ? window.outerHeight : window.innerHeight; // window.innerHeight for safari

        if(!ele){
            return;
        }

        let bottom = this.managePosition();
        let chatIconBottom = bottom;
  		if(window.location.pathname == '/'){ // hide for homePage only
            ele.style.bottom = '0px';
            ele.classList.remove('backShow');
            this.manageChatIconPostion(chatIconBottom);        
  			return true;
  		}
        
    	if(wScroll > (wHeight*this.afterFold)){
    		this.show = true;
            ele.style.bottom = bottom+'px';
    		ele.classList.add('backShow'); // show back to top
            chatIconBottom = bottom + 45;
    	}else if(wScroll < (wHeight*this.afterFold) && wScroll > wHeight && this.show){
    		this.show = true;
            ele.style.bottom = bottom+'px';;
    		ele.classList.add('backShow');
            chatIconBottom = bottom + 45;
    	}else{
            ele.style.bottom = '0px';
            this.show = false;
            ele.classList.remove('backShow');
            chatIconBottom = bottom;
    	}

        this.manageChatIconPostion(chatIconBottom);        
    }

    manageChatIconPostion(bottomPos){
        if(document.getElementsByClassName('primary-chat-icon') && document.getElementsByClassName('primary-chat-icon')[0] && bottomPos >0){
            document.getElementsByClassName('primary-chat-icon')[0].style.bottom = bottomPos + 'px';
	    if(document.getElementsByClassName('chat--prompt') && document.getElementsByClassName('chat--prompt')[0])
		    document.getElementsByClassName('chat--prompt')[0].style.bottom = bottomPos + 'px';
        }
    }

    goToTop(){
    	window.scrollTo(0,0);
        this.trackEvent();
        setTimeout(()=>{ this.manageChatIconPostion(this.defaultBottom); },100);             
    }

    managePosition(){
        let elePos       = document.getElementById('backTop');
        let stikcyHeight = 0;
        if(elePos){
            elePos = elePos.offsetHeight;
            for(var i in this.bottomStickyList){
                let ele = document.getElementById(this.bottomStickyList[i]);
                if(this.bottomStickyList[i] == 'clpBtmSticky' && ele){
                    stikcyHeight += (ele.style.display == 'block') ? ele.offsetHeight : 0;
                }else if(this.bottomStickyList[i] == 'stickyBanner' && ele){
                    stikcyHeight += (!ele.classList.contains('display-none')) ? ele.offsetHeight : 0;
                }else if(this.bottomStickyList[i] == 'examBtmCTA' && ele){
                    stikcyHeight += (ele.classList.contains('exm-BtmsSticky')) ? ele.offsetHeight : 0;
                }else if((this.bottomStickyList[i] == 'cpSticky' || this.bottomStickyList[i] == 'chpBtmCTA') && ele){
                    stikcyHeight += (!ele.classList.contains('hide')) ? ele.offsetHeight : 0;
                }else if(this.bottomStickyList[i] == 'cp-btmSticky' && ele){
                    stikcyHeight += (ele.classList.contains('button-fixed')) ? ele.offsetHeight : 0;
                }
            }
        }   
        return (stikcyHeight) ? stikcyHeight+elePos : this.defaultBottom;
    }

	render()
	{
		return (
			<React.Fragment>
				<a className="back-toTop" id="backTop" onClick={this.goToTop.bind(this)}></a>
			</React.Fragment>
		)
	}
}
export default BackTop;
