import React from 'react';
import { validateEmailAddress } from './commonValidations';
import config from './../../config/config';
var compareCookieName = 'mob-compare-global-data';

export function formatNumber(number){
    number = Number(number);
    if(number>=1000){
        number = Math.round((number/1000),1)+'k';
    }
    return number;
}

/*
    below function is used for formatting string in to uppercase
    i/p : abcExam o/p: ABC EXAM
*/
export function formatHeadings(string)
{
    if(!string || string.length == 0)
    {
        return;
    }
    //format heading for layer
    return string.replace(/([A-Z])/g, ' $1').replace(/^(.)*/, function(str){ return str.toUpperCase(); })
}
export function addingDomainToUrl(url,domainName)
{
    if(typeof url == 'undefined' || typeof domainName == 'undefined' || url == null)
        return;
    if(url.startsWith(domainName) || url.startsWith('http'))
        return url;
    return domainName+url;
}
export function getCookie(c_name)
{
    var sanitizedCookie = "; "+document.cookie;
    if (document.cookie.length>0){
        var c_start=sanitizedCookie.indexOf("; "+c_name + "=");
        if (c_start!=-1){
            c_start=c_start + c_name.length+1;
            var c_end=document.cookie.indexOf("; ",c_start);
            if (c_end==-1) { c_end=document.cookie.length ; }
            return unescape(document.cookie.substring(c_start,c_end));
        }
    }
    return "";
}

export function setCookie(c_name,value,expireTime,timeUnit) {
    if(typeof COOKIEDOMAIN == 'undefined') {
        COOKIEDOMAIN = '';
    }
    var today = new Date();
    today.setTime( today.getTime() );
    var cookieExpireValue = 0;
    var expireTime = (typeof(expireTime) != 'undefined')?expireTime:0;
    var timeUnit = (typeof(timeUnit) != 'undefined')?timeUnit:'days';
    if(expireTime != 0){
        if(timeUnit == 'seconds'){
            expireTime = expireTime * 1000;
        }else{
            expireTime = expireTime * 1000 * 60 * 60 * 24;
        }
        var exdate=new Date( today.getTime() + (expireTime) );
        var cookieExpireValue = exdate.toGMTString();
        document.cookie=c_name+ "="
        +escape(value)+";path=/;domain="+COOKIEDOMAIN+""+((expireTime==null) ? "" : ";expires="+cookieExpireValue);
    }else{
        document.cookie=c_name+ "=" +escape(value)+";path=/;domain="+COOKIEDOMAIN;
    }
    if(document.cookie== '') {
        return false;
    }
    return true;
}
export function scrollToElementId(elementId)
{
    if(document.getElementById(elementId) != null)
    {
        var rect = document.getElementById(elementId).getBoundingClientRect();
        if(typeof rect.y != 'undefined')
        {
            window.scrollTo(0, window.scrollY + rect.y);
        }
        else
        {
            rect = document.getElementById(elementId).offsetTop;
            window.scrollTo(0, window.scrollY + rect);
        }
    }
}

/**
 * To check whether a user is logged-in or not
 * @method isUserLoggedIn
 * @return {Boolean}
 * @author Mansi Gupta
 * @date   2018-05-15
 */
export function isUserLoggedIn() {
    var userCookie = getCookie('user');
    var flag       = false;
    if (userCookie) {
        var userCookieData = userCookie.split('|');

        if (userCookieData[0] && validateEmailAddress(userCookieData[0]) == true) {
            flag = true;
        } else {
            flag = false;
        }

        if (flag == true && userCookieData[1] && userCookieData[1].length > 63) {
            flag = true;
        }
    }
    return flag;
}

export function updateNotification()
{
    var cookiename = compareCookieName;
    var cmpDataArr  = new Array();

    if(getCookie(cookiename)){
        cmpDataArr = getCookie(cookiename).split("|");
    }

    var cookiename = "anaInAppNotificationCountForMobileSite";
    var cookieAnA = "0";

    if(getCookie(cookiename) && getCookie(cookiename) != ""){
        //cookieAnA = getCookie(cookiename);
    }

    var totalAdded = cmpDataArr.length;
    var totalShortListed = (getCookie('mob_shortlist_Count')) ? getCookie('mob_shortlist_Count') : 0;
    var sumt = parseInt(totalAdded) + parseInt(totalShortListed) + parseInt(cookieAnA); // compare + shortlist + ana only
    var totalRecomm = (getCookie('mobileTotalUserReco') !='') ? getCookie('mobileTotalUserReco') : 0;
    totalRecomm = parseInt(totalRecomm);

    if (sumt >0 || totalRecomm>0) {
        document.getElementById('notification').innerText = (totalRecomm + sumt);
        document.getElementById('notification').style.display='block';
    }else{
        document.getElementById('notification').innerText = 0;
        document.getElementById('notification').style.display='none';
    }

    if(document.getElementById('total-college-compare')){
        document.getElementById('total-college-compare').innerText = 'Compare Colleges ('+totalAdded+')';
    }
    if(document.getElementById('total-shortlisted-colleges')){
        document.getElementById('total-shortlisted-colleges').innerText = 'Shortlist ('+totalShortListed+')';
    }
    //document.getElementById('shrtCnt').innerText = '('+totalShortListed+')';
}

