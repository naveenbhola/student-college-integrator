<html>
	<head>
<!-- Google Analytics Content Experiment code -->
<script>function utmx_section(){}function utmx(){}(function(){var
k='8943562-3',d=document,l=d.location,c=d.cookie;
if(l.search.indexOf('utm_expid='+k)>0)return;
function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
</script><script>utmx('url','A/B');</script>
<!-- End of Google Analytics Content Experiment code -->
		<!-- ga code-->
		<script>
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-4454182-1']);
			//_gaq.push(['_setDomainName', 'shiksha.com']);
			_gaq.push(['_deleteCustomVar', 1]);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
			function _pageTracker (type) {
				this.type = type;
				this._trackEvent = function(a,b,c) {
					_gaq = _gaq||[];
				   _gaq.push(['_trackEvent', a, b, c]);
				};
				this._trackPageview = function(a) {
					_gaq = _gaq||[];
					if(typeof(a) == 'undefined'){
						_gaq.push(['_trackPageview']);
					}else{
						_gaq.push(['_trackPageview', a]);
					}
				};
				this._setCustomVar = function(a,b,c,d) {
					_gaq = _gaq||[];
				   _gaq.push(['_setCustomVar', a, b, c, d]);
				};
				this._trackEventNonInteractive = function(a,b,c,d,e) {
					_gaq.push(['_trackEvent', a, b, c,d,e]);
				};
			}
			var pageTracker = new _pageTracker();
		</script>
		  
	</head>
	<body>
		<?php if($_GET['version']  ==  1){?>
		<div id="new">NEW VIEW</div>
		<?php } else { ?>
		<div id="original">ORIGINAL VIEW</div>
		<?php }?>
		<button onclick="alert('clicked')">CLICK ME</button>
	</body>
</html>