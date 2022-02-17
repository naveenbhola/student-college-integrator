<div class="sp-blck-fm clear">
    <div class="time-line">
        <?php
			if($downloadMessageType == 'downloadBrochure'){
				echo $dlBrochureData['reco'];
			}
			else
			{
				if(count($relatedGuides)>0){
				$firstGuide = reset($relatedGuides);
		?>
    	<p class="tm-text">Students who downloaded above student guide were also interested in</p>
    	<div class="dwnld-block" <?php echo (count($relatedGuides)==1?'style="margin-bottom:10px;"':''); ?>>
    		<div class="img-cl">
    			<a href="">
    				<img id="ins_hdr_img1" src="<?php echo  str_replace('_s','_300x200', $firstGuide['contentImageURL']); ?>" alt="<?php echo htmlentities( $firstGuide['strip_title']); ?>" title="<?php echo htmlentities( $firstGuide['strip_title']); ?>" style="width: 300px; height: 200px; cursor: pointer">
    			</a>
    		</div>
    		<div class="text-cl">
                <a class="clk-txts" href="<?php echo ( $firstGuide['contentUrl']); ?>"><?php echo htmlentities( $firstGuide['strip_title']); ?></a>
                <p class="guide-txts"><?php echo $firstGuide['summary']; ?>
                </p>
				<?php if($firstGuide['download_link'] !=''){ ?>
                <a href="javascript:void(0);" class="gray-downld-btn lrg"
				onclick="contentDownloadFlowStart = false;downloadPDF('<?php echo $downloadControllerUrl; ?>',['<?php echo base64_encode($firstGuide['download_link']); ?>','<?php echo $firstGuide['contentId']; ?>',1214,'<?php echo htmlentities($firstGuide['strip_title']); ?>','new','<?php echo $MISTrackingDetails['conversionType']; ?>','<?php echo $contentType; ?>']);">
                    <span class="font-12">Download Guide</span>
                </a>
				<?php } ?>
				<?php if($firstGuide['downloadCount']>0){ ?>
                <div class="fnt-10"><?php echo $firstGuide['downloadCount']; ?> people downloaded this guide</div>
				<?php } ?>
    		</div>
    	</div>
		<?php if(count($relatedGuides)>1){ ?>
    	<ul class="sugst-col">
			<?php for($i=1;$i<count($relatedGuides);$i++){ ?>
    		<li>
    			<div class="guides-col">
    				<div class="inner-blocks">
    					<div class="cl-img">
    					 	<a href="">
    					 		<img src="<?php echo str_replace('_s','_172x115', $relatedGuides[$i]['contentImageURL']); ?>" alt="<?php echo htmlentities( $relatedGuides[$i]['strip_title']); ?>" title="<?php echo htmlentities( $relatedGuides[$i]['strip_title']); ?>" height="115" align="middle" width="172">
    					 	</a>
    					</div>
    					<div class="txt-guide">
                            <a class="clk-txt" href="<?php echo ( $relatedGuides[$i]['contentUrl']); ?>"><?php echo htmlentities( $relatedGuides[$i]['strip_title']); ?></a> 
                            <p class="sugts-p">
                            <?php echo formatArticleTitle($relatedGuides[$i]['summary'],150); ?>
                            </p>
							<?php if($relatedGuides[$i]['download_link'] !=''){ ?>
                            <a href="javascript:void(0);" class="gray-downld-btn"
							onclick="contentDownloadFlowStart = false;downloadPDF('<?php echo $downloadControllerUrl; ?>',['<?php echo base64_encode($relatedGuides[$i]['download_link']); ?>','<?php echo $relatedGuides[$i]['contentId']; ?>',1214,'<?php echo $relatedGuides[$i]['strip_title']; ?>','new','<?php echo $MISTrackingDetails['conversionType']; ?>','<?php echo $contentType; ?>']);">
                                <span class="font-12">Download Guide</span>
                            </a>
							<?php } ?>
							<?php if($relatedGuides[$i]['downloadCount']>0){ ?>
                            <div class="fnt-10"><?php echo ( $relatedGuides[$i]['downloadCount']); ?> people downloaded this guide</div>
							<?php } ?>
    					</div>
    				</div>
    			</div>
    		</li>
			<?php } ?>
    	</ul>
		<?php }// count >1 
		 }// count >0 ?>
        <?php }
		// dont show bottom back link if no results/recommendtaions
		if($dlBrochureData['reco'] != '' || count($relatedGuides)>0){
			$this->load->view('studyAbroadCommon/abroadSignup/widgets/thankYouPageBackLink');
		}
		?>
    </div>
</div>