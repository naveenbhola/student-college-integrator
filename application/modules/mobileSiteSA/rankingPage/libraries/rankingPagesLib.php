<?php
class RankingPagesLib {
    
    private $CI = '';
    
    function __construct(){
        $this->CI = &get_instance();
    }

    public function validateMobileRankingPageURL($recommendedUrl,$pageNumber)
    {
        $userEnteredURL = getCurrentPageURLWithoutQueryParams();
        //check for non numeric values and check for valid pagination number
        if(!is_numeric($pageNumber)){ 
            redirect($recommendedUrl, 'location', 301);
		}
		$newUrl = $recommendedUrl;
		if($pageNumber >1){
			$newUrl = $recommendedUrl."-".$pageNumber;
		}
        if($userEnteredURL != $newUrl && $newUrl != "") 
        {
            redirect($newUrl, 'location', 301);
        }
         	return;
    }

    public function validatePaginationNumber($recommendedUrl,$pageNumber,$totalRankingTuplesCount,$sliceNumber)
    {
    	
    	if($sliceNumber >1)
    	{
        	redirect($recommendedUrl, 'location', 301); 
    	}
        if($pageNumber>1)
        {
			//we are deducting 2 here because for frist page tuple we are adding RANKING_PAGE_FIRSTPAGE_COUNT contsant seprately 
        	$pg = $pageNumber-2;
        	$resultsToBeShown =RANKING_PAGE_FIRSTPAGE_COUNT +(RANKING_PAGE_TUPLE_COUNT*$pg);
        	if($resultsToBeShown > $totalRankingTuplesCount) redirect($recommendedUrl, 'location', 301);
        }
    }
}    
?>
