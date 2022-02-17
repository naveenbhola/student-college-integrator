import React from 'react';
import {Link} from 'react-router-dom';

/*
Usage : <Anchor link={false} className="abcd" title="title of link" to={"/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0"}><div><p>Homepage</p><span>></span></div></Anchor>
*/
const checkForNotNullUrl = url => {
  return ((typeof url == 'object' && url.pathname) || typeof url == 'string');
};

const Anchor = (props) => {
  let remainingProps = {};
  if(props.link && checkForNotNullUrl(props.to)){
    remainingProps = {...props};
    delete remainingProps.link;
    return (<Link {...remainingProps}>{props.children}</Link>);
  }else{
    let href = '#';
    if(typeof props.to == 'string'){
      href = props.to;
    }else if(typeof props.to == 'object' && typeof props.to.pathname != 'undefined') {
      href = props.to.pathname;
    }
    remainingProps = {...props};
    delete remainingProps.to;
    delete remainingProps.link;
    return (<a {...remainingProps} href={href}>{props.children}</a>);
  }
};

Anchor.defaultProps = {
  link: true
};

export default Anchor;
