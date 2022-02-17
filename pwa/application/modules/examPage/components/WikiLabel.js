import React from 'react';
import  './../assets/chphomePageCss.css';

class Wiki extends React.Component
{
   constructor(props)
   {
      super(props);
   }

   render()
   {
      return (
            <React.Fragment>
                <h2 className="tbSec2">{this.props.labelName}</h2>
            </React.Fragment>
         )
   }
}

export default Wiki;
