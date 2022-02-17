<img id="shikshaTrackingImage" src="" alt="" height="1" width="1"/>
<script>
$j(document).ready(function(){
	$j('#shikshaTrackingImage').attr('src','/tracking/trackPageLoad/<?=$url?>/<?=$referalUrl?>/'+Math.floor(Math.random() * 1000000));
}
);
if(typeof(pageTracker) != 'undefined' && typeof(personalized) != 'undefined' &&  personalized != ''){
	var gnbElements = {};
	pageTracker._trackEvent(personalized, 'open', personalized);
	if(personalized == 'personalized'){
		gnbElements = $j('#header').find('a');
	}else{
		gnbElements = $j('#top-nav').find('a');
	}
	var menuelements = $j('#categorysearchoverlay').find('a');
	
	gnbElements.on("mouseover", function(){
		pageTracker._trackEvent(personalized, 'navigation-hover', $j(this).html());
	});
	gnbElements.on("click", function(){
		pageTracker._trackEvent(personalized, 'navigation-click', $j(this).html());
	});
	
	menuelements.on("mouseover", function(){
		pageTracker._trackEvent(personalized, 'menu-hover', $j(this).html());
	});
	menuelements.on("click", function(){
		pageTracker._trackEvent(personalized, 'menu-click', $j(this).html());
	});
}
</script>