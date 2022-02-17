<div class="author-articles">
<ul>
<?php

$i =-1;
foreach($articles as $article) {
    $i++;
    $fullTitle= $article['blogTitle'];
    $content = strip_tags($article['summary']);
	if(strlen($content) > 200){
		$content = preg_replace('/\s+?(\S+)?$/', '',substr($content, 0,200))."...";
	}
    $url = $article['url'];
    $title = $article['blogTitle'];
    $articleImage = $article['blogImageURL'] == '' ? '/public/images/articlesDefault_s.gif' : $article['blogImageURL'];
    $article['creationDate']=date("dS M, Y", strtotime($article['creationDate']));
   
?>

                    <li >
                    	<div class="article-figure"><img src="<?php echo MEDIA_SERVER.$articleImage; ?>"/></div>
                        <div class="article-details">
                            <a title="<?= $title; ?>" href="<?= $url;?>"><?= $title; ?></a>
                            <p><?=$content ;?></p>
                            <p class="published-date">Published on: <span><?= $article['creationDate'];?></span></p>
                        </div>
                    </li>
                    <?php
    }
?>

                </ul>
            </div>
	    
                
                
          <!---      <div align="right" class="lineSpace_25" style="margin-bottom:3px;">
		    	    <span style="margin-right:22px;">
			            <span class="pagingID" id="paginataionPlace2"><?php echo preg_replace('/\/0\/20|\/0\/50/','',$paginationHTML);?></span>
				    </span>
				    <span class="normaltxt_11p_blk bld pd_Right_6p" align="right" id="countOffset_DD2"></span>
    				<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
	    			    <select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $totalArticles > 5 ?'inline' : 'none'; ?>">
		    			    <option value="5" <?php// echo $number5;?>>5</option>
			    			<option value="10" <?php// echo $number10;?>>10</option>
				    		<option value="15" <?php //echo $number15;?>>15</option>
					    	<option value="20" <?php// echo $number20;?>>20</option>
    					</select>
				</span>
		</div>-->
         


