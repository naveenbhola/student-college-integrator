import React from 'react';

import DesktopSearchLayer from './DesktopSearchLayer';

class DesktopSearchWrapper extends React.Component {
    render(){
        return <DesktopSearchLayer {...this.props} searchPage = {true} isSearchLayerOpen = {true}/>
    }
}

export default DesktopSearchWrapper;