import React from 'react';
/**
Only POC
**/
export default class DesktopPage extends React.Component
{
	open()
	{
		var reg = new window.RegistrationForm();
		reg.showRegistrationForm();
	}
	render()
	{
		return(	
			<div>
				<h3> Desktop Site</h3>
				<a href="javacript:void(0);" onClick={this.open.bind(this)}>Click to Sign Up</a><br/>
				<b>Note - Please ignore header and footer</b><br/>
			</div>
		) ;
	}
}