import React, { Component } from 'react';
import FooterHorizontalList from './FooterHorizontalList';

class FooterBottomSection extends Component {
  render(){
    let listData = [];
    this.props.order.forEach(
      key => {
        listData.push(<FooterHorizontalList key={key} title={key} listItems={this.props.data[key]} />);
      }
    );
    return (
      <div className="n-footer3">
        <div className="container">
          <ul className="fotr_seo">
            {listData}
          </ul>
          <div className="n-tradeMarkFotr">
            <p>Trade Marks belong to the respective owners. Copyright &copy; {new Date().getFullYear()} Info edge India Ltd. All rights reserved.</p>
            <p className="clr"></p>
          </div>
        </div>
      </div>
    );
  }
}

export default FooterBottomSection;
