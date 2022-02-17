import React from 'react';
import Anchor from './../../../reusable/components/Anchor';

const HeaderLogo = (props) => {
  return (
    <div className="n-logo">
      <Anchor to={"/"} title="Shiksha.com">
      <i className="icons ic_logo ic_logo_prefix"></i>
      <i className="icons ic_logo"></i></Anchor>
    </div>
  );
}

export default HeaderLogo;
