import React from 'react';
import { withFormsy } from 'formsy-react';

class MyInput extends React.Component {
  constructor(props) {
    super(props);
    this.changeValue = this.changeValue.bind(this);
  }

  changeValue(event) {
    // setValue() will set the value of the component, which in
    // turn will validate it and the rest of the form
    // Important: Don't skip this step. This pattern is required
    // for Formsy to work.
    this.props.setValue(event.currentTarget.value);
  }

  render() {
    // An error message is returned only if the component is invalid
    const errorMessage = this.props.getErrorMessage();

    return (
      <div>
        <input
          className={this.props.className}
          onChange={this.changeValue}
          type="text"
          placeholder={this.props.placeholder}
          maxLength="10"
          minLength="10"
          value={this.props.getValue() || ''}
        />
        <div style={{display:'block', clear: 'both', marginTop:'4px'}}>
          <div className="errorMsg" style={{color:'#3E4847', fontSize:'10px',display: 'block',float: 'left'}} >{errorMessage}</div>
        </div>
      </div>
    );
  }
}

export default withFormsy(MyInput);
