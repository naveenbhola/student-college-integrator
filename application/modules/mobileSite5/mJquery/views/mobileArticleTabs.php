    <section id="tab" class="clearfix"> 
    	<table width="100%" cellpadding="0" cellspacing="0">
       	    <tr>
       	    
       	    	<?php if($blogType != 'news'):?>
					<td <?php echo ($blogType == 'allArticles')?'class="active"':''; ?>>
						<a id="overviewAllArticlesLink" href="javascript:void(0);" onclick="redirectArticle('allArticles','<?php echo $baseUrl?>' , '<?php echo $categoryId;?>')" >
						<i class="icon-latest"></i>
						Latest
						</a>
	            	</td>
		            <td <?php echo ($blogType  == 'popular')?'class="active"':''; ?>>
						<a id="overviewPopularArticlesLink" href="javascript:void(0);" onclick="redirectArticle('popular','<?php echo $baseUrl;?>' , '<?php echo $categoryId;?>')"> 
						<i class="icon-popular"></i>
						Popular
						</a>
					</td>       	    	
       	    	<?php else:?>
					<td class="" >
						<a id="overviewNewsArticlesLink">
						<i class="icon-latest"></i>
						News
						</a>
	            	</td>
       	    	
       	    	<?php endif;?>	
	            
            </tr>
        </table>
    </section>