<?php
$adjacents = 1;
$limit = $paginateData['perPage']; 		                        //how many items to show per page
$total = $paginateData['totalResult'];
$page  = ($paginateData['pageNo'] + 1);
$fromPage  = $paginateData['fromPage'];
$courseId =  $paginateData['courseId'];
$instituteId = $paginateData['instituteId'];
	
if($paginateData['pageNo'] >0) {
	$startResult = (($paginateData['pageNo'] * $limit) + 1);
} else {
	$startResult = 1;
}

$totalResultOnPage = ($page * $limit);

if($totalResultOnPage <= $total) {
   $totalResultOnPage = $totalResultOnPage;
} else {
   $totalResultOnPage = $total;
}
        
        
        if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;						//previous page is page - 1
	$next = $page + 1;						//next page is page + 1
	$lastpage = ceil($total/$limit);		                //lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"new-pagination clearfix\" style=\"font-size: 14px;\" id=\"new-pagination-$courseId\">";
                $pagination .= "<p style=\"font-size:16px\" class=\"flLt\">Showing $startResult - $totalResultOnPage of $total</p>";
                $pagination .= "<ul>";
		//previous button
		if ($page > 1) 
                        $pagination.= "<li class=\"prev\"><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($prev,'$fromPage',$courseId,$instituteId,this)\"><span>&lsaquo;</span> Back</a></li>";
		else
			$pagination.= "<li class=\"prev\"><a class=\"inactive\" href=\"javascript:void(0);\"><span>&lsaquo;</span> Back</a></li>";	
		
		//pages	
		if ($lastpage < 4 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
                                        $pagination.= "<li class=\"active\"><a href=\"javascript:void(0);\">$counter</a></li>";
				else
                                        $pagination.= "<li><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($counter,'$fromPage',$courseId,$instituteId,this)\">$counter</a></li>";
			}
		}
		elseif($lastpage > 3 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"javascript:void(0);\">$counter</a></li>";
					else
                                                $pagination.= "<li><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($counter,'$fromPage',$courseId,$instituteId,this)\">$counter</a></li>";
				}
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents + 2; $counter++)
				{
					if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"javascript:void(0);\">$counter</a></li>";					
					else
                                                $pagination.= "<li><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($counter,'$fromPage',$courseId,$instituteId,this)\">$counter</a></li>";
				}
			}
			//close to end; only hide early pages
			else
			{
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"javascript:void(0);\">$counter</a></li>";
					else
                                                $pagination.= "<li><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($counter,'$fromPage',$courseId,$instituteId,this)\">$counter</a></li>";
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
                        $pagination.= "<li class=\"next\"><a href=\"javascript:void(0);\" onclick=\"getCommonAnApagination($next,'$fromPage',$courseId,$instituteId,this)\">Next <span>&rsaquo;</span></a></li>";
		else
			$pagination.= "<li class=\"next\"><a href=\"javascript:void(0);\" class=\"inactive\">Next <span>&rsaquo;</span></a></li>";
		
                $pagination.= "</ul>";
                $pagination.= "</div>\n";		
	}
        echo $pagination;
?>