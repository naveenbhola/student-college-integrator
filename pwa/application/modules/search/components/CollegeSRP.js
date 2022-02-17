import React from 'react';
import CategoryPage from "../../listing/categoryList/components/CategoryPage";
import ZeroResultPage from './../../listing/categoryList/components/ZrpPage';
import {parseQueryString} from './../../../utils/commonHelper'

class CollegeSRP extends React.Component {
    constructor(props) {
        super(props);
    }
    render(){
        if(!this.props.location.search){
            return <ZeroResultPage />;
        }
        const params = parseQueryString(this.props.location.search);
        /*if(!(params['q']))
            return <ZeroResultPage />;*/
        return(
            <CategoryPage searchedKeyword = {decodeURIComponent(params['q'])} isSrp={true}  location={this.props.location}/>
        );
    }
}
export default CollegeSRP;