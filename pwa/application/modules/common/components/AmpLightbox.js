import React from 'react';

class AmpLightBox extends React.Component{
  constructor(props){
    super(props)
  }
  render(){
    const { data, heading, id, datatype, taponPage} = this.props;
    return(
      <React.Fragment>
          <amp-lightbox id={id} layout="nodisplay">
            <div className="lightbox" on={ taponPage ?  "tap:"+id+".close" : ''} role="button" tabIndex="0">
              <a on={`tap:${id}.close`} className="cls-lightbox color-f font-w6 t-cntr" role="button" tabIndex="0">&times;</a>
              <div className="m-layer">
                 <div className="min-div color-w catg-lt">
                   {heading != null ? <div className="f14 color-3 bck1 pad10 font-w6">{heading}</div>: ''}

                       { (typeof datatype !='undefined') ? <React.Fragment><ul key="da">{data}</ul> </React.Fragment> : <React.Fragment>{data}</React.Fragment>  }

                 </div>
              </div>
            </div>
          </amp-lightbox>
        </React.Fragment>
    )
  }
}


AmpLightBox.defaultProps ={
  taponPage : false
}
export default AmpLightBox;
