import sha1 from 'sha1';

var uniqueVisitorId;
var visitSessionId;
var isNewVisitor;
var isNewSession;
var visitorSessionId;
var COOKIE_DOMAIN = '.shiksha.com';

export function track_visitor(req, res){
  isNewVisitor = false;
  isNewSession = false;
  /**
     * Visitor Id
     * If visitorId cookie not set, it's new visitor/user, generate new visitorId.
     * If set, use existing visitorId
     */

  if(typeof req.cookies.visitorId=='undefined' || req.cookies.visitorId==''){
    uniqueVisitorId = generateUniqueVisitorId();
    res.cookie('visitorId', uniqueVisitorId, { domain:COOKIE_DOMAIN, expires:  new Date(Date.now() + 15552000)});
    isNewVisitor = true;
  }
  else {
    uniqueVisitorId = req.cookies.visitorId;
  }

  /**
   * Visitor session id
   * Generate new session id if:
   *  - It's a new visitor
   *  - visitorSessionId and lastVisitTime cookies not set, either first time or these cookies expired after 30 min of inactivity
   *  - Date has changed, new request after midnight 12:00
   */
  
  if(isNewVisitor) {
      isNewSession = true;
  }
  else if(typeof req.cookies.visitorSessionId!='undefined' && req.cookies.visitorSessionId!='') {
      if(typeof req.cookies.lastVisitTime!='undefined' && req.cookies.lastVisitTime!='') {
        let lastVisitDate    = dateFormat('visitorSessionId', parseInt((req.cookies.lastVisitTime))*1000);
        let currentVisitDate = dateFormat('visitorSessionId');
        if(lastVisitDate != currentVisitDate) {
          isNewSession = true;
        }
      }
      else {
        isNewSession = true;
      }
  }
  else {
    isNewSession = true;
  }

  if(isNewSession) {
    visitSessionId = generateVisitorSessionId();
  }
  else {
    visitSessionId = req.cookies.visitorSessionId;
  }
  res.cookie('visitorSessionId', visitSessionId, {domain:COOKIE_DOMAIN, expires:  new Date(Date.now() + 1800)});
  res.cookie('lastVisitTime', time(), {domain:COOKIE_DOMAIN, expires:  new Date(Date.now() + 1800)});
}

function mt_getrandmax () {
  return 2147483647;
}

function mt_rand (min, max) {
  var argc = arguments.length
  if (argc === 0) {
    min = 0
    max = 2147483647
  } else if (argc === 1) {
    throw new Error('Warning: mt_rand() expects exactly 2 parameters, 1 given')
  } else {
    min = parseInt(min, 10)
    max = parseInt(max, 10)
  }
  return Math.floor(Math.random() * (max - min + 1)) + min
}

function uniqid(pr, en) {
  var pr = pr || '', en = en || false, result;

  function seed(s, w) {
    s = parseInt(s, 10).toString(16);
    return w < s.length ? s.slice(s.length - w) : (w > s.length) ? new Array(1 + (w - s.length)).join('0') + s : s;
  }

  result = pr + seed(parseInt(new Date().getTime() / 1000, 10), 8) + seed(Math.floor(Math.random() * 0x75bcd15) + 1, 5);

  if (en) result += (Math.random() * 10).toFixed(8).toString();

  return result;
}

function dateFormat(type, val){  
  let now ='';
  if(val=='' || typeof val=='undefined'){
    now = new Date();  
  }else{
    now = new Date(val);  
  }
  let year = "" + now.getFullYear();
  let month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
  let day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
  let hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
  let minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
  let second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
  let fullTime = '';
  if(type=='generateVisitorSessionId'){
    fullTime = year+ month+day+hour+minute+second;
  }else{
    fullTime = year+'-'+month+'-'+day;
  }
  return fullTime;
}

function generateVisitorSessionId(){
  let sessid = 0 ;
  let sessidString = '' ;
  while (sessidString.length < 32) {
    sessid += mt_rand(0, mt_getrandmax());
    sessidString = sessid;
  }
  sessid += generateUniqueVisitorId();
  visitorSessionId = sha1(uniqid(sessid, true));
  let fullTime = dateFormat('generateVisitorSessionId');
  visitorSessionId += fullTime;
  return visitorSessionId;
}

function time () {
  return Math.floor(new Date().getTime() / 1000)
}

function base_convert (number, frombase, tobase) { // eslint-disable-line camelcase
  return parseInt(number + '', frombase | 0)
    .toString(tobase | 0)
}

function microtime(getAsFloat){
  var s
  var now
  if (typeof performance !== 'undefined' && performance.now) {
    now = (performance.now() + performance.timing.navigationStart) / 1e3
    if (getAsFloat) {
      return now
    }

    // Math.round(now)
    s = now | 0

    return (Math.round((now - s) * 1e6) / 1e6) + ' ' + s
  } else {
    now = (Date.now ? Date.now() : new Date().getTime()) / 1e3
    if (getAsFloat) {
      return now
    }

    // Math.round(now)
    s = now | 0

    return (Math.round((now - s) * 1e3) / 1e3) + ' ' + s
  }
}

function rand (min, max) {
  var argc = arguments.length
  if (argc === 0) {
    min = 0
    max = 2147483647
  } else if (argc === 1) {
    throw new Error('Warning: rand() expects exactly 2 parameters, 1 given')
  }
  return Math.floor(Math.random() * (max - min + 1)) + min
}

function generateUniqueVisitorId(){
  uniqueVisitorId = base_convert(microtime(true), 10, 36) + rand(1000, 9999);
  return uniqueVisitorId;
}
