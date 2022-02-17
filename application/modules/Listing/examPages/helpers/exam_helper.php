<?php

function formatDatesForResultDeclarationSection($resultStartDate,$resultEndDate){

	$startUnixTime                = strtotime($resultStartDate);
	$formatDate['startMonthName'] = strtoupper(date("M",$startUnixTime));
	$formatDate['startDateNum']   = date("d",$startUnixTime);
	
	
	
	$endUnixTime                  = strtotime($resultEndDate);
	$formatDate['endMonthName']   = strtoupper(date("M",$endUnixTime));
	$formatDate['endDateNum']     = date("d",$endUnixTime);

	return $formatDate;

}
// @output - retrun className based on file extension
function getFileType($fileUrl){
	$extList  = array('pdf'=>'__pdf_file','jpg'=>'__jpg_file','jpeg'=>'__jpg_file','png'=>'__png_file','xls'=>'__xls_file','xlsx'=>'__xls_file','doc'=>'__docx_file','docx'=>'__docx_file','zip'=>'__zip_file');
	$fileInfo = pathinfo($fileUrl); 
	$ext      = strtolower($fileInfo['extension']);
	return $extList[$ext];
}

function pagination($totalItems, $currentPage, $pageSize=10, $maxPages=5) {
    // calculate total pages
    $totalPages = ceil($totalItems / $pageSize);

    // ensure current page isn't out of range
    if ($currentPage < 1) {
        $currentPage = 1;
    } else if ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }

    $startPage=0; $endPage =0;
    if ($totalPages <= $maxPages) {
        // total pages less than max so show all pages
        $startPage = 1;
        $endPage = $totalPages;
    } else {
        // total pages more than max so calculate start and end pages
        $maxPagesBeforecurrentPage = floor($maxPages / 2);
        $maxPagesAftercurrentPage = ceil($maxPages / 2) - 1;
        if ($currentPage <= $maxPagesBeforecurrentPage) {
            // current page near the start
            $startPage = 1;
            $endPage = $maxPages;
        } else if ($currentPage + $maxPagesAftercurrentPage >= $totalPages) {
            // current page near the end
            $startPage = $totalPages - $maxPages + 1;
            $endPage = $totalPages;
        } else {
            // current page somewhere in the middle
            $startPage = $currentPage - $maxPagesBeforecurrentPage;
            $endPage = $currentPage + $maxPagesAftercurrentPage;
        }
    }

    // calculate start and end item indexes
    $startIndex = ($currentPage - 1) * $pageSize;
    $endIndex = min(startIndex + $pageSize - 1, $totalItems - 1);
  
    for($i=0;$i<($endPage + 1) - $startPage;$i++){
    	$pages[] =$startPage + $i;
    }

    // return object with all pager properties required by the view
    return array('totalItems'=>  $totalItems,
            'currentPage'=>  $currentPage,
            'pageSize'=>  $pageSize,
            'totalPages'=>  $totalPages,
            'startPage'=>  $startPage,
            'endPage'=>  $endPage,
            'startIndex'=>  $startIndex,
            'endIndex'=> $endIndex,
            'pages'=> $pages);
}


