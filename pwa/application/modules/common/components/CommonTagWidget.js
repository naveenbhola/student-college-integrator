import React, { Component } from 'react';
import {Link} from 'react-router-dom';
import  './../assets/commonTagWidget.css';
import Anchor from './../../reusable/components/Anchor';

const clickHandler=(data, callFunction, gaCategory)=>{
    if(typeof callFunction == 'function'){
        callFunction(data, gaCategory);
    }
}

const CommonTagWidget=(props)=>{
  var dataList = [];
  for(let i=0;i<props.data.length;i++){
    let activeClass = '';
    if(props.selectedTag == props.data[i].id){
      activeClass='active';
    }else if(!props.selectedTag && props.data[i].selectedClass && props.data[i].selectedClass != ''){
      activeClass=props.data[i].selectedClass;
    }
    dataList.push(<Anchor link={props.link} key={props.data[i].id} to={props.data[i].url} onClick={()=>{clickHandler(props.data[i], props.callFunction, props.gaCategory)}} className={"_clist rippleefect "+activeClass} key={"years_" + i} >{(props.data[i].name.length>31) ? props.data[i].name.substr(0,31)+'...' : props.data[i].name}</Anchor>);
  }

  return (
        <div className={"_browsesection "+props.sectionClass}>
          {props.mainHeading && <div className="_sctntitle">{props.mainHeading} 
              {props.view_all && <a onClick={props.onClickViewAll} className="view--filters">View All</a>}
          </div>}
              {props.showReset && <Link className='reset-link' to={props.pageUrl} onClick={props.onClickReset} ><i className='reset-ico'></i> Reset </Link>}
            <div className="_browseBy">
              <div className="_browseRow">
                {dataList}
              </div>
            </div>
       </div> 
    )
}

CommonTagWidget.defaultProps = {
  link : true,
  selectedClass:'',
  sectionClass: "",
  selectedTag :null,
  showReset: false
};
export default CommonTagWidget;