<div class="col-lg-4 pL0">
	<div class="slidrWidgt01 slidrWidgt001 featuredArticleHome">
		<span class="slidrBtnNav">
			<a class="prev_Slide" >
				<i class="icons ic_sldr_prv"></i>
			</a>
			<a class="next_Slide">
				<i class="icons ic_sldr_nxt"></i>
			</a>
		</span>
		<div class="slidrWidgt_inner">
		<h1 class="wgt_HeadT1">Featured Articles</h1>
			<ul>
			 <?php foreach ($featuredArticle as $carousel):
	 			
		 		$condNewWindow = "";
		 		$carouselUrl   = "";
		 		if($carousel['carousel_open_new_window'] == 'YES') {
		 			 $condNewWindow = "target='blank'";
		 		}
		 		$destinationUrl = $carousel['carousel__destination_url'];
		 		if(strpos($destinationUrl, "http://") === 0) {
		 			$carouselUrl = $destinationUrl;
		 		}else{
		 			$carouselUrl = "http://".$destinationUrl;
		 		}

		 		$newtext = substr($carousel['carousel_description'],0,150);
				$newtext = preg_replace("/[\\\]/",'',$newtext);

			 ?>
				<li>
					<div class="w01_head">
						<a href="<?=$carouselUrl?>" <?=$condNewWindow?>  trackaction="homepagecontentwidget">
							<p class="wgt_HeadT2">
								<?php echo preg_replace("/[\\\]/",'',$carousel['carousel_title']);?>
							</p>
						</a>
					</div>
					<div class="w01_mid">
						<a href="<?=$carouselUrl?>" <?=$condNewWindow?> trackaction="homepagecontentwidget" tracklable="<?php echo 'image_'.$destinationUrl;?>">
							<img data-original="<?php echo $carousel['carousel_photo_url'];?>" class="w01_fullimg lazy" />
						</a>
					</div>
					<div class="w01_fot">
						<a href="<?=$carouselUrl?>" <?=$condNewWindow?> trackaction="homepagecontentwidget"><p class="wgt_HeadT3"><?=$newtext?></p></a>
						<a class="n-knowMore" href="<?=$carouselUrl?>"  <?=$condNewWindow?> trackaction="homepagecontentwidget" tracklable="KNOW_MORE_CONTENT_BUTTON">Know More</a>
					</div>
				</li>
			<?php endforeach;?>  			
			</ul>
		<p class="clr"></p>
	</div>
	</div>
</div>