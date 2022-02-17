import React from 'react';
import PropTypes from 'prop-types';
let currScroll = 0, scrollTop = 0, isBottomBtnFixed = true;

class ButtonWidget extends React.Component{
	constructor(props){
		super(props);
		this.state = {
			fixedClass : 'button-fixed'
		};
	}
	componentDidMount = () => {
		if(this.props.deviceType === 'mobile'){
			window.addEventListener('scroll', this.handleBottomButtonSticky);
		}
	};
	componentWillUnmount = () => {
		if(this.props.deviceType === 'mobile') {
			window.removeEventListener('scroll', this.handleBottomButtonSticky);
		}
	};

	handleBottomButtonSticky = () => {
		currScroll = window.scrollY;
		if(isBottomBtnFixed && currScroll > scrollTop) {
			if(currScroll > (document.getElementsByClassName('button-container')[0].offsetTop - window.outerHeight + document.getElementsByClassName('button-wrapper')[0].clientHeight)){
				isBottomBtnFixed = false;
				this.setState({fixedClass : ''});
			}
		}else if(!isBottomBtnFixed && currScroll < scrollTop){
			if(currScroll <= (document.getElementsByClassName('button-container')[0].offsetTop - window.outerHeight + document.getElementsByClassName('button-wrapper')[0].clientHeight)){
				isBottomBtnFixed = true;
				this.setState({fixedClass : 'button-fixed'});
			}
		}
		scrollTop = currScroll;
	};
	render(){
		return (<div className="button-container">
			<div id='cp-btmSticky' className={'button-wrapper '+(this.state.fixedClass)+(this.props.commonValidationMsg !== null ? ' btn-wid-msg' : '')}>
				{this.props.commonValidationMsg && <p className='cp-err-msg'>{this.props.commonValidationMsg}</p>}
				<div className="table">
					{this.props.deviceType === 'mobile' ? <div className="table-cell"><p>Step {this.props.step} of 3</p></div> : null}
					{this.props.previousStep && <div className="table-cell"><button type='button' className="button button--secondary" onClick={this.props.previousStep}>Back</button></div>}
					<div className="table-cell"><button type='submit' disabled={this.props.isButtonDisabled} className="button button--orange">{this.props.nextBtnText}</button></div>
				</div>
			</div>
		</div>);
	}
}

ButtonWidget.defaultProps = {
  deviceType: 'mobile',
  isButtonDisabled: false,
  nextBtnText: 'Next',
  step: 1,
  commonValidationMsg : null
};
ButtonWidget.propTypes = {
  commonValidationMsg: PropTypes.any,
  deviceType: PropTypes.string.isRequired,
  isButtonDisabled: PropTypes.bool,
  nextBtnText: PropTypes.string,
  previousStep: PropTypes.func,
  step: PropTypes.number.isRequired
};

export default ButtonWidget;