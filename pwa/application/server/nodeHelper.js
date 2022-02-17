function getHeaders(req)
{
   let no_cache = 0;
   let shiksha_refresh_cache = 0;
   
    var Cookie = req.cookies;
    var origin = req.headers.host;
    var cookieKeys = Object.keys(Cookie);
    var tmpArray = new Array();
    for(var i in cookieKeys){
        var cookieStr = cookieKeys[i]+'='+Cookie[cookieKeys[i]];
        tmpArray.push(cookieStr);
    }
    
    if(Object.keys(req.query).length && req.query.constructor === Object && typeof (req.query.skipCache)!='undefined' && req.query.skipCache==1){
        no_cache = 1;
        shiksha_refresh_cache = 1;
        //tmpArray.push('skipCache=true');
    }   

    var transaction = '';
    if(typeof(req['headers']['x-transaction-id']) != 'undefined'){
        transaction = req['headers']['x-transaction-id'];
    }

    let cookieData = (tmpArray.length) ? tmpArray.join(';') : null;
	return {
		headers: {'Content-Type': 'application/json; charset=utf-8','cookie': cookieData, 'origin' : origin, 'x-transaction-id' : transaction, 'no_cache': no_cache,'shiksha_refresh_cache':shiksha_refresh_cache},
		withCredentials: true
	}
}

var cssFilesVersionArray = {};
var jsFilesVersionArray = {}
function getCSSWithVersion(filename,path = 'pwa_mobile',mode='')
{
	if(process.env.NODE_ENV == 'development' || mode == 'development')
    {
    	return filename+'.css';
    }
    else {//(process.env.NODE_ENV == 'production')
        ensureFilledVersionArray(path,'css');
        for(var i in cssFilesVersionArray[path])
        {
        	if(cssFilesVersionArray[path][i]['originalPath'] == filename+".min.css")
        	{
        		return "build/"+cssFilesVersionArray[path][i]['versionedPath'];
        	}
        }
    }
}
function getJSWithVersion(filename,path = 'pwa_mobile',mode='')
{
	if(process.env.NODE_ENV == 'development' || mode == 'development')
    {
    	return filename+'.js';
    }
    else {//(process.env.NODE_ENV == 'production')
        ensureFilledVersionArray(path,'js');
        for(var i in jsFilesVersionArray[path])
        {
        	if(jsFilesVersionArray[path][i]['originalPath'] == filename+".js")
        	{
        		return jsFilesVersionArray[path][i]['versionedPath'];
        	}
        }
    }
}

function ensureFilledVersionArray(path,type = 'js'){
	//const fs = require("fs");
	//const fpath = require("path");
    if(type == 'js'){
        //if(typeof jsFilesVersionArray[path] == 'undefined' || !jsFilesVersionArray[path]) {
        	jsFilesVersionArray[path] = {};
            if(path == 'pwa_mobile'){
                jsFilesVersionArray[path] = JSON.parse(require("fs").readFileSync("mappings/pwa_mobile_vm_js.js","utf-8"));
            }
						//for jquery based registration, response and header search on pwa desktop
						if(path == 'shikshaDesktop'){
							jsFilesVersionArray[path] = JSON.parse(require("fs").readFileSync("../public/mappings/desk_vm_js.js","utf-8"));
						}
        //}
    }
    else if(type == 'css'){
        //if(typeof cssFilesVersionArray[path] == 'undefined' || !cssFilesVersionArray[path]){
        	cssFilesVersionArray[path] = {};
            if(path == 'pwa_mobile'){
                cssFilesVersionArray[path] = JSON.parse(require("fs").readFileSync("mappings/pwa_mobile_vm_css.js","utf-8"));
            }
						if(path == 'shikshaDesktop'){
                cssFilesVersionArray[path] = JSON.parse(require("fs").readFileSync("../public/mappings/desk_vm_css.js","utf-8"));
            }
        //}
    }
}
function getCssMinify(css){
	return css.replace(/\n/g, '').replace(/\s\s+/g, ' ');
}

let DEBUG_ON = false;
function includeJSFiles(page, path, additionalAttributes = [], configObj){
	if(process.env.NODE_ENV != 'development'){
        ensureFilledVersionArray(path, 'js');
    }

	let shikshaGruntConfig  = JSON.parse(require("fs").readFileSync("../public/gruntConfig.json","utf-8"));
	let minifiedJS          = shikshaGruntConfig['mappings'][page]['minifiedName'];
	let returnJSFILES       = [];
	let tag                 = '';
	let additionalAttributeString = (additionalAttributes.length>0 ? additionalAttributes.join(' ') : '');

	if(process.env.NODE_ENV == 'development'){
  		DEBUG_ON = true;
  		additionalAttributeString = additionalAttributeString.replace('crossorigin' ,'');
  	}
  	
	if(!DEBUG_ON){
		jsFilesVersionArray[path].forEach(
			value => {
				if(value['originalPath'] == minifiedJS){
					returnJSFILES = value['versionedPath'];
				}
			}
		);

		if(returnJSFILES.length > 0){
			tag = "<script type='text/javascript' " + additionalAttributeString + " src='" + configObj.JS_DOMAIN + "/public/" + shikshaGruntConfig['mappings'][page]['cwd'] + "/build/" + returnJSFILES + "'></script>";
		}
	}else{
		shikshaGruntConfig['mappings'][page]['files'].forEach(
			jsFiles => {
				tag += "<script type='text/javascript' " + additionalAttributeString + " src='"+configObj.SHIKSHA_HOME+"/public/" + shikshaGruntConfig['mappings'][page]['cwd'] + '/' + jsFiles + "'></script>";
			}
		);
	}
	return tag;
}

function includeCSSFiles(page, path, configObj){
	if(process.env.NODE_ENV != 'development'){
		ensureFilledVersionArray(path,'css');
	}

	let shikshaGruntConfig  = JSON.parse(require("fs").readFileSync("../public/gruntCssConfig.json","utf-8"));
	let minifiedCSS         = shikshaGruntConfig['mappings'][page]['minifiedName'];
	let returnCSSFILES      = [];
	let tag                 = '';

	if(process.env.NODE_ENV == 'development'){
  	DEBUG_ON = true;
  }
	if(minifiedCSS != ''){
		if(!DEBUG_ON){
			cssFilesVersionArray[path].forEach(
				value => {
					if(value['originalPath'] == minifiedCSS){
						returnCSSFILES = value['versionedPath']
					}
				}
			);
			if(returnCSSFILES.length > 0){
				tag = "<link type='text/css' rel='stylesheet' href='"+configObj.CSS_DOMAIN+"/public/"+shikshaGruntConfig['mappings'][page]['cwd']+"/build/"+ returnCSSFILES+"'></link>";
			}
		}else{
			shikshaGruntConfig['mappings'][page]['files'].forEach(
				cssFiles => {
					tag += "<link type='text/css' rel='stylesheet' href='"+configObj.SHIKSHA_HOME+"/public/"+shikshaGruntConfig['mappings'][page]['cwd']+'/'+ cssFiles+"'></link>";
				}
			);
		}
	}
	return tag;
}

module.exports = {getCSSWithVersion,getHeaders,getJSWithVersion,getCssMinify,includeJSFiles,includeCSSFiles}
