import React from 'react';
import Anchor from './../../reusable/components/Anchor';
import {removeDomainFromUrl} from './../../../utils/urlUtility';

class SingleLayerTemplate extends React.Component{
    constructor(props)
    {
        super(props);    
        this.state ={
            data : {},
            filter : true
        }
    }
    componentDidMount()
    {
        this.setState({'data':this.props.data});
    }
    componentWillReceiveProps(newProps)
    {
        if(newProps.searchText.length > 0)
            this.filterDisplayList(newProps.searchText);
        else
            this.setState({'data':newProps.data});    
    }
    filterDisplayList(searchText)
    {
        var dispalyedItems = this.props.data;
        Object.filter = (obj, predicate) => Object.keys(obj).map( function(key) { 
             return dispalyedItems[key].filter(function(n,i){
                return n.name.toLowerCase().indexOf(searchText.toLowerCase()) !== -1;
            })
         } );
        var filtered = Object.filter(dispalyedItems, searchText); 
        var finalResult = {};
        var keyNames = Object.keys(this.props.data);
        for(var i in filtered)
        {
            if(filtered[i].length > 0)
                finalResult[keyNames[i]] = filtered[i];
        }
        this.setState({'data':finalResult});
    }
    static formatHeadings(string)
    {
        if(!string || string.length == 0)
        {
            return;
        }
        //format heading for layer 
        //return string.replace(/([A-Z])/g, ' $1').replace(/^(.)*/, function(str){ return str.toUpperCase(); })
        return string.replace(/([A-Z])/g, ' $1').replace(/^(.)*/, function(str){ return str.charAt(0).toUpperCase() + str.substring(1); });
    }
    handleOptionClick(n,event)
    {
        const {onClick} = this.props;
        onClick(event,n);
    }
    render()
    {
        const {isAnchorLink} = this.props;
        if( this.state.data == null || Object.keys(this.state.data) == 0)
            return (<div className="noResult-found">Sorry, no {this.props.layerType} found for your query "{this.props.searchText}"</div>);
        var values = this.state.data;
        let showSubHeading = this.props.showSubHeading;
        var self = this;
       return(
                <div className='pwa-cont pad'>
                        {
                            Object.keys(values).map(function(name,index){
                            return (
                                <div key={index}>
                                { (values[name] != null && values[name].length > 0 )? 
                                    <React.Fragment>
                                        { showSubHeading ? <strong key= { index } >{SingleLayerTemplate.formatHeadings(name)}</strong> : '' }
                                        
                                        <ul key={'ul'+index} className="pad10-l">
                                        {
                                            values[name].map(function(n,i){
                                                if(isAnchorLink)
                                                    return <li key={i}><Anchor link={self.props.isLink} onClick={ (self.props.isLink) ? self.props.onCloseLayer : null} className="block" key={'a'+i} to={(self.props.isLink) ?removeDomainFromUrl(n.url) : n.url}>{n.name}</Anchor></li>
                                                else
                                                    return <li key={i} onClick={self.handleOptionClick.bind(self,n)}> {n.name} </li>
                                                    
                                            })
                                        }
                                        </ul>
                                    </React.Fragment>
                                : ''}
                                    
                                </div>
                                )
                            })
                        }
                </div>
        )
    }

}

SingleLayerTemplate.defaultProps = {
    isLink : false    // false then <a> tag, if true then <Link> tag
}

export default SingleLayerTemplate;