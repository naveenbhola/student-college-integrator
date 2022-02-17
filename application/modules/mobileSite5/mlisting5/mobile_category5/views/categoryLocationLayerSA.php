<?php
    global $countriesForStudyAbroad;
    global $countries;
    $countries4 = $locationRepository->getCountriesByRegion(4);
?>
<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="countryOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3>Choose Country/Region</h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="stream-list" id="layer-list-ul">
	<?php
	$urlRequest = clone $request;
	$urlRequest->setData(array('countryId'=>1,'regionId'=>1));
	if($request->getRegionId() != 1 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
		<div class="details" style="margin-left:0px;">
		    <h2>South East Asia</h2>
		    <span> Malaysia | Singapore | Thailand</span>
		</div>
	</li>
        <?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>2));
	if($request->getRegionId() != 2 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
		<div class="details" style="margin-left:0px;">
		    <h2>Europe</h2>
		    <span>  France | Germany | Holland | Spain | Poland</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>3));
	if($request->getRegionId() != 3 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>Middle East</h2>
		    <span> Qatar | Saudi Arabia | UAE</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>4));
	if($request->getRegionId() != 4 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>UK-Ireland</h2>
		    <span>Ireland | UK</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>5));
	if($request->getRegionId() != 5 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>New Zealand & Fiji</h2>
		    <span>NewZealand | Fiji</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>6));
	if($request->getRegionId() != 6 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>Far East</h2>
		    <span>Japan | North Korea | South Korea</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>36,'regionId'=>0));
	if($request->getCountryId() != 36){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	     <div class="details" style="margin-left:0px;">
		    <h2>China-HK, Taiwan</h2>
		    <span>China | Hong Kong | Taiwan</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>1,'regionId'=>8));
	if($request->getRegionId() != 8 || $request->getCountryId() != 1){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>Africa</h2>
		    <span>Mauritius</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>3,'regionId'=>0));
	if($request->getCountryId() != 3){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>United States</h2>
		    <span>Boston | New York | Chicago | Texas</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>5,'regionId'=>0));
	if($request->getCountryId() != 5){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>Australia</h2>
		    <span>Sydney | Melbourne | Brisbane | Perth</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('countryId'=>8,'regionId'=>0));
	if($request->getCountryId() != 8){
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	    <div class="details" style="margin-left:0px;">
		    <h2>Canada</h2>
		    <span>Toronto | Edmonton | Ottawa | Vancouver</span>
		</div>
	    </li>
	<?php
	}
	?>
	<?php
	$urlRequest->setData(array('categoryId'=>$request->getCategoryId(),'subCategoryId'=>$categoryRepository->getCrossPromotionMappedCategory($request->getSubCategoryId())->getId()));
	$urlRequest->setData(array('countryId'=>2,'regionId'=>0));
	?>
	    <li onclick="window.location='<?=$urlRequest->getURL()?>'"  style="cursor:pointer;">
	     <div class="details" style="margin-left:0px;">
		<h2>India</h2>
		<span></span>
	     </div>
	    </li>
	<?php
	?>      
    </ul>      
</section>