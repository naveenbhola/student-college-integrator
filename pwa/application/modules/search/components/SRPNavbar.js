import PropTypes from 'prop-types'
import React, {Component} from "react";
import {Link} from 'react-router-dom';
import "../assets/SRPNavBar.css";
import Analytics from "../../reusable/utils/AnalyticsTracking";

class SRPNavbar extends Component{
    constructor(props){
        super(props);
        this.state = {
            navBarClass : ''
        }

    }

    componentDidMount(){
        window.isHeaderFixed = false;
        if(!this.props.isMobile)
            window.addEventListener('scroll', this.examStickyHeader);
    }


    examStickyHeader = () => {
        if(this.props.isMobile)
            return;
        let currScroll = document.documentElement.scrollTop;
        this.lastScrollTop = 0;
        let navHeader = document.querySelector('.fixed_tile');
        let footerSelector = document.getElementById('footer');
        if(footerSelector.offsetTop < document.documentElement.clientHeight + 200)
            return;
        if(!navHeader)
            return;
        if(currScroll >= footerSelector.offsetTop - document.documentElement.clientHeight + 200  && !this.footerSeenFlag){
            this.setState({navBarClass : ''});
            //navHeader.classList.remove('stickynav');
            this.footerSeenFlag = true;
        }
        else if(currScroll > 156 && !this.footerSeenFlag){
            this.setState({navBarClass : 'stickynav'});
            //navHeader.classList.add('stickynav');
        }
        else if(currScroll < footerSelector.offsetTop - document.documentElement.clientHeight + 200 && this.footerSeenFlag){
            this.footerSeenFlag = false;
            this.setState({navBarClass : 'stickynav'});
            //navHeader.classList.add('stickynav');
        }
        if(currScroll <= 156){
            this.setState({navBarClass : ''});
            //navHeader.classList.remove('stickynav');
        }
    };

    componentWillUnmount(){
        window.isHeaderFixed = true;
        window.removeEventListener('scroll', this.examStickyHeader);
    }
    getTabUrl(tabData){
        if(tabData.name === 'Colleges' && this.props.tabsData.collegeSRPQuery){
            return {pathname : tabData.url, search : this.props.tabsData.collegeSRPQuery,
                state : {'tabsData' : this.props.tabsData}};
        }
        return {pathname : tabData.url , search : '?q=' + encodeURIComponent(this.props.keyword) + '&rf=searchWidget' ,
            state : {'tabsData' : this.props.tabsData}};
    }

    trackEvent = (actionLabel, label) => {
        if(!this.props.gaCategory)
            return;
        const categoryType = this.props.gaCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };
    getTabCount(tabData){
        if(this.props.activeTab === tabData.name && this.props.activeTabCount && this.props.activeTabCount >= 0) {
            return this.props.activeTabCount;
        }
        if(tabData.name === 'Colleges' && this.props.tabsData.collegeSRPCount){
            return this.props.tabsData.collegeSRPCount;
        }
        return (tabData.count && tabData.count >= 0 ? tabData.count : '');
    }
    examTabsData = () => {
        if(!this.props.tabsData || !this.props.tabsData.tabsData)
            return [];
        const tabsData = this.props.tabsData.tabsData;
        let tabsArray = [];
        let showSrpNavBar = true;
        tabsData.map((tab, index)=>{
            tabsArray.push(<Link to={this.getTabUrl(tab)} className={this.props.activeTab === tab.name ? 'active' : ''}
                                 key={"tabs" + index} onClick={this.trackEvent.bind(this, tab.name + '_tab', 'click')}>
                                    <i className={'navbar-icons '+tab.name} /> {tab.name} <span>{this.getTabCount(tab)}</span>
                                {showSrpNavBar = showSrpNavBar && tab.count >= 0}
            </Link>)
        });
        if(showSrpNavBar)
            return tabsArray;
        else return [];
    };

    render(){
        const examTabsData = this.examTabsData();
        if(!examTabsData || examTabsData.length === 0){
            return null;
        }
        return(
            <div className={"fixed_tile " + this.state.navBarClass}>
                {this.props.showHeading && this.props.keyword  ? <div className="exams_head">
                    <p className="exam-clg-rslts">Showing Results for <strong>'{this.props.keyword}'</strong></p>
                </div> : '' }
                {Object.keys(this.props.tabsData).length > 0 ?<div className="flex-navbar">
                    {examTabsData}
                </div> : ''}
            </div>
        )
    }
}
SRPNavbar.defaultProps = {
  showHeading: true
};
export default SRPNavbar;

SRPNavbar.propTypes = {
  activeTab: PropTypes.string,
  activeTabCount: PropTypes.number,
  isMobile: PropTypes.bool.isRequired,
  keyword: PropTypes.string,
  showHeading: PropTypes.bool,
  tabsData: PropTypes.object.isRequired
};
