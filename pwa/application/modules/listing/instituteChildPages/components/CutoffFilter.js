import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import style from './../assets/cutoffFilter.css';
import DropDown from '../../../common/components/desktop/DropDown';
import {getObjectSize, parseQueryParams, convertObjectIntoQueryString, resetGNB, getQueryVariable} from "./../../../../utils/commonHelper";
import {contentLoaderHelper} from "./../../../../utils/ContentLoaderHelper";
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import {withRouter} from 'react-router-dom';

class CutoffFilter extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      layerOpenExam :false,
      layerOpenCollege :false,
      layerOpenSpecialization :false,
      layerOpenCategory :false,

      filterChanged : false,
      filtersWithSearch : {},
      showLoader : true,
      filters : this.props.filters

     };
  }

  trackEvent = (actionLabel, label='click') => {
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };

  componentDidMount(){
    let self = this;
      
      document.addEventListener("click", function(e){
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-ex') < 0)){
          self.closeExamLayer();
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-clg') < 0)){
          self.closeCollegeLayer();
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-spe') < 0)){
          self.closeSpecializationLayer();
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-cat') < 0)){
          self.closeCategoryLayer();
        }
      });

      const {filters} = this.props;
      if(getObjectSize(filters) === 0)
            return;

      let filtersWithSearch = {};
      this.props.filtersWithSearch.map((filterType)=>{
          filtersWithSearch[filterType] = this.props.filters[filterType];
      });
      this.setState({filters : this.props.filters, showLoader: false, filtersWithSearch : filtersWithSearch}); 


  } 

  componentWillReceiveProps(nextProps) {
      const prevfilters = this.props.filters;
      const {filters} = nextProps;
      if(getObjectSize(prevfilters) === 0 && getObjectSize(filters) > 0){
          //const selectedCount = this.getSelectedFiltersCount(filters);
          let filtersWithSearch = {};
          nextProps.filtersWithSearch.map((filterType)=>{
              filtersWithSearch[filterType] = nextProps.filters[filterType];
          });
          this.setState({filters : nextProps.filters, filtersWithSearch : filtersWithSearch});
      }
  }

  handleClickOnFilters(){
    let dataObj = [];
    dataObj.clickType = 'exam';
    this.props.storeChildPageDataForPreFilled(contentLoaderHelper(this.props.childPageData,dataObj,"CutOff",this.props.pageUrl));
  }

  handleClickOnExam() {
    if(this.props.isDesktop){
      this.scrollY = 0;
    }

    this.trackEvent('Exam_filter');
    //if(!this.props.courseSpecificData){
    //  this.props.clickHandlerForCourseData();
    //}
    this.setState({layerOpenExam:true});
  }

  handleClickOnCollege() {
    if(this.props.isDesktop){
      this.scrollY = 0;
    }
    this.trackEvent('Colleges_filter');
    //if(!this.props.courseSpecificData){
    //  this.props.clickHandlerForCourseData();
    //}
    this.setState({layerOpenCollege:true});
  }


  handleClickOnSpecialization() {
    if(this.props.isDesktop){
      this.scrollY = 0;
    }
    this.trackEvent('Specializations_filter');
    //if(!this.props.courseSpecificData){
    //  this.props.clickHandlerForCourseData();
    //}
    this.setState({layerOpenSpecialization:true});
  }


  handleClickOnCategory() {
    if(this.props.isDesktop){
      this.scrollY = 0;
    }
    this.trackEvent('Category_filter');
    //if(!this.props.courseSpecificData){
    //  this.props.clickHandlerForCourseData();
    //}
    this.setState({layerOpenCategory:true});
  }

  /*renderSearchTemplate()
  {
    const {placeHolderText} = this.props;
    return (<div className="pwa-lyrSrc click-cls-course" key="search-tab">
      <i className='search-ico sddsd click-cls-course'></i>
      <input className="click-cls-course" type="text" placeholder={placeHolderText} ref={(input) => this.inputText = input} ange={this.getInputValue.bind(this)}/>
    </div>);
  }*/

  closeExamLayer(){
    this.setState({layerOpenExam : false,filtersWithSearch : {}});
  }
  closeCollegeLayer(){
    this.setState({layerOpenCollege: false,filtersWithSearch : {}});
  }
  closeSpecializationLayer(){
    this.setState({layerOpenSpecialization: false,filtersWithSearch : {}});
  }
  closeCategoryLayer(){
    this.setState({layerOpenCategory: false,filtersWithSearch : {}});
  }



  generateFilterHtml(filterData, filterType,uniqueClass) {
        let enabled = false;
        let checked = false;
        const filtersList = filterData.map((tuple, i) =>
            <li key={tuple.filterType + '_' + tuple.id+i} className={tuple['enabled'] ? 'enable '+uniqueClass : 'disable '+uniqueClass}>
                {enabled = (enabled || tuple['enabled'])}
                {checked = (checked || tuple.checked)}
                <input className={uniqueClass} onChange = {this.onChangeFilters(tuple)} className="hide_inputs" name="filters" type="checkbox"
                       filtertype = {filterType} id={"chck-" + tuple.filterType + "-" + tuple.id+i} defaultChecked= {tuple.checked} />
                <label  className={"option_label "+uniqueClass} htmlFor={"chck-" + tuple.filterType + "-" + tuple.id+i}>{tuple.name} {tuple.count > 0 ? '('+tuple.count+')' : ''}</label>
            </li>
        );
        if(!enabled && this.props.filtersWithSearch.indexOf(filterType) === -1)
            return false;
        
        if(checked){
            this.showClearAll = true;
        }
    return(
       <div className={"filters_dropdwn cutoff_multi "+uniqueClass} >
             {(this.props.filtersWithSearch.indexOf(filterType) !== -1) ? this.generateSearch(filterType,uniqueClass) : ''}
              <ul className={uniqueClass}>
                  {filtersList}
               </ul>  
             </div>  
      )
  }

  generateSearch(filterType,uniqueClass){

      return(
      <React.Fragment>
          <input type="text" className={"cutof_search "+uniqueClass} placeholder={"Search "+filterType} autoComplete={'off'} name="search" onChange={this.getInputValue.bind(this, filterType)} filtertype = {filterType}/>
      </React.Fragment>
      );
  }


  generateSingleSelectHtml(filterData, filterType,uniqueClass) {

    const filtersList = filterData.map((tuple, i) =>
            <li onClick = {this.onChangeFilters(tuple)} filtertype = {filterType} key={tuple.filterType + '_' + tuple.id} className={tuple['enabled'] ? 'enable '+uniqueClass : 'disable '+uniqueClass}>
            {tuple.name}
            </li>
        );
    return(
             <div className={"filters_dropdwn "+uniqueClass}>
             {(this.props.filtersWithSearch.indexOf(filterType) !== -1) ? this.generateSearch(filterType,uniqueClass) : ''}
              <ul className={uniqueClass}>
                  {filtersList}
               </ul>  
             </div>  
      ) 
  }

  onChangeFilters = tuple => (event) => {
      resetGNB();
      const {displayName} = this.props;
      this.handleClickOnFilters();
      const filterType = event.target.attributes.filtertype.value;
      this.trackEvent(displayName[filterType],'Filters_click');
      const alias = this.props.shortName;
      const filterTupleType = tuple.filterType;
      const checkedFlag = !tuple.checked;
      let params;
      let shortName = alias[filterTupleType];
      let tupleId = tuple.id.toString();
      params = this.pruneUrlForFilters(this.props.location.search);
      
      params['rf'] = 'filters';

      if(filterType=='category'){
          params[shortName] = [tupleId];         
      }
      else{
        if(checkedFlag) {
            if (!params[shortName]) {
                params[shortName] = [tupleId];
            } else if (params[shortName].indexOf(tupleId) === -1) {
                params[shortName].push(tupleId);
            }
        } else{
            if(params[shortName] && params[shortName].indexOf(tupleId) !== -1){
                delete params[shortName][params[shortName].indexOf(tupleId)];
                if(params[shortName].length === 0){
                    delete params[params.indexOf(shortName)];
                }
            }
        }
      }      
      const queryString = convertObjectIntoQueryString(params);
      let url = this.props.pageUrl;
      if(queryString && this.props.urlHasParams) {
          url += '&' + queryString;
      } else if(queryString){
          url += '?' + queryString;
      }
      this.props.history.push(url);
  };

  pruneUrlForFilters(queryParams){
      let paramsOld = {};
      if(queryParams)
          paramsOld = parseQueryParams(queryParams);
      paramsOld['rf'] = 'filters';
      return paramsOld;
  }

  getInputValue = (filterType, event) => {
      const searchText = event.target.value.trim();
      let filtersWithSearch;
      if(searchText.length > 0) {
          let filteredData = this.props.filters[filterType].filter(function(n,i){
              return n.enabled && n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
          });
          let filtersWithSearch = {...this.state.filtersWithSearch};
          filtersWithSearch[filterType] = filteredData;
          this.setState({filtersWithSearch : filtersWithSearch});
          return;
      }
      filtersWithSearch = {...this.state.filtersWithSearch};
      filtersWithSearch[filterType] = this.state.filters[filterType];
      this.setState({filtersWithSearch : filtersWithSearch});
  };

  getFilterHtml(){
    const {filterOrder} = this.props;
    const {filters} = this.state;
    if(getObjectSize(filters) === 0 || getObjectSize(filterOrder) === 0 ) {
        return null;
    }
    let allFilterHtml = [];
    for(let filterType of filterOrder) {
              if(!filters[filterType])
                continue;
              let filterData = filters[filterType];  
              if(this.props.filtersWithSearch.indexOf(filterType) !== -1 && this.state.filtersWithSearch[filterType]){
                  filterData = this.state.filtersWithSearch[filterType];
              }

              switch(filterType){
               case 'exam':
               allFilterHtml.push(
                 <div key={'div_'+filterType} className="filters_exam filter_arw click-cls-ex" onClick={this.handleClickOnExam.bind(this)}>
                  <div className='filter__text click-cls-ex'>Select Exam </div>
                 <DropDown data={this.generateFilterHtml(filterData,'exam','click-cls-ex')} isOpen={this.state.layerOpenExam} completeHtml={true} />
                 </div> 
                );
               break;

              case 'institute':
               allFilterHtml.push(
                 <div className="filters_exam filter_arw click-cls-clg" onClick={this.handleClickOnCollege.bind(this)}>
                  <div className='filter__text click-cls-clg'>Select Institute </div>
                 <DropDown data={this.generateFilterHtml(filterData,'institute','click-cls-clg')} isOpen={this.state.layerOpenCollege} completeHtml={true} />
                 </div> 
                );
               break;
              
              case 'specialization':
               allFilterHtml.push(
                 <div className="filters_exam filter_arw click-cls-spe" onClick={this.handleClickOnSpecialization.bind(this)}>
                  <div className='filter__text click-cls-spe'>Select Specialization </div>
                 <DropDown data={this.generateFilterHtml(filterData,'specialization','click-cls-spe')} isOpen={this.state.layerOpenSpecialization} completeHtml={true} />
                 </div> 
                );
               break;
              case 'category':
              allFilterHtml.push(<div className="filters_course filter_arw click-cls-cat" onClick={this.handleClickOnCategory.bind(this)}>
                              <div className='filter__text click-cls-cat' >Select Category</div>
                              <DropDown data={this.generateSingleSelectHtml(filterData,'category','click-cls-cat')} isOpen={this.state.layerOpenCategory} completeHtml={true}/>
                            </div>);
              break;
              }
    }


    return(
      <React.Fragment>
          {allFilterHtml}
      </React.Fragment>


    )
  }


  render(){
      return(
        <section className='listingTuple' id="admission">
              <div className="_container">
              <div className="cutt-off_filters">    
                 {this.getFilterHtml()} 
              </div>
                
              </div>
        </section>      
      )
    
  }
}

function mapDispatchToProps(dispatch){
  return bindActionCreators({ storeChildPageDataForPreFilled}, dispatch);
}



export default connect(null,mapDispatchToProps)(withRouter(CutoffFilter));
