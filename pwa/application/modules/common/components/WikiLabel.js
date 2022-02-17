import React from 'react';
import PropTypes from 'prop-types';

const WikiLabel = (props) => {
   if(props.labelName === '' || props.labelName == null){
      return null;
   }
   return (<h2 className="tbSec2">{props.labelName}</h2>);
};

WikiLabel.defaultProps = {
   labelName:'labelName'
};
WikiLabel.propTypes = {
   labelName : PropTypes.string
};
export default WikiLabel;