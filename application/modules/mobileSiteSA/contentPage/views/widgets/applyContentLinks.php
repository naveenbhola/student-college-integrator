<?php $iconList = array(1=>'sop-icn',2=>'lor-icn',3=>'essay-icn',4=>'cv-icn',5=>'visa-icn');?>

<section  class="detail-widget"  data-enhance="false">
	<div class="detail-widegt-sec clearfix">
		<div class="detail-info-sec clearfix" style="margin-bottom:0px;padding-bottom:0px;">
			<div class="more-app-process-sec" style="margin:0px;">
				<?php if(count($learnApllicationProcessData)>0){?>
				<strong>Learn more about application process</strong>
				<ul style="margin-top: 38px;">
				<?php foreach($learnApllicationProcessData as $key=>$tuple){
                    if($tuple['type'] == 'CV')
                    {
                        $searchStr = 'stu-cv-icon';
                        $replaceStr = 'cv-icn';
                    }
                    else
                    {
                        $searchStr = '-icon';
                        $replaceStr = '-icn';
                    }
                    $tuple['icon'] = str_replace($searchStr,$replaceStr,$tuple['icon']);
				    ?>
					<li>
						<i class="mobile-sop-sprite <?php echo $tuple['icon']; ?> flLt"></i>
						<div class="app-process-block">
							<strong><a href="<?php echo $tuple['contentURL'];?>" title="<?php echo $tuple['heading'];?>"><?php echo $tuple['heading'];?></a></strong>
							<p><?php echo $tuple['name'];?></p>
						</div>
					</li>
				<?php } ?>
				</ul>
				<?php } ?>
				<p>This article was written by: <?= htmlentities($contentData['authorInfo']['firstname']." ".$contentData['authorInfo']['lastname']);?>, <span style="color:#999"><?php echo date("d M'y",strtotime($contentData['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($contentData['contentUpdatedAt']));?></span></p>
			</div>
			<div style="float: left;">
				<div class="flLt">
					<iframe
						src="https://www.facebook.com/plugins/like.php?href=<?php echo $contentData['contentURL']; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
						colorscheme=light " scrolling="no" frameborder="0"
						allowTransparency="true"
						style="border: none; overflow: hidden; width: 80px; height: 20px"></iframe>
				</div>
				<div class="flLt" style="padding-left:10px;">
					<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>							
				
			</div>
		</div>
		<?php
        $this->load->view('widgets/browseUniversity');
        $this->load->view('contentPage/widgets/applyContentRelatedArticles');
        $this->load->view('contentPage/widgets/commentsSection');
        ?>
	</div>
</section>