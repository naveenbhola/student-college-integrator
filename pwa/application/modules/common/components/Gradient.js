import React from 'react';
import './../assets/gradient.css';
import {Link} from 'react-router-dom';

class Gradient extends React.Component{
	constructor()
	{
		super();
	}

	render()
	{
		return (
			<React.Fragment>
                <div className="gradient-col" id="viewMoreLink">
                    <Link  className="gradVw-mr button button--secondary rippleefect" onClick={this.props.onClick} to={this.props.url}>
                    	{this.props.heading}
                        <i className="blu-arrw"></i>
                    </Link>
                </div>
			</React.Fragment>
		)
	}
}
export default Gradient;