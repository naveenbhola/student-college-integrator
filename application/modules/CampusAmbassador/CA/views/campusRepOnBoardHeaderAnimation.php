<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.hoverintent.minified'); ?>"></script>
<script>
var headingShown = 0;
var timeFrame = 800;
$j( "#heading1" ).hoverIntent(
  function() {
	if (headingShown!=1) {
		headingShown = 1;
		$j('#heading2Tooltip,#heading3Tooltip').stop(true,true).hide();	
		$j('#heading2,#heading3').hide();
		setTimeout(function(){$j('#heading1Tooltip').show(timeFrame/2)},20);
	}
  }
);

$j( "#heading2" ).hoverIntent(
  function() { 
	if (headingShown!=2) {
		headingShown = 2;	
		$j('#heading1Tooltip,#heading3Tooltip').stop(true,true).hide();
		$j('#heading1,#heading3').hide();
		setTimeout(function(){$j('#heading2Tooltip').show(timeFrame/2)},20);
	}
  }
);

$j( "#heading3" ).hoverIntent(
  function() { 
	if (headingShown!=3) {
		headingShown = 3;	
		$j('#heading2Tooltip,#heading1Tooltip').stop(true,true).hide();
		$j('#heading1,#heading2').hide();
		setTimeout(function(){$j('#heading3Tooltip').show(timeFrame/2)},20);
	}
  }
);

$j( "#headerTop" ).hoverIntent(
  function() {},
  function() {
	resetHeader();
  }
);

function resetHeader(){
	if (headingShown==1) {
		headingShown = 0;
		$j('#heading1Tooltip').stop(true,true).hide();
		setTimeout(function(){ $j('#heading2,#heading3').show(timeFrame); },20);		
		setTimeout(function(){$j('#heading1Tooltip').hide()},timeFrame/2);
	}
	if (headingShown==2) {
		headingShown = 0;
		$j('#heading2Tooltip').stop(true,true).hide();
		setTimeout(function(){ $j('#heading1,#heading3').show(timeFrame); },20);		
		setTimeout(function(){$j('#heading2Tooltip').hide()},timeFrame/2);
	}
	if (headingShown==3) {
		headingShown = 0;
		$j('#heading3Tooltip').stop(true,true).hide();
		setTimeout(function(){ $j('#heading1,#heading2').show(timeFrame); },20);		
		setTimeout(function(){$j('#heading3Tooltip').hide()},timeFrame/2);
	}
}
</script>

