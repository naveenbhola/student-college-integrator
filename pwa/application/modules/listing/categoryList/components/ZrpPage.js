import React from 'react';
import styles from './../assets/ZrpPage.css';
import APIConfig from './../../../../../config/apiConfig';
import PopupLayer from './../../../common/components/popupLayer';
import SingleSelectLayer from './../../../common/components/SingleSelectLayer';
import {getRequest} from './../../../../utils/ApiCalls';
import {Link, withRouter} from 'react-router-dom';
class ZeroResultPage extends React.Component
{
	constructor(props)
	{
		super(props);
        this.state = {
            streamData : null,
            isAnchorLink : false,
            search : false
        };
	}
    prepareSelectStreamLayer()
    {
        const {streamData} = this.state;
        if(streamData && typeof streamData != 'undefined'){
            var layerData = new Array();
            for(var streamId in streamData){
                var s = streamData[streamId];
                    layerData.push(<li key={ s.id } streamid={s.id} onClick={this.userSelectedStream.bind(this, {'id':s.id,'name':s.name})}><span>{s.name}</span></li>)
            }
            return (<ul className="ul-list" key="ullist">{ layerData }</ul>);
        }
        else
        {
            return(<ul className="ul-list" key="ullist">some error occured</ul>);
        }
    }
    openStreamLayer()
    {
        this.PopupLayer.open();
    }
    modalClose()
    {
        this.setState({...this.state,'isOpen' : false,'streamData':{}});
    }
    getAllStreams()
    {
        this.setState({...this.state,'isOpen' : true,layerType : 'stream',layerHeading : 'Select Stream',showSubHeading : false});
        getRequest(APIConfig.GET_ALL_STREAMS).then((response) =>{
            if(response.data !=null){
                this.displayList(response.data.data); 
            }
          })
          .catch((err)=> {});
    }
    displayList(jsonData)
    {
        let customData = {};
        customData['streamList'] = new Array();
        for(var i in jsonData)
        {
            customData['streamList'].push(jsonData[i]);
        }
        this.setState({streamData: customData});
    }
    handleOptionClick(event,data)
    {
        const {config} = this.props;
        if(typeof data.name == 'undefined' || !data.name)
            return;
        const streamParams = '?q='+encodeURIComponent(data.name)+'&s[]='+data.streamId+'&rf=zrp';
        this.props.history.push('/search' + streamParams);

    }
    generateSearchZRP(){
	    if(!this.props.isSrp){
	        return (<p className="noResult-msg">Sorry no results found!</p>);
        }
        return (
            <React.Fragment>
                <p className="noResult-msg">Sorry no results <br/>found for "{this.props.keyword}"</p>
                <div className="modify_col">
                    <Link to={{ pathname : '/searchLayer' , keyword : this.props.keyword}} className="modify_btn" >Modify Search</Link>
                </div>
                <div className="modify_col">
                    <p className="noResult-msg">OR</p>
                </div>
            </React.Fragment>
        );
    }
	render()
	{
        return (
        <div className="applyFilter-container rsult-bg">
            <div className="noResult-Page" id="notFound-Page">
                {this.generateSearchZRP()}

                    <p>View colleges by stream</p>
            
            <div className="search-inner-container noResult-bg">
                <div className="search-field clearfix zerp-list">
                    <ul>
                        <li>
                             <div className="search-layer-field " onClick={this.getAllStreams.bind(this)}>
                                <input type="text" className="search-clg-field shikshaSelect_input" readOnly="readonly" placeholder="Select Stream" id="zeroResultDropDown1_input"/>
                                <em className="pointerDown"></em>
                            </div>
                            <p className="clr"></p>
                        </li>
                       
                    </ul>
                </div>
            </div> 
            <SingleSelectLayer isDesktop={this.props.isDesktop} show={this.state.isOpen} onClose={this.modalClose.bind(this)} data={this.state.streamData} heading= {this.state.layerHeading} showSubHeading={this.state.subHeading} layerType={this.state.layerType} handleOptionClick={this.handleOptionClick.bind(this)} isAnchorLink={this.state.isAnchorLink} search={this.state.search}/>
            <p className="clearfix mb50 find-clTxt">Didn't find the college you were looking for?<br/>
                Let us know at <a href="mailto:site.feedback@shiksha.com" className="feedbck-info">site.feedback@shiksha.com</a></p>
            </div>
        </div>
            )
    }
}

export default withRouter(ZeroResultPage);