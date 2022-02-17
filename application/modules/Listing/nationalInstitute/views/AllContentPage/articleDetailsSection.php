<?php 
	$GA_Tap_On_Article = 'ARTICLE_TUPLE';
?>
<div id="allContentTuple">
	<div class="col-md-8 no-padLft left-widget">
		<div class="loaderAjax" id="allContentTupleLoader" style="display: none;background: rgba(0, 0, 0, 0.180392);"></div>
	    <?php 
	    $i=0;
	    foreach($articleInfo as $articles){?>
	    <a href="<?php echo $articles['url'];?>"><div class="group-card gap rnk-crd" ga-attr="<?=$GA_Tap_On_Article;?>" style="cursor:pointer;">
	        <strong><?php echo htmlentities($articles['blogTitle']);?></strong>
	        <p><?php echo htmlentities($articles['summary']);?></p>
	    </div></a>
	    <?php 
	    	$i++;
	    	if($i==3)
	    	{
	    		$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
	    		$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));
	    	}
	    	if($i==6)
	    	{
	    		$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C1','bannerType'=>"content"));
	    		$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C1_2','bannerType'=>"content"));
	    	}
	    } 
	    if($i<3 || $i<6)
	    {
	    	$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
	    	$this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));
	    }
	    ?>
	</div>
</div>
