/**
 * webpack.config.server.js
 *
 * (C) 2017 mobile.de GmbH
 *
 * @author <a href="mailto:pahund@team.mobile.de">Patrick Hund</a>
 * @since 09 Feb 2017
 */

var webpack = require('webpack');
var path = require('path');
const nodeExternals = require('webpack-node-externals');
var BUILD_DIR = path.resolve(__dirname, './dist');
var APP_DIR = path.resolve(__dirname, './application/server');
const CleanWebpackPlugin = require('clean-webpack-plugin');


const config = {
   entry: {
     server: APP_DIR + '/index.js',     
   },
   output: {
     filename: '[name].bundle.dev.js',
     path: BUILD_DIR,
     publicPath : '/js/',
     chunkFilename: '[name].[chunkhash].js'
   },
   mode : 'development',
   target : 'node',
   externals: [nodeExternals()],
   resolve: {
        modules: ['node_modules', 'src'],
        extensions: ['*', '.js', '.json']
    },
   module: {
    rules: [
     {
       test: /\.(jsx|js)?$/,
       exclude: /(node_modules\/)/,
       use: {
         loader: "babel-loader",
         options: {
           presets: ['react', 'es2015','stage-2'], // Transpiles JSX and ES6,
            plugins: [
              'syntax-dynamic-import',
              'transform-class-properties',
              'transform-object-assign',
              'react-loadable/babel'
            ],
         }
       }
     },
       {
        test: /\.css/,
         use: [{
      loader: 'css-loader',
      options: {
        modules: true,
        localIdentName: '[local]',//[name]_____[hash:base64:5]
        sourceMap: true,
        importLoaders: 1
      }
    },
      ]
      },
      {
        test: /\.(png|jpg|jpeg|gif|svg|eot|ttf|woff|woff2)$/,
        loader: 'raw-loader'
      },
     {
       test : /.ejs$/,
       loader : 'raw-loader'

     }
    ],

  },
  optimization: {
      runtimeChunk: false,
      splitChunks: false
  },
   /* resolve: {
    alias: {
      'react-loadable': path.resolve(__dirname, './Loadable/loadable.js'),
    },
  },*/
   plugins: [
   new CleanWebpackPlugin(['dist'], { root: path.resolve(__dirname , './'), verbose: true , beforeEmit : true}),
    new webpack.DefinePlugin({
       'process.env.NODE_ENV': JSON.stringify('development')
     }),
    /*new ReactLoadablePlugin({
      filename:  path.resolve(__dirname, './public/js/', 'react-loadable.json'),
    })    */
  ]
};

module.exports = config;
//  
