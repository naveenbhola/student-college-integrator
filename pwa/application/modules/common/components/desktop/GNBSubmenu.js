import React,{Component} from 'react';
import config from './../../../../../config/config';
import {withRouter} from 'react-router-dom';

class GNBSubmenu extends Component {

  contentClickHandler = (e) => {
    let {history} = this.props;
    let clickLink = e.target.getAttribute('relative');
    if(clickLink == 'true'){
      e.preventDefault();
      let url = e.target.href.replace(/^.*\/\/[^\/]+/, '');
      history.push(url);
      this.props.hideGNB();
    }
  };

  render(){
    return (
      <div className="submenu">
        <ul className="g_lev2" onClick={this.contentClickHandler} dangerouslySetInnerHTML={createGNBMarkup(this.props.nodeName, this.props.nodeData)}>
        </ul>
        <div className="gnb-nav-indctr">
          <span className="disable"><i className="go-up"></i></span>
          <span><i className="go-dwn"></i></span>
        </div>
      </div>
    );
  } 
}

 const getAnchorHref = (data) => {
    let anchorHref = '';
    if(typeof data != 'undefined' && typeof data['urlHost'] == 'undefined'){
        anchorHref= "href='"+data['url']+"'";
        return anchorHref;
    }
    if(data['urlHost']=='national'){
      anchorHref= "href='"+config().SHIKSHA_HOME + data['url']+"'";
    }else if(data['urlHost']=='abroad'){
      anchorHref= "href='"+config().SHIKSHA_STUDYABROAD_HOME + data['url']+"'";
    }else if(data['urlHost']=='relative'){
      anchorHref= "href='"+ data['url']+"'";
    }else if(data['urlHost']=='ask'){
      anchorHref= "href='"+config().SHIKSHA_ASK_HOME + data['url']+"'";
    }
    return anchorHref;
  }


