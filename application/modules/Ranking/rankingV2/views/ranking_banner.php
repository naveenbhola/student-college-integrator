<?php 
if(!empty($banner_details) && !preg_match('/(\.swf)/',$banner_details['file_path']) && $banner_details['file_path'] != '') { ?>
        <div id="RNR_pushdownbanner" class="clear top-ad-wrap" style = "width:943px; display:inline-block; margin-left: 22px;">
            <iframe id="categoryPagePushDownBannerFrame" width="100%" height="300" scrolling="no"  frameborder="0" src="<?php echo $banner_details['file_path'];?>" id="TOP" bordercolor="#000000" vspace="0" hspace="0" marginheight="0" marginwidth="0" style="z-index:1; display:inline-block;"></iframe>
        </div>
<?php }  else if(!empty($banner_details) && $banner_details['file_path']) { ?>
	<div class="top-banner-cont" id="ranking_banner_cont" style="width:940px;"></div>
<?php }    ?>