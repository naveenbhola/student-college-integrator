<?php
if($institutes)
{ ?>
    
    <h1 class="search-result-title clear-width">
        <strong>
            <?php 
                            $appliedFilters = $request->getAppliedFilters();
            global $locationname;
            $singularPluralInstitute = ($categoryPage->getTotalNumberOfInstitutes() > 1) ? 'Colleges' : 'College';
            if(!$request->isAjaxCall()) {
                // if(is_array($appliedFilters['city'])) {
                //     echo $categoryPage->getTotalNumberOfInstitutes()." ".(($request->getSubCategoryId() == 56)?'Engineering':$request->getSubCategoryName())." $singularPluralInstitute Found";
                // }
                // else {
                    echo $categoryPage->getPageHeadingTextForRNR($locationname,$pageTitleForFilters,$isSourceRegistration);
                //}
            }
            else  {
                echo $categoryPage->getTotalNumberOfInstitutes()." ".(($request->getSubCategoryId() == 56)?'Engineering':$request->getSubCategoryName())." $singularPluralInstitute Found";
            }
            ?>
        </strong>
	</h1>
    <?php 
    
}
else
{
    if($request->getPageNumberForPagination() > 1) {
        $urlRequest = clone $request;
        $urlRequest->setData(array('pageNumber'=>1));
        $url = $urlRequest->getURL();
        header("location:".$url);
    }
    else {
        echo '<h1 class="no-result clear-width">No results found. Please change your criteria.</h1>';
    }
}
?>
<div style="float:left" id="belowTitleDiv"></div>
<div style="clear: both;"></div>
