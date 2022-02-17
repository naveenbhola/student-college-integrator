import React from 'react';
import './../assets/Banner.css';
import Banner from './Banner';
import Menu from './Menu';

class TopSection extends React.Component
{
   constructor(props)
   {
      super(props);
      this.state = {}
   }

   render()
   {
      return (
            <React.Fragment>
               <section id="topSection">
                  <Banner isPdfCall={this.props.isPdfCall} count={this.props.count} imageUrl={this.props.imageUrl}  displayName={this.props.displayName} chpData={this.props.sectionData}/>
                  <Menu isPdfCall={this.props.isPdfCall} config={this.props.config} sectionData={this.props.sectionData}/>
               </section>
            </React.Fragment>
      )
   }
}
export default TopSection;