import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../../assets/desktop/CategoryFold.css'
import Anchor from "../../../reusable/components/Anchor";
import {event} from "../../../reusable/utils/AnalyticsTracking";
let _this = null;
class CategoryFoldComponent extends Component {
	constructor(props)
  	{
  		super(props);
  	}
    _this = this;
    createTabHeadList(CategoryFoldData){
        let listHTML = [];
        if(typeof CategoryFoldData != undefined && CategoryFoldData != null){
            CategoryFoldData.forEach((data, index) =>{
                listHTML.push(<li key={index} onClick={this.switchTabContent.bind(this)} key={index} data-index={index} className={(index == 0) ? 'active' : ''}>{data.name}</li>);
            });
        }
        return listHTML;
    }
    getTabContentLinkWidget = (listData, childData, childIndex) => {
        if(childData.shikshaUrl.urlHost === 'relative'){
            return (
                <Anchor data-type={childData.className} onClick={this.trackEvent.bind(this, listData.name, childData.title)} to={childData.shikshaUrl.url} >
                    <div className={childData.className}> <span> <i onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}></i> </span> <strong onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)}  onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}>{childData.title}</strong>
                        <p onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)}  onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}>{childData.subtitle}</p>
                        <p className="answer" onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}> {childData.description}</p>
                    </div>
                </Anchor>
            );
        }else{
            return (
                <a data-type={childData.className} onClick={this.trackEvent.bind(this, listData.name, childData.title)} href={childData.shikshaUrl.url}>
                    <div className={childData.className}> <span> <i onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}></i> </span> <strong onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)}  onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}>{childData.title}</strong>
                        <p onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)}  onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}>{childData.subtitle}</p>
                        <p className="answer" onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+childIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+childIndex)}> {childData.description}</p>
                    </div>
                </a>
            );
        }
    };
    createMarkup = (htmlText) => { 
        return {__html: htmlText}; 
    };
    getOtherTabContentLinkWidget = (listData, childData, cIndex, i) => {
        if(childData.shikshaUrl.urlHost === 'relative'){
            return <Anchor data-type={childData.className} onClick={this.trackEvent.bind(this, listData.name, childData.title)} to={childData.shikshaUrl.url}>
                <i onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+ i +'_'+cIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+ i +'_'+cIndex)}></i><strong onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+ i +'_'+cIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+ i +'_'+cIndex)}><strong dangerouslySetInnerHTML={this.createMarkup(childData.title)} /></strong></Anchor>
        }else{
            return <a data-type={childData.className} onClick={this.trackEvent.bind(this, listData.name, childData.title)} href={childData.shikshaUrl.url}>
                <i onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+ i +'_'+cIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+ i +'_'+cIndex)}></i><strong onMouseLeave={this.mouseOutAction.bind(this, listData.name+'_'+ i +'_'+cIndex)} onMouseOver={this.hoverAction.bind(this, listData.name+'_'+ i +'_'+cIndex)}><strong dangerouslySetInnerHTML={this.createMarkup(childData.title)} /></strong></a>;
        }
    };

    createTabContentsDataList(listData){
        let listHTML = [];
        if(typeof listData != undefined && listData != null){
            listData.child.forEach((childData, childIndex) =>{
                listHTML.push(
                    <li key={childIndex} data-matchkey={listData.name+'_'+childIndex} className={(childIndex == 0)?"first":""}>
                        {this.getTabContentLinkWidget(listData, childData, childIndex)}
                    </li>
                )
            });
        }
        return listHTML;
    }
    createOtherTabContentsDataList(listData){
        let finalHTML = [];
        if(typeof listData != undefined && listData != null){
            let listHTML = [], start = 0, end = 0, dataArr = null;
            let totalLength = parseInt(listData.child.length);
            if(totalLength <=0){
                return;
            }
            let liLength = Math.ceil(totalLength/10);
            for(let i=0;i<liLength;i++){
                listHTML = [];
                start = i*10;
                end   = (i+1)*10;
                dataArr = listData.child.slice(start, end);
                    dataArr.forEach((childData, cIndex) =>{
                        let item = <div key={listData.name+'_'+ i +'_'+cIndex} data-matchkey={listData.name+'_'+ i +'_'+cIndex} className={childData.className}>
                            {this.getOtherTabContentLinkWidget(listData, childData, cIndex, i)}
                        </div>;
                        listHTML.push(item);
                });    
                finalHTML.push(<li key={i}>{listHTML}</li>);
            } 
        }
        return finalHTML;
    }

    createTabContents(contentData){
        let listHTML = [];
        if(typeof contentData != undefined && contentData != null){
            contentData.forEach((data, index) =>{
                let temEle = '';
                if(data.name != "MORE"){
                    temEle= <ul className="cFL">{this.createTabContentsDataList(data)}</ul>;
                }else if(data.name == "MORE"){
                    temEle=<div id="homepageOtherStreamsSlider" className="sliderContainer otherSlider-v2"> 
                            {(data.child.length > 0)?<a className="leftArrow  ar-disable"><i  onClick={this.slideAction.bind(this)}></i></a>:''}
                            <div className="slidingArea slidingArea-v2">
                                <ul className="cFL others">
                                    {this.createOtherTabContentsDataList(data)}
                                </ul>
                            </div> 
                            {(data.child.length > 0)?<a className="rightArrow"><i onClick={this.slideAction.bind(this)}></i></a>:'' }
                        </div>;
                }
                listHTML.push(<div data-index={index} key={index} className={(index == 0)?'ative':'hide'}>{temEle}</div>);
            });
        }
        return listHTML;
    }
    trackEvent(action, label){
        event({category : 'DesktopHomePage', action : action, label : label});
    }

    switchTabContent(e){

        this.trackEvent('Change Category', 'Click');
        // get current clicked index
        let currentElementIndex = e.target.getAttribute('data-index');

        // to switch the content boxes
        let tabContentBoxes = document.querySelectorAll(".tabContent > div");
        tabContentBoxes.forEach(function(currentBox) {
            (currentBox.getAttribute('data-index') == currentElementIndex)?currentBox.style.display = 'block': currentBox.style.display = 'none';
        });

        //make active to the current tab
        let tablist = document.querySelectorAll("._ctFold > li");
        tablist.forEach(function(currentTab) {
            (currentTab.getAttribute('data-index') == currentElementIndex)?currentTab.className = 'active': currentTab.className = '' ;
        });


    }

    mouseOutAction(elem, e){
        document.querySelector('[data-matchkey="'+ elem +'"]').classList.remove('hover');
        e.preventDefault();
    }

    hoverAction(elem, e){
        document.querySelectorAll('[data-matchkey]').forEach((data, index)=>{
            data.classList.remove('hover');
        });
        document.querySelector('[data-matchkey="'+ elem +'"]').classList.add('hover');
        e.preventDefault();
    }
    slideAction(e){
        let elem = e.target.parentNode;
        if(!elem.classList.contains('ar-disable')){
            if(elem.classList.contains('leftArrow')){
                document.querySelector('#homepageOtherStreamsSlider ul.cFL.others').style.left = '0%';
                document.querySelector('a.leftArrow').classList.add('ar-disable');
                document.querySelector('a.rightArrow').classList.remove('ar-disable');
            }else if(elem.classList.contains('rightArrow')){
                document.querySelector('#homepageOtherStreamsSlider ul.cFL.others').style.left = '-100%';
                document.querySelector('a.rightArrow').classList.add('ar-disable');
                document.querySelector('a.leftArrow').classList.remove('ar-disable');
            }
        }
    }

    returnFoldHTML(foldData){
        let listHTML = [];
        if(typeof foldData != undefined && foldData != null){
            listHTML = <section key={1} className="courseBanner">
                            <div className="_cntr">
                                <div className="tabSection">
                                    <ul className="_ctFold">
                                        {this.createTabHeadList(foldData)}
                                    </ul>
                                </div>
                                <div className="tabContent" id="_tabCnt">
                                    {this.createTabContents(foldData)}
                                </div>
                            </div>
                        </section>
        }
        return listHTML;
    }    

    componentDidMount(){
    }
 	
  	render(){
  		return(
            <React.Fragment>
                {this.returnFoldHTML(this.props.CategoryFoldData)}
            </React.Fragment>
      )
  	}

}
export default CategoryFoldComponent;