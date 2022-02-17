import React from 'react';
import ReactDOM from 'react-dom';
import styles from './../assets/categorySection.css';


class Search extends React.Component{
	render()
	{
		<div className="pwa-lyrSrc" key="search-tab">
		            <i className="search-ico"></i>
		            <input type="text" placeholder="Search an exam" ref={(input) => this.inputText = input} onChange={this.getInputValue.bind(this)}/>
		</div>
	}
}

export default Search;