var webpack = require('webpack');
const merge = require('webpack-merge');
const clientConfig =  require('./webpack.config.client');
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const fs = require("fs");
var path = require('path');
const VIEWS_DIR =  path.resolve(__dirname, './../views/');
var BUILD_DIR = path.resolve(__dirname, './../public/js/');
//var BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
//const CompressionPlugin = require("compression-webpack-plugin")
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

var getCSSWithVersion = require('./../application/server/nodeHelper.js').getCSSWithVersion;

var shikshaCommonCssFile = getCSSWithVersion('shikshaCommon');
var shikshaDesktopCommonCssFile = getCSSWithVersion('shikshaDesktopCommon');


const clientFileChanges_mobile = new Array(path.join(VIEWS_DIR,'index_temp.ejs'),path.join(BUILD_DIR,'..','app-shell.html'));
const clientFileChanges_desktop = new Array(path.join(VIEWS_DIR,'index_desktop_temp.ejs'),path.join(BUILD_DIR,'..','desktop-app-shell.html'));

//clientConfig.output.publicPath = "https://js.shiksha.ws/pwa/public/js/";
clientConfig.output.filename = '[name]-shiksha-main-[hash].js';
clientConfig.output.publicPath = '/pwa/public/js/';

module.exports = env => {
  return merge(clientConfig, {
            mode : 'none',
            plugins : [
              new webpack.DefinePlugin({
                 'process.env.NODE_ENV': "'"+env.NODE_ENV+"'"
               }),
              new UglifyJsPlugin(),
              /*new CompressionPlugin(),*/
              function() {
                      this.plugin("done", function(statsData) {
                          var stats = statsData.toJson();
                          if (!stats.errors.length) {
                              clientFileChanges_mobile.forEach(function(index){
                                //var htmlFileName = "app-shell.html";
                                var html = fs.readFileSync(index, "utf8");
                                var clientBunldeHash = stats.assetsByChunkName.mobile;
                                if(Array.isArray(stats.assetsByChunkName.mobile))
                                {
                                    clientBunldeHash  = stats.assetsByChunkName.mobile.filter(bundle => bundle.endsWith('js'));
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
                              clientFileChanges_desktop.forEach(function(index){
                                //var htmlFileName = "app-shell.html";
                                var html = fs.readFileSync(index, "utf8");
                                var clientBunldeHash = stats.assetsByChunkName.desktop;
                                if(Array.isArray(stats.assetsByChunkName.desktop))
                                {
                                    clientBunldeHash  = stats.assetsByChunkName.desktop.filter(bundle => bundle.endsWith('js'));
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

                                if(index.indexOf('desktop-app-shell.html') > -1)
                                {
                                    htmlOutput =htmlOutput.replace(
                                    /<link\s+href=(["'])(.+?)(build\/)?shikshaDesktopCommon(.*)\.css\1/i,
                                  "<link href=$1$2" + shikshaDesktopCommonCssFile + "$1");
                                }

                                fs.writeFileSync(
                                    index,
                                    htmlOutput);
                              });
                          }
                      });
                  },
            //      new BundleAnalyzerPlugin()
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
          //test: /[\\/]node_modules[\\/]react[\\/]/,
          name: 'vendors',
          chunks: 'all'
        },
        default : false
      },
      minSize : 200000
    }
            }
          });
};
