import React, { Component } from 'react';
import FooterVerticalList from './FooterVerticalList';

class FooterMiddleSection extends Component {
  render(){
    let listData = [];
    let label = '';
    this.props.order.forEach(
      key => {
        listData.push(<FooterVerticalList key={key} title={key} listItems={this.props.data[key]} />);
      }
    );
    return (
      <div className="n-footer1">
        <div className="container">
          <div className="n-row">
            {listData}
            <p className="clr"></p>
          </div>
        </div>
      </div>
    );
  }
}

export default FooterMiddleSection;
