import React from 'react';
import './../assets/BreadCrumb.css';
import Anchor from './../../reusable/components/Anchor';
import PropTypes from 'prop-types';

class BreadCrumb extends React.Component{
	constructor(props)
	{
		super(props);
	}

	createLink(){
		let list = [];
		this.props.breadCrumb.forEach((value,index)=>{
			if(value.url){
				let item = <span key={index+1}> <Anchor link={(value.isAbsoluteUrl) ? false : true} to={value.url}> <span className="bredcrumbs_span">{(value.name == 'Home')? <i className="pwaIcon homeType1"></i> : value.name}</span> </Anchor> </span>;
				list.push(item);	
				list.push(<span key={index+10} className="breadcrumb_arw">&rsaquo;</span>);
			}else{
				let item = <span key={index+1}> <span className="bredcrumbs_span">{value.name}</span></span>;
				list.push(item);
				index<(this.props.breadCrumb.length-1) ? list.push(<span key={index+10} className="breadcrumb_arw"> &rsaquo;</span>):"";
			}
			
		});
		return list;
	}

	render()
	{	
		if(this.props.breadCrumb == null || this.props.breadCrumb.length<=0){
			return null;
		}
		return (
			<React.Fragment>
				<div className="pwa_breadcumbs">
					{this.createLink()}
				</div>
			</React.Fragment>
		)
	}
}
BreadCrumb.propTypes = {
	breadCrumb : PropTypes.array
}
export default BreadCrumb;