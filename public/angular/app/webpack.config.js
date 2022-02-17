const webpack = require('webpack');

module.exports = {
	entry: './main.js',
	output: {
		path: './bin',
		filename: 'main.bundle.js'
	},
	plugnins: [
		new webpack.optimize.UglifyJsPlugin({
			compress:{
				warnings:true,
			},
			output:{
				comments:false,
			},
		}),
	]
};
