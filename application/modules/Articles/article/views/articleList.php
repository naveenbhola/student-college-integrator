<?php 
$headerComponents = array(
    'jsFooter' => array('common'),
    'title'	=>	$seoDetails['title'],
    'tabName' =>	'Articles',
    'taburl' =>  $_SERVER['REQUEST_URI'],
    'metaDescription' => $seoDetails['description'],
    'product' => 'Articles',
    'shikshaProduct' => 'Articles',   
	'bannerProperties' => array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'HEADER'),
	'canonicalURL'=>$seoURLS['canonicalURL'],
	'nextURL'=> $seoURLS['nextURL'],
	'previousURL'=> $seoURLS['prevURL'],
    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
    'callShiksha'=>1,
	'searchEnable'=>true,
	'noIndexMetaTag' => $noIndexMetaTag
);
$this->load->view('common/header', $headerComponents);
?>
<div class="spacer10 clearFix"></div>
<div class="mlr10">
	<div style="float:left; width:100%;">
		<div style="padding:5px;float:left;"><h1 style="font-size:20px"><span id="criteriaLabel" class="OrgangeFont"><?php echo $seoDetails['h1'];?></span></h1></div>
		<div class="float_R" style="margin-top:16px;"><div id="pagingIDc"><span><span class="pagingID"><?php echo preg_replace('/\/0\/25/','',$paginationHTML);?></span></span></div></div>
			<div class="clearFix"></div>
	</div>
	<?php if(!empty($totalArticles)){
                $remaining = ($limit['lowerLimit'] + $limit['pageSize']) ;
                $remaining = $remaining > $totalArticles ? $totalArticles : $remaining;
	?>
	<div style="padding-left:5px;">Showing <?php echo ($limit['lowerLimit'] +1) .' - ' . $remaining .' of '. $totalArticles  ; ?></div>
	<?php } ?>
	<div class="dottedLine">&nbsp;</div>
	<div style="margin:0">
        <div class="float_L" style="width:692px">
            <div class="wdh100">
            <?php if(!empty($totalArticles)){?>
            	<div><?php $this->load->view('articleListInner');?></div>
            	<div align="right" class="lineSpace_25" style="margin-bottom:3px;">
	    	    	<span><span class="pagingID"><?php echo preg_replace('/\/0\/20|\/0\/50/','',$paginationHTML);?></span></span>
    	    	</div>
    	    <?php }else{ ?><div>No News and Articles Available</div><?php } ?>
        	</div>
    	</div>
    	<div class="float_R"  style="width:265px">
    		<div class="wdh100">
	    		<?php
	    		  $this->benchmark->mark('code_start');
	                $bannerProperties = array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'SIDE');
	            	$this->load->view('common/banner', $bannerProperties);  
	         	?>
	     		<div class="lineSpace_10">&nbsp;</div>
	            <div><?php $bannerProperties = array('pageId'=>'ARTICLE_DETAILS', 'pageZone'=>'SIDEBANNER'); $this->load->view('common/banner', $bannerProperties);?></div>
	                <!--End_google_Banner-->
	                <?php  $this->benchmark->mark('code_end');?>
            </div>
        </div>
        <div class="clear_B">&nbsp;</div>
        <div class="lineSpace_15">&nbsp;</div>
    </div>
    <div class="clearFix"></div>			
</div>
<?php
	$this->load->view('common/footer', $bannerProperties);
?>
<script src="//<?php echo JSURL; ?>/public/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
$j(window).load(function(){
	$j("img.lazy").lazyload({effect : "fadeIn",threshold : 100}); 
});
</script>
<?php $this->load->view('common/newMMPForm'); ?>
