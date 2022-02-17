import React from 'react';

class SearchTypes extends React.Component {
  toggleSearchTabsAndMenu(obj){
    this.props.toggleSearchTabsAndMenu(obj);
  }
  openSearchTypeMenu(obj){
    this.props.openSearchTypeMenu(obj);
  }
  render(){
    let list = [], custStyle = '';
    this.props.searchTabOrder.forEach(
      currObj => {
        custStyle = {display : 'block'};
        if(currObj == this.props.toBeShown){
          custStyle = {display : 'none'};
        }
        list.push(<li key={currObj} style={custStyle} tabname={currObj} onClick={this.toggleSearchTabsAndMenu.bind(this)}>{currObj}</li>);
      }
    );
    let ulClass = 'slideClose';
    if(this.props.isMenuOpen){
      ulClass = 'slideOpen';
    }
    return (
      <React.Fragment>
        <p onClick={this.openSearchTypeMenu.bind(this)}><b>{this.props.toBeShown}</b><i className="icons ic_dropdownsumo"></i></p>
        <ul className={ulClass}>{list}</ul>
      </React.Fragment>
    );
  }
}

export default SearchTypes;
