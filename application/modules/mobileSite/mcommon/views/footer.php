</div>
<div <?php if(($total_results<=3 && $device_type != "low" && ($boomr_pageid == 'REFINE_LOCATION_SEARCH_PAGE')) /*|| 
	(empty($total_results) && $boomr_pageid == 'SEARCH_RESULT_PAGE') || ($total_results<=1 && $device_type != "low" && $boomr_pageid == 'SEARCH_SIMILAR_COURSES_PAGE')*/):?>class="fixed" <?php endif;?>>
<div id="footer">
    <div id="footer-nav">
	    <ul>
<li><a href="<?php echo SHIKSHA_HOME; ?>" title = 'Home' >Home</a><span>|</span></li>
<li><a title="About Us" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/aboutus">About Us</a><span>|</span></li> 
<li><a title="Privacy" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/privacy">Privacy</a><span>|</span></li>
<li><a title="Contact Us" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/contactUs">Contact Us</a></li>

	</ul>
	<div class="view-full"><a title="View Full Site" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/viewfullsite">View Full Site</a></div>

	<div class="clearFix">&nbsp;</div>
	<div class="inst-detail-list" >
		<dt onClick="toggleQuickLinks();" style="padding-bottom: 5px;">
			<a href="javascript:void(0);">
			    <span style="font:bold 13px Tahoma, Geneva, sans-serif; color:#505251;">Quick Links</span>
			    <i style="float: none;height: 9px;position: absolute;right: 5px;font-size: 20px;width: 20px;" id="quickLinkIcon">+</i>
			</a>
                </dt>
				
                <dd style="margin-bottom:0;display: none;" id="quickLinks">
                	<div class="tiny-contents">
				<p class="quick-link-head">Study in India</p>
				<div class="link-box">
					<a href="<?=SHIKSHA_MANAGEMENT_HOME?>">Management Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_SCIENCE_HOME?>">Science & Engineering</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_BANKING_HOME?>">Banking & Finance Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_TESTPREP_HOME?>">Test Preparaion Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_IT_HOME?>">Information Technology Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_ANIMATION_HOME?>">Animation, Visual Effects, Gaming & Comics (AVGC) Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_HOSPITALITY_HOME?>">Hospitality , Aviation & Tourism Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_MEDICINE_HOME?>">Medicine, Beauty & Health Care Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_DESIGN_HOME?>">Design Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_MEDIA_HOME?>">Media , Films & Mass Communication Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_ARTS_HOME?>">Arts, Law, Languages and Teaching Colleges</a><span class="link-sep"> | </span>
					<a href="<?=SHIKSHA_RETAIL_HOME?>">Retail Colleges</a>
				</div>
				<p id="studyAbroadLinks" class="quick-link-head" style="display:none;">Study Abroad</p>
			</div>
                </dd>
        </div>	

	
    </div>
</div>
</div>
<!--</div> -->
<!-- END HEADER -->
<?php
global $serverStartTime;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
$url = SHIKSHA_HOME . "/public/" ;
if (function_exists('googleAnalyticsGetImageUrl')) {
$googleAnalyticsImageUrl = googleAnalyticsGetImageUrl($url);
}
else{
$googleAnalyticsImageUrl = "";
}

global $ci_mobile_capbilities; if(!isset($ci_mobile_capbilities)) { $ci_mobile_capbilities = $_COOKIE['ci_mobile_capbilities']; $wurfl_data = json_decode($ci_mobile_capbilities,true);} else { $wurfl_data = $ci_mobile_capbilities; }
?>
<script type="text/javascript">

