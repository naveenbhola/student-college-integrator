<?php
// The Id of the experiment running on the page
$experimentId = 'HXI-bmEsQ1GATjfhve2i_Q';

// The variation chosen for the visitor
$chosenVariation = rand(0,2);

?>


<html>
	<head>

		<!-- 1. Load the Content Experiments JavaScript Client -->
		<script src="//www.google-analytics.com/cx/api.js"></script>



		<!-- 2. Set the chosen variation for the Visitor (Dynamically in this case) -->
		<script>
		  cxApi.setChosenVariation(
		    <?php echo  $chosenVariation; ?>,             // The index of the variation shown to the visitor
		    <?php echo  $experimentId ?>                 // The id of the experiment the user has been exposed to
		  );
		</script>
		<!-- End Set Experiment Values -->

		<script>
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-4454182-1']);
			//_gaq.push(['_setDomainName', 'localshiksha.com']);
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
		<?php if($chosenVariation == 0) {?>
			<h1> ORIGINAL PAGE </h1>
			<?php } elseif($chosenVariation == 1) { ?>
			<h1> VARIATION PAGE 1</h1>
			<?php } elseif($chosenVariation == 2) { ?>
			<h1> VARIATION PAGE 2</h1>
			<?php } ?>
		<button onclick="alert('clicked')">CLICK ME</button>
	</body>

</html>