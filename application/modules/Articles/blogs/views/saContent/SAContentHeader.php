<script>
	var TIME_DELAY_FOR_PDF_DOWNLOAD = '<?php echo TIME_DELAY_FOR_PDF_DOWNLOAD;?>';
</script>
<?php 
	$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
	$urlContent = urlencode($constructed_url);
?>
		
		<?php  // Getting title heading
		$title=$content['data']['strip_title'];
		if(empty($levelTwoNavBarData)){
				$title_heading="<h1 itemprop='headline name'> $title </h1>"; }
			else
				$title_heading="<h2  class='artGuide-title'> $title </h2>"; 
		echo $title_heading;
		?>
		<!--<meta itemprop="articleBody" content="<?php //echo html_escape(strip_tags($content['data']['summary'])); ?>
				<?php //foreach($content['data']['sections'] as $index => $data ):?>
                		<?php //echo html_escape(strip_tags($data['heading'])) ;?><?php //echo html_escape(strip_tags($data['details']));?>
				<?php //endforeach;?>" />-->
		
   		
   						<div style="margin: 7px 0 0; float: left;">
							<div class="flLt">
								<iframe
									src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlContent; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
									colorscheme=light " scrolling="no" frameborder="0"
									allowTransparency="true"
									style="border: none; overflow: hidden; width: 80px; height: 20px"></iframe>
							</div>
							<div class="flLt" style="width:80px;">
								<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
							</div>							
							
   					</div>
   		<?php if($content['data']['commentCount'] > 0 ):?>			
        <div class="flLt commnt-sec"><i class="article-sprite commnt-icon"></i><a href="javascript:void(0);" onclick="scrollComments('');" > <?php echo $content['data']['commentCount']; ?> <?php echo  ($content['data']['commentCount'] > 1)?'comments':'comment';?></a></div>
        <?php else:?>
        <div class="flLt commnt-sec"><i class="article-sprite commnt-icon"></i><a href="javascript:void(0);" onclick="scrollComments('<?php echo $content['data']['content_id']; ?>');" > Post your comment </a></div>
        <?php endif;?>
        
        <div class="clearfix"></div>
	
	<a rel="author" href="<?=$authorUrl; ?>"></a>
         <div class="author-details" style="margin-top:10px;">By: <strong>
         			<?php echo $content['data']['username']; ?>
	 	
	 ,</strong>
	
	  <span  content="<?php echo date('y-m-d',strtotime($content['data']['contentUpdatedAt']));?>T<?php echo date('h:i A',strtotime($content['data']['contentUpdatedAt'])); ?>"> <?php echo date("d M'y",strtotime($content['data']['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($content['data']['contentUpdatedAt']));?>
	  </span>
             <?php
             $datetime = new DateTime($content['data']['created']);
             $isoDateTime=$datetime->format(DateTime::ATOM);
             ?>
             <meta itemprop="datePublished" content="<?php echo $isoDateTime;?>"/>  
             <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                 <meta itemprop="url" content="<?php echo $content['data']['contentImageURL'];?>">
                   <?php 
                if(($imageInfo=getimagesize($content['data']['contentImageURL']))){
                    ?>
                    <meta itemprop="width" content="<?php echo $imageInfo[0] ?>">
                    <meta itemprop="height" content="<?php echo $imageInfo[1] ?>">
                <?php }
                    ?>
             </div>
             <div itemprop="author" itemscope itemtype="http://schema.org/Person"> 
             <meta itemprop="name" content="<?php echo $content['data']['username']; ?>"/>
         	 </div>       	
	     </div>	 	
             <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                 <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                 <meta itemprop="url" content="http://www.shiksha.com/public/images/logo-abroad.gif">
                 <meta itemprop="width" content="190">
                 <meta itemprop="height" content="53">
                 </div>
                 <meta itemprop="name" content="studyabroad.shiksha.com">
             </div>
	  
	  
