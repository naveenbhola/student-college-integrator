import React, { Component } from 'react';
import { connect } from 'react-redux';

import AskBtn from '../../common/components/desktop/AskBtn';
import QNALayer from '../../common/components/desktop/QNALayer';
import {showResponseFormWrapper} from '../../../utils/regnHelper';

const customStyles = {
  content : {
    top : '50%', left : '50%', right : 'auto', bottom : 'auto', padding : '0', marginRight : '-50%', width : '780px', transform : 'translate(-50%, -50%)', overflow : 'visible'
  },
  overlay : {
    position : 'fixed', width : '100%', height : '100%', left : '0', right : '0', bottom : '0', top : '0', zIndex : '9999', background : 'rgba(28, 37, 44, 0.75)'
  }
};

class DesktopCommonComponents extends Component {
  constructor(props) {
    super(props);

    this.state = {
      modalIsOpen: false
    };

    this.openAskModal = this.openAskModal.bind(this);
    this.afterOpenModal = this.afterOpenAskModal.bind(this);
    this.closeAskModal = this.closeAskModal.bind(this);
  }

  showResponseForm(){
    let listingId = 1653, actionType = 'downloadBrochure', listingType = 'course';
    let formData = {
      trackingKeyId : 929,
      callbackFunction : 'callBackAfterResp',
      callbackFunctionParams : {}
    };
    showResponseFormWrapper(listingId, actionType, listingType, formData);
  }
  showResponseForm2(){
    let listingId = 93176, actionType = 'downloadBrochure', listingType = 'course';
    let formData = {
      trackingKeyId : 1234,
      callbackFunction : 'callBackAfterResp',
      callbackFunctionParams : {}
    };
    showResponseFormWrapper(listingId, actionType, listingType, formData);
  }

  openAskModal() {
    this.setState({modalIsOpen: true});
  }

  afterOpenAskModal() {
    // references are now sync'd and can be accessed.
    //this.subtitle.style.color = '#f00';
  }

  closeAskModal() {
    this.setState({modalIsOpen: false});
  }
  render() {
    return (
      <React.Fragment>
        <div className="desktopCommonComponents" id="main-wrapper" style={{minHeight:'100px'}}>
          <div>Hello, I am a dummy page.</div>
          <div>Content will come here.1</div>
          <div>Content will come here.2</div>
          <div>Content will come here.3</div>
          <div>Content will come here.4</div>
          <div>
            <AskBtn
            ctaLabel="Ask your Question"
            onClick={this.openAskModal}
            />
            <AskModal
            isOpen={this.state.modalIsOpen}
            onAfterOpen={this.afterOpenAskModal}
            onRequestClose={this.closeAskModal}
            shouldCloseOnOverlayClick={false}
            style={customStyles}
            >
              <QNALayer
                isLayerOpen={this.state.modalIsOpen}
                closeModal={this.closeAskModal}
                postingForPage="commonComponentPage"
                postingType="layer"
                qPostingTitle = 'Need guidance on career and education? Ask our experts'
                postingForType = 'question'
                /*courseIdQP = "1234"*/
                instituteId = "5678"
                responseAction = 'ques_post'
                qtrackingPageKeyId = "234"
                dtrackingPageKeyId = "567"
                quesDiscKeyId = "1222"
                entityId = "678"
                tagEntityType = "test"
                instituteCourses = {[{course_id:1,course_name:'1123'},{course_id:2,course_name:'321'},{course_id:31,course_name:'123'},{course_id:32,course_name:'321'},{course_id:21,course_name:'123'},{course_id:22,course_name:'321'},{course_id:11,course_name:'123'},{course_id:112,course_name:'321'},{course_id:231,course_name:'123'},{course_id:222,course_name:'321'}]}
                courseViewFlag = {false}
              />
            </AskModal>
          </div>
          <div>Content will come here.5</div>
          <div>Content will come here.6</div>
          <div>Content will come here.7</div>
          <div>Content will come here.8</div>
          <div>Content will come here.9</div>
          <div>
          <a href="javascript:void(0);" onClick={this.showResponseForm.bind(this)}>Download Brochure (Course - 1653)</a>
          </div>
          <div>Content will come here.0</div>
          <div>Content will come here.1</div>
          <div>Content will come here.2</div>
          <div>Content will come here.3</div>
          <div>
          <a href="javascript:void(0);" onClick={this.showResponseForm2.bind(this)}>Download Brochure (Course - 93176)</a>
          </div>
          <div>Content will come here.4</div>
          <div>Content will come here.5</div>
          <div>Content will come here.6</div>
          <div>Content will come here.7</div>
          <div>Content will come here.8</div>
          <div>Content will come here.9</div>
          <div>Content will come here.0</div>
          <div>Hello, I am a dummy page.</div>
          <div>Content will come here.1</div>
          <div>Content will come here.2</div>
          <div>Content will come here.3</div>
          <div>Content will come here.4</div>
          <div>Content will come here.5</div>
          <div>Content will come here.6</div>
          <div>Content will come here.7</div>
          <div>Content will come here.8</div>
          <div>Content will come here.9</div>
          <div>Content will come here.0</div>
          <div>Content will come here.1</div>
          <div>Content will come here.2</div>
          <div>Content will come here.3</div>
          <div>Content will come here.4</div>
          <div>Content will come here.5</div>
          <div>Content will come here.6</div>
          <div>Content will come here.7</div>
          <div>Content will come here.8</div>
          <div>Content will come here.9</div>
          <div>Content will come here.0</div>
        </div>
      </React.Fragment>
    );
  }
  componentDidMount(){
    AskModal.setAppElement('#root');
    window.isHeaderFixed = true;
    window.isCompareEnable = true;
    window.shikshaProduct = 'courseHomePage';
    window.productPage = 'courseHomePage';
  }
}

function mapStateToProps(state)
{
    return {
        config : state.config
    }
}
export default connect(mapStateToProps)(DesktopCommonComponents);
