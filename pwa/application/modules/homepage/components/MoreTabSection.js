import React from 'react';
import styles from './../assets/categorySection.css';
import Analytics from './../../reusable/utils/AnalyticsTracking';
import {rippleEffect} from './../../reusable/utils/UiEffect';
import {getObjectSize} from './../../../utils/commonHelper';

class MoreTabSection extends React.Component{
	constructor(props)
	{
		super(props);
		this.state = {
            isOpen : false,
            customList : {},
            layerHeading : '',
            search: false,
            subHeading : false,
            placeHolderText : '',
        }
		this.trackEvent = this.trackEvent.bind(this);
	}

    trackEvent(actionLabel,event)
    {
    	rippleEffect(event);
        Analytics.event({category : 'HPbody', action : actionLabel, label : 'NavLink'});
    }
    getLocationLayer(params)
    {
        const {onClick} = this.props;
        onClick('',params);
    }
	render()
	{
		let {cssNames} = this.props;
		let excludeStreams = [3,5];
		this.countSubvalues = 0;
		if(Object.keys(this.props.categoryData).length == 0)
			return <div data-index="5" id="other" className="chooseStream loader active">Loading...</div>
		else
		{
			var self = this;
			return (
				  <div data-index="5" id="other" className="chooseStream">
				  <input type="checkbox" className='read-more-state-out hide' id="more-streams"/>
				  <div className='read-more-wrap'>
				{this.props.categoryData.map(function(key,index){
					self.countSubvalues = 0;
					return (key.id == 21 ? (<ul className={'others' + ' ' + (index > 9 ? 'read-more-target-out' : '')} key={'more_' + index}>
		                    <li className="first" key={"stream_"+ key.id}>
	                        <label  className="anchrHp ripple dark" > <div className={cssNames[index]} onClick={ self.trackEvent.bind(self,'MidMore')}> <span> <i>&nbsp;</i> </span> <div><a href={self.props.config.SHIKSHA_HOME+"/sarkari-exams/exams-st-21"}> <h3>{key.name}</h3> </a></div></div> </label></li></ul>) : (excludeStreams.indexOf(key.id) == -1) ?  (
					 	<ul className={'others' + ' ' + (index > 9 ? 'read-more-target-out' : '')} key={'more_' + index}>
		                    <li className="first" key={"stream_"+ key.id}>
		                    	<input type="checkbox" className="accrdn-check" name={"stream"+ key.id} id={"stream"+ key.id}/>	
	                        <label  htmlFor= {"stream"+ key.id} className="anchrHp ripple dark" > <div className={cssNames[index]} onClick={ self.trackEvent.bind(self,'MidMore')}> <span> <i>&nbsp;</i> </span> <div> <h3>{key.name}</h3> </div> <span> <i>&nbsp;</i> </span> </div> </label>
		                        {
		                         <React.Fragment>
		                        	<ul className="lbl02" id="_ct1">
		                        	<input type="checkbox" className='read-more-state hide' id={"stream_child"+ key.id}/>
		                        	<div className='read-more-wrap'>
		                        	<li>
					                                <a className='tabClk'>
					                                    <div className='mba-exam' onClick={self.getLocationLayer.bind(self,{streamId : key.id})}>
					                                        <div>
					                                            <span>All {key.name}</span>
					                                            <input type="hidden" className="stream_only"/>
					                                            <div className='select-className'> <select style={{'display':'none'}}></select> </div>
					                                        </div>
					                                            <span> <i>»</i> </span>
					                                    </div>
					                                </a>
					                   </li>
			                        		{(getObjectSize(key.substreams) > 0) ? self.generteLink(key, key.substreams, 'substreamId') : ''}
						                    {(getObjectSize(key.specializations) > 0) ? self.generteLink(key, key.specializations, 'specializationId') : ''}
						                  </div>
						                  { self.countSubvalues > 10 ? (<label htmlFor={"stream_child"+ key.id} className='read-more-trigger view-all pl59'>View more</label>) : ''}
		                        		</ul>
		                        		</React.Fragment>
		                        	}
		                        
		                    </li>
                </ul>) : '')
            })
				}
				</div>
                   <label htmlFor="more-streams" className='read-more-trigger view-all cntr ripple dark' onClick={(event) => rippleEffect(event)}>View more</label>
            </div>
				)
		}
	}

	generteLink(key, data, sVariable){
		var html = new Array();
		var count = 1;
		for(var sindex in data){
			this.countSubvalues++;
			var skey = data[sindex];
			if(sVariable == 'substreamId'){
				html.push(<li className={'first' + ' '+(count > 10 ? 'read-more-target' : '')} key={'sub' + sindex}>
						                                <a className="tabClk">
						                                    <div className='mba-exam' onClick={this.getLocationLayer.bind(this,{streamId : key.id, substreamId : skey.id})}>
						                                        <div> <span>{skey.name}</span> </div>
						                                        <span> <i>»</i> </span> </div>
						                                </a>
						                            </li>);

			}else if(sVariable == 'specializationId'){
				html.push(<li className={'first' + ' '+(count > 10 ? 'read-more-target' : '')} key={'sub' + sindex}>
						                                <a className="tabClk">
						                                    <div className='mba-exam' onClick={this.getLocationLayer.bind(this,{streamId : key.id, specializationId : skey.id})}>
						                                        <div> <span>{skey.name}</span> </div>
						                                        <span> <i>»</i> </span> </div>
						                                </a>
						                            </li>);
			}
			
			count++;
		}
		return html;
	}
}

MoreTabSection.defaultProps = {
	cssNames : ['o-mngmnt','o-engg','','o-hsptl','','o-anim','o-media','o-it','o-human','o-art','o-se','o-arch','o-acnt','o-bank','o-aviation','o-teaching','o-nursing','o-mdcn','o-beauty','o-gexams','o-rtl']
}
export default MoreTabSection;
