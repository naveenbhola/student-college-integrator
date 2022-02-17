var webpack = require('webpack');
const merge = require('webpack-merge');
const clientConfig =  require('./webpack.config.client');


clientWebpack = merge(clientConfig, {
  mode : 'proudction',
  plugins : [
    new webpack.DefinePlugin({
       'process.env.NODE_ENV': JSON.stringify('test1')
     }),
  ],
  optimization: {
     splitChunks: {
      cacheGroups: {
        default: false,//disable default 'commons' chunk behavior
        vendors: false, //disable vendor splitting(not sure if you want it)
      }
    }
  }
});



module.exports = clientWebpack;
