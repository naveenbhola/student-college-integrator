import React from 'react';
import  './../assets/chphomePageCss.css';
import WikiContent from './WikiContent';
import WikiLabel from './WikiLabel';

class Wiki extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   render()
   {
      //const {config}   = this.props;
      return (
            <React.Fragment>
                <section id={"section_"+`${this.props.order}`}>
               <div className="_container">
                  <WikiLabel labelName={this.props.sectiondata.labelName}/>
                  <div className="_subcontainer">
                     <WikiContent order={this.props.order} sectiondata={this.props.sectiondata} sectionname={this.props.sectionname} deviceType={this.props.deviceType}/>
                  </div>
               </div>
            </section>
            </React.Fragment>
         )
   }
}

export default Wiki;
