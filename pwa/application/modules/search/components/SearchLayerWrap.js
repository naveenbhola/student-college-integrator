import React from 'react';

import SearchLayer from './SearchLayer';

class SearchLayerWrap extends React.Component {
  render(){
    return <SearchLayer {...this.props} />
  }
}

export default SearchLayerWrap;
