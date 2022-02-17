	import { combineReducers } from 'redux';

import config from './../config/config.js';


const rootReducer = combineReducers({
	config : config
});

export default rootReducer;