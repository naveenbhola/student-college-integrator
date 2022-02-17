<?php
class Miscelleneous	
{
    function paginateTab($urlWithoutStart,$numfound,$start,$rows,$searchCriteria)
    {
        $list="";
	if($rows == 0)
	{
          $rows =15;
	}
	$startCount=(((integer)($start/($rows*10)))*10)+1;
	
        if(isset($numfound) && $numfound > $startCount)
        {
            $list="<span class=\"normaltxt_11p_blk fontSize_12p mar_right_5p\">Result Page:</span>";
            if($start==0)
            {
                $list.="<a href=\"#\" class=\"hide\" style=\"border:1px #E6E6E6 solid;\">Prev</a> &nbsp;";
            }
            else
            {
                $list.="<a href=\"".$urlWithoutStart.($start-$rows)."/".$rows."/".$searchCriteria."\">Prev</a>";
            }
            for($i=$startCount;$i<(($numfound/$rows)+1)&&($i<=$startCount+9);$i++)
            {
                if($i==(($start/$rows)+1))
                {
                      $list.="<a href=\"".$urlWithoutStart.($rows*($i-1))."/".$rows."/".$searchCriteria."\" class=\"show\" style=\"border:1px #FD8103 solid;\">".$i."</a>&nbsp;";
                }
                else
                {
                    $list.="<a href=\"".$urlWithoutStart.($rows*($i-1))."/".$rows."/".$searchCriteria."\">".$i."</a>&nbsp;";		  	
                }
            }
            if(($start == ($numfound-($numfound%$rows))-$rows) || ($numfound < ($start+$rows)))
            {
               $list.="<a href=\"#\" class=\"hide\" style=\"border:1px #E6E6E6 solid;\">Next</a>&nbsp;";
            }
            else 
            {
                $list.="<a href=\"".$urlWithoutStart.($start+$rows)."/".$rows."/".$searchCriteria."\">Next</a>&nbsp;";
            }
        }
        else
        {
            error_log_shiksha("No Results Found");
        }
        return($list);
	}
	
	
	function paginateByAjaxTab($urlWithoutStart,$numfound,$start,$rows,$searchCriteria,$functionName,$paramlist)
    	{
        $list="";
        $startCount=(((integer)($start/($rows*10)))*10)+1;
        if(isset($numfound) && $numfound > $startCount)
        {
            $list="<span class=\"normaltxt_11p_blk fontSize_12p mar_right_5p\">Result Page:</span>";
            if($start==0)
            {
                $list.="<a href=\"#\" class=\"hide\" style=\"border:1px #E6E6E6 solid;\">Prev</a>";
            }
            else
            {
		$url = $searchCriteria==""?$urlWithoutStart.($start-$rows)."/".$rows:$urlWithoutStart.($start-$rows)."/".$rows."/".$searchCriteria;
                $list.="<a href=\"javascript:void(0)\" onClick=\"".$functionName."('".$url."',".$paramlist.");\">Prev</a>";
            }
            for($i=$startCount;$i<(($numfound/$rows)+1)&&($i<=$startCount+9);$i++)
            {
                if($i==(($start/$rows)+1))
                {
		    $url = $searchCriteria==""?$urlWithoutStart.($rows*($i-1))."/".$rows:$urlWithoutStart.($rows*($i-1))."/".$rows."/".$searchCriteria;	
                    $list.="<a href=\"javascript:void(0)\" onClick=\"".$functionName."('".$url."',".$paramlist.");\" class=\"show\" style=\"border:1px #FD8103 solid;\"><span>".$i."</span></a>";
                }
                else
                {
		    $url = $searchCriteria==""?$urlWithoutStart.($rows*($i-1))."/".$rows: $urlWithoutStart.($rows*($i-1))."/".$rows."/".$searchCriteria; 	
                    $list.="<a href=\"javascript:void(0)\" onClick=\"".$functionName."('".$url."',".$paramlist.");\">".$i."</a>";
                }
            }
            if(($start == ($numfound-($numfound%$rows))-$rows) || ($numfound < ($start+$rows)))
            {
               $list.="<a href=\"#\" class=\"hide\" style=\"border:1px #E6E6E6 solid;\">Next</a>";
            }
            else 
            {
		$url = $searchCriteria == ""?$urlWithoutStart.($start+$rows)."/".$rows:$urlWithoutStart.($start+$rows)."/".$rows."/".$searchCriteria;
                $list.="<a href=\"javascript:void(0)\" onClick=\"".$functionName."('".$url."',".$paramlist.");\"><span>Next</span></a>";
            }
        }
        else
        {
            error_log_shiksha("No Results Found");
        }
        return($list);
	}
	
	function bredCrumbs()
	{
	   $betcrumb = array(array('Discusssion',$discussionUrl));	
	
	}

	function catPageSourceInfo()
    {
		$refererURL = $_SERVER['HTTP_REFERER'];
		if($_REQUEST['profiling'] == 1){
			error_log("PAGESOURCE: SHIKSHA HOME: " . SHIKSHA_HOME);
			error_log("PAGESOURCE: REFERRER: ". $refererURL);
			error_log("PAGESOURCE: 1st: ". parse_url($refererURL, PHP_URL_HOST));
			error_log("PAGESOURCE: 2nd: ". parse_url(SHIKSHA_HOME, PHP_URL_HOST));
		}
		if(strpos(parse_url($refererURL, PHP_URL_HOST),parse_url(SHIKSHA_HOME, PHP_URL_HOST)) !== false)
			$var['int_ext'] = "internal";
		else
			$var['int_ext'] = "external";
	
		$pattern1 = "/^\/marketing\/Marketing\//";
		$pattern2 = "/\/customizedmmp\/mmp\//";
	
		$result1 = preg_match($pattern1, parse_url($refererURL, PHP_URL_PATH));
		$result2 = preg_match($pattern2, parse_url($refererURL, PHP_URL_PATH));
	
		if($result1 || $result2)
			$var['marketing'] = "true";
		else
			$var['marketing'] = "false";
	
		return($var);
    }
}
?>
