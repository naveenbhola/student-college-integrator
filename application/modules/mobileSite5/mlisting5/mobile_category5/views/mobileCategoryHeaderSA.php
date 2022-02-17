<script language = javascript>
var COOKIEDOMAIN = "<?php echo COOKIEDOMAIN; ?>";
</script>

<?php

$locationBuilder = new LocationBuilder;
$locationRepository = $locationBuilder->getLocationRepository();
global $cityList;
$cityList = $locationRepository->getCitiesByMultipleTiers(array(1),2);
$_COOKIE['userCity']=="All Cities";

?>

<?php
/*    if (getTempUserData('confirmation_message')){?>
        <div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
        <?php echo getTempUserData('confirmation_message'); ?>
        </div> 
<?php } 
?>
<?php 
   deleteTempUserData('confirmation_message');*/
?>

    <!-- Show the Category page Header -->    
    <header id="page-header" class="clearfix" data-role="header">
        <div class="head-group" data-enhance="false">
            <a class="head-icon" href="#mypanel"><i class="icon-menu"></i></a>
            <h1>
            	<div class="left-align">
                    <?php echo displayTextAsPerMobileResolution($category_data->getName(),1,true);?><br />
                    <p>in <span class="change-loc transparency">
		    <?php $urlRequest = clone $request;
		    if($urlRequest->getCountryId()==1){
			echo substr($locationRepository->findRegion($request->getRegionId())->getName(),0,11);
			if(strlen($locationRepository->findRegion($request->getRegionId())->getName())>11) {echo '...';}
		    } else {
			echo substr($locationRepository->findCountry($urlRequest->getCountryId())->getName(),0,11);
			if(strlen($locationRepository->findCountry($urlRequest->getCountryId())->getName())>11) {echo '...';}
		    }
		    ?>
		    <a id="lId" class="change-loc" href="#categoryAbroadLocationDiv" data-inline="true" data-rel="dialog" data-transition="slide">Change<i class="icon-right"></i></a>
		    </span></p>
                </div>
            </h1>
            <?php
            //Only if the Institute count is greater than 1 OR filters are applied, we will show the refine button
            $appliedFilters = $request->getAppliedFilters();
	    $appliedFiltersSet = sanitizeAppliedFilters($appliedFilters,true);
            if((($categoryPage->getTotalNumberOfInstitutes() > 1)) || (isset($appliedFiltersSet) && count($appliedFiltersSet)>0)){?>
            <div class="head-filter" id="showFilterButton">
                            <a id="refineOverlayOpen" href="#refineDiv" data-inline="true" data-rel="dialog" data-transition="slide" >
                                    <i class="icon-busy" aria-hidden="true"></i>
                                    <p>Filter</p>
                            </a>
            </div>
            <?php } ?>
        </div>
    </header>    
    <!-- End the Header for Category page -->
    