export const updateHeaderNotification = (notifs) => {
  //console.log(notifs.shortlistCount);
  if(isUserLoggedIn() && notifs.shortlistCount > 0){
    document.querySelector('#myShortlistCount').innerHTML = notifs.shortlistCount;
    document.querySelector('#selectedIcons').classList.remove('ic_head_shorlist1x');
    document.querySelector('#selectedIcons').classList.add('ic_head_shorlisted1x');
    document.querySelector('#_myShortlisted').style.display = 'block';
  }
}

export function getCompareData(cookieName){
  var finalCookieName = compareCookieName;
  if(typeof cookieName != 'undefined' && cookieName != ''){
    finalCookieName = cookieName;
  }
  var cmpCookieArr = new Array();
  if(getCookie(finalCookieName)){
      cmpCookieArr = getCookie(finalCookieName).split("|");
  }
  return cmpCookieArr;
}

export function getCompareCourse(deskCookieName){
    var finalCookieName = '';
    if(typeof deskCookieName != 'undefined' && deskCookieName != ''){
      finalCookieName = deskCookieName;
    }
    var courseList = new Array();
    var data = getCompareData(finalCookieName);
    for(var i in data){
        var cookieItemArray = data[i].split("::");
        courseList.push(cookieItemArray[0]);
    }
    return courseList;
}

export function removeCourseFromCompare(courseId){
    var array = new Array();
    var cmpCookieArr = new Array();
    array = getCompareData();
    for(var i in array){
    var cookieItemArray = array[i].split("::");
        if(cookieItemArray[0]==courseId){
            array.splice(i,1);
            cmpCookieArr = array;
            setCookie(compareCookieName,cmpCookieArr.join("|"),30);
            break;
        }
    }
    updateNotification();
    updateCompareCTAText(courseId);
}

function updateCompareCTAText(courseId){
        var e = document.getElementsByClassName('btnCmp'+courseId);  // Find the elements
        for(var i = 0; i < e.length; i++){
            e[i].innerText = 'Compare';
        }
}

export function getComparePageUrl(){
        var cmpData = new Array();
        cmpData = getCompareData();
        var finalUrl ='';
        var urlStr   ='';
        for(var i = 0; i <  2; i++){
            if(cmpData[i] != undefined){
                var cmpStr    = cmpData[i].split("::");
                    finalUrl += cmpStr[0]+'-'; // courseId
            }
        }
        finalUrl = finalUrl.substring(0, (finalUrl.length)-1);
        if(finalUrl){
          urlStr = '-'+finalUrl;
        }
        finalUrl = config().SHIKSHA_HOME+'/resources/college-comparison'+urlStr;
        window.location = finalUrl;
}

export function showToastMsg(msg, time=2000, autoHide=false){
    if(msg && typeof(document.getElementById('toastMsg'))!='undefined' && document.getElementById('toastMsg')){
        document.getElementById('toastMsg').innerText=msg;
        document.getElementById('report-msg').classList.add("showMe");
        if(!autoHide){
            setTimeout(function(){document.getElementById('report-msg').classList.remove("showMe")}, time);
        }
    }
}

export function removeCompareCourseFromRHL(ele){
    var courseId    = ele.getAttribute('data-courseId');
    var instituteId = ele.getAttribute('data-instituteId');
    if(document.getElementById('l'+courseId)){
        document.getElementById('l'+courseId).remove();
    }
    removeCourseFromCompare(courseId);
}

export function removeQueryString(){
    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname
    window.history.pushState({path:newurl},'',newurl);
}

export function retainQueryVariable(retainVariable){ // accept this retainVariable, will remove all query string
    let value = (retainVariable) ? getUrlParameter(retainVariable) : '';
    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname
    if(value !=''){
        newurl = newurl+'?'+retainVariable+'='+value;
    }
    window.history.pushState({path:newurl},'',newurl);
}

