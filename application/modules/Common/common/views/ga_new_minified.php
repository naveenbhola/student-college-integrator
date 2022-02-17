<?php  if(!$_REQUEST['loadFromStatic']) { ?>

<script>
var _gaq=_gaq||[];
_gaq.push(['_setAccount','UA-4454182-1'],['_setDomainName', 'shiksha.com'],['_deleteCustomVar', 1],['_trackPageview']);
(function(b,c){
    var a=b.createElement(c),d=b.getElementsByTagName(c)[0];
    a.async=a.src='//www.google-analytics.com/ga.js';
    d.parentNode.appendChild(a,d)
})(document,'script');
function _pageTracker(e){
    this.type=e;
    this._trackEvent=function(e,t,n) {
        _gaq=_gaq||[];_gaq.push(["_trackEvent",e,t,n])
    };
    this._trackPageview=function(e){
        _gaq=_gaq||[];
        if(typeof e=="undefined"){
            _gaq.push(["_trackPageview"])
        } else {
            _gaq.push(["_trackPageview",e])
        }
    };
    this._setCustomVar=function(e,t,n,r){
        _gaq=_gaq||[];
        _gaq.push(["_setCustomVar",e,t,n,r])
    };
    this._trackEventNonInteractive = function(a,b,c,d,e) {
        _gaq.push(['_trackEvent', a, b, c,d,e]);
    };
    this._setCallback=function(e){
        _gaq.push(['_set', 'hitCallback', e]);
    }
}var pageTracker=new _pageTracker;
var gaCallbackURL;
</script>
<?php } ?>

