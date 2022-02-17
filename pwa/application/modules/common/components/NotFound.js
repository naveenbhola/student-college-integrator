import React from 'react';
import {Link} from 'react-router-dom';
import config from './../../../../config/config';

const NotFound = (props) =>{
	let ctaUrl = (typeof(props) != 'undefined' && props && typeof(props.deviceType) != 'undefined' && props.deviceType == 'desktop') ? <a href={config().SHIKSHA_HOME} className="bkHome-btn">Go to Home Page</a>  : <Link to={{ pathname : '/'}} className="bkHome-btn">Go to Home Page</Link>;
	return (
			<section className="notFound-Div">
				<div className="notFound-Page" id="notFound-Page">
			       <img src="https://images.shiksha.ws/pwa/public/images/pwa_404.png" alt="shiksha 404 image" />
			        <p className="title">Page Not Found</p>
			        <p>Sorry, the page that you are <br/>trying to access is not available<br/> or has been moved.</p>
			  		{ ctaUrl }
			    </div>
		    </section>
		)
}
export default NotFound;
