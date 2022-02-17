var webpack   = require('webpack');
const { InjectManifest } = require('workbox-webpack-plugin');
var path      = require('path');
var BUILD_DIR = path.resolve(__dirname, './../public/workbox/');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin');

var  globIgnores = (process.env.NODE_ENV == 'production') ? ['**/shikshaCommon.css','**/service-worker.js','js/**/*'] : ['**/service-worker.js','js/**/*'];

const jsDomain = "https://js.shiksha.ws";
const cssDomain = "https://css.shiksha.ws";
const imgDomain = "https://images.shiksha.ws";

const config  = {
   entry: {
     client: path.resolve(__dirname, '../','source-service-worker.js'),
   },
   mode : 'production',
   output: {
     filename: 'service-worker.js',
     path : BUILD_DIR,
     publicPath:'/pwa/public/workbox/'
   },
    plugins: [
       new CleanWebpackPlugin(['workbox'], { root: path.resolve(__dirname , './../public/'), verbose: true , beforeEmit : true}),
       new InjectManifest({
        swDest: path.resolve(__dirname, '../','service-worker.js'),
        swSrc: path.resolve(__dirname, '../','source-service-worker.js'),
        globDirectory: path.resolve(__dirname, './../public/'),
	globPatterns: ['**/*.{html,js,css,ejs,svg,jpg,woff,gif}'],
        include: ['**/*.{html,js,css,ejs,svg,jpg,woff,gif}'],
        globIgnores : globIgnores,
        modifyUrlPrefix : {
          'js_temp': '/pwa/public/js',
          'js':'/pwa/public/js',
          '' : '/pwa/public/'
        },
        manifestTransforms: [
          // Basic transformation to remove a certain URL:
          (originalManifest) => {
            const manifest = originalManifest.map(function(entry)
               {
                if(entry.url.match('^.*\.js$'))
                {
                  entry.url = jsDomain+entry.url;
                }else if(entry.url.match('^.*\.css$'))
                {
                  entry.url = cssDomain+''+entry.url;
                }
                else if(entry.url.match('^.*\.svg$') || entry.url.match('^.*\.jpg$') || entry.url.match('^.*\.woff$') || entry.url.match('^.*\.gif$'))
                {
                  entry.url = imgDomain+''+entry.url;
                }
                return entry;
             });
            // Optionally, set warning messages.
            const warnings = []; 
            return {manifest, warnings};
          }
        ]
      }),
      new UglifyJsPlugin()
    ]
};
module.exports = config;

