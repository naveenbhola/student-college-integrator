var webpack = require('webpack');
const merge = require('webpack-merge');
const serverConfig =  require('./webpack.config.server');

module.exports = env => {
	return merge(serverConfig,{
			  mode : 'none',
			  plugins : [
			    new webpack.DefinePlugin({
			         'process.env.NODE_ENV': "'"+env.NODE_ENV+"'"
			       })
			  ] 
			});
};