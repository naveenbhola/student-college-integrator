const webpack = require('webpack');

module.exports = {
	entry: [
			'./node_modules/core-js/client/shim.min.js',
			'./node_modules/zone.js/dist/zone.js',
			'./node_modules/reflect-metadata/Reflect.js',
			'./node_modules/ng2-bootstrap/bundles/ng2-bootstrap.min.js',
			'./dist/main.js'
			],
	output: {
		path: './dist',
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