const getGNBSubmenu = (levelZeroKey, levelZeroData) => {
  let tempArr = [], levelFirstData, levelSecondData, levelThirdData, levelForthData;
  let extraTR = [], scrollClass = '', index = 0;
  let pntrClass = '', href = '#';
  let submenu2Class = 'submenu2';
  levelZeroData[0][0].forEach(
    levelFirstData => { //Popular courses, exams, predictors
      pntrClass = 'pntr';
      href= 'javascript:void(0)';
      if(typeof levelFirstData.url != 'undefined' && levelFirstData.url != ''){
        href = levelFirstData.url;
        if(levelZeroKey == 'MBA' || levelZeroKey == 'Counseling'){
          pntrClass = 'pntr dontShowArrow';
        }
      }
      if(levelZeroKey == 'Counseling'){
        submenu2Class = 'submenu2 counsellingTab';
      }
      tempArr.push('<li key="'+levelFirstData.key+'"><a href="'+href+'" class="'+pntrClass+'">'+levelFirstData.key+'</a><div class="'+submenu2Class+'"><table border="0">');
      levelSecondData = levelFirstData.child;
      levelSecondData.forEach(
        tableDataArr => {
          tempArr.push('<tr>');
          extraTR = [];
          let iter = 0;
          tableDataArr.forEach(
            rowData => { //row 1 and row 2 data
              iter++;
              if(levelFirstData.key == 'IIM Call Predictor' || levelFirstData.key == 'Abroad Counseling Service'){
                return;
              }
              if(levelFirstData.key != 'College Predictors'){
                if((levelZeroKey == "MBA" || levelZeroKey == "Engineering" || levelZeroKey == "Design" || levelZeroKey == "Law") && iter == 2){
                  //return;
                }
              }
              levelThirdData = rowData;
              scrollClass = 'g_lev3';
              extraTR.push('<td></td>');
              if(levelZeroKey == 'More Courses' && (levelThirdData.key == 'Popular Courses' || levelThirdData.key == 'Popular Specializations')){
                  scrollClass = 'g_lev3 otherSclr';
              }
              tempArr.push('<td>');
              index = 0;
              let levelThirdDataCount = levelThirdData.length;
              levelThirdData.forEach(
                levelForthData => {
                  if(levelZeroKey == 'More Courses' && (levelForthData.key == 'Popular Courses' || levelForthData.key == 'Popular Specializations')){
                    scrollClass = 'g_lev3 otherSclr';
                  }
                  if(levelForthData.key == 'Popular Courses' || levelForthData.key == 'Popular Specializations'){
                    tempArr.push('<div class="head_cours">'+levelForthData.key+'</div>');
                    return;
                  }
                  if(index == 0){
                    tempArr.push('<ul class="'+scrollClass+'">');
                  }
                  let anchorClass2='', greaterThanTag = '',onClick="";
                  let gaTrackingPath = levelZeroKey+':'+levelFirstData.key;
                  let gaTrackingTrailPath1 = '', gaTrackingTrailPath2 = '';
                  let anchorClass='', anchorHref='';
                  if(typeof levelForthData['type'] != 'undefined' && levelForthData['type']=='heading'){
                    anchorClass= 'class="head_cours"';
                    anchorHref= "";
                    gaTrackingTrailPath1 = ':'+levelForthData.key;
                  }else if(typeof levelForthData['type'] != 'undefined' && levelForthData['type']=='sub-heading'){
                    anchorClass= 'class="head_cours-sub"';
                    anchorHref= "";
                    gaTrackingTrailPath1 += ':'+levelForthData.key;
                  }else if(typeof levelForthData['type'] != 'undefined' && levelForthData['type']=='url'){
                    anchorClass= "";
                    anchorHref = getAnchorHref(levelForthData);                    
                    gaTrackingTrailPath2 = ':'+levelForthData.key;
                  }else if(typeof levelForthData['type'] != 'undefined' && levelForthData['type']=='click'){
                    anchorClass= "";
                    anchorHref = getAnchorHref(levelForthData);
                    onClick = 'yes';
                    gaTrackingTrailPath2 = ':'+levelForthData.key;
                  }else if(typeof levelForthData['type'] != 'undefined' && levelForthData['type']=='all'){
                    anchorClass= "";
                    anchorClass2= "class='linkk'";
                    anchorHref = getAnchorHref(levelForthData);
                    onClick = typeof levelForthData['click'] != 'undefined' ? levelForthData['click'] : '';
                    greaterThanTag = '> ';
                    gaTrackingTrailPath2 = ':'+levelForthData.key;
                  }else if(typeof levelForthData['type'] != 'undefined' && (levelForthData['type']=='urlWithAnchor' || levelForthData['type']=='link')){
                    anchorClass= "";
                    anchorClass2= "class='linkk'";
                    anchorHref = getAnchorHref(levelForthData);
                    greaterThanTag = '> ';
                    gaTrackingTrailPath2 = ':'+levelForthData.key;
                  }else{
                    anchorClass= "";
                    anchorClass2= "";
                    anchorHref= "href='javascript:void(0)'";
                    greaterThanTag = '';
                    gaTrackingTrailPath2 = '';
                  }
                  let trackAttr = '';
                  tempArr.push('<li '+anchorClass+'>');
                  if(anchorHref!='' && onClick=='' && typeof(levelForthData['hide']) == 'undefined'){
                    if(levelForthData['urlHost']=='relative'){
                      tempArr.push('<a relative="true"'+anchorHref+' '+anchorClass2+'>'+greaterThanTag+' '+levelForthData.key+'</a>');
                    }else{
                      tempArr.push('<a '+anchorHref+' '+anchorClass2+'>'+greaterThanTag+' '+levelForthData.key+'</a>');
                    }
                  }else if(anchorHref!='' && onClick!='' && typeof(levelForthData['hide']) == 'undefined'){
                    if(levelZeroKey == 'MBA' || levelZeroKey == 'Engineering'){
                      //obsolete case now
                      if(levelForthData['urlHost']=='relative'){
                        tempArr.push('<a relative="true"'+anchorClass2+' '+anchorHref+' action="'+onClick+'">'+greaterThanTag+' '+levelForthData.key+'</a>');
                      }else{
                        tempArr.push('<a '+anchorClass2+' '+anchorHref+' action="'+onClick+'">'+greaterThanTag+' '+levelForthData.key+'</a>');
                      }                      
                    }else{ 
                      tempArr.push('<a href="javascript:void(0);" '+trackAttr+' careerPage="'+levelForthData.key+'" class="career-page-link">'+levelForthData.key+'</a>');
                    }
                  }else if(anchorHref=='' && typeof(levelForthData['hide']) == 'undefined'){
                    tempArr.push(levelForthData.key);
                  }else{
                    tempArr.push('');
                  }
                  tempArr.push('</li>');
                  index++;
                  if(index == levelThirdDataCount){
                    tempArr.push('</ul>');
                  }
                }//level fourth end
              );
              tempArr.push('</td>');
            }//rowData end
          );
          tempArr.push('</tr>');
        }
      );
      tempArr.push('<tr class="lastTrTd">'+extraTR.join(" ")+'</tr>');
      tempArr.push('</table></div></li>');
    }
  );
  return tempArr.join(" ");
}

const createGNBMarkup = (nodeName, nodeData) => {
  return {__html : getGNBSubmenu(nodeName, nodeData)};
}

export default withRouter(GNBSubmenu);
