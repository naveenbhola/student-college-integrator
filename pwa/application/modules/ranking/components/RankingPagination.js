import PropTypes from 'prop-types'
import React from 'react';

import Pagination from './../../listing/categoryList/components/Pagination';

class RankingPagination extends React.Component {
  render(){
    return (
        <Pagination categoryData={this.props.rankingPageData} from='ranking' onRankingPageSelect={this.props.onRankingPageSelect} gaTrackingCategory={this.props.gaTrackingCategory} />
    );
  }
}

export default RankingPagination;

RankingPagination.propTypes = {
  gaTrackingCategory: PropTypes.any,
  onRankingPageSelect: PropTypes.any,
  rankingPageData: PropTypes.any
}