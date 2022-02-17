<?php 
$config['bsbTypes'] = array('applyPagePromotion' => true, 'applyPagePromotionCP'=>true);

//beacon tracking pagenames in bsbTypeToPageMapping
$config['bsbTypeToPageMapping'] = array(
	'applyPagePromotion' => array('articlePage', 'universityPage', 'countryPage', 'countryHomePage', 'universityRankingPage', 'courseRankingPage','shipmentWelcomePage'),
	'applyPagePromotionCP' => array('categoryPage')
);
?>