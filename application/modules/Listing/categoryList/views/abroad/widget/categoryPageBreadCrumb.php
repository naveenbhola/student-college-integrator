<?php
        if(isset($bannerProperties['pageId']) && isset($bannerProperties['pageZone'])) {
                echo '<div class="banner-468x60">';
                $bannerProperties = array('pageId'=>$bannerProperties['pageId'], 'pageZone'=>$bannerProperties['pageZone'],'shikshaCriteria' => $bannerProperties['shikshaCriteria']);
                $this->load->view('common/banner',$bannerProperties);
                echo '</div>';
        }
?>

<div id="breadcrumb"><?php
foreach($breadcrumbData as $key => $breadCrumb) {	
?>
     <span itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><?php
		if($breadCrumb['url'] != "") {
		?>
		  <a href="<?=$breadCrumb['url']?>" itemprop="url"><span itemprop="title"><?=htmlentities(str_replace("in abroad","abroad",$breadCrumb['title']))?></span></a>
		  <span class="breadcrumb-arr">&rsaquo;</span>
		  <?php
		} else {  ?>
		   <span itemprop="title"><?=$breadCrumb['title']?></span>
		<?php } ?>		
     </span>
<?php
}
?>
</div>