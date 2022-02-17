 <?php
			if(isset($gutterBannerProperties) && $gutterBannerProperties['pageId'] != '' && $gutterBannerProperties['pageZone'] != ''){
		    ?>
			<div style="position: fixed; right: 13px; top: 45px; width: 130px; overflow: visible; z-index: 99;display:none" id="gutter_studyAbroad">
			    <div style="padding:7px 7px 7px 15px" tabindex="7">
		    <?php
			$bannerPropertiesGutter = array('pageId'=>$gutterBannerProperties['pageId'], 'pageZone'=>$gutterBannerProperties['pageZone']);
			$this->load->view('common/banner',$bannerPropertiesGutter);
		    ?>
			    </div>
			</div>
			<script>
                            if (screen.width>1024){
				document.getElementById('gutter_studyAbroad').style.display = '';
			    }
			</script>
<?php }?>		