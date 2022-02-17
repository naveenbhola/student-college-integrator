import React from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import style from './../assets/cutoffFilter.css';
import DropDown from '../../../common/components/desktop/DropDown';
import {getObjectSize, parseQueryParams, convertObjectIntoQueryString, resetGNB, getQueryVariable} from "./../../../../utils/commonHelper";
import {jsUcfirst} from "./../../../../utils/stringUtility";
import {contentLoaderHelper} from "./../../../../utils/ContentLoaderHelper";
import Analytics from './../../../reusable/utils/AnalyticsTracking';
import {storeChildPageDataForPreFilled} from './../../instituteChildPages/actions/AllChildPageAction';
import {withRouter} from 'react-router-dom';
class CutoffFilter extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      layerOpenExam :false,
      layerOpenInstitute :false,
      layerOpenSpecialization :false,
      layerOpenCategory :false,

      filtersWithSearch : {},
      showLoader : true,
      filters : this.props.filters

     };
  }

  trackEvent = (actionLabel, label) => {
        const categoryType = this.props.gaTrackingCategory;
        Analytics.event({category : categoryType, action : actionLabel, label : label});
    };

  componentDidMount(){
    let self = this;
      
      document.addEventListener("click", function(e){
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-exa') < 0)){
          self.closeLayer('Exam');
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-ins') < 0)){
          self.closeLayer('Institute');
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-spe') < 0)){
          self.closeLayer('Specialization');
        }
        if (typeof e.target.getAttribute('class') == 'undefined' || !e.target.getAttribute('class') || (typeof e.target.getAttribute('class') != 'undefined' && e.target.getAttribute('class') && e.target.getAttribute('class').indexOf('click-cls-cat') < 0)){
          self.closeLayer('Category');
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

  handleClickOnFilters(showFullLoader){
    let dataObj = [];
    dataObj.clickType = 'exam';
    dataObj.showFullLoader = showFullLoader;
    this.props.storeChildPageDataForPreFilled(contentLoaderHelper(this.props.childPageData,dataObj,"CutOff",this.props.pageUrl));
  }

  handleClickOnFilter(filterType){
      this.trackEvent(filterType+'_filter');    
      let key = 'layerOpen'+filterType;
      let newKeyValue = this.state[key];
      this.setState({[key]:!newKeyValue});  
  }

  trackEvent(action)
  {
    Analytics.event({category : 'CutoffPage', action : action, label : 'click'});
  }

  closeLayer(type){
    let key='layerOpen'+type;
    this.setState({[key] : false,filtersWithSearch : {}});
  }

  generateFilterHtml(filterData, filterType,uniqueClass) {
        let enabled = false;
        let checked = false;
        const filtersList = filterData.map((tuple, i) =>
            <li filtertype = {filterType} key={tuple.filterType + '_' + tuple.id+i} className={tuple['enabled'] ? 'enable '+uniqueClass : 'disable '+uniqueClass} onClick = {this.onChangeFilters(tuple)}>
                {enabled = (enabled || tuple['enabled'])}
                {checked = (checked || tuple.checked)}
                <input className={"hide_inputs "+uniqueClass}  name="filters" type="checkbox"
                        filtertype = {filterType} id={"chck-" + tuple.filterType + "-" + tuple.id+i} defaultChecked= {tuple.checked} />
                <label filtertype = {filterType}  className={"option_label "+uniqueClass} htmlFor={"chck-" + tuple.filterType + "-" + tuple.id+i}>{tuple.name} {tuple.count > 0 ? '('+tuple.count+')' : ''}</label>
            </li>
        );
        if(!enabled && this.props.filtersWithSearch.indexOf(filterType) === -1)
            return false;
        
        if(checked){
            this.showClearAll = true;
        }
    return(
       <div className={"filters_dropdwn cutoff_multi "+uniqueClass}>
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
            <li onClick = {this.onChangeFilters(tuple)} filtertype = {filterType} key={tuple.filterType + '_' + tuple.id+'_'+i} className={tuple['checked'] ? 'selected '+uniqueClass : uniqueClass}>
            {tuple.name}{tuple.count > 0 ? ' ('+tuple.count+')' : ''}
            </li>
        );
    return(
             <div className={"filters_dropdwn "+uniqueClass}>
             {(this.props.filtersWithSearch.indexOf(filterType) !== -1) ? this.generateSearch(filterType,uniqueClass) : ''}
              <ul className={'scroll_ '+uniqueClass}>
                  {filtersList}
               </ul>  
             </div>  
      ) 
  }


  onChangeFilters = tuple => (event) => {
      resetGNB();
      const {displayName} = this.props;
      const filterType = event.target.attributes.filtertype.value;
      let showFullLoader = false;
      if(filterType =='institute'){
        showFullLoader  = true;
      }
      this.handleClickOnFilters(showFullLoader);

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
      }else if(filterType=='institute' && this.props.isCutOffPage){
        let examParams = "";
        let searchParams ="";
        if(this.props.requestData.appliedFilters.pageType == 'icox'){
          if(this.props.requestData && this.props.requestData.appliedFilters && this.props.requestData.appliedFilters.examIds.length != 0){
              examParams =this.props.shortName.exam +'[]='+this.props.requestData.appliedFilters.examIds[0];
          }
        searchParams = '?'+ examParams+ '&rf=filters';
        }
        let newPageUrl  = tuple.url+searchParams;
        this.props.history.push(newPageUrl);
        return true;
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
      if(this.props.isCutOffPage){
          let listingIdCheck = this.props.instituteId.toString();
          if(!params['i']){
            params['i'] = [];
            params['i'].push(this.props.instituteId);       
          }else if(params['i'].indexOf(listingIdCheck) ==-1){
            params['i'].push(listingIdCheck);       
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
    const {filterOrder,requestData} = this.props;
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
              let filterTypeKey = jsUcfirst(filterType);
              let uniqueClass = 'click-cls-'+filterType[0]+filterType[1]+filterType[2];
              let stateKey = this.state['layerOpen'+filterTypeKey];
              let showHeading = 'Select '+filterTypeKey;

              if(requestData && requestData.appliedFilters){
                let key = filterType+'Ids';
                if(requestData.appliedFilters[key] && requestData.appliedFilters[key].length>0){
                  let keyHeading = requestData.appliedFilters[key].length>1?filterTypeKey+'s':filterTypeKey;
                  if(filterType == 'category'){
                    showHeading = this.props.appliedCategory ? this.props.appliedCategory : "1 Category Selected";
                  }else{
                    showHeading = requestData.appliedFilters[key].length+' '+keyHeading+' Selected';
                  }
                }
              }

              switch(filterType){
               case 'exam':
               case 'specialization':
               allFilterHtml.push(
                 <div key={'div_'+filterType} className={"filters_exam filter_arw "+uniqueClass}>
                  <div key={'innerdiv_'+filterType} className={'filter__text '+uniqueClass} onClick={this.handleClickOnFilter.bind(this,filterTypeKey)} >{showHeading} </div>
                 <DropDown data={this.generateFilterHtml(filterData,filterType,uniqueClass)} isOpen={stateKey} completeHtml={true} />
                 </div> 
                );
               break;
              case 'category': 
              case 'institute':
              allFilterHtml.push(<div key={'div_'+filterType} className={"filters_course filter_arw "+uniqueClass} >
                              <div key={'innerdiv_'+filterType} className={'filter__text '+uniqueClass} onClick={this.handleClickOnFilter.bind(this,filterTypeKey)} ><p className={uniqueClass}>{showHeading} </p></div>
                              <DropDown data={this.generateSingleSelectHtml(filterData,filterType,uniqueClass)} isOpen={stateKey} completeHtml={true}/>
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
        <section className='listingTuple' id="admission" >
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
