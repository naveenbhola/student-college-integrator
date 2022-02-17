import React from 'react';
import '../assets/UserActionMsgBox.css';

const UserActionMsgBox = (props) => {
   if(props.msgString === '' || props.msgString == null){
      return null;
   }
   let icon = null;
   switch (props.iconFlag) {
      case 'success':
         icon = <svg className="checkmark" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <circle className="circle" cx="16" cy="16" r="16" fill="#0c3"> </circle>
            <path className="check" d="M9 16l5 5 9-9" fill="none" stroke="#fff" strokeWidth="2.5" strokeLinecap="round"> </path>
         </svg>;
      break;
      case 'failure':
         icon = <svg className="checkmark" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <circle className="circle" cx="16" cy="16" r="16" fill="#0c3"> </circle>
            <path className="check" d="M9 16l5 5 9-9" fill="none" stroke="#fff" strokeWidth="2.5" strokeLinecap="round"> </path>
         </svg>;
      break;
   }
   return (<div className='user-action-msg-box'>
      <div className='child-box msg-icon'>{icon}</div>
      <div className='child-box msg-txt'><p>{props.msgString}</p></div>
   </div>);
};

UserActionMsgBox.defaultProps = {
   msgString:'',
   iconFlag : 'success'
};
export default UserActionMsgBox;