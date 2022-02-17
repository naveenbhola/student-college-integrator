import React from 'react';
const ToolTipMsg = (props) => {
	if(props && props.hideSaveListMsg == null){
		return null;
	}
	return (
		<div className="savedList" id="savedList">
                <div className="relative">
                    <i className="boxArrow"></i>
                    <i className="boxCross" onClick={props.hideSaveListMsg}>x</i>
                    <strong>{(props && props.heading) ? 'Already Saved' : 'List Saved'}</strong>
                    <p>You can access saved list from here</p>
                </div>
            </div>
		);
}
export default ToolTipMsg;