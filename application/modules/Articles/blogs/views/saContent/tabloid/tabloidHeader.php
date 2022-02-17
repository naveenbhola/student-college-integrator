<script>
	var TIME_DELAY_FOR_PDF_DOWNLOAD = '<?php echo TIME_DELAY_FOR_PDF_DOWNLOAD;?>';
</script>
<?php 
	$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
	$urlContent = urlencode($constructed_url);
?>
		
		<?php if($content['data']['type'] == 'article'):?>
		<?php if($loadedForTabloid !=1){ ?>
		<div style="width: 13%;float: right;padding: 15px 0px;text-align: center;background-color: #eee;border-radius: 70px; color:#999;">
			<span style=" font: 48px bold Verdana, Geneva, sans-serif; ">+<?=(count($popularArticles)>0?count($popularArticles):count($moreArticles))?></span><br> more articles
		</div>
		<?php } ?>
		<h1 style="font-size:50px !important; width:87%;" itemprop="name"><?php echo htmlentities($content['data']['strip_title']) ; ?></h1>
		<!--<meta itemprop="articleBody" content="<?php //echo html_escape(strip_tags($content['data']['summary'])); ?>
				<?php //foreach($content['data']['sections'] as $index => $data ):?>
                		<?php //echo html_escape(strip_tags($data['heading'])) ;?><?php //echo html_escape(strip_tags($data['details']));?>
				<?php //endforeach;?>" />-->
		<?php else:?>
		<h1 style="font-size:50px !important; width:87%;"><?php echo htmlentities($content['data']['strip_title']) ; ?></h1>   		
   		<?php endif;?>		
   		<a rel="author" href="<?=$authorUrl; ?>"></a>
		<div class="author-details flLt" style="margin-top:10px;">By: <strong>
			<?php if($content['data']['type'] == 'article'):?>
			<span itemprop="author" itemscope itemtype="http://schema.org/Person"> 
				<span itemprop="name">         	
					<?php echo $content['data']['username']; ?>
				</span>
			</span>	 			
			<?php else:?>
				<?php echo $content['data']['username']; ?>
			<?php endif;?>
			,</strong>
			<?php if($content['data']['type'] == 'article'):?>
			<span itemprop="datePublished" content="<?php echo date('y-m-d',strtotime($content['data']['contentUpdatedAt']));?>T<?php echo date('h:i A',strtotime($content['data']['contentUpdatedAt'])); ?>"> <?php echo date("d M'y",strtotime($content['data']['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($content['data']['contentUpdatedAt']));?>
			</span>
			<?php else:?>
			<span> <?php echo date("d M'y",strtotime($content['data']['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($content['data']['contentUpdatedAt']));?>
			</span>
			<?php endif;?>
		</div>
		
		<div style="margin: 7px 0 0;" class = "flRt">
			<div class="flLt" style="margin-right:5px;">
				<iframe
					src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlContent; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
					colorscheme=light " scrolling="no" frameborder="0"
					allowTransparency="true"
					style="border: none; overflow: hidden; width: 80px; height: 20px"></iframe>
			</div>
			<div class="flLt" style="width:70px;">
				<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</div>							
			
		</div>
		<div class="flLt commnt-sec" style="margin-left:15px;"><i class="article-sprite commnt-icon"></i>
   		<?php if($content['data']['commentCount'] > 0 ):?>			
        <a href="javascript:void(0);" onclick="scrollComments('');" > <?php echo $content['data']['commentCount']; ?> <?php echo  ($content['data']['commentCount'] > 1)?'comments':'comment';?></a>
        <?php else:?>
        <a href="javascript:void(0);" onclick="scrollComments('<?php echo $content['data']['content_id']; ?>');" > Post your comment </a>
        <?php endif;?>
        </div>
        <div class="clearfix"></div>