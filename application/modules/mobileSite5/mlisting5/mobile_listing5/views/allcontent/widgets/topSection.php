<?php 
    $totalCountSubtext = "";
    $GA_Tap_On_Filter = 'FILTER';
    $GA_Tap_On_Sort = 'SORT';
    $GA_Tap_On_Apply_Filters = 'APPLY_FILTERS';
    $h1Text = '';
    switch ($pageType) {
        case 'articles':
            $totalCountSubtext = "News & Notifications ".$currentYear.($totalElements > 1 ? '' : '');
            break;
        case 'questions':
	    if($contentType == 'discussion'){
		$totalCountSubtext = "Discussions".($totalElements > 1 ? '' : '');
	    }
	    else{
	        $totalCountSubtext = "Answered questions".($totalElements > 1 ? '' : '');
	    }
            break;
        case 'reviews':
            $totalCountSubtext = "Reviews on Placements, Faculty & Facilities".($totalElements > 1 ? '' : '');
            break;
        
        default:
            # code...
            break;
    }
    	$totalCountText = "";
	if($allReviewsCount>0 && $pageType=='reviews'){
                $totalCountText = "(".$allReviewsCount.")";
	}
	
	if($totalElements>0 && $pageType!='reviews'){
                $totalCountText = "(".$totalElements.")";
        }
     $instituteNameWithLocation = str_replace(", "," ",$instituteNameWithLocation);
?>
<div class="new-container">
    <div class="all-cunt-card art-count no-btm">
        <h1 ><a href="<?php echo $instituteUrl;?>"><?php echo htmlentities($instituteNameWithLocation)?></a><?php echo $totalCountSubtext; ?><span class="art-count allContCountText pdf_displayNone"><?php echo $totalCountText; ?></span></h1>
        <?php if(!empty($seoHeadingText)) { ?>
        <p class="reviews-Hd-Det pdf_displayNone"><?php echo getTextfromhtml($seoHeadingText,70); ?>...<span class='rd-more-head link-blue-small' >readMore</span></p>
        <h2 class="reviews-Hd-Det" style="display: none"> <?php echo $seoHeadingText; ?> </h2>
        <?php } ?>        
        <?php if(!empty($seoHeadingText) && $pdf) { ?>
        <p class="reviews-Hd-Det"> <?php echo $seoHeadingText; ?> </p>
        <?php } ?>
    </div>

</div>

 <div class="sort-filter-sec pdf_displayNone">
            <?php if((!empty($filtersArray['Institutes']) && count($filtersArray['Institutes']) > 0 )|| (!empty($filtersArray['Courses']) && count($filtersArray['Courses']) > 0 )) { ?>
                    <a id="allcontentFilters" href="#allContentPageFilters" class="first-child" data-rel="dialog" ga-attr="<?=$GA_Tap_On_Filter;?>" data-transition="none"><i class="msprite filter-icon"></i> FILTER </a>
                    <?php } ?>
            <?php if(!empty($sortingOptions) && count($sortingOptions) > 0) {?>
            <a href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Sort;?>" onclick="openSingleSelectLayer('sortingFilters','dropDown4_input')" id="dropDown4_input"><i class="msprite sort-icon"></i> SORT BY </a>
            <div class="select-Class">
            <select name="dropDown4" class="sortingFilters" id="dropDown4" style="display:none;" onchange="updateAllContentBySort()">
                        <?php foreach($sortingOptions as $sortKey => $sortValue ){ ?>
                        <option disabled="disabled"></option>
                                <option value="<?php echo $sortValue;?>"><?php echo $sortValue;?></option>
                        <?php } ?>
                        
                </select>                     
            </div>
            <?php } ?>            
              
            <input type="hidden" name="listingId" id="listingId" value="<?php echo $listing_id;?>">
            <input type="hidden" name="listingType" id="listingType" value="<?php echo $listing_type;?>">
            <input type="hidden" name="selectedInstituteId" id="selectedInstituteId" value="<?php echo $selectedInstituteId;?>">
            <input type="hidden" name="selectedCourseId" id="selectedCourseId" value="<?php echo $selectedCourseId;?>">
            <input type="hidden" name="selectedSortingOption" id="selectedSortingOption" value="<?php echo $selectedSortOption;?>">
            <input type="hidden" name="pageType" id="pageType" value="<?php echo $pageType;?>">
            <input type="hidden" name="filters_ga" id="filters_ga" value="<?php echo $GA_Tap_On_Apply_Filters;?>">
            <input type="hidden" name="ga_sort" id="ga_sort" value="<?php echo $GA_Tap_On_Sort;?>">
            <input type="hidden" name="contentType" id="contentType" value="<?php echo $contentType?>" >
            <input type="hidden" name="selectedFilterRating" id="selectedFilterRating" value="<?php echo $selectedFilterRating?>" >
             <input type="hidden" name="selectedTagId" id="selectedTagId" value="<?php echo $selectedTagId;?>" >

</div>
