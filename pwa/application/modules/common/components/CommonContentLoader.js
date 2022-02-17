import React from 'react';

class CommonContentLoader extends React.Component{
	constructor(props)
   	{
      super(props);
      this.state = {};
   	}
    
   	render(){
		return (
			<React.Fragment>		 	
				<section className="pwa_pagecontent chp">
				   <div className="pwa_container">
					   <div id="pageLoader" >
					    <div className="pageLoader2">
					        <div className="loaderv1_wrapper">
					           <div className="loaderv1_wrapper-col">
					              <span className="inner_loaderv1"></span>
					           </div>
					       </div>
					    </div>
					</div>
				   </div>
				</section>
		       
		       
	       </React.Fragment>
		      
			);
		}
}

export default CommonContentLoader;