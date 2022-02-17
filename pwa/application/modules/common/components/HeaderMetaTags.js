import React from 'react';

class headerMetaTags extends React.Component
{
	render()
	{
		if(this.props.metaTitle && this.props.metaTitle.trim() == '')
		{
			this.props.metaTitle = "Higher Education in India | Shiksha.com";
		}
		if(this.props.metaTitle && this.props.metaDescription.trim() == '')
		{
			this.props.metaDescription = "Explore thousands of colleges and courses on India's leading higher education portal - Shiksha.com. See details like fees, admission process, reviews and much more.";			
		}
		return(
				    <React.Fragment>
				    	
				    </React.Fragment>
			)
	}
}

export default headerMetaTags;