<?php if($wurfl_data["ajax_support_javascript"] == "true") { ?>
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
/*
(function(window){
    var undefined,
        link = function (href) {
            var a = window.document.createElement('a');
            a.href = href;
            return a;
        };
    window.onerror = function (message, file, row) {
        var host = link(file).hostname;
        _gaq.push([
            '_trackEvent',
            (host == window.location.hostname || host == undefined || host == '' ? '' : 'external ') + 'error',
            message, file + ' LINE: ' + row, undefined, undefined, true
        ]);
    };
}(window));

if (typeof _gaq !== "undefined" && _gaq !== null) {
    $(document).ajaxSend(function(event, xhr, settings){
        _gaq.push(['_trackPageview', settings.url]);
    });
}
*/
$(function(){
    var isDuplicateScrollEvent,
        scrollTimeStart = new Date,
        $window = $(window),
        $document = $(document),
        scrollPercent;

   /* $window.scroll(function() {
        scrollPercent = Math.round(100 * ($window.height() + $window.scrollTop())/$document.height());
        if (scrollPercent > 90 && !isDuplicateScrollEvent) { //page scrolled to 90%
            isDuplicateScrollEvent = 1;
            _gaq.push(['_trackEvent', 'scroll',
                'Window: ' + $window.height() + 'px; Document: ' + $document.height() + 'px; Time: ' + Math.round((new Date - scrollTimeStart )/1000,1) + 's',
                undefined, undefined, true
            ]);
        }
    });*/
});
<?php  }  ?>
</script>
<?php if($wurfl_data["ajax_support_javascript"]  == "false" && $googleAnalyticsImageUrl!="") { ?>
<div style="height:1px;overflow:hidden" ><img src="<?php echo $googleAnalyticsImageUrl; ?>" /></div>
<?php } ?>
<?php //loadBeaconTracker($beaconTrackData); ?>
</body>

