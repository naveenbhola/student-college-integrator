<?php
$headerComponents = array('css'             => array(),
                              'js'              => array('searchSA','searchSAv2'),
                              'canonicalURL'    => $canonicalURL,
                              'title'           => $seoTitle,
                              'metaDescription' => $metaDescription,
			      'hideSeoRevisitFlag' => true,
			      'hideSeoRatingFlag' => true,
			      'hideSeoPragmaFlag' => true,
			      'hideSeoClassificationFlag' => true,
			      'pgType'	        => $pgType,
			      'robotsMetaTag' => $robots,
                              'metaKeywords'    => ''
                              );
    $this->load->view('commonModule/header',$headerComponents);
  ?>
<form id="abroadsearchform" name="abroadsearchformGNB" method="GET" action="<?php if($isNewSearchPage){echo "/searchPage/searchPageV2";}else{echo "/search-abroad/" ;} ?>" onsubmit="return validateSearchForm();" data-enhance=false>
<div class="header-unfixed">
<div class="layer-header layer-search" style="background:#fff">
    <a href="javascript:void(0)" class="back-box" data-rel="back"><i class="sprite back-icn"></i></a>
    <div class="layer-search-box">
        <div class="search-outer" style="width: 96% ! important;display:block !important;">
            <a href="javascript:void(0);" onclick="submitSearch();" class="search-btn" data-role=none><i class="sprite search-icn3"></i></a>
			<div style="margin-right:35px; padding-top:2px !important;">
                            <?php if($isNewSearchPage) { ?>
                                <input type="text" id="seachTextBox"  name="q" class="universal-txt search-field" placeholder="Search For a College or Course" value="<?= htmlentities(base64_decode($keywordEncoded));?>" style="width:100%;">
                            <?php }else{?>
                                <input type="text" id="seachTextBox"  name="keyword" class="universal-txt search-field" placeholder="Search For a College or Course" value="<?= htmlentities(base64_decode($keywordEncoded));?>" style="width:100%;">
                            <?php } ?>
                                <input type="hidden" name="from_page" value="mobileSearchPage">
			</div>
		</div>
    </div>
</div>
</div>
<?php if($isNewSearchPage){ ?>
<div style="width: 86% ! important;height:200px;overflow-y:scroll;display:block !important;color:red;background:#bbbbbb;" id="msbas">
	
</div>
<input type='text' id='lsb' />
<div style="width: 86% ! important;height:200px;overflow-y:scroll;display:block !important;color:red;background:#bbbbbb;" id="lsbas">
</div>
<script>
	$j(document).ready(function(){
		$j("#seachTextBox").on("keyup",getSuggestions);
		$j("#lsb").on("keyup",getLocSuggestions);
	});
</script>
<?php } ?>
</form>