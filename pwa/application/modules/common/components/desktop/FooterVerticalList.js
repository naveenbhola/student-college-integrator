import React, { Component } from 'react';

import {openInNewWindow} from '../../../../utils/commonHelper';

class FooterVerticalList extends Component {
  popitup(url){
    openInNewWindow(url);
  }
  render() {
    let listItems = [];
    for (var i = 0; i < this.props.listItems.length; i++) {
      if(this.props.listItems[i].title == 'Feedback'){
        continue;
      }
      if(this.props.listItems[i].target == 'tab'){
        listItems.push(<li key={i}><a target="_blank" track={this.props.listItems[i].track} href={this.props.listItems[i].url} title={this.props.listItems[i].title} rel={this.props.listItems[i].rel}>{this.props.listItems[i].title}</a></li>);
      }else if(this.props.listItems[i].target == 'window'){
        listItems.push(<li key={i}><a onClick={this.popitup.bind(this, this.props.listItems[i].url)} track={this.props.listItems[i].track} href='javascript:void(0);' title={this.props.listItems[i].title} rel={this.props.listItems[i].rel}>{this.props.listItems[i].title}</a></li>);
      }else{
        listItems.push(<li key={i}><a track={this.props.listItems[i].track} href={this.props.listItems[i].url} title={this.props.listItems[i].title} rel={this.props.listItems[i].rel}>{this.props.listItems[i].title}</a></li>);
      }
    }
    return (
      <div className="col-lg-3">
        <div className="n-fotrCntBx">
          <h3>{this.props.title.replace('_', ' ')}</h3>
          <ul>
            {listItems}
          </ul>
        </div>
      </div>
    );
  }
}
export default FooterVerticalList;
