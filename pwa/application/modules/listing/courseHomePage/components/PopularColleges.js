import React from 'react';
import  './../assets/chphomePageCss.css';
import {Link} from 'react-router-dom';
import WikiContent from './../../../common/components/WikiContent';
import CategoryTuple from '../../categoryList/components/CategoryTuple';
import {addingDomainToUrl} from './../../../../utils/commonHelper';

class PopularColleges extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   getSectionHtml(){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.sectiondata.wikiData).map(function (data, index){
              return (<WikiContent key={index} order={self.props.order} sectiondata={data} sectionname={self.props.sectionname}/>);
      });
      sectionData.push(html);
      return sectionData;
   }

   render()
   {
      const {config} = this.props;
      let chpWikiData    = '';
      let url = this.props.sectiondata.allTupleUrl;
      if(this.props.sectiondata.wikiData!=null){
        chpWikiData   = this.getSectionHtml();
      }
      let categoryData = {};
      if(this.props.sectiondata.tuple!=null){
        categoryData.categoryInstituteTuple = this.props.sectiondata.tuple;
      }
      let categoryDataNum = Object.keys(categoryData).length;
      let gaCategoryLabel = (this.props.deviceType == 'desktop') ? 'CHP_Desktop' : 'CHP';
      let isPdfCall     = this.props.deviceType =='mobile'? this.props.isPdfCall: false;
      let isPdfCallCTP  = this.props.deviceType =='mobile'? this.props.isPdfCall: true;
      return (
            <React.Fragment>
		         <section id="PopularColleges">
		            <div className="_container">
		              <h2 className="tbSec2">Popular {this.props.labelname} Colleges in India</h2>
                  <div className="_subcontainer">
                    {chpWikiData}
                    {categoryDataNum!=0 && <CategoryTuple isPdfCall={isPdfCallCTP} config={this.props.config} categoryData={categoryData} gaTrackingCategory='CHP' isCHP={true} showInTable={false} srtTrackId={this.props.deviceType === 'mobile' ? "1881" : "1951"} ebTrackid={this.props.deviceType === 'mobile' ? "1885": "1953"} recoShrtTrackid="1891" recoEbTrackid="1887" isCallReco={this.props.isCallReco} gaTrackingCategory={gaCategoryLabel} pageType = {"courseHomePage"} deviceType={this.props.deviceType}/>}
                    {(url && !isPdfCall) ? <div className="button-container"><Link to={url} className="button button--secondary arrow">View All Popular Colleges</Link></div>:''}
                    {(url && isPdfCall) ? <div className="button-container"><a href={addingDomainToUrl(url, this.props.config.SHIKSHA_HOME)} ><button type="button" name="button" className="button button--secondary arrow">View All Popular Colleges</button></a></div> : ''}
                  </div>
		            </div>
		         </section>
            </React.Fragment>
         )
   }
}

export default PopularColleges;