<script type="text/javascript">
/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-fontface-backgroundsize-borderimage-borderradius-boxshadow-flexbox-hsla-multiplebgs-opacity-rgba-textshadow-cssanimations-csscolumns-generatedcontent-cssgradients-cssreflections-csstransforms-csstransforms3d-csstransitions-applicationcache-canvas-canvastext-draganddrop-hashchange-history-audio-video-indexeddb-input-inputtypes-localstorage-postmessage-sessionstorage-websockets-websqldatabase-webworkers-geolocation-inlinesvg-smil-svg-svgclippaths-touch-webgl-shiv-mq-cssclasses-addtest-prefixed-teststyles-testprop-testallprops-hasevent-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function D(a){j.cssText=a}function E(a,b){return D(n.join(a+";")+(b||""))}function F(a,b){return typeof a===b}function G(a,b){return!!~(""+a).indexOf(b)}function H(a,b){for(var d in a){var e=a[d];if(!G(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function I(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:F(f,"function")?f.bind(d||b):f}return!1}function J(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return F(b,"string")||F(b,"undefined")?H(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),I(e,b,c))}function K(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return y("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},A=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=F(e[d],"function"),F(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),B={}.hasOwnProperty,C;!F(B,"undefined")&&!F(B.call,"undefined")?C=function(a,b){return B.call(a,b)}:C=function(a,b){return b in a&&F(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return J("flexWrap")},s.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},s.canvastext=function(){return!!e.canvas&&!!F(b.createElement("canvas").getContext("2d").fillText,"function")},s.webgl=function(){return!!a.WebGLRenderingContext},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.geolocation=function(){return"geolocation"in navigator},s.postmessage=function(){return!!a.postMessage},s.websqldatabase=function(){return!!a.openDatabase},s.indexedDB=function(){return!!J("indexedDB",a)},s.hashchange=function(){return A("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},s.rgba=function(){return D("background-color:rgba(150,255,150,.5)"),G(j.backgroundColor,"rgba")},s.hsla=function(){return D("background-color:hsla(120,40%,100%,.5)"),G(j.backgroundColor,"rgba")||G(j.backgroundColor,"hsla")},s.multiplebgs=function(){return D("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return J("backgroundSize")},s.borderimage=function(){return J("borderImage")},s.borderradius=function(){return J("borderRadius")},s.boxshadow=function(){return J("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return E("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return J("animationName")},s.csscolumns=function(){return J("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return D((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),G(j.backgroundImage,"gradient")},s.cssreflections=function(){return J("boxReflect")},s.csstransforms=function(){return!!J("transform")},s.csstransforms3d=function(){var a=!!J("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return J("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.localstorage=function(){try{return localStorage.setItem(h,h),localStorage.removeItem(h),!0}catch(a){return!1}},s.sessionstorage=function(){try{return sessionStorage.setItem(h,h),sessionStorage.removeItem(h),!0}catch(a){return!1}},s.webworkers=function(){return!!a.Worker},s.applicationcache=function(){return!!a.applicationCache},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(m.call(b.createElementNS(r.svg,"animate")))},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var L in s)C(s,L)&&(x=L.toLowerCase(),e[x]=s[L](),v.push((e[x]?"":"no-")+x));return e.input||K(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)C(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},D(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.mq=z,e.hasEvent=A,e.testProp=function(a){return H([a])},e.testAllProps=J,e.testStyles=y,e.prefixed=function(a,b,c){return b?J(a,b,c):J(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};

var session_id = '<?php  echo sessionId(); ?>';
var ip_address = '<?php echo ip2long(S_REMOTE_ADDR); ?>';
var _canvasSupport = false;
var _videoSupport  = false;
var _localstorageSupport = false;
var _webworkersSupport = false;
var _applicationcacheSupport = false;
var _geolocationSupport = false;
var _formdateSupport = false;
var _formplaceholderSupport = false;
var _formsautofocusSupport = false;
var _html5historySupport = false;

if (Modernizr.canvas) {
  _canvasSupport = true;
}

if (Modernizr.video) {
 _videoSupport = true;
}

if (Modernizr.localstorage) {
_localstorageSupport = true;
}

if (Modernizr.webworkers) {
_webworkersSupport = true;
}

if (Modernizr.applicationcache) {
_applicationcacheSupport = true;
}

if (Modernizr.geolocation) {
_geolocationSupport = true;
}

if (Modernizr.inputtypes.date) {
_formdateSupport = true;
}

if (Modernizr.input.placeholder) {
_formplaceholderSupport = true;
}

if (Modernizr.input.autofocus) {
_formsautofocusSupport = true;
}

if (Modernizr.history) {
_html5historySupport = true;
}

var _fontface = false;
var _backgroundsize = false;
var _borderimage = false;
var _borderradius = false;
var _boxshadow = false;
var _flexbox = false;
var _opacity = false;
var _cssanimations = false;
var _cssgradients = false;
var _cssreflections = false;
var _csstransforms = false;
var _csstransitions = false;
var _mediaqueries = false;

if(typeof window.matchMedia == 'function'){
_mediaqueries = true;
}
if (Modernizr.fontface) {
_fontface = true;
}
if (Modernizr.backgroundsize) {
_backgroundsize = true;
}
if (Modernizr.borderimage) {
_borderimage = true;
}
if (Modernizr.borderradius) {
_borderradius = true;
}
if (Modernizr.boxshadow) {
_boxshadow = true;
}
if (Modernizr.flexbox) {
_flexbox = true;
}
if (Modernizr.opacity) {
_opacity = true;
}
if (Modernizr.cssanimations) {
_cssanimations = true;
}
if (Modernizr.cssgradients) {
_cssgradients = true;
}
if (Modernizr.cssreflections) {
_cssreflections = true;
}
if (Modernizr.csstransforms) {
_csstransforms = true;
}
if (Modernizr.csstransitions) {
_csstransitions = true;
}

</script>

<script type="text/javascript">
BOOMR.init({
		
		user_ip: "<?php echo S_REMOTE_ADDR; ?>",

		log:null,
		
		beacon_url: "<?php echo SHIKSHA_HOME; ?>/mcommon/MobileBeacon/mobilebeacon/",
		
		BW: {
			enabled: false,
			base_url: "<?php echo SHIKSHA_HOME; ?>/public/mobile/js/vendor/boomerang/images/"
		},
		
		DNS: {   
                                    enabled: false,                                      
		              base_url: "<?php echo SHIKSHA_HOME; ?>/public/mobile/js/vendor/boomerang/images/"
		}  
	});

	var t_bodyend = new Date().getTime();                        

BOOMR.plugins.RT.setTimer("t_head", t_headend - t_pagestart).
                       setTimer("t_body", t_bodyend - t_headend).  
                       setTimer("t_js", t_bodyend - t_jsstart);
                       
BOOMR.addVar("server_p_time", "<?php echo $tempForTracking;?>");
BOOMR.addVar("hml5canvas", _canvasSupport);
BOOMR.addVar("hml5video", _videoSupport);
BOOMR.addVar("hml5localstorage", _localstorageSupport);
BOOMR.addVar("hml5webworkers", _webworkersSupport);
BOOMR.addVar("hml5applicationcache", _applicationcacheSupport);
BOOMR.addVar("hml5geolocation", _geolocationSupport);
BOOMR.addVar("hml5frmdate", _formdateSupport);
BOOMR.addVar("hml5frmsautofocus", _formsautofocusSupport);
BOOMR.addVar("hml5history", _html5historySupport);
BOOMR.addVar("ip_address", ip_address);
BOOMR.addVar("session_id", session_id);
BOOMR.addVar("userid", logged_in_userid);
BOOMR.addVar("user_agent", "<?php echo $_SERVER['HTTP_USER_AGENT']; ?>");
BOOMR.addVar("boomr_pageid", "<?php if(isset($boomr_pageid)) { echo $boomr_pageid ; } else { echo  ""; } ?>");

BOOMR.addVar("mediaqueries", _mediaqueries);
BOOMR.addVar("fontface", _fontface);
BOOMR.addVar("backgroundsize", _backgroundsize);
BOOMR.addVar("borderimage", _borderimage);
BOOMR.addVar("borderradius", _borderradius);
BOOMR.addVar("boxshadow", _boxshadow);
BOOMR.addVar("flexbox", _flexbox);
BOOMR.addVar("opacity", _opacity);
BOOMR.addVar("cssanimations", _cssanimations);
BOOMR.addVar("cssgradients", _cssgradients);
BOOMR.addVar("cssreflections", _cssreflections);
BOOMR.addVar("csstransforms", _csstransforms);
BOOMR.addVar("csstransitions", _csstransitions);

<?php
if (is_array($_REQUEST) AND array_key_exists('_debug', $_REQUEST))
{
?>   
BOOMR.subscribe('before_beacon', function(o) {
	var html = "", t_name, t_other, others = [];

	if(!o.t_other) o.t_other = "";

	for(var k in o) {
		if(!k.match(/^(t_done|t_other|bw|lat|bw_err|lat_err|u|r2?)$/)) {
			if(k.match(/^t_/)) {
				o.t_other += "," + k + "|" + o[k];
			}
			else {
				others.push(k + " = " + o[k]);
			}
		}
	}

	if(o.t_done) { html += "This page took " + o.t_done + " ms to load<br>"; }
	if(o.t_other) {
		t_other = o.t_other.replace(/^,/, '').replace(/\|/g, ' = ').split(',');
		html += "Other timers measured: <br>";
		for(var i=0; i<t_other.length; i++) {
			html += "&nbsp;&nbsp;&nbsp;" + t_other[i] + " ms<br>";
		}
	}

	if(o.bw) { html += "Your bandwidth to this server is " + parseInt(o.bw*8/1024) + "kbps (&#x00b1;" + parseInt(o.bw_err*100/o.bw) + "%)<br>"; }
	if(o.lat) { html += "Your latency to this server is " + parseInt(o.lat) + "&#x00b1;" + o.lat_err + "ms<br>"; }

	var r = document.getElementById('results');
	r.innerHTML = html;

	if(others.length) {
		r.innerHTML += "Other parameters:<br>";

		for(var i=0; i<others.length; i++) {
			var t = document.createTextNode(others[i]);
			r.innerHTML += "&nbsp;&nbsp;&nbsp;";
			r.appendChild(t);
			r.innerHTML += "<br>";

		}
	}
});
<?php
}
?>
// When ready...
window.addEventListener("load",function() {
    // Set a timeout...
    setTimeout(function(){
        // Hide the address bar!
        window.scrollTo(0, 1);
    }, 0);
});
</script>

<?php
if (is_array($_REQUEST) AND array_key_exists('_debug', $_REQUEST))
{
    echo '<div id="results"></div>'; 
}
?>
<?php
	if($_GET['source'] == 'Registration' && $_GET['mmpsrc'] > 0 && $_GET['newUser'] == 1) {
		$this->load->view('registration/common/conversionTracking');
	}
?>
</html>
