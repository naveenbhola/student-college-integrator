<div>			
            <?php
                foreach($snippets as $snippetNumber => $snippet) {
                    $snippetDescription = (empty($snippet['summary']) ? strip_tags($snippet['blogText']) : $snippet['summary']);
                    $snippetTitle = $snippet['blogTitle'];
                    $snippetUrl = $snippet['url'];
                    $snippetImage = str_replace('_m', '_a',$snippet['blogImageURL']);
            ?>			
			<div style="background:url(/public/images/bgSinppet.gif) repeat-x; left top;height:179px; display:none" id="snippet_<?php echo $snippetNumber; ?>">
				<div style="background:url(/public/images/bgSinppetImg.gif) no-repeat left top; height:179px;float:left;width:302px">
				<a href="<?php echo $snippetUrl; ?>" title="<?php echo $snippetTitle; ?>"><img src="<?php echo $snippetImage; ?>" style="position:relative; top:6px; left:17px" border=0 /></a>
				</div>
				<div style="background:url(/public/images/bgSinppet.gif) repeat-x; left top; height:179px;">
					<div style="line-height:6px">&nbsp;</div>
					<div style="border-right:1px solid #CAE6F4;height:163px">
						<div class="lineSpace_10">&nbsp;</div>
						<div><a class="bld fontSize_13p" href="<?php echo $snippetUrl; ?>" title="<?php echo $snippetTitle; ?>"><?php echo $snippetTitle; ?></a></div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="fontSize_12p lineSpace_18" style="padding-right:20px">
							<?php echo substr($snippetDescription, 0, 400); ?> ... &nbsp; &nbsp; <a href="<?php echo $snippetUrl; ?>" title="<?php echo $snippetTitle; ?>" class="fontSize_12p">Read More</a>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="newPagination" style="line-height:22px">
							<?php
							if(count($snippets) > 1){
							for($snippetPageNumber=0; $snippetPageNumber<count($snippets); ){
							$snippetPageClassName = '';
							if($snippetPageNumber == $snippetNumber) { $snippetPageClassName = 'selectedPage';}
							?>
							<a href="javascript:void(0);" class="<?php echo $snippetPageClassName; ?>" onclick="return showSnippet(<?php echo $snippetPageNumber .','. count($snippets); ?>);"><?php echo ++$snippetPageNumber; ?></a>
							<?php } } ?>
						</div>
					</div>
				</div>
				<div class="clear_L"></div>
			</div>
            <?php 
                }
            ?>
</div>
<script>
    function showSnippet(snippetId, snippetsCount) {
        for(var snippetIdCounter = 0; snippetIdCounter < snippetsCount; snippetIdCounter++) {
            if(snippetIdCounter == snippetId) {
                document.getElementById('snippet_'+snippetIdCounter).style.display = 'block';
            } else {
                document.getElementById('snippet_'+snippetIdCounter).style.display = 'none';
            }
        }
    }
    showSnippet(<?php echo $selectedSnippet .','. count($snippets); ?>) ;
</script>
	

