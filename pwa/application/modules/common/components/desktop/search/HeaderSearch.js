import React, {Component} from 'react';
import DesktopSearchLayer from '../../../../search/components/DesktopSearchLayer'

class HeaderSearch extends Component{
  constructor(props){
    super(props);
    this.state = {
        isSearchLayerOpen : false
    }
  }
    openSearchLayer = () => {
      window.referrer = window.location.href;
        this.setState({isSearchLayerOpen : true});
    };
    closeSearchLayer(){
        this.setState({isSearchLayerOpen : false});
    }
  render(){
     return(
        <React.Fragment>
          <div className="inside-gnbpage">
            <a onClick={this.openSearchLayer}>
            <div className="pwadesktop-srchbox">
              Search Colleges, Courses, Exams, QnA, & Articles
              <button type="button" name="button" className="srchBtnv1">Search</button>
            </div></a>
              <DesktopSearchLayer isSearchLayerOpen={this.state.isSearchLayerOpen}
                  onClose={this.closeSearchLayer.bind(this)} searchPage = {false}/>
        </div>
        </React.Fragment>
      );
    }

}

export default HeaderSearch;
