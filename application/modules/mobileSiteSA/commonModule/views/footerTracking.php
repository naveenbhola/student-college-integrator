<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-4454182-1']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script');
ga.type = 'text/javascript';
ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);
})();
function _pageTracker(e){
this.type=e;
  this._trackEventNonInteractive=function(a, b, c, d, e) {
  _gaq=_gaq||[]; _gaq.push(['_trackEvent', a, b, c, d, e]);
};
  this._setCallback=function(e){
  _gaq.push(['_set', 'hitCallback', e]);
};
this._setCustomVar=function(e,t,n,r){
  _gaq=_gaq||[];
  _gaq.push(["_setCustomVar",e,t,n,r])
};
}

var pageTracker=new _pageTracker;
var gaCallbackURL;

function gaTrackEventCustom(currentPageName, eventAction, opt_eventLabel, event, callbackUrl) {
if(typeof(opt_eventLabel) == 'undefined') {
       opt_eventLabel = "";
}
if(typeof(pageTracker) != 'undefined') {
       if (typeof(callbackUrl) != 'undefined' && typeof(gaCallbackFunction) == 'function') {
               if(!event){
                       event = window.event;
               }
               if (event != 'undefined') {
                       if (event.cancelBubble) {
                               event.cancelBubble = true;
                       } else {
                               if(event.stopPropagation) {
                                       event.stopPropagation();
                               }
                       }
                       if(event.preventDefault){
                               event.preventDefault();    
                       }
               }
               gaCallbackURL = callbackUrl; //set global variable
               pageTracker._setCallback(gaCallbackFunction);
       }
       pageTracker._trackEventNonInteractive(currentPageName, eventAction, opt_eventLabel, 0, true);
       if(typeof(callbackUrl) != 'undefined') {	
               setTimeout(function() {
                       gaCallbackURL = callbackUrl;
                       gaCallbackFunction();
               }, 3000);
       }
}
return true;
}

var gaCallbackFunction = function() {
var url = gaCallbackURL;
gaCallbackURL = '';
if (url != '') {
       window.location = url;
}
}                

function trackEventByGAMobile(eventTracked){
try{
        _gaq.push(['_trackEvent',eventTracked, 'click']);
}catch(e){}
}
</script>