export function addQueryParams(params,url=''){
  if(url==''){
    url = window.location.href;
  }
  if(typeof params == 'undefined'){
    return url;
  }
  var urlArr =  url.split('?'),queryParams = {};
  if(urlArr[1]){
    var urlParams = urlArr[1].split('&');
    var temp='';
    urlParams.forEach(function(value){
      temp='';
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


export function pushNewUrl(newurl){
    window.history.pushState({path:newurl},'',newurl);
}

export function getObjectSize(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
}
export function getObjectSlice(obj,start,last){
    var newObj = {};
    var count =-1;
    for(var i in obj)
    {
        count++;
        if(count < start)
            continue;
        if(count >= last)
            break;

        newObj[i] = obj[i];
    }
    return newObj;
}

export function getJsonFromUrl(url) {
    if(!url) url = location.href;
    var question = url.indexOf("?");
    var hash = url.indexOf("#");
    if(hash==-1 && question==-1) return {};
    if(hash==-1) hash = url.length;
    var query = question==-1 || hash==question+1 ? url.substring(hash) :
        url.substring(question+1,hash);
    var result = {};
    query.split("&").forEach(function(part) {
        if(!part) return;
        part = part.split("+").join(" "); // replace every + with space, regexp-free version
        var eq = part.indexOf("=");
        var key = eq>-1 ? part.substr(0,eq) : part;
        var val = eq>-1 ? decodeURIComponent(part.substr(eq+1)) : "";
        var from = key.indexOf("[");
        if(from==-1) result[decodeURIComponent(key)] = val;
        else {
            var to = key.indexOf("]",from);
            var index = decodeURIComponent(key.substring(from+1,to));
            key = decodeURIComponent(key.substring(0,from));
            if(!result[key]) result[key] = [];
            if(!index) result[key].push(val);
            else result[key][index] = val;
        }
    });
    return result;
}

export function parseQueryString( queryString ) {
    queryString = queryString.substring(1);
    var params = {}, queries, temp, i, l;
    // Split into key/value pairs
    queries = queryString.split("&");
    // Convert the array of strings into an object
    for ( i = 0, l = queries.length; i < l; i++ ) {
        temp = queries[i].split('=');
        params[temp[0]] = temp[1];
    }
    return params;
}

// return query variable of values, where variable is key of queryparam
export function getQueryVariable(variable, url) {
    var query = (url) ? url.substring(1) : ""; // remove "?" from urls string
    if(query == "" || !query)
        return "";
    var vars = query.split('&');
    var arrayData = new Array();
    var strData = '';
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        var key = pair[0].split(/[[\]]{1,2}/);
        if (key.length > 1 && decodeURIComponent(key[0]) == variable){
            arrayData.push(decodeURIComponent(pair[1]));
        }else if(decodeURIComponent(key[0]) == variable){
            strData = decodeURIComponent(pair[1]);
        }
    }
    return (arrayData.length) ? arrayData : strData;
}
//TODO change this
export function getQueryVarCityState(variable, url) {
    let query = (url) ? url.substring(1) : ""; // remove "?" from urls string
    if(query == "" || !query)
        return "";
    let vars = query.split('&');
    let arrayData = new Array();
    let strData = '';
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        var key = pair[0].split(/[[\]]{1,2}/);
        if (key.length > 1 && key[0] === variable){
            if(!isNaN(pair[1]))
                arrayData.push(pair[1]);
            else{
                console.log("Shiksha_Search_NAN",url);
            }
        }else if(key[0] == variable){
            console.log("Shiksha_Search_Not Array ",url);
            if(!isNaN(pair[1]))
                strData = pair[1];
            else{
                console.log("Shiksha_Search_NAN",url);
            }
        }
    }
    return (arrayData.length) ? arrayData : strData;
}

export function lazyLoadCss(){
    if(typeof commonCss =='undefined' || typeof commonCss !='string')
    {
        return null;
    }
    if(document.getElementById('shkCmn') && document.getElementById('shkCmn').href==''){
        document.getElementById('shkCmn').href = commonCss;
    }
    /*var l = document.createElement('link'); l.rel = 'stylesheet';
        l.href = cssFile;
    var h = document.getElementsByTagName('head')[0]; h.appendChild(l);*/
}

export function prep_url(str)
{
    if (str == 'http://' || str == '')
    {
        return '';
    }
    var urlProtocol = str.indexOf('http');
    if (urlProtocol == '-1')
    {
        str = 'http://'+str;
    }

    return str;
}

export function getEBCookie(courseId){
    var courseArr = new Array();
    courseArr = (getCookie('applied_courses')) ? JSON.parse(atob(getCookie('applied_courses'))) : [];
    if(courseArr.length>0 && courseArr.toString().indexOf(courseId) != '-1'){
        return true;
    }
    return false;
}

export function setEBCookie(courseId){
    var preEB = new Array();
    preEB = (getCookie('applied_courses')) ? JSON.parse(atob(getCookie('applied_courses'))) : [];
    preEB.push(courseId);
    if(preEB.length){
        setCookie('applied_courses',btoa(JSON.stringify(preEB)),0,'/');
    }
}

export function retainRecoCTAState(courseId){
    if(getEBCookie(courseId)){
        var e = document.getElementsByClassName('brchr_'+courseId);  // Find the elements
        for(var i = 0; i < e.length; i++){
            e[i].innerText = 'Brochure Mailed';
            e[i].classList.add('ebDisabled');
        }
    }
}

export function retainFeeDetailsCTAState(courseId,callBackFunction){
    if(getEBCookie(courseId)){
        if(callBackFunction){callBackFunction();}
        var e = document.getElementsByClassName('getFeeDtl_'+courseId);  // Find the elements
        for(var i = 0; i < e.length; i++){
            e[i].classList.add('gfeedisabled');
        }
    }
}

export function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}


export function splitPathQueryParamsFromUrl(stringUrl)
{
    if(typeof stringUrl != "string" || stringUrl == "")
        return {pathname : "",search : ""};
    var location = {};
    var vars = "", hash;
    var hashes = stringUrl.indexOf('?') >-1 ? stringUrl.slice(stringUrl.indexOf('?') + 1).split('&') : [];
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');if(vars != ""){vars += "&" }
        if(typeof hash[0] != 'undefined' && typeof hash[1] != 'undefined')
            vars += hash[0]+"="+hash[1];
    }
    location.pathname = stringUrl.indexOf('?') >-1 ? stringUrl.slice(0,stringUrl.indexOf('?')) : stringUrl;
    location.search = "";
    if(vars != "")
    {
        location.search = '?'+vars;
    }
    return location;
}

