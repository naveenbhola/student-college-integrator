import React, { Component } from 'react';

class FooterHorizontalList extends Component {
  render() {
    let listItemss = [];
    let separator = '';
    for (var i = 0; i < this.props.listItems.length; i++) {
      if(i > 0){
        separator = <i></i>;
      }
      if(this.props.listItems[i].target == 'tab'){
        listItemss.push(
          <span key={i}>
          {separator}
          <a target="_blank" href={this.props.listItems[i].url} title={this.props.listItems[i].title}>{this.props.listItems[i].title}</a>
          </span>
        );
      }else{
        listItemss.push(
          <span key={i}>
          {separator}
          <a href={this.props.listItems[i].url} title={this.props.listItems[i].title}>{this.props.listItems[i].title}</a>
          </span>
        );
      }
    }
    return (
        <li>
          <div>{this.props.title.replace('_', ' ')}<i>:</i></div>
          <div>{listItemss}</div>
        </li>
    );
  }
}
export default FooterHorizontalList;
