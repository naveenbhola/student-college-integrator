import React from 'react';
import  './../assets/chphomePageCss.css';
import Wiki from '../../../common/components/Wiki';

class HomePage extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   getSections(){
      var self = this;
      var sectionData  = new Array();
      let html = (this.props.sectiondata).map(function (data, index){
               if(data.labelName == "OverView")
               {
                  data.labelName = (self.props.displayName) ? 'What is '+self.props.displayName+'?' : "Overview";
               }
               return (<Wiki key={index} sectiondata={data} order={index} sectionname={self.props.sectionname} deviceType={self.props.deviceType}/> );
      });
      sectionData.push(html);
      return sectionData;
   }

   render()
   {
      let chpWikiData   = this.getSections();
      return (
            <React.Fragment>
             {chpWikiData}
            </React.Fragment>
         )
   }
}

export default HomePage;
