webpack = require('webpack');
var path = require('path');

var BUILD_DIR = path.resolve(__dirname, './public/js/');
var APP_DIR = path.resolve(__dirname, './application/client');

const { ReactLoadablePlugin } = require('react-loadable/webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const CleanWebpackPlugin = require('clean-webpack-plugin');
const VIEWS_DIR =  path.resolve(__dirname, './views/');

const devMode = process.env.NODE_ENV !== 'production';
const fs = require("fs");


//var getCSSWithVersion = require('./application/server/nodeHelper.js').getCSSWithVersion;

const clientFileChanges_mobile  = new Array(path.join(VIEWS_DIR,'index.ejs'),path.join(BUILD_DIR,'..','app-shell.html'));
const clientFileChanges_desktop = new Array(path.join(VIEWS_DIR,'index_desktop.ejs'),path.join(BUILD_DIR,'..','desktop-app-shell.html'));

const config = {
   entry: {
     mobile : APP_DIR + '/index.js',
     desktop: APP_DIR + '/index_desktop.js'
   },
   output: {
     filename: '[name]-shiksha-main-[hash].js',
     path: BUILD_DIR,
     publicPath : '/pwa/public/js/',
     chunkFilename: '[name].[chunkhash].js'
   },
   devtool : "eval-source-map",
   mode : 'development',
   module: {
    rules: [
     {
       test: /\.(jsx|js)?$/,
       exclude: /(node_modules\/)/,
       use: {
         loader: "babel-loader",
         options: {
           presets: ['react', 'es2015','stage-2'], // Transpiles JSX and ES6
           plugins: [
              'syntax-dynamic-import',
              'transform-class-properties',
              'transform-object-assign',
              'babel-plugin-syntax-dynamic-import',
	            'react-loadable/babel'
              ]
         }
       }
     },
     {
        test: /\.(png|jpg|jpeg|gif|svg|eot|ttf|woff|woff2)$/,
        loader: 'raw-loader'
      },
         {
          test: /\.(css)?$/,
          use: [
           MiniCssExtractPlugin.loader,
          'css-loader',
        ],

     },

    ],

  },
  plugins: [
    new CleanWebpackPlugin(['js'], { root: path.resolve(__dirname , './public/'), verbose: true , beforeEmit : true}),
    new webpack.DefinePlugin({
       'process.env.NODE_ENV': JSON.stringify('development')
     }),
    new ReactLoadablePlugin({
      filename:  path.resolve(__dirname, './public/js/', 'react-loadable.json'),
    }),
  new MiniCssExtractPlugin({
      // Options similar to the same options in webpackOptions.output
      // both options are optional
      filename: "[name].[contenthash].css",
    }),
/*    new webpack.ExtendedAPIPlugin(),*/
/*  new webpack.debug.ProfilingPlugin({
  outputPath: path.resolve(__dirname , './profileEvents.json')
})
*/
        function() {
            this.plugin("done", function(statsData) {
                var stats = statsData.toJson();
                if (!stats.errors.length) {
                    clientFileChanges_mobile.forEach(function(index){
                      //var htmlFileName = "app-shell.html";
                      var html = fs.readFileSync(index, "utf8");
                      var clientBunldeHash = stats.assetsByChunkName.mobile;
                      if(Array.isArray(clientBunldeHash))
                      {
                          clientBunldeHash  = clientBunldeHash.filter(bundle => bundle.endsWith('js'));
                      }
                      var htmlOutput = html.replace(
                          /shikshaMain = (["'])(.+?)mobile-shiksha-main(.*)\.js\1/i,
                        "shikshaMain = $1$2" + clientBunldeHash + "$1");
                      var clientBunldeHash = stats.assetsByChunkName.vendors;
                      if(Array.isArray(stats.assetsByChunkName.vendors))
                      {
                          clientBunldeHash  = stats.assetsByChunkName.vendors.filter(bundle => bundle.endsWith('js'));
                      }
                      var htmlOutput = htmlOutput.replace(
                          /vendorMain = (["'])(.+?)vendors(.*)\.js\1/i,
                        "vendorMain = $1$2" + clientBunldeHash + "$1");

                      fs.writeFileSync(
                          index,
                          htmlOutput);
                    });
                    clientFileChanges_desktop.forEach(function(index){
                      //var htmlFileName = "app-shell.html";
                      var html = fs.readFileSync(index, "utf8");
                      var clientBunldeHash = stats.assetsByChunkName.desktop;
                      if(Array.isArray(clientBunldeHash))
                      {
                          clientBunldeHash  = clientBunldeHash.filter(bundle => bundle.endsWith('js'));
                      }
                      var htmlOutput = html.replace(
                          /shikshaMain = (["'])(.+?)desktop-shiksha-main(.*)\.js\1/i,
                        "shikshaMain = $1$2" + clientBunldeHash + "$1");
                      var clientBunldeHash = stats.assetsByChunkName.vendors;
                      if(Array.isArray(stats.assetsByChunkName.vendors))
                      {
                          clientBunldeHash  = stats.assetsByChunkName.vendors.filter(bundle => bundle.endsWith('js'));
                      }
                      var htmlOutput = htmlOutput.replace(
                          /vendorMain = (["'])(.+?)vendors(.*)\.js\1/i,
                        "vendorMain = $1$2" + clientBunldeHash + "$1");
                      if(index.indexOf('app-shell.html') > -1)
                      {
                          htmlOutput =htmlOutput.replace(
                          /<link\s+href=(["'])(.+?)(build\/)?shikshaCommon(.*)\.css\1/i,
                        "<link href=$1$2" + shikshaCommonCssFile + "$1");
                      }

                      fs.writeFileSync(
                          index,
                          htmlOutput);
                    });
                }
            });
        }
  ],
  optimization: {
    minimizer: [
      new OptimizeCSSAssetsPlugin({})
    ],
      splitChunks: {
      minChunks : 1,
      cacheGroups: {
        commons: {
         test: /[\\/]node_modules[\\/]((?!react-helmet|react-google-charts|formsy-react).*)[\\/]/,
          name: 'vendors',
          chunks: 'all'
        },
        default : false
      },
      minSize : 200000
    }
  },
};

//config.plugins.push(fileChanger);
module.exports = config;
