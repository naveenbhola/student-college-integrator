import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Link } from 'react-router-dom';
import {splitPathQueryParamsFromUrl} from './../../../../utils/commonHelper';

class RecoLinkWidget extends Component {
  	
  	render(){
  		if(this.props.recoData == null || this.props.recoData.length<=0){
  			return null;
  		}
  		return(	
  			<React.Fragment>
	  			<div key="0101" className="ctpRecoLinks ">
	  				<h2 className="ctp-Rcohdng">You may be interested in the following:</h2>
	  				<div key="0102" className="rctp-RcoLinks">
	  					{ this.prepareLinks() }
	  				</div>
	  			</div>	
  			</React.Fragment>
  		)
  	}

  	prepareLinks(){
  		return(<ul key="recolist">{this.generateLink(this.props.recoData)}</ul>)
  	}

    generateLink(v){
      var list = new Array();
      var index = 0;
      for(var i in v){

        var urlObject = splitPathQueryParamsFromUrl(v[i].url);
        var urlPathname = "";
        var searchParams = "";
        if(typeof urlObject == "object" && typeof urlObject.search != "undefined" && urlObject.search != "")
        {
          searchParams = urlObject.search;
        }
        if(typeof urlObject == "object" && typeof urlObject.pathname != "undefined" && urlObject.pathname != "")
        {
          urlPathname = urlObject.pathname;
        }
        if(!urlPathname || urlPathname == "" || typeof urlPathname == 'undefined')
          continue;

          list.push(<li key={index}><Link to={{ pathname : urlPathname,search : searchParams}}>{v[i].heading}</Link></li>);
          index++;
      }
      return list;
    }
}

export default RecoLinkWidget;
