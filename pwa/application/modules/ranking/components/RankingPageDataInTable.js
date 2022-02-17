import PropTypes from 'prop-types'
import React from 'react';

import RankingPageDataTable from './RankingPageDataTable';
import FullPageLayer from './../../common/components/FullPageLayer';
import Analytics from './../../reusable/utils/AnalyticsTracking';

class RankingPageDataInTable extends React.Component {
  constructor(props){
    super(props);
    this.state = {
      showTable : false
    };
  }
  showRankingDataTable(){
    this.trackEvent('Data in table', 'click');
    this.setState({showTable : true});
  }
  hideRankingDataTable(){
    this.setState({showTable : false});
  }
  trackEvent(actionLabel,label){
    Analytics.event({category : this.props.gaTrackingCategory, action : actionLabel, label : label});
  }
  render(){
    let tableDataForLayer = null;
    if(this.state.showTable){
      tableDataForLayer = <RankingPageDataTable rankingPageId={this.props.rankingPageId} tableData={this.props.tableData} />
    }
    return (
        <React.Fragment>
          <p className="f14_normal show-desk"><a href="javascript:void(0);" onClick={this.showRankingDataTable.bind(this)}>Show data in table</a></p>
          <FullPageLayer data={tableDataForLayer}
                         heading={this.props.tableData.length+' '+this.props.rankingPageName}
                         isOpen={this.state.showTable}
                         desktopTableData={this.props.deviceType == 'desktop' ? true : false}
                         onClose={this.hideRankingDataTable.bind(this)}/>
          <div className="ranking-table-seo">
            <RankingPageDataTable rankingPageId={this.props.rankingPageId} tableData={this.props.tableData} />
          </div>
        </React.Fragment>
    );
  }
}

RankingPageDataInTable.defaultProps = {
  deviceType : 'mobile'
}

export default RankingPageDataInTable;

RankingPageDataInTable.propTypes = {
  deviceType: PropTypes.string,
  gaTrackingCategory: PropTypes.any,
  rankingPageId: PropTypes.any,
  rankingPageName: PropTypes.any,
  tableData: PropTypes.any
}