export function getUrlParameter(name) {
    if(typeof location == 'undefined' || typeof location.search == 'undefined' || location.search == ''){
        return "";
    }
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

export function pruneSrpQueryParams(queryParams, basicQueryParams = null) {
    const params = parseQueryString(queryParams);
    const uafParams = getQueryVariable('uaf',queryParams);
    let srpQueryParams = '?q='+params['q'];
    for(let param in params){
        if(param === 'q')
            continue;
        if(basicQueryParams.indexOf(param) !== -1){
            srpQueryParams += '&' + param + '=' + params[param];
            continue;
        }
        switch(param){
            case('bc[]') :
                if(uafParams.indexOf('base_course') === -1)
                    srpQueryParams += '&bc[]=' + params[param];
                    break;
            case('ss[]') :
                if(uafParams.indexOf('sub_spec') === -1)
                    srpQueryParams += '&ss[]=' + params[param];
                break;
            case('sp[]') :
                if(uafParams.indexOf('specialization') === -1)
                    srpQueryParams += '&sp[]=' + params[param];
                break;
            case('sb[]') :
                if(uafParams.indexOf('substream') === -1)
                    srpQueryParams += '&sb[]=' + params[param];
                break;
            case('s[]') :
                    srpQueryParams += '&s[]=' + params[param];
                break;

        }
    }
    return srpQueryParams
}

//returns all values of query params, even if its array
export function parseQueryParams( queryString ) {
    queryString = queryString.substring(1);
    let params = {}, queries, temp, i, l, key;
    // Split into key/value pairs
    queries = queryString.split("&");
    // Convert the array of strings into an object
    for ( i = 0, l = queries.length; i < l; i++ ) {
        temp = queries[i].split('=');
        key = temp[0].split(/[[\]]{1,2}/);
        if(key.length > 1){
            if(!params[key[0]])
                params[key[0]] = [];
            params[key[0]].push(decodeURIComponent(temp[1]));
        }
        else{
            params[temp[0]] = decodeURIComponent(temp[1]);
        }
    }

    return params;
}

export function Ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

export function triggerEvent(el, type){
  if ('createEvent' in document) {
    // modern browsers, IE9+
    var e = document.createEvent('HTMLEvents');
    e.initEvent(type, false, true);
    el.dispatchEvent(e);
  } else {
    // IE 8
    var e = document.createEventObject();
    e.eventType = type;
    el.fireEvent('on'+e.eventType, e);
  }
}

export function extend(a, b){
    for(var key in b){
      if(b.hasOwnProperty(key)){
        a[key] = b[key];
      }
    }
    return a;
}

export const toggle = (x) => {
  let style = window.getComputedStyle(x);
  if(style.display != "none"){
    x.style.display = "none";
  }else{
    x.style.display = "block";
  }
}

export const openInNewWindow = (url, height1, width1, scroll1, top1, left1) => {
  if(typeof(height1) == 'undefined'){
    if(url.indexOf('communityGuideline') == -1){
      height1 = 600, width1=600, scroll1="yes", top1=50, left1=200;
    }
    else{
      height1 = 600, width1=1000, scroll1="yes", top1=50, left1=200;
    }
  }
  let newWindow = window.open(url, 'name', 'height='+height1+', width='+width1+', scrollbars='+scroll1+', top='+top1+', left='+left1);
  if (window.focus) {
    newWindow.focus();
  }
  return false;
}

export const getImageUrlBySize = (imageUrl, size = 'big') => {
  let imageFinalPath = imageUrl;
  let index = imageUrl.lastIndexOf('.');
  let imagePrefixPath, imageSuffixPath;
  if(index < imageUrl.length){
    imagePrefixPath = imageUrl.substr(0, index);
    imageSuffixPath = imageUrl.substr(index+1);
  }
  switch (size) {
    case 'big':
      imageFinalPath = imagePrefixPath + '_b.' + imageSuffixPath;
    break;
    case 'small':
      imageFinalPath = imagePrefixPath + '_s.' + imageSuffixPath;
    break;
    case 'medium':
      imageFinalPath = imagePrefixPath + '_m.' + imageSuffixPath;
    break;
    default:
      imageFinalPath = imagePrefixPath + '_' + size + '.' + imageSuffixPath;
  }
  return imageFinalPath;
}

export function convertObjectIntoQueryString(data) {
    let str = "";
    for (let key in data) {
        if(!Array.isArray(data[key])){
            if (str != "") {
                str += "&";
            }
            str += key + "=" + encodeURIComponent(data[key]);
            continue;
        }
        for(let lkey in data[key]) {
            if (str != "") {
                str += "&";
            }
            str += key+'[]' + "=" + encodeURIComponent(data[key][lkey]);
        }
    }
    return str;
}
export function categoryPageShuffle(catPageData, seed){
    let milArr = [], spArr = [];
    let milCount = 0, sponsorCount=0;
    if(catPageData.totalMainInsttCount && typeof catPageData.totalMainInsttCount !== "undefined"){
        milCount = catPageData.totalMainInsttCount;
    }
    if(catPageData.totalSponsorInsttCount && typeof  catPageData.totalSponsorInsttCount !== "undefined"){
        sponsorCount = catPageData.totalSponsorInsttCount;
    }
    if(sponsorCount > 0) {
        spArr = shuffleArrayOnSeed(catPageData.categoryInstituteTuple.slice(0, sponsorCount), seed);
    }
    if(milCount > 0){
        milArr = shuffleArrayOnSeed(catPageData.categoryInstituteTuple.slice(sponsorCount, sponsorCount + milCount), seed);
    }
    return spArr.concat(milArr, catPageData.categoryInstituteTuple.slice(sponsorCount + milCount));
}
function shuffleArrayOnSeed(array, seed) {
    let m = array.length, t, i;
    // While there remain elements to shuffle…
    while (m) {
        // Pick a remaining element…
        i = Math.floor(pseudoRandom(seed) * m--);        // <-- MODIFIED LINE
        // And swap it with the current element.
        t = array[m];
        array[m] = array[i];
        array[i] = t;
        ++seed                                     // <-- ADDED LINE
    }
    return array;
}

function pseudoRandom(seed) {
    var x = Math.sin(seed++) * 10000;
    return x - Math.floor(x);
}

export function prunePramsForURL(paramsObj){
    for (let key in paramsObj) {
        if(key === 'rf' || key === 'fr' || key === 'url')
            continue;
        if(Array.isArray(paramsObj[key])) {
            if(paramsObj[key].indexOf('undefined') !== -1)
                delete paramsObj[key][paramsObj[key].indexOf('undefined')];
            else if(paramsObj[key].indexOf('') !== -1)
                delete paramsObj[key][paramsObj[key].indexOf('')];
        } else if(!Array.isArray(paramsObj[key])){
            delete paramsObj[key];
        }
    }
    return paramsObj;
}

export function resetGNB(){
    if(typeof document === 'undefined'){
        return ;
    }
    setTimeout(() => {
        document.querySelector('#_globalNav').classList.remove('_gnb-sticky');
        document.querySelector('#_globalNav').classList.remove('_gnb-toggle-anim');
        document.querySelector('#main-wrapper').style.marginTop = '';
    }, 75);
}

export function togglePageCSSForFullPageLayer(flag){
  if(flag == 'add'){
    document.getElementById('fullLayer-container').classList.add("show");
    if(document.getElementById('wrapperMainForCompleteShiksha')){
        document.getElementById('wrapperMainForCompleteShiksha').classList.add("noscroll");
    }
  }else{
    document.getElementById('fullLayer-container').classList.remove("show");
    if(document.getElementById('wrapperMainForCompleteShiksha')){
        document.getElementById('wrapperMainForCompleteShiksha').classList.remove("noscroll");
    }
  }
}

export function makeFiltersSticky(currScroll,filterSidebarElement,resultsContainer,footerSelector,stickyBooleans,gnbHeight,lastScrollTop,filterBottomFixPos){
    let smallFilter = false;
    if(resultsContainer && filterSidebarElement && resultsContainer.clientHeight < filterSidebarElement.clientHeight){
        return stickyBooleans;
    }
    if(filterSidebarElement.clientHeight <= document.documentElement.clientHeight - gnbHeight){
        smallFilter = true;
    }
    const filterTopOffset = filterSidebarElement.getBoundingClientRect().top + window.pageYOffset;
    if (currScroll > lastScrollTop){
        //scrolling down
        //footer is in view port
        if(currScroll > footerSelector.offsetTop - document.documentElement.clientHeight
            && !stickyBooleans.footerSeenFlag && !stickyBooleans.isTopSetToMax && !smallFilter){
            stickyBooleans.smallFilterTopFixed = false;
            stickyBooleans.isTopSetToMax = true;
            stickyBooleans.isRelativeFlag = false;
            stickyBooleans.footerSeenFlag = true;
            filterSidebarElement.style.top = (resultsContainer.clientHeight - filterSidebarElement.clientHeight) + 'px';
            filterSidebarElement.style.position = 'relative';
            filterSidebarElement.style.bottom = 'auto';
        }else if(currScroll > footerSelector.offsetTop - document.documentElement.clientHeight
            && !stickyBooleans.footerSeenFlag && !stickyBooleans.isTopSetToMax && smallFilter){
            stickyBooleans.smallFilterTopFixed = false;
            stickyBooleans.isTopSetToMax = true;
            stickyBooleans.isRelativeFlag = false;
            stickyBooleans.footerSeenFlag = true;
            filterSidebarElement.style.top = (resultsContainer.clientHeight - document.documentElement.clientHeight+gnbHeight ) + 'px';
            filterSidebarElement.style.position = 'relative';
            filterSidebarElement.style.bottom = 'auto';
        }
        const top = resultsContainer.offsetTop - gnbHeight;
        if(smallFilter && !stickyBooleans.footerSeenFlag && currScroll > top && !stickyBooleans.smallFilterTopFixed){
            stickyBooleans.smallFilterTopFixed = true;
            stickyBooleans.topIsReachedFlag = false;
            filterSidebarElement.style.top = gnbHeight + 'px';
            filterSidebarElement.style.bottom = 'auto';
            filterSidebarElement.style.position = 'fixed';
        }
        //last filter is in view port
        if(currScroll > filterBottomFixPos && !stickyBooleans.bottomFixedFlag &&
            !stickyBooleans.footerSeenFlag && !stickyBooleans.isTopSetToMax && !smallFilter){
            stickyBooleans.isRelativeFlag = false;
            stickyBooleans.bottomFixedFlag = true;
            //this.setState({...this.state, stickyClass :'bottom-fixed'});
            filterSidebarElement.style.top = 'auto';
            filterSidebarElement.style.bottom = '0px';
            filterSidebarElement.style.position = 'fixed';
        }
        if(stickyBooleans.topFixedFlag){
            stickyBooleans.smallFilterTopFixed = false;
            stickyBooleans.topFixedFlag = false;
            filterSidebarElement.style.top = filterTopOffset  - resultsContainer.offsetTop + 'px';
            filterSidebarElement.style.bottom = 'auto';
            filterSidebarElement.style.position = 'relative';
        }
        if(currScroll > resultsContainer.offsetTop && stickyBooleans.topIsReachedFlag){
            stickyBooleans.topIsReachedFlag = false;
        }
    } else {
        //scrolling up and filter top is shown
       if(currScroll < filterTopOffset && !stickyBooleans.topFixedFlag && !stickyBooleans.topIsReachedFlag &&
            filterTopOffset > resultsContainer.offsetTop && !stickyBooleans.footerSeenFlag){
            stickyBooleans.smallFilterTopFixed = false;
            stickyBooleans.topFixedFlag = true;
            filterSidebarElement.style.top = gnbHeight + 'px';
            filterSidebarElement.style.bottom = 'auto';
            filterSidebarElement.style.position = 'fixed';
            stickyBooleans.isRelativeFlag = false;
            stickyBooleans.isTopSetToMax = false;
        }
        //top is seen
        const top = resultsContainer.offsetTop - gnbHeight;
        if((currScroll < top) && !stickyBooleans.topIsReachedFlag){
            stickyBooleans.smallFilterTopFixed = false;
            filterSidebarElement.style.top = '0px';
            filterSidebarElement.style.bottom = 'auto';
            filterSidebarElement.style.position = 'relative';
            stickyBooleans.topIsReachedFlag = true;
            stickyBooleans.topFixedFlag = false;
        }
        if(!stickyBooleans.topFixedFlag && !stickyBooleans.topIsReachedFlag && !stickyBooleans.isRelativeFlag && !smallFilter){
            stickyBooleans.isRelativeFlag = true;
            filterSidebarElement.style.top = (filterTopOffset - resultsContainer.offsetTop) + 'px';
            filterSidebarElement.style.bottom = 'auto';
            filterSidebarElement.style.position = 'relative';
        }
        if(currScroll < footerSelector.offsetTop - document.documentElement.clientHeight &&
            stickyBooleans.footerSeenFlag){
            stickyBooleans.footerSeenFlag = false;
        }
        stickyBooleans.bottomFixedFlag = false;
    }
    return stickyBooleans;
};
export function formatDate(myDate, format = 'mm dd, yyyy') {
    if(myDate == ''){
        return '';
    }
    let shortMonths = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct","Nov", "Dec"];
    //let date = new Date(myDate).getTime();
    let currentDate = new Date(myDate.replace(/-/g, '/')); // for safari we added replace
    let day         = currentDate.getDate();
    let monthIndex  = currentDate.getMonth();
    let year        = currentDate.getFullYear();
    switch(format){
    case 'mm dd, yyyy' : return shortMonths[monthIndex]+' '+day+ ', ' +year;
    case 'DD MM yy' : return day+' '+shortMonths[monthIndex]+' \''+year.toString().substr(-2);
    case 'd m y': return day+' '+shortMonths[monthIndex]+' '+year;
    case 'd m\'y': return day+' '+shortMonths[monthIndex]+' \''+year.toString().substr(-2);
    default : return year+'-'+(monthIndex+1)+ '-' +day;
    }
}


export function PageLoadToastMsg(type = 'SRM'){
    if(type=='SRM'){
        return 'Thank you for interest in SRM. You may receive an email from SRM to verify your email ID to apply for SRMJEEE.';
    }
    else
        return null;
}
export function makeTopWidgetSticky() {
    var ftrh = ($j('#footer').length>0) ? $j('#footer').offset().top : 0; 
    ftrh = ftrh - 100;
    var sticky = $j('#sticky_header'),
    scroll = $j(window).scrollTop();
    var CTASection = ($j('#CTASection').length>0) ? $j('#CTASection').offset().top : 0;
    if(scroll >= CTASection && scroll< ftrh) {
       sticky.removeClass('hid');
    }
    else{
        sticky.addClass('hid');
    }
};


export function sectionLoder(){
        return(

                <div class="_conatiner min-heigth250">
                   <h2 class="tbSec2"><div className="loader-line shimmer wdth30 ht20"></div></h2> 
                   <div class="_subcontainer">
                      <div class="wikkiContents">
                         <div className="loader-line shimmer"></div>
                         <div className="loader-line shimmer wdt85"></div>
                      </div>
                   </div>
                </div>
            )

}

export function mobileSectionLoder(){
        return(
            <section className="loaderDiv">
                <div className="_container">
                 <h2 className="tbSec2"><div className="loader-line shimmer wdt55"></div></h2>
                    <div className="_subcontainer">
                    <div className="loader-container">
                       <div className="loader-ContDiv">
                            <div className="loader-line shimmer"></div>
                            <div className="loader-line shimmer wdt75"></div>
                            <div className="loader-line shimmer wdt75"></div>
                       </div>
                       <div className="loader-ContDiv">
                            <div className="loader-line shimmer"></div>
                            <div className="loader-line shimmer wdt85"></div>
                       </div>
                    </div>
                  </div>
                </div>
            </section>
            )

}

export function removeParamFromUrl(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

export function getInlineCSS(moduleName){
    let modulename     = moduleName.toLowerCase();
    let desktopModules = [];
    let mobileModules  = ['exampage','collegepredictor'];
    if(global.isMobileRequest === true && mobileModules.indexOf(modulename) !=-1){
        return require('../../public/css/firstFold/mobile/inline_'+modulename+'.css');
    }else if(global.isMobileRequest !== true && desktopModules.indexOf(modulename)!=-1){
        return require('../../public/css/firstFold/desktop/inline_'+modulename+'.css');
    }else {
        return null;
    }
}

export function validateField(field,type='string',propertyPath=false) {
    const validate =  typeof field !== 'undefined'
        && field != null;
    if(!validate)
        return validate;
    switch (type) {
        case 'string' :
            return field !== '';
        case 'array' :
            return field.length !==0;
        case 'object' :
            if(!propertyPath)
                return Object.keys(field).length !==0;
            else
                return hasOwnDeepProperty(field,propertyPath)
    }
    return false;
}

export function CTAResponseHTML(isPaid, instituteName,actionType,pdfUrl){
    if(actionType =='Request_Call_Back'){
        if(!isPaid){
            return (
                <p>You may receive calls from similar colleges.</p>)
        }else{
            return(
                <p>{"You will soon receive a call from "+ instituteName+'.'}</p>
            )
        }
    }
    else if(actionType=='Get_Free_Counselling'){
        if(!isPaid){
            return (
                <p>You may receive counselling calls from similar colleges.</p>)
        }else{
            return(
                <p>{" You will soon receive a counselling call from "+ instituteName+'.'}</p>
            )
        }
    }

    else if(actionType=='Get_Admission_Details' || actionType == 'Download_CourseList' || actionType==='Download_Top_Reviews' || actionType==='Download_Top_Questions' || actionType==='Get_Scholarship_Details' || actionType=='Download_Cutoff_Details'){
        return(
            <div>Your download should start automatically. If it does not start automatically <a className="link" href={(pdfUrl) ? pdfUrl : 'javascript:void();'} target="_blank">Click Here</a> to manually download it.</div>
        )
    }

    return null;

}

export function goTo(targetEle, deviceType){
        if(document.getElementById(targetEle)){
            let topSpace   = (deviceType == 'desktop') ? 120 : 30; 
            let elePositon = document.getElementById(targetEle).getBoundingClientRect().top;
            elePositon = (window.scrollY>elePositon || window.scrollY<elePositon) ? (window.scrollY + elePositon) - topSpace : elePositon;

            if(elePositon>0){
                setTimeout(function(){console.log(elePositon);window.scrollTo(0, elePositon);},500);     
            }
        }
    }

export function setFeedbackWidgetFlagCookie(pageType, pageId, subPageType = '') {
    if(pageType === '' || pageId === ''){
        return;
    }
    if(subPageType !== ''){
        pageType += '-'+subPageType;
    }
    let cookieName = 'feedbackPages';
    let currentPages = getCookie(cookieName);
    let nextPage = pageType+':'+pageId;
    if(currentPages === ''){
        setCookie(cookieName , nextPage, '1800', 'seconds');
    }else{
        let exist = checkIfFeedbackAlreadySubmitted(pageType, pageId);
        currentPages += '_' + nextPage;
        if(!exist){
            setCookie(cookieName , currentPages, '1800', 'seconds');
        }
    }
}

export function checkIfFeedbackAlreadySubmitted(pageType, pageId, subPageType = ''){
    if(subPageType !== ''){
        pageType += '-'+subPageType;
    }
    let currentPages = getCookie('feedbackPages');
    let nextPage = pageType+':'+pageId;
    if(currentPages === ''){
        return false;
    }else{
        let allPages = currentPages.split('_');
        return allPages.indexOf(nextPage) !== -1;
    }
}

export function getPageCanonicalUrl(){
    if(typeof window != 'undefined' && window){
        let canonical = "";
        let links = document.getElementsByTagName("link");
        for (let i = 0; i < links.length; i ++) {
            if (links[i].getAttribute("rel") === "canonical") {
                canonical = links[i].getAttribute("href");
                break;
            }
        }
        return (canonical) ? canonical : window.location.href;
    }
}

export function chunkArray(myArray, chunkSize) {
    let arrayLength = myArray.length;
    let chunks = [];
    for (let index = 0; index < arrayLength; index += chunkSize) {
        chunks.push(myArray.slice(index, index + chunkSize));
    }
    return chunks;
}

export function clearCTAInfo(){
    if(typeof window.sessionStorage != 'undefined'){
        Object.keys(window.sessionStorage).filter(function(k) { return /EPCTA_/.test(k); }).forEach(function(k) {
            window.sessionStorage.removeItem(k);
        });
    }
}
