import React from 'react';
import {removeDomainFromUrlV2} from "../../../utils/urlUtility";
import { Link } from "react-router-dom";
import  "./../assets/BreadCrumbs.css"
class BreadCrumbs extends React.Component {
    constructor(props) {
        super(props);
    }
    generateHTML(){
        const breadCrumbsData = this.props.breadCrumbData;
        const length = breadCrumbsData.length;
        return breadCrumbsData.map((tuple, i) =>
            <React.Fragment  key={'crumb_' + i}>
                {tuple.url ? (i === 0  && !this.props.isMobile) ? <span> <a href={tuple.url}> <span>{(tuple.name == 'Home')? <i className="pwaIcon homeType1"></i> : tuple.name}</span> </a> </span> :<span>
                        <Link to={removeDomainFromUrlV2(tuple.url)}> <span>{(tuple.name == 'Home')? <i className="pwaIcon homeType1"></i> : tuple.name}</span> </Link> </span> :
                    <span><span>{(tuple.name == 'Home')? <i className="pwaIcon homeType1"></i> : tuple.name}</span></span>}
                {length - 1 !== i ? <span  className="breadcrumb-arrow">›</span> : ''}
            </React.Fragment>
        );
    }
    render(){
       return (<div className="breadcrumb_v1">
                    {this.generateHTML()}
              </div>);
    }
}
BreadCrumbs.defaultProps = {
    isMobile : false
};
export default BreadCrumbs;