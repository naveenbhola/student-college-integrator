import React from 'react';
export default function ErrorMsg()
{
	const reload= () => 
		{
			window.location.reload();
		}
	return (
			<div className="notFound-Div">
				<div className="notFound-Page">
			       <span className="oops-img"></span>
			        <p className="title noMrg">Oops.</p>
			        <p>Something Went Wrong <br/>Please try again</p>
			        <a href="javascript:void(0)" onClick={reload} className="bkHome-btn">Refresh</a>
			    </div>
			</div>
		)
}