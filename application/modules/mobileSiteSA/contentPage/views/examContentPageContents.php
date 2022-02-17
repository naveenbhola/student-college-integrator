<div itemscope itemtype="http://schema.org/Article">
<?php $this->load->view('contentPage/widgets/topNavBar'); ?>
<div class="exm-cntnt wrapper clearfix" itemprop="articlebody">
	<div class="exm-cntnt-wrp clearfix">
    <div  class="newExam-page">	
    	<h2><?php echo htmlentities($contentDetails['strip_title']);?></h2>	
    	<?php echo $contentDetails['sections']['0']['details']; ?> 
    </div>
<?php if(count($relatedArticleData) > 0){ ?>
    <div class="mid-exm-widget clearfix">
	<div class="newExam-widget">
	        	<h2>
	                	<div class="widget-head"><p>Related articles <i class="sprite blue-arrw"></i></p></div>
	            </h2>
	            <ul id="sameExamArticles">
	            	<?php foreach($relatedArticleData as $data){?>
	            	<li>
	                	<div class="examArtcle-img"><a href="<?php echo $data['contentURL']; ?>"><img height="50" width="75" src="<?php echo str_replace("_s", "_75x50", $data['contentImageURL']); ?>"></a></div>
	                    <div class="examArtcle-link"><a href="<?php echo $data['contentURL']; ?>"><?php echo htmlentities(formatArticleTitle($data['strip_title'],150)); ?></a></div>
	                </li>
	                <?php } ?>
	            </ul>
	</div>
	</div>
<?php } ?>
<div class="newExam-page">
<?php echo $contentDetails['description2']; ?>
</div>
<?php $this->load->view('contentPage/widgets/examContentDownloadBrochure');?>
<div class="detail-widegt-sec clearfix">
<div class="detail-info-sec clearfix">
<div class="more-app-process-sec" style="margin:0px;">
            <p>This article was written by: <span itemprop="author"><?= htmlentities($contentDetails['username']);?></span>, <span style="color:#999"><?php echo date("d M'y",strtotime($contentDetails['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($contentDetails['contentUpdatedAt']));?></span></p>
	</div>
	<div>
		<div class="flLt">
			<iframe
				src="https://www.facebook.com/plugins/like.php?href=<?php echo $contentDetails['contentURL']; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
				colorscheme=light " scrolling="no" frameborder="0"
				allowTransparency="true"
				style="border: none; overflow: hidden; width: 70px; height: 20px"></iframe>
		</div>
		<div class="flLt">
			<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>							
		
	</div>
</div>
</div>
</div>
    </div>
<div class="detail-widegt-sec exm-cntnt-wrp clearfix">
<?php $this->load->view('widgets/browseUniversity'); ?>
</div>
<div class="detail-widegt-sec exm-cntnt-wrp clearfix">
<?php
$this->load->view('contentPage/widgets/commentsSection');
?>

</div>
<div class="exm-cntnt-wrp clearfix">
<?php if(!empty($relatedArticles)){  $this->load->view('contentPage/widgets/examContentRelatedArticlesSlider'); }?>
</div>

<?php
if(strtotime($contentDetails['contentUpdatedAt'])>=strtotime($contentDetails['created'])){
    $dateTimeToBeUsed=$contentDetails['created'];
}else{
    $dateTimeToBeUsed=$contentDetails['contentUpdatedAt'];
}
$datetime = new DateTime($dateTimeToBeUsed);
$isoDateTime = $datetime->format(DateTime::ATOM);
?>
<meta itemprop="datePublished" content="<?php echo $isoDateTime; ?>"/>   	
<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
    <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
        <meta itemprop="url" content="http://www.shiksha.com/public/images/logo-abroad.gif">
        <meta itemprop="width" content="190">
        <meta itemprop="height" content="53">
    </div>
    <meta itemprop="name" content="studyabroad.shiksha.com">
</div>
<?php if (isset($contentDetails['contentImageURL']) && !empty($contentDetails['contentImageURL'])){?>
 <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <meta itemprop="url" content="<?php echo $contentDetails['contentImageURL']; ?>">
      <?php 
        if(($imageInfo=getimagesize($contentDetails['contentImageURL']))){
            ?>
            <meta itemprop="width" content="<?php echo $imageInfo[0] ?>">
            <meta itemprop="height" content="<?php echo $imageInfo[1] ?>">
        <?php }
    ?>
</div>
   <?php
 }
	$this->load->view('contentPage/widgets/stickyContentBrochureDownload');
?>
</div>