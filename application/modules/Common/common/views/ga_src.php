<script>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-4454182-1']);
	_gaq.push(['_setDomainName', 'shiksha.com']);
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
