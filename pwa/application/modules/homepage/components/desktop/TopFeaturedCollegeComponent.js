import React, { Component } from 'react';
import PropTypes from 'prop-types';
import '../../assets/desktop/TopFeaturedCollege.css';

class TopFeaturedCollegeComponent extends Component {
  	constructor(props){
    		super(props);
        this.liWidth    = 247;
        this.slideSize  = 5;
        this.slideClick = 1;
        this.totalItemArea = 0;
    }

    uniDirectionalSlider=()=>{
      let itemsList   = parseInt(document.querySelectorAll('.tpfc li').length);
      let totalSlides = Math.ceil(itemsList/this.slideSize);
      let liWidth = this.liWidth*this.slideSize*this.slideClick;
      let ele = document.getElementById('collegeAds');
      if(liWidth<=this.totalItemArea+5 && this.slideClick < totalSlides){
          ele.style.marginLeft = '-'+liWidth+'px';
          this.slideClick = this.slideClick+1;
      }else{
          ele.style.marginLeft = '';
          this.slideClick = 1;
      }
    }

    componentDidMount(){
  	    let ulWidth = document.querySelector('.tpfc').clientWidth;
        let itemsList  = document.querySelectorAll('.tpfc li');
  	    this.liWidth = ulWidth/this.slideSize;
        document.querySelector('.tpfc').style.width = (ulWidth*Math.ceil(itemsList.length/this.slideSize))+'px';

      let totalItemArea = 0;
      if(itemsList.length){
          itemsList.forEach(function(e,row) {
            totalItemArea +=e.offsetWidth;
          });
          this.totalItemArea = ulWidth - 5;
          let calLiWidth = (this.totalItemArea)/this.slideSize;
              calLiWidth = (calLiWidth) ? calLiWidth : this.liWidth;
          itemsList.forEach(function(ele,row){
              ele.style.width = (calLiWidth)+'px';
          });
          document.getElementById('tpfc').style.visibility = 'visible';
      }
    }

    createElement=()=>{
        let ele  = [];
        let data = this.prepareData();
        if(data.length<=0){
          return null;
        }
        let splitTxt1 = '<big>', splitTxt2 = '<small>';
        data.forEach((row,i)=>{
            let eleTextArr = [];
            if(row.displayText.indexOf(splitTxt1) != -1){
                eleTextArr = row.displayText.split(splitTxt1);   
            }else if(row.displayText.indexOf(splitTxt2) != -1){
                eleTextArr = row.displayText.split(splitTxt2);   
            }
            let heading = (eleTextArr.length) ? <strong className="n-bnrSmllTxt">{eleTextArr[0]}<b>{eleTextArr[1]}</b></strong> : <strong className="n-bnrSmllTxt">{row.displayText}</strong>
            let tempEle = <a href={row.targetUrl}>
                    <div>
                        {heading}
                        <p>{row.usp}</p>
                    </div>
                </a>;
            ele.push(<li key={row.bannerId}>{tempEle}</li>);
        });
        return ele;
    }

    shuffle(a) {
      if(typeof window !== 'undefined'){
          return a;
      }
      for (let i = a.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [a[i], a[j]] = [a[j], a[i]];
      }
      return a;
    }

    prepareData(){
      let finalArray  = [];
      let defaultList = this.props.collegeAds.defaults;
      let paidList    = this.props.collegeAds.paid;
      finalArray      = paidList;
      this.shuffle(paidList);
      this.shuffle(defaultList);
      let paidCount = paidList.length;
      if(paidCount % 5 != 0) {
        let remainingFeaturedTextCount = 5 - (paidCount % 5);
        if(defaultList.length >= remainingFeaturedTextCount) {
            let remainingRandomArray = defaultList.slice(0,remainingFeaturedTextCount);
            if(!Array.isArray(remainingRandomArray)){
              finalArray.push(defaultList[remainingRandomArray]);
            }else{
                remainingRandomArray.forEach((value,index)=>{
                  finalArray.push(defaultList[index]);
                });
            }
        }else{
            defaultList.forEach((value,index)=>{
              finalArray.push(value);
            });
        }
      }else if(paidCount==0){
            for(let i = 0; i < 5; i++) {
                finalArray.push(defaultList[i]);
            }
      }
      return finalArray;
    }
 	
  	render(){
      if(this.props.collegeAds == null){
        return null;
      }
  		return(
          	<div className="n-featColg" id="tpfc" style={{visibility: 'hidden'}}>
                    <a className="featTxt"><i className="icons ic_featdTxt"></i></a>
                    <a onClick={this.uniDirectionalSlider.bind(this)} className="featMoveArw sldrDisableBtn"><i className="icons ic_right-gry"></i></a>
                    <ul id="collegeAds" className="n-featBanrs addTranstnEfct tpfc" style={{marginLeft: "0%"}}>
                        {this.createElement()}
                        <p className="clr"></p>
                    </ul>
            </div>
      )
  	}
}
TopFeaturedCollegeComponent.propTypes = {
  collegeAds : PropTypes.object
}
export default TopFeaturedCollegeComponent;