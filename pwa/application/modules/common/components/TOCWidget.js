import React from 'react';
import './../assets/TOCWidget.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';

class TOCWidget extends React.Component{
	constructor(props)
	{
		super(props);
	}

	componentDidMount(){
		let self = this;
		document.querySelectorAll('.table-content p').forEach(function(ele) {
			ele.addEventListener('click' , function(e){
				let targetEle = e.currentTarget.getAttribute('data-scrol');
				self.goTo(targetEle);
			});
		});
	}

	trackEvent(type)
   	{	
	  	let deviceType = (this.props.deviceType === 'desktop') ? 'DESK' : 'MOB';
	  	let category  = this.props.gaCategory;
	  	let labelName = category+'_TOC_'+type; 
	  	let actionlabel = labelName+'_'+deviceType;
	  	Analytics.event({category : category, action : actionlabel, label : labelName});
   	}

	goTo(targetEle){
		if(document.getElementById(targetEle)){
			this.closeTOC();
			let topSpace   = (this.props.deviceType == 'desktop') ? 120 : 100; 
			let elePositon = document.getElementById(targetEle).getBoundingClientRect().top;
			elePositon = (window.scrollY>elePositon || window.scrollY<elePositon) ? (window.scrollY + elePositon) - topSpace : elePositon;
			if(elePositon>0){
				setTimeout(function(){window.scrollTo(0, elePositon);},50);
				setTimeout(()=>{ this.examMenuToggle();},55);				
			}
			this.trackEvent('Content_Click');
		}
	}

	tocStatus(){
		if(document.getElementById("table-cnt").checked){
			this.trackEvent('Expand');
		}else{
			this.trackEvent('Collapse');
		}
		this.examMenuToggle();
	}

	examMenuToggle(){
		let menuEle = document.getElementById('listContainer');
		if(this.props.deviceType != 'desktop' && menuEle && !menuEle.classList.contains("collapse")){
	    	document.getElementById('expended').click();
		}
	}

	closeTOC(){
		if(document.getElementById("tocBox")){
			document.getElementById("table-cnt").checked = false;
		}
	}

	createLink(){
		let items = '';
		for(var i=0;i<this.props.tocData.length;i++){
			if(typeof this.props.tocData[i]!='undefined' && this.props.tocData[i]){
				items+= this.props.tocData[i];
			}
		}
		return items;
	}

	render()
	{
		if(this.props.tocData == null || this.props.tocData == '' || this.props.tocData =='undefined'  || (this.props.tocData.length ==1 && this.props.tocData[0] !='undefined' && this.props.tocData[0].match(/data-scrol=/g).length<=1)){ //data should be array type
			return null;
		}
		return (
			<React.Fragment>
			<div id="tocContainer">
			<div className="toc-block" id="tocBox">
				<div className="css-acrdn">
			       <input type="checkbox" name="showtable-data" className="toggle-input" id="table-cnt"/>
			       <label onClick={this.tocStatus.bind(this)} htmlFor="table-cnt" className="block-label"><h2>Table of Contents</h2> <i className="rotate-ico"></i> </label>
			       <div className="table-content" dangerouslySetInnerHTML={{ __html : this.createLink()}}></div>
				</div>
			</div>
			</div>
			</React.Fragment>
		)
	}
}
TOCWidget.defaultProps = {
	deviceType : 'mobile'
};
export default TOCWidget;