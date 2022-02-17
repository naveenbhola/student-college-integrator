import React from 'react';
import Anchor from "../../reusable/components/Anchor";
class JeeMainResultBanner extends React.Component{
   render() {
       return (
           <div id="jeeMainResBanner" className="fixedLeft">
               <div className="blackBox">
                   <span className="cross" id='closeBtn'>x</span>
                   <Anchor onClick={this.closeWidget} to="/b-tech/jee-main-exam-results">
                   <div className="textBox">
                       <p>JEE Main Result declared</p>
                       <span className='link'>Read Details<i className="arrow right"> </i></span>
                   </div>
                   </Anchor>
               </div>
           </div>
       );
   }
   closeWidget = () => {
       document.getElementById('jeeMainResBanner').style.display = 'none';
   };
   componentDidMount() {
       document.getElementById('closeBtn').addEventListener('click', this.closeWidget);
   }
}
export default JeeMainResultBanner;
