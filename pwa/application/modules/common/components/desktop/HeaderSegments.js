import React, { Component } from 'react';

import HeaderLogo from './HeaderLogo';
import HeaderSearch from './search/HeaderSearch';
import HeaderGNB from './HeaderGNB';
import HeaderUserLogin from './HeaderUserLogin';

const HeaderSegments = (props) => {
  let headerDivClass = 'n-headerP innerpage-header';
  if(props.isHomePage){
    headerDivClass = 'n-headerP';
  }
  return (
    <header id="page-header" className="header">
      <div className={headerDivClass} id="_globalNav">
        <div className="shiksha-navCut"></div>
        <div className="n-header">
          <HeaderLogo config={props.config} />
          {!props.isHomePage && <HeaderSearch />}
          <HeaderGNB />
          <HeaderUserLogin isHomePage={props.isHomePage} isUserLoggedIn={props.isUserLoggedIn} />
          <p className="clr"></p>
        </div>
      </div>
    </header>
  );
}
HeaderSegments.defaultProps = {
  isUserLoggedIn : false,
  isHomePage : false
};

export default HeaderSegments;
