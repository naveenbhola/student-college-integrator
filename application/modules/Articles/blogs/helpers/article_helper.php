<?php
function createSEOMetaTagsForArticle($base_url,$blogId,$pageNum,$resultCount,$type='new'){
				$result = array();
				if($type=='old'){
					/**Canonical Url**/
					$result['canonicalURL'] = $base_url.'?token=aa&page='.$pageNum;
					if($resultCount>$pageNum){
						$result['nexturl'] = $base_url.'?token=aa&page='.($pageNum+1);
						if($pageNum>1){
							$result['previousurl'] = $base_url.'?token=aa&page='.($pageNum-1); 
						}else{
							$result['previousurl'] = '';
						}
					}else if($resultCount==$pageNum){
				       /**If total number of page count is equal to current page number then create next
					  page url and if current page is not first page then create previous page url**/
					   if($pageNum<=1){
						$result['nexturl'] = '';
						$result['previousurl'] = '';
					   }else{
						$result['nexturl'] = '';
						$result['previousurl'] = $base_url.'?token=aa&page='.($pageNum-1);
					   }
					}else{
						$result['nexturl'] = '';
						$result['previousurl'] = '';
						$result['canonicalURL'] = '';
					}
				}else{
					$result['canonicalURL'] = $base_url.'-article-'.$blogId;
				       if($resultCount>$pageNum){
				      /**If total number of page count is greater than current page number then create next
				      page url and if current page is not first page then create previous page url**/
				      $result['nexturl'] = $base_url.'-article-'.$blogId.'-'.($pageNum+1);
				      if($pageNum!=1){
					$result['previousurl'] = $base_url.'-article-'.$blogId.'-'.($pageNum-1);
				      }else{
					$result['previousurl'] = '';
				      }
					}else if($resultCount==$pageNum){
				   /**If total number of page count is equal to current page number then create next
				      page url and if current page is not first page then create previous page url**/
					   if($pageNum==1){
					$result['nexturl'] = '';
					$result['previousurl'] = '';
					  }else{
					$result['nexturl'] = '';
					$result['previousurl'] = $base_url.'-article-'.$blogId.'-'.($pageNum-1);
					 }
					}else{
					$result['nexturl'] = '';
					$result['previousurl'] = '';
					$result['canonicalURL'] = '';
					}
	
				}
				return $result;
}

function createSEOMetaTagsForAuthorProfilePage($startOffset,$countOffset,$baseUrl,$resultCount,$separator='-',$pageNumberToBeDisplayed){
		$result = array();
                $pageNumber=$startOffset/$countOffset+1;
		if($pageNumber-1==0){				
                   $result['previousURL'] = '';
		}
		else if($pageNumber-1==1){
				$result['previousURL'] = $baseUrl;	
		}
		else{
                      $result['previousURL'] = $baseUrl.$separator.($pageNumber-1);	
		}
		if($resultCount>($startOffset+$countOffset)){
		      $result['nextURL'] = $baseUrl.$separator.($pageNumber+1);
		}else{
		      $result['nextURL'] = '';
		}
		if($pageNumberToBeDisplayed!=1){$pageNumber='';$separator='';}
		$result['canonicalURL'] = $baseUrl;
		return $result;
}

function createSEOMetaTagsForFlavouredAndLatestArticles($startOffset,$countOffset,$baseUrl,$resultCount,$separator='/'){
		$result = array();
		if($_SERVER['QUERY_STRING']!='')
				$queryString='?'.$_SERVER['QUERY_STRING'];
		if($startOffset<=0 || ($startOffset%$countOffset!=0)){
			$result['canonicalURL'] = $baseUrl.$queryString;
			$result['previousURL'] = '';
		}
        else{
			$result['canonicalURL'] = $baseUrl.$separator.$startOffset.$separator.$countOffset.$queryString;
			if($startOffset-$countOffset==0){
				 $result['previousURL'] = $baseUrl.$queryString;
			}else{
				$result['previousURL'] = $baseUrl.$separator.($startOffset-$countOffset).$separator.$countOffset.$queryString;
			}
		}
		if($resultCount>($startOffset+$countOffset)){
		      $result['nextURL'] = $baseUrl.$separator.($startOffset+$countOffset).$separator.$countOffset.$queryString;
		}else{
		      $result['nextURL'] = '';
		}
		return $result;
}

function addAltText($title,$description){
    $dom = new DOMDocument;
    $dom->loadHTML($description);

    $anchors = $dom->getElementsByTagName('img');
    $html = '';

    if($anchors->length > 0)
    {
        foreach($anchors as $anchor)
        {
                $rel     = array();
                if ($anchor->hasAttribute('alt') AND ($relAtt = $anchor->getAttribute('alt')) !== '')
                {
                        $rel = preg_split('/\s+/', trim($relAtt));
                }
                // if alt tag is not their is url then insert  the title
                if(count($rel)<=0)
                {
                        $rel[] = $title;
                }
                // set the alt and src atrribute in image tag
                $anchor->setAttribute('alt', implode(' ', $rel));
        }

        foreach($dom->getElementsByTagName('body')->item(0)->childNodes as $element) {
                $html .= $dom->saveHTML($element);
        }
    }
    else{
            $html = $description;
    }

    return $html;
}


?>
