import PropTypes from 'prop-types'
import React from 'react';
import {Link} from "react-router-dom";
import './../assets/locationWidgetStyle.css';
import FullPageLayer from './../../common/components/FullPageLayer';

class RankingLocationWidget extends React.Component{
  constructor(props){
    super(props)
    this.state = {
      showCityTable : false,
      showStateTable : false
    }
  }
  showCityList(){
    this.setState({showCityTable : true});
  }
  hideCityList(){
    this.setState({showCityTable : false});
  }
  showStateList(){
    this.setState({showStateTable : true});
  }
  hideStateList(){
    this.setState({showStateTable : false});
  }
  render(){
    let stateList = [], cityList = [];
    let stateListForLayer = [], cityListForLayer = [];
    let cityCount = 0, stateCount = 0;
    if(typeof this.props.data != 'undefined' && this.props.data != null){
      this.props.data.forEach(
          (currLoc) => {
            if(currLoc.filterType == 'city'){
              cityListForLayer.push(<li key={"lyr_"+(currLoc.id == null ? 0 : currLoc.id)}><Link to={currLoc.url}>{currLoc.name}</Link></li>);
              if(cityCount < 4){
                cityList.push(<li key={"key_"+(currLoc.id == null ? 0 : currLoc.id)}><Link to={currLoc.url}>{currLoc.name}</Link></li>);
              }
              cityCount++;
            }else{
              stateListForLayer.push(<li key={"lyr_"+(currLoc.id == null ? 0 : currLoc.id)}><Link to={currLoc.url}>{currLoc.name}</Link></li>);
              if(stateCount < 4){
                stateList.push(<li key={"key_"+(currLoc.id == null ? 0 : currLoc.id)}><Link to={currLoc.url}>{currLoc.name}</Link></li>);
              }
              stateCount++;
            }
          }
      );
    }
    return (
        <div className="top_widget">
          <div className="widget_head">
            <h2 className="h2">{this.props.heading}</h2>
          </div>
          <div className="widget_content clear_float">
            <div className="flt_lft list_col">
              <h4 className="f14_bold clr_0">Cities</h4>
              <ul className="rslts_ul">{cityList}</ul>
              {cityCount > 4 ?
                  <React.Fragment>
                    <button className="button button--secondary" name="button" onClick={this.showCityList.bind(this)}>View All</button>
                    <FullPageLayer
                        data={<div className="loc-wrap"><ul className="loc-list">{cityListForLayer}</ul></div>}
                        heading={this.props.layerHeading}
                        isOpen={this.state.showCityTable}
                        desktopTableData={this.props.deviceType == 'desktop' ? true : false}
                        onClose={this.hideCityList.bind(this)}/>
                  </React.Fragment>
                  : null}
            </div>
            <div className="flt_right list_col">
              <h4 className="f14_bold clr_0">States</h4>
              <ul className="rslts_ul">{stateList}</ul>
              {stateCount > 4 ?
                  <React.Fragment>
                    <button type="button" name="button" className="button button--secondary" onClick={this.showStateList.bind(this)}>View All</button>
                    <FullPageLayer
                        data={<div className="loc-wrap"><ul className="loc-list">{stateListForLayer}</ul></div>}
                        heading={this.props.layerHeading}
                        isOpen={this.state.showStateTable}
                        desktopTableData={this.props.deviceType == 'desktop' ? true : false}
                        onClose={this.hideStateList.bind(this)}/>
                  </React.Fragment>
                  : null}
            </div>
          </div>
        </div>
    );
  }
}

RankingLocationWidget.defaultProps = {
  deviceType : 'mobile'
}

export default RankingLocationWidget;

RankingLocationWidget.propTypes = {
  data: PropTypes.any,
  deviceType: PropTypes.string,
  heading: PropTypes.any,
  layerHeading: PropTypes.any
}