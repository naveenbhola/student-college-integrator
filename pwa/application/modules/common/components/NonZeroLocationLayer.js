import React from 'react';
import {getCookie,setCookie,showToastMsg} from './../../../utils/commonHelper';
import {defaultLocationLayerSteamIds} from './../../listing/categoryList/config/categoryConfig';
import SingleSelectLayer from './../../common/components/SingleSelectLayer';
import {hierarchyMap} from './../../homepage/config/homepageConfig';
import {getRequest } from './../../../utils/ApiCalls';
import APIConfig from './../../../../config/apiConfig';
import {withRouter } from 'react-router-dom';

class NonZeroLocation extends React.Component{
    constructor(props)
    {
        super(props);
        this.state = {
            isOpen : false,
            customList : {},
            categoryData : {},
            layerHeading : '',
            search: false,
            subHeading : false,
            placeHolderText : '',
            layerType : '',
            isAnchorLink : false,
        }
        this.streamValues = {};
    }
    componentDidMount()
    {
        const {categoryName,data} = this.props;
        if(!categoryName && Object.keys(data).length == 0)
            return;
        else
        {
            this.getLocationLayer(categoryName,data);
        }
    }
    getLocationLayer(categoryName,obj)
    {
        let selectedType = getCookie('selectedLocationType');
        let selectedLocation  = getCookie('selectedLocation');
        let params = {};
        if( categoryName != '' && typeof hierarchyMap[categoryName] != 'undefined')
        {
            params = hierarchyMap[categoryName];
        }
        if(typeof obj != 'undefined' && typeof obj == 'object')
        {
            this.streamValues = obj;
            params = Object.assign({},obj,params);
            if(typeof obj['streamId'] != 'undefined' && defaultLocationLayerSteamIds.indexOf(parseInt(obj['streamId'])) > -1)
            {
                let data = {filterType : 'city' , id : 1};
                data = Object.assign({},params,data);
                this.checkCategoryPageExists(categoryName,data,false);
                return;
            }
        }
        else
        {
            this.streamValues = {};
        }
        if(selectedType != '' && selectedLocation != '' && typeof selectedType != 'undefined' && typeof selectedLocation != 'undefined' && parseInt(selectedLocation) > 0)
        {
            let data = {filterType : selectedType , id : selectedLocation};
            data = Object.assign({},params,data);
            this.checkCategoryPageExists(categoryName,data);
            return;
        }
        this.getLocationData(categoryName,params);
    }
    getLocationData(categoryName,params)
    {
        const {hamburgerClose} = this.props;
        if(typeof hamburgerClose == 'function')
        {
            hamburgerClose();
        }
        let categoryNameMapping = {'design' : 'Design','law' : 'Law' , 'engg' : 'B.Tech', 'mba' : 'MBA'};
        let layerHeading = (categoryName != '' && typeof categoryName != 'undefined') ? categoryNameMapping[categoryName]+ ' Locations' : 'Locations';
        this.setState({...this.state,'isOpen' : true,'layerHeading' : layerHeading,'search' : true,'subHeading':true,'placeHolderText':'Enter Location',isAnchorLink : false,layerType : 'location'});
        let hashValue = btoa(JSON.stringify(params));
        getRequest(APIConfig.GET_LOCATIONBY_FILTER+'?data='+hashValue).then((response) => {
            this.displayList(response.data.data);
        });
    }
    displayList(customData)
    {
        this.setState({'customList' : customData});
    }
    checkCategoryPageExists(categoryName,data,isSetCookie = true)
    {
        let {history,hamburgerClose} = this.props;
        let fetchPromis = this.getCategoryPageURL({},data,isSetCookie);
        Promise.resolve(fetchPromis).then((response) =>
        {
            if(typeof response.data.data != 'undefined' &&  response.data.data && response.data.data != '')
            {
                let redirectUrl = response.data.data.substring(0,1) != '/' ? '/'+response.data.data: response.data.data;
                this.modalClose();
                history.push(redirectUrl);
            }else
            {
                delete data['filterType'];
                delete data['id'];
                showToastMsg('Sorry, result not found for your location. Please try for another location.');
                this.getLocationData(categoryName,data);
            }
        });
    }

    getCategoryPageURL(event,data,isSetCookie=true)
    {      
        let params = {};
        let categoryName = this.props.categoryName;
        if(typeof hierarchyMap[categoryName] != 'undefined')
        {
            params = hierarchyMap[categoryName];
        }
        if(data.filterType == 'city')
        {
            params['cityId'] = data.id;
        }
        else if(data.filterType == 'state')
        {
            params['stateId'] = data.id;
        }
        if(isSetCookie)
        {
            setCookie('selectedLocation',data.id);
            setCookie('selectedLocationType',data.filterType);
        }
        delete data['filterType'];
        delete data['id'];
        /*if(typeof data == 'object' && Object.keys(data).length > 0)
        {
            params = Object.assign({},params,data);
        }*/
        if(typeof this.streamValues == 'object' && Object.keys(this.streamValues).length > 0)
        {
            params = Object.assign({},params,this.streamValues);
        }
        let hashValue = btoa(JSON.stringify(params));
        return getRequest(APIConfig.GET_CHECKIFCATEGORYPAGE_EXISTS+'?data='+hashValue);
    }
    navigateToCategoryPage(event,data)
    {
        let {history} = this.props;
        let fetchPromise = this.getCategoryPageURL({},data);
        var self = this;
        Promise.resolve(fetchPromise).then(function(response){
            if(typeof response.data.data != 'undefined' &&  response.data.data && response.data.data != '')
            {
                let redirectUrl = response.data.data.substring(0,1) != '/' ? '/'+response.data.data: response.data.data;
                self.modalClose();
                history.push(redirectUrl);           
            }
            else
            {
                showToastMsg('Sorry, result not found for your location. Please try for another location.');
            }
        })
    }
    modalClose()
    {
        const {onClose,hamburgerClose} = this.props;
        this.setState({...this.state,'isOpen' : false,'customList':{}});
        if(typeof onClose == 'function')
            onClose();
        if(typeof hamburgerClose == 'function')
            hamburgerClose();
    }
    render()
    {
        if(this.state.isOpen)
        {
            return (<SingleSelectLayer show={this.state.isOpen} onClose={this.modalClose.bind(this)} data={this.state.customList} search={this.state.search} heading= {this.state.layerHeading} showSubHeading={this.state.subHeading} placeHolderText={this.state.placeHolderText} layerType={this.state.layerType} isAnchorLink={this.state.isAnchorLink} handleOptionClick={this.navigateToCategoryPage.bind(this)}>
                    </SingleSelectLayer>)
        }
        else
        {
            return null;
        }
    }
}
export default withRouter(NonZeroLocation);