export function makeURLAsHyperlink(string)
{
    var regx = new RegExp("(((http|https):\\/\\/[^\\s\\\"\\,\\'\\$\\)\\}]+)|((www[.])[^\\s\\\"\\,\\'\\$\\)\\}]+))","g");
    // Check if there is a url in the text
    var positions = [];
    var i = 0;
    var array1;
    while((array1 = regx.exec(string)) !== null)
    {
        if(array1[0].indexOf(".") > -1)
        {
            var pos = array1[0].lastIndexOf('.');
            if(array1[0].length-1 == pos)
            {
                array1[0] = array1[0].substr(0, pos) + '' + array1[0].substr(array1['index'] + 1);
            }
            if(typeof positions[i] == "undefined")
            {
              positions[i] = [];
            }
            positions[i]['start'] = array1.index;   
            positions[i]['end'] = array1.index + array1[0].length;
            i++;
        }
    }
           var string1 = "";
    for (i=(positions.length)-1; i >=0; i--) {
        var match = string.substr(positions[i]['start'], positions[i]['end']-positions[i]['start']);
        if(match.indexOf("shiksha.com") > -1) {
            var replacement = '<a class="nrml-link word-wrap word-break" href="'+match+'">'+match+'</a>';
            }
        else{
            var replacement = '<a rel="nofollow" target="_blank" class="nrml-link word-wrap word-break" href="'+match+'" >'+match+'</a>';
        }
        string = string.substr(0, positions[i]['start']) + replacement + string.substr(positions[i]['end']);
    }
    string = string.replace(/((\")|(\'))(www.)/i,'$1http://');
    string = parseUrlFromContent(string);
    return string;
}

export function parseUrlFromContent(string,isTag=true,isAllHttp=false)
{
  if(isAllHttp){
      string.replace(/http:\/\//g,'https://');
  }
  string = string.replace(/(www\.|http?:\/\/)?([a-zA-Z0-9]{2,}\.)?(shiksha|ieplads)\.com/g,function (match){
            match = match.replace('http://', 'https://');
            return match;
          });

  if(isTag){
        // change href or src attributes values only
        string = string.replace(/(href|src)=[\"|'](\s)*((www\.|http?:\/\/)?([a-zA-Z0-9]{2,}\.)?shiksha\.com([^'\"])*)[\"|']/g , "$1=https://$3");
      }
      else{
        //change all matched in substrings in string
        string = string.replace(/(www\.|http?:\/\/)?([a-zA-Z0-9]{2,}\.)?shiksha\.com()/g , "https://$0"); 
        string = string.replace(/(@https:\/\/shiksha)+/i , "@shiksha");// discard shiksha mail
      }
      string = string.replace(/(https:\/\/)+/i , "https://");
      string = string.replace(/(http:\/\/https:\/\/)+/i , "https://");
      return string;
}

export function seo_url(uri = '', separator="-", numOfWords=40, converToLower = true){
      var uri_array = [];
      var url = htmlspecialchars_decode(uri);
      var url = url.replace(/`\[.*\]`U/g,'');
      var url = url.replace(/(&(amp;)@%#!?#?[^A-Za-z0-9];)+/gi,'');
      var url = url.replace(/([^a-z0-9])+/gi,'-');
      var url = url.replace(/([-]+)/g,'-').trim().toLowerCase();
          uri_array = url.split('-');
          uri_array = uri_array.slice(0, (numOfWords-1));
      var url = ucwords(uri_array.join(separator));
      if(converToLower) {
          url = url.toLowerCase();
      }
      return url;
}

export function ucwords(a) {
  return a.replace(/\w+/g, function(a){ 
      return a.charAt(0).toUpperCase() + a.slice(1).toLowerCase()
  })
}

export function htmlspecialchars_decode (string, quoteStyle) {

  var optTemp = 0
  var i = 0
  var noquotes = false

  if (typeof quoteStyle === 'undefined') {
    quoteStyle = 2
  }

  string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  }
  if (quoteStyle === 0) {
    noquotes = true
  }
  if (typeof quoteStyle !== 'number') {
    // Allow for a single string or an array of string flags
    quoteStyle = [].concat(quoteStyle)
    for (i = 0; i < quoteStyle.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quoteStyle[i]] === 0) {
        noquotes = true
      } else if (OPTS[quoteStyle[i]]) {
        optTemp = optTemp | OPTS[quoteStyle[i]]
      }
    }
    quoteStyle = optTemp
  }
  if (quoteStyle & OPTS.ENT_HTML_QUOTE_SINGLE) {
    // PHP doesn't currently escape if more than one 0, but it should:
    string = string.replace(/&#0*39;/g, "'")
    // This would also be useful here, but not a part of PHP:
    // string = string.replace(/&apos;|&#x0*27;/g, "'");
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"')
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&')
  return string
}

export function add_query_params(url,params){
  if(typeof params == 'undefined'){
    return url;
  }
  var urlArr =  url.split('?'),queryParams = {};
  var temp;
  if(urlArr[1]){
    var urlParams = urlArr[1].split('&');

    urlParams.forEach(function(value){
       temp = value.split('=');
      if(temp[0].indexOf('[]') > -1){
        if(!queryParams[temp[0]]){
          queryParams[temp[0]] = [];
        }
        queryParams[temp[0]].push(temp[1]);
      }
      else{
        queryParams[temp[0]] = temp[1];
      }
    });
  }

  if(typeof params != 'object'){
    var params = params.split('&');
    params.forEach(function(value){
      temp = value.split('=');
      queryParams[temp[0]] = temp[1];
    });
  }
  else{
    for(var i in params){
      queryParams[i] = params[i];
    }
  }

  var returnUrl = [];
  for(var i in queryParams){
    if(queryParams[i]){
      if(typeof queryParams[i] == 'object'){
        queryParams[i].forEach(function(ele){
          returnUrl.push(i+'='+ele);
        });
      }
      else{
        returnUrl.push(i+'='+queryParams[i]);
      }
    }
  }
  return urlArr[0]+"?"+returnUrl.join('&');
}

export function removeDomainFromUrl(url) {
    var urlSplit = url.split('/');
    var host = urlSplit[0] + "//" + urlSplit[2] + "/";
    var returnUrl = url.replace(host, '');
    if(returnUrl != url) {
      returnUrl = "/"+returnUrl;
    }
    return returnUrl;
}
export function removeDomainFromUrlV2(url) {
    const urlSplit = url.split('/');
    const host = urlSplit[0] + "//" + urlSplit[2];
    let returnUrl = url.replace(host, '');
    if(returnUrl !== url && returnUrl[0] !== '/') {
        returnUrl = "/"+returnUrl;
    }
    return returnUrl;
}

export function getSeoUrl(id, type, title){
  var url = '';
  switch(type){
    case 'tag':
        url = '/tags/'+seo_url(title, '-', 1000,true)+'-tdp-'+id;
        break;
  }
  return url;
}
