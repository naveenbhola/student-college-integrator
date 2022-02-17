<div class="sop-left-col">
	<div class="sop-content clearfix">
		
		<?php  // Getting title heading 
		
		$title=$contentData['strip_title'];
		if(empty($topNavData['links_data'])){
				$title_heading="<h1 itemprop='headline name'> $title </h1>"; }
			else
				$title_heading="<h2  class='contentHeading-h2'> $title </h2>"; 
		echo $title_heading;
		?>
		
		<?php $this->load->view('applyContent/widget/applyContentContent'); ?>

		<?php $this->load->view('applyContent/widget/applyContentApplicationProcessWidget');
	    $this->load->view('studyAbroadArticleWidget/studyAbroadListingUnivesityWidget');?>
		<div class="article-info">
			<p>This article was written by:
				<strong><?= htmlentities($contentData['authorInfo']['firstname']." ".$contentData['authorInfo']['lastname']);?></strong>,
				<span><?php echo date("d M'y",strtotime($contentData['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($contentData['contentUpdatedAt']));?></span>
			</p>
		</div>
	<div style="margin: 12px 0 0; float: left;">
			<div class="flLt">
				<iframe
					src="https://www.facebook.com/plugins/like.php?href=<?php echo $contentData['contentURL']; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
					colorscheme=light " scrolling="no" frameborder="0"
					allowTransparency="true"
					style="border: none; overflow: hidden; width: 80px; height: 20px"></iframe>
			</div>
			<div class="flLt" style="width:80px;">
				<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</div>
	</div>
</div>
	<?php
    $this->load->view('/blogs/saContent/commentSection',array('content_id' => $contentId));
    ?>
</div>
