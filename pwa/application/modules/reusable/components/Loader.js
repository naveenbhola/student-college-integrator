import React from 'react';
export default function Loader(props) {
  if(props.show)
  {
      return (<div id="common-loader" className="loader-col-msearch">
                  <div className="three-quarters-loader-msearch">
                    Loading…
                  </div>
              </div>)
  }
  else
    return null;
}

export function ShowLoader() {
      return (<div id="common-loader" className="loader-col-msearch">
                  <div className="three-quarters-loader-msearch">
                    Loading…
                  </div>
              </div>)
}