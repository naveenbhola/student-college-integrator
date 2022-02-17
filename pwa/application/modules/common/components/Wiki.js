import React from 'react';
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
                  <WikiLabel labelName={this.props.sectiondata[this.props.labelName]}/>
                  <div className="_subcontainer">
                     <WikiContent cutWikiContent={this.props.cutWikiContent} labelValue={this.props.labelValue} order={this.props.order} sectiondata={this.props.sectiondata} sectionname={this.props.sectionname} deviceType={this.props.deviceType}/>
                  </div>
               </div>
            </section>
            </React.Fragment>
         )
   }
}

Wiki.defaultProps = {
   labelName:'labelName',
   labelValue: 'labelValue',
   cutWikiContent : false
}

export default Wiki;
