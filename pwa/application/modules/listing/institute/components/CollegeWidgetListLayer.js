import PropTypes from 'prop-types'
import React, {Component} from 'react';
import FullPageLayer from '../../../common/components/FullPageLayer';
import {fetchInstituteCollegeListPageData} from './../actions/InstituteDetailAction';
import {getObjectSize} from './../../../../utils/commonHelper';

class CollegeWidgetListLayer extends Component{
    constructor(props){
        super(props);
        this.state = {
            isLayerOpen : false,
            isShowLoader : false,
            collegeHtml : <p></p>
        }
    }

    renderSearchTemplate()
    {
        return (<div className={"pwa-lyrSrc collegelistlayer"} key="search-tab">
            <i className='search-ico'></i>
            <input type="text" placeholder="Search a college or department" ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)}/>
        </div>);
    }
    filterDisplayList(searchText)
    {
        if(searchText.length > 0)
        {
            if(typeof this.state.collegeLayerData != 'undefined' && this.state.collegeLayerData)
            {

                var dispalyedItems = Object.assign({},this.state.collegeLayerDataFix);

                Object.filter = function( obj) {
                    var result = {}, key;
                    for (key in obj) {
                        if (obj.hasOwnProperty(key) && obj[key].name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1) {
                            result[key] = obj[key];
                        }
                    }
                    return result;
                };

                var filtered = Object.filter(dispalyedItems.topInstituteData, searchText);

                dispalyedItems.topInstituteData = filtered;
                this.prepareLayerHtml(dispalyedItems,this.state.aggregateReviewData,this.state.collegeLayerDataFix);
            }
        }
        else
        {
            this.prepareLayerHtml(this.state.collegeLayerDataFix,this.state.aggregateReviewData,this.state.collegeLayerDataFix);
        }
    }

    getInputValue()
    {
        let searchText = this.inputText.value.trim();
        this.filterDisplayList(searchText);
    }

    fetchCompleteLayerData(instituteId){
        this.setState({isShowLoader:true,isLayerOpen:true});
        fetchInstituteCollegeListPageData(instituteId).then((response)=>{
            if(response.data.data)
            {
                this.prepareLayerHtml(response.data.data.collegeWidgetData,response.data.data.agrregateReviewsData,response.data.data.collegeWidgetData);
            }

        })
    }

    prepareLayerHtml(collegeLayerData,reviewLayerData,collegeLayerDataFix)
    {
        let collegeHtml = this.props.renderCollegeInfo({collegeWidgetData : collegeLayerData,agrregateReviewsData : reviewLayerData});
        let collegeHtmlArray = [];
        if(typeof collegeLayerData.topInstituteData == 'object' && getObjectSize(collegeLayerData.topInstituteData) == 0)
        {
            collegeHtmlArray.push(<p key="list" className="noResult-found"> No, search result found </p>);
        }
        else {
            collegeHtmlArray.push(<ul key="list" className="collegelistlayer_ul">{collegeHtml}</ul>);
        }
        this.setState({collegeHtml : collegeHtmlArray,isShowLoader:false,collegeLayerData : collegeLayerData,collegeLayerDataFix : collegeLayerDataFix,aggregateReviewData : reviewLayerData});
    }
    openCollegeListLayer = () => {
        const listingid = this.props.listingId;
        this.fetchCompleteLayerData(listingid);
    }

    closeCollegeLayer = () => {
        this.setState({isLayerOpen : false,isShowLoader:false});
    }

    renderCollegeCount(){
        const {collegelistWidget} = this.props;
        if(typeof collegelistWidget['collegeWidgetData']['instituteCount'] == 'undefined' || (typeof collegelistWidget['collegeWidgetData']['instituteCount'] != 'undefined' && (collegelistWidget['collegeWidgetData']['instituteCount'] == null || collegelistWidget['collegeWidgetData']['instituteCount'] == 0)))
        {
            return null;
        }

        return(
            <div className="tac _padaround btn-topMrgn">
                <button onClick={this.openCollegeListLayer} className="button button--secondary rippleefect arrow">View All {collegelistWidget['collegeWidgetData']['instituteCount']} Colleges

                </button>
            </div>
        )
    }

    render(){
        let searchHtml = this.renderSearchTemplate();
        return(
            <React.Fragment>
                {this.renderCollegeCount()}
                <FullPageLayer onClose={this.closeCollegeLayer} isOpen={this.state.isLayerOpen} heading={'Colleges/Departments'} data={this.state.collegeHtml} isShowLoader={this.state.isShowLoader} isSearchExist = {true} searchHtml = {searchHtml}/>
            </React.Fragment>
        )
    }

}

export default CollegeWidgetListLayer;

CollegeWidgetListLayer.propTypes = {
  collegelistWidget: PropTypes.any,
  listingId: PropTypes.any,
  renderCollegeInfo: PropTypes.any
}