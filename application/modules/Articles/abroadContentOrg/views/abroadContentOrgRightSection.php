<?php
$totalContentCount = count($pageData['articlesData']);
$i = 0;
$commentCountStr = "";
if($pageData['articlesData'][$i]['commentCount'] != 0) {
    $commentCountStr = $pageData['articlesData'][$i]['commentCount']. ($pageData['articlesData'][$i]['commentCount'] > 1 ? " comments" : " comment");
}

$viewCountStr = "";
if($pageData['articlesData'][$i]['viewCount'] != 0) {
    $viewCountStr = $pageData['articlesData'][$i]['viewCount']. ($pageData['articlesData'][$i]['viewCount'] > 1 ? " views" : " view");
}

if($commentCountStr != "" && $commentCountStr != "") {
    $commentCountStr .= " | ";
}
?>
<div class="exam-right-col">
		    <?php if(is_array($pageData['articlesData']) && $totalContentCount) {	?>
                    <div class="about-exam-title clearwidth">
                        <strong class="title-caption"><?= $stageDetails['rightSectionHeading'];?></strong>						
                    </div>		    
                    <div class="article-display clearwidth">
                        <ul>
                            <li>
                            	<div class="full-article-block clearwidth">
                                	 <div class="article-img flLt">
                                         <a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>" target="_blank"><img title="<?=htmlentities($pageData['articlesData'][$i]['strip_title'])?>" alt="<?=htmlentities($pageData['articlesData'][$i]['strip_title'])?>" src="<?php echo str_replace("_s","_300x200",MEDIAHOSTURL.$pageData['articlesData'][$i]['contentImageURL']);?>" width="302" height="200" /></a>
                                    </div>
                                     <div class="article-detail">
                                    	<strong><a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>" target="_blank"><?php echo formatArticleTitle(htmlentities($pageData['articlesData'][$i]['strip_title']),100);?></a></strong>
                                        <div class="article-caption">
                      	                  <p><?php echo formatArticleTitle(strip_tags($pageData['articlesData'][$i]['summary']),280);?></p>
                                    	</div>
                                        <div class="article-cmnt">
                                         <?php if($commentCountStr != "") { ?><i class="abroad-exam-sprite comment-icon"></i><?php echo $commentCountStr; } ?> <?php echo $viewCountStr;?>
                                        </div>
                                        <div class="clearwidth mt20">
	                                    	<a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>"  target="_blank" class="read-more-btn" style="display:inline-block;">Read More <span>&rsaquo;</span> </a>
                                        </div>
                                    </div>
                                </div>
                            </li><?php
		    for($i = 1; $i < $totalContentCount; $i++)
		    {
			$commentCountStr = "";
			if($pageData['articlesData'][$i]['commentCount'] != 0) {
			    $commentCountStr = $pageData['articlesData'][$i]['commentCount']. ($pageData['articlesData'][$i]['commentCount'] > 1 ? " comments" : " comment");
			}
			
			$viewCountStr = "";
			if($pageData['articlesData'][$i]['viewCount'] != 0) {
			    $viewCountStr = $pageData['articlesData'][$i]['viewCount']. ($pageData['articlesData'][$i]['viewCount'] > 1 ? " views" : " view");
			}
			
			if($commentCountStr != "" && $commentCountStr != "") {
			    $commentCountStr .= " | ";
			}
			
		       if($i % 2 != 0) {
			    $class = "flLt";
			    $liOpenTag = "<li>";
			    $liCloseTag = "";
		       } else {
			    $class = "flRt";
			    $liOpenTag = "";
			    $liCloseTag = "</li>";
		       }
		       
		       echo $liOpenTag;
			    ?>                            
                                <div class="article-block <?=$class?>">
                                    <strong><a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>" target="_blank"><?php echo formatArticleTitle(htmlentities($pageData['articlesData'][$i]['strip_title']),70);?></a></strong>
                                    <div class="article-img">
                                        <a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>" target="_blank"><img title="<?=htmlentities($pageData['articlesData'][$i]['strip_title'])?>" alt="<?=htmlentities($pageData['articlesData'][$i]['strip_title'])?>" src="<?php echo str_replace("_s","_300x200",MEDIAHOSTURL.$pageData['articlesData'][$i]['contentImageURL']);?>" width="302" height="200" /></a>
                                    </div>
                                    <div class="article-caption">
                                        <p><?php echo formatArticleTitle(strip_tags($pageData['articlesData'][$i]['summary']),130);?></p>
                                    </div>
                                    
                                    <div class="article-cmnt">
                                        <?php if($commentCountStr != "") { ?><i class="abroad-exam-sprite comment-icon"></i><?php echo $commentCountStr; } ?> <?php echo $viewCountStr;?>
                                    </div>
                                    <a href="<?php echo $pageData['articlesData'][$i]['contentURL'];?>" target="_blank" class="flRt read-more-btn">Read More <span>&rsaquo;</span> </a>
				 </div>
                               <?php
		       echo $liCloseTag;
		    }   
			       ?>                            
                        </ul>
                    </div>
		   <?php } // End of if(is_array($pageData['articlesData']) && $totalContentCount) ?>
		   <div class="noResult-article about-exam-title clearwidth" style="display:none; margin-bottom:60px;">
                    	<strong class="title-caption">We couldn't find any article or guide to match your filters. </strong>
			<a href="javascript:void(0)" class="reset-btn" onclick="$j('.filter-all, .filter-value').prop('checked',true);/*setTimeout(function(){*/submitFilters()/*},100)*/;"><i class="abroad-exam-sprite reset-icon"></i>Reset all filters</a>
                    </div>
                    <?php
			if(!$ajaxRequest){
			    $this->load->view('widget/abroadContentOrgBottomWidget');
			}
		    ?>
                </